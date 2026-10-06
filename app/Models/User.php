<?php
namespace App\Models;

class User extends Model
{
    protected string $table    = 'users';
    protected array  $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'whatsapp',
    ];

    // Equivalente ao antigo findByEmail()
    public function findByEmail(string $email): ?array
    {
        return $this->findOneBy(['email' => strtolower(trim($email))]);
    }

    // Equivalente ao antigo findByUsername()
    public function findByUsername(string $username): ?array
    {
        return $this->findOneBy(['username' => $username]);
    }

    public function isAdmin(): bool
    {
        return ($this->rule ?? null) === 'admin';
    }

    // find(), create(), update(), delete(), exists() já vêm da Model base
}
