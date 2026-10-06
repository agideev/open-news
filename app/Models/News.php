<?php
namespace App\Models;

use PDO;

class News extends Model
{
    protected string $table    = 'news';
    protected array  $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'image',
        'audio',
        'status',
        'published_at',
    ];


    public function findBySlug(string $slug): ?array
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    // app/Models/News.php

    public function findWithRelations(int $id): ?array
    {
        $news = $this->find($id);
        return $news ? $this->decorate($news) : null;
    }

    public function findBySlugWithRelations(string $slug): ?array
    {
        $news = $this->findBySlug($slug);
        return $news ? $this->decorate($news) : null;
    }

    /**
     * Aplica image_url, audio_url e carrega as imagens da galeria.
     */
    private function decorate(array $news): array
    {
        $news['image_url'] = !empty($news['image'])
            ? path('/' . ltrim($news['image'], '/'))
            : null;

        $news['audio_url'] = !empty($news['audio'])
            ? path('/' . ltrim($news['audio'], '/'))
            : null;

        $images = (new NewsImage())->forNews((int) $news['id']);
        foreach ($images as &$image) {
            $image['image_url'] = !empty($image['image'])
                ? path('/' . ltrim($image['image'], '/'))
                : null;
        }
        unset($image);

        $news['images'] = $images;

        return $news;
    }
    public function recent(int $page = 1, int $perPage = 10, ?string $status = null): array
    {
        $page    = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset  = ($page - 1) * $perPage;

        // =====================================================
        // FILTRO POR STATUS
        // =====================================================

        $where  = '';
        $params = [];

        if ($status !== null) {
            $where = " WHERE `status` = :status";
            $params[':status'] = $status;
        }

        // =====================================================
        // TOTAL DE NOTÍCIAS
        // =====================================================

        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM `news`{$where}"
        );

        $stmt->execute($params);

        $total = (int) $stmt->fetchColumn();

        // =====================================================
        // BUSCAR NOTÍCIAS MAIS RECENTES PRIMEIRO
        // =====================================================

        $sql = "SELECT * FROM `news`{$where}
                ORDER BY `created_at` DESC, `published_at` DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        // =====================================================
        // PROCESSAR RESULTADOS
        // =====================================================

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as &$item) {
            $item['image_url'] = !empty($item['image'])
                ? url($item['image'])
                : null;
        }

        unset($item);

        // =====================================================
        // RETORNO
        // =====================================================

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        ];
    }

}
