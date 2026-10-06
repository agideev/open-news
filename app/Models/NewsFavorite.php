<?php
namespace App\Models;

use PDO;

class NewsFavorite extends Model
{
    protected string $table    = 'news_favorites';
    protected array  $fillable = [
        'news_id',
        'user_id',
    ];

    /**
     * Verifica se o usuário já favoritou a notícia.
     */
    public function findByUserAndNews(int $userId, int $newsId): ?array
    {
        return $this->findOneBy([
            'user_id' => $userId,
            'news_id' => $newsId,
        ]);
    }

    /**
     * Alterna o favorito: cria se não existir, remove se existir.
     * Retorna true se passou a estar favoritado, false se foi removido.
     */
    public function toggle(int $userId, int $newsId): bool
    {
        $existing = $this->findByUserAndNews($userId, $newsId);

        if ($existing !== null) {
            $this->delete((int) $existing['id']);
            return false;
        }

        $this->create([
            'news_id' => $newsId,
            'user_id' => $userId,
        ]);

        return true;
    }

    /**
     * Total de favoritos de uma notícia.
     */
    public function countByNews(int $newsId): int
    {
        return count($this->findBy(['news_id' => $newsId]));
    }

    /**
     * Retorna as notícias favoritadas por um usuário, com paginação.
     */
    public function newsByUser(int $userId, int $page = 1, int $perPage = 12): array
    {
        $page    = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset  = ($page - 1) * $perPage;

        // Total
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM `news_favorites` WHERE `user_id` = :uid"
        );
        $stmt->execute([':uid' => $userId]);
        $total = (int) $stmt->fetchColumn();

        // Itens
        $sql = "SELECT n.*, nf.created_at AS favorited_at
                FROM `news_favorites` nf
                INNER JOIN `news` n ON n.id = nf.news_id
                WHERE nf.user_id = :uid
                ORDER BY nf.created_at DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uid',    $userId,  PDO::PARAM_INT);
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Resolve image_url
        foreach ($items as &$item) {
            $item['image_url'] = !empty($item['image'])
                ? path('/' . ltrim($item['image'], '/'))
                : null;
        }
        unset($item);

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        ];
    }
}
