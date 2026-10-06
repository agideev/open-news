<?php
namespace App\Controllers;

use App\Models\News;
use App\Models\NewsFavorite;
use App\Models\NewsImage;
use App\Core\Request;
use App\Core\JsonResponse;
use App\Core\Validator;
use App\Core\Auth;
use App\Services\UploadService;
use Exception;

class NewsController
{
    /**
     * Listagem do admin: todas as notícias (draft + published).
     */
    public function adminIndex(): void
    {
        $newsModel = new News();
        $page      = max(1, (int) ($_GET['page'] ?? 1));

        $paginator = $newsModel->recent($page, 12);

        view('news.admin.index', [
            'title'     => 'Gerenciar Notícias',
            'items'     => $paginator['items'],
            'paginator' => $paginator,
        ]);
    }
    /**
     * Listagem pública: somente notícias publicadas.
    */
    public function index(): void
    {
        $newsModel = new News();
        $page      = max(1, (int) ($_GET['page'] ?? 1));

        // Supondo que recent() aceite um filtro por status (ver observação abaixo)
        $paginator = $newsModel->recent($page, 12, 'published');

        view('news.index', [
            'title'     => 'Notícias',
            'items'     => $paginator['items'],
            'paginator' => $paginator,
        ]);
    }

    public function create(): void
    {
        view('news.create', [
            'title' => 'Nova Notícia',
        ]);
    }

    public function show(string $slug): void
    {
        $newsModel = new News();
        $news = $newsModel->findBySlugWithRelations($slug);

        if ($news === null) {
            http_response_code(404);
            view('errors.404', ['title' => 'Notícia não encontrada']);
            return;
        }

        // Apenas publicadas podem ser vistas publicamente
        if (($news['status'] ?? 'draft') !== 'published') {
            http_response_code(404);
            view('errors.404', ['title' => 'Notícia não encontrada']);
            return;
        }

        // Próximas / anteriores (opcional — não exigido, omitir se preferir)
        $related = $newsModel->recent(1, 4, 'published')['items'];

        view('news.show', [
            'title'   => $news['title'],
            'news'    => $news,
            'related' => $related,
        ]);
    }

    public function store(): void
    {
        verify_csrf();

        $request = new Request();

        $data = $request->only([
            'title', 'summary', 'content', 'status', 'published_at'
        ]);

        // Validação
        $validator = new Validator($data);
        $validator->setLabels([
            'title'   => 'Título',
            'content' => 'Conteúdo',
        ]);

        $valid = $validator->validate([
            'title'        => 'required|min:3|max:180',
            'summary'      => 'max:500',
            'content'      => 'required'
        ]);

        if (!$valid) {
            JsonResponse::error('Dados inválidos.', 422, $validator->getErrors());
        }

        if (!Auth::check()) {
            JsonResponse::error('Não autenticado.', 401);
        }

        // Slug
        $data['slug'] = $this->makeSlug((string) $data['title']);

        // Upload da imagem principal
        if (!empty($_FILES['image']['tmp_name'])) {
            try {
                $data['image'] = UploadService::store(
                    $_FILES['image'],
                    'news',
                    $data['slug']
                );
            } catch (Exception $e) {
                JsonResponse::error($e->getMessage(), 400);
            }
        }

        // Upload do áudio principal
        if (!empty($_FILES['audio']['tmp_name'])) {
            try {
                $data['audio'] = UploadService::store(
                    $_FILES['audio'],
                    'news/audios',
                    $data['slug'] . '_audio'
                );
            } catch (Exception $e) {
                JsonResponse::error($e->getMessage(), 400);
            }
        }

        $newsModel = new News();
        $news = $newsModel->create($data);

        if ($news === null) {
            JsonResponse::error('Não foi possível criar a notícia.', 500);
        }

        // Sincroniza apenas as imagens da galeria
        $this->syncImages((int) $news['id'], (string) $data['slug']);

        setMessage('success', 'Notícia criada com sucesso.');
        JsonResponse::success('Notícia criada com sucesso.', $news, 201);
    }

    private function syncImages(int $newsId, string $slug): void
    {
        $imageModel = new NewsImage();

        // Imagens existentes no banco
        $existing     = $imageModel->forNews($newsId);
        $existingById = [];
        foreach ($existing as $e) {
            $existingById[(int) $e['id']] = $e;
        }

        $submitted = $_POST['images'] ?? [];
        if (!is_array($submitted)) $submitted = [];
        $submitted = array_slice($submitted, 0, 20, true);

        $keptIds = [];

        foreach ($submitted as $i => $img) {
            $id       = isset($img['id']) && is_numeric($img['id']) ? (int) $img['id'] : null;
            $caption  = trim((string) ($img['caption'] ?? ''));
            $position = isset($img['position']) && is_numeric($img['position'])
                ? (int) $img['position']
                : 0;

            // Upload de nova imagem (se enviada)
            $newFile = null;
            if (
                !empty($_FILES['images']['tmp_name'][$i]['image']) &&
                ($_FILES['images']['error'][$i]['image'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK
            ) {
                try {
                    $newFile = UploadService::store(
                        [
                            'tmp_name' => $_FILES['images']['tmp_name'][$i]['image'],
                            'error'    => $_FILES['images']['error'][$i]['image'],
                            'size'     => $_FILES['images']['size'][$i]['image'],
                        ],
                        'news/images',
                        $slug . '_img_' . $i
                    );
                } catch (Exception $e) {
                    $newFile = null;
                }
            }

            if ($id !== null && isset($existingById[$id])) {
                // ---------- UPDATE ----------
                $updateData = [
                    'caption'  => $caption !== '' ? mb_substr($caption, 0, 255) : null,
                    'position' => $position,
                ];

                if ($newFile !== null) {
                    if (!empty($existingById[$id]['image'])) {
                        UploadService::deleteFile($existingById[$id]['image']);
                    }
                    $updateData['image'] = $newFile;
                }

                $imageModel->update($id, $updateData);
                $keptIds[] = $id;
            } else {
                // ---------- CREATE ----------
                // Sem arquivo não dá pra criar (image é NOT NULL)
                if ($newFile === null) {
                    continue;
                }

                $created = $imageModel->create([
                    'news_id'  => $newsId,
                    'image'    => $newFile,
                    'caption'  => $caption !== '' ? mb_substr($caption, 0, 255) : null,
                    'position' => $position,
                ]);

                if ($created !== null) {
                    $keptIds[] = (int) $created['id'];
                }
            }
        }

        // ---------- REMOVE as que sumiram ----------
        foreach ($existingById as $oldId => $old) {
            if (!in_array($oldId, $keptIds, true)) {
                if (!empty($old['image'])) {
                    UploadService::deleteFile($old['image']);
                }
                $imageModel->delete($oldId);
            }
        }
    }

    public function edit(int $id): void
    {
        $newsModel = new News();
        $news = $newsModel->find($id);

        if ($news === null) {
            http_response_code(404);
            view('errors.404', ['title' => 'Notícia não encontrada']);
            return;
        }

        $images = (new NewsImage())->forNews($id);

        view('news.edit', [
            'title'  => 'Editar — ' . $news['title'],
            'news'   => $news,
            'images' => $images,
        ]);
    }

    public function update(int $id): void
    {
        verify_csrf();

        $newsModel = new News();
        $news = $newsModel->find($id);

        if ($news === null) {
            JsonResponse::error('Notícia não encontrada.', 404);
        }

        $request = new Request();
        $data    = $request->all();

        // Validação (mesmas regras do store)
        $validator = new Validator($data);
        $validator->setLabels([
            'title'   => 'Título',
            'content' => 'Conteúdo',
        ]);

        $ok = $validator->validate([
            'title'   => 'required|min:3|max:180',
            'summary' => 'max:500',
            'content' => 'required',
        ]);

        if (!$ok) {
            JsonResponse::error('Verifique os dados.', 422, $validator->getErrors());
        }

        // Whitelist dos campos editáveis
        $data = $request->only([
            'title', 'summary', 'content', 'status', 'published_at'
        ]);

        // Slug: regenera se o título mudou (ignorando o próprio registro)
        if ($data['title'] !== $news['title']) {
            $data['slug'] = $this->makeSlug($data['title'], $id);
        }

        // Upload: imagem principal (só se enviada nova)
        if (!empty($_FILES['image']['tmp_name'])) {
            try {
                $newImage = UploadService::store(
                    $_FILES['image'],
                    'news',
                    $data['slug'] ?? $news['slug']
                );
                if (!empty($news['image'])) {
                    UploadService::deleteFile($news['image']);
                }
                $data['image'] = $newImage;
            } catch (Exception $e) {
                JsonResponse::error($e->getMessage(), 400);
            }
        }

        // Upload: áudio principal (só se enviado novo)
        if (!empty($_FILES['audio']['tmp_name'])) {
            try {
                $newAudio = UploadService::store(
                    $_FILES['audio'],
                    'news/audios',
                    ($data['slug'] ?? $news['slug']) . '_audio'
                );
                if (!empty($news['audio'])) {
                    UploadService::deleteFile($news['audio']);
                }
                $data['audio'] = $newAudio;
            } catch (Exception $e) {
                JsonResponse::error($e->getMessage(), 400);
            }
        }

        if (!$newsModel->update($id, $data)) {
            JsonResponse::error('Não foi possível atualizar a notícia.', 500);
        }

        // Sincroniza as imagens da galeria
        $this->syncImages((int) $id, (string) ($data['slug'] ?? $news['slug']));

        setMessage('success', 'Notícia atualizada com sucesso.');
        JsonResponse::success('Notícia atualizada com sucesso.', [
            'id' => $id,
        ]);
    }

    public function favorites(): void
    {
        if (!Auth::check()) {
            redirect('/login');
        }

        $userId = (int) Auth::id();
        $page   = max(1, (int) ($_GET['page'] ?? 1));

        $paginator = (new NewsFavorite())->newsByUser($userId, $page, 12);

        view('news.favorites', [
            'title'     => 'Meus Favoritos',
            'items'     => $paginator['items'],
            'paginator' => $paginator,
        ]);
    }

    public function favorite(int $id): void
    {
        verify_csrf();

        $userId = Auth::id();

        $newsModel = new News();
        $news = $newsModel->find($id);

        if ($news === null) {
            JsonResponse::error('Notícia não encontrada.', 404);
        }

        $favoriteModel = new NewsFavorite();
        $isFavorited   = $favoriteModel->toggle((int) $userId, (int) $id);
        $count         = $favoriteModel->countByNews((int) $id);

        JsonResponse::success(
            $isFavorited ? 'Adicionado aos favoritos.' : 'Removido dos favoritos.',
            [
                'favorited' => $isFavorited,
                'count'     => $count,
            ]
        );
    }

    /**
     * Gera um sufixo aleatório curto (letras + números).
     */
    private function makeSlug(string $value, ?int $ignoreId = null): string
    {
        // 1) Normaliza: minúsculo, sem acento, só a-z0-9 e hífen
        $base = mb_strtolower(trim($value), 'UTF-8');
        $base = iconv('UTF-8', 'ASCII//TRANSLIT', $base) ?: $base;
        $base = preg_replace('/[^a-z0-9]+/', '-', $base) ?? '';
        $base = trim($base, '-') ?: 'news';

        // 2) SQL de checagem
        $sql = "SELECT COUNT(*) FROM `news` WHERE `slug` = :slug";
        if ($ignoreId !== null) {
            $sql .= " AND `id` != :id";
        }
        $conn = getDbConnection();
        $stmt = $conn->prepare($sql);

        $exists = function (string $slug) use ($stmt, $ignoreId): bool {
            $params = [':slug' => $slug];
            if ($ignoreId !== null) {
                $params[':id'] = $ignoreId;
            }
            $stmt->execute($params);
            return (int) $stmt->fetchColumn() > 0;
        };

        // 3) Se o slug base está livre, retorna
        if (!$exists($base)) {
            return $base;
        }

        // 4) Colisão → incrementa sufixo numérico (-2, -3, -4, ...)
        $i = 2;
        while ($exists($base . '-' . $i)) {
            $i++;
        }

        return $base . '-' . $i;
    }

    public function destroy(): void
    {
        verify_csrf();

        $request = new Request();
        $id      = $request->input('id');

        if (!is_numeric($id)) {
            JsonResponse::error('ID inválido.', 422);
        }

        $id = (int) $id;

        $newsModel = new News();
        $news = $newsModel->find($id);

        if ($news === null) {
            JsonResponse::error('Notícia não encontrada.', 404);
        }

        // Remove arquivos físicos
        if (!empty($news['image'])) {
            UploadService::deleteFile($news['image']);
        }
        if (!empty($news['audio'])) {
            UploadService::deleteFile($news['audio']);
        }

        // Remove imagens da galeria (arquivos + registros)
        $imageModel = new NewsImage();
        foreach ($imageModel->forNews($id) as $img) {
            if (!empty($img['image'])) {
                UploadService::deleteFile($img['image']);
            }
            $imageModel->delete((int) $img['id']);
        }

        if (!$newsModel->delete($id)) {
            JsonResponse::error('Não foi possível excluir a notícia.', 500);
        }

        setMessage('warning', 'Notícia excluída com sucesso.');
        JsonResponse::success('Notícia excluída com sucesso.');
    }
}
