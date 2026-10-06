<?php
namespace App\Models;

class AiUsage extends Model
{
    protected string $table    = 'ai_usage';
    protected array  $fillable = [
        'user_id',
        'usage_date',
        'credits_used',
        'daily_limit',
    ];

    public function findByUserAndDate(int $userId, string $date): ?array
    {
        return $this->findOneBy([
            'user_id'    => $userId,
            'usage_date' => $date,
        ]);
    }

    public function forUser(int $userId): array
    {
        return $this->findBy(['user_id' => $userId]);
    }
}
