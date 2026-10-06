<?php
// app/Models/NewsImage.php
namespace App\Models;

class NewsImage extends Model
{
    protected string $table    = 'news_images';
    protected array  $fillable = [
        'news_id',
        'image',
        'caption',
        'position',
    ];

    public function forNews(int $newsId): array
    {
        return $this->findBy(['news_id' => $newsId]);
    }
}
