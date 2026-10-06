<?php
namespace App\Core;

use App\Models\User;

class Auth
{
    private const SESSION_KEY = 'auth_user_id';

    /**
     * Tenta autenticar por e-mail e senha.
     */
    public static function attempt(string $email, string $password): bool
    {
        $userModel = new User();
        $user = $userModel->findOneBy(['email' => $email]);

        if ($user === null) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        self::login((int) $user['id']);

        return true;
    }

    /**
     * Registra o usuário na sessão.
     */
    public static function login(int $userId): void
    {
        self::ensureSession();
        session_regenerate_id(true);
        $_SESSION[self::SESSION_KEY] = $userId;
    }

    /**
     * Encerra a sessão do usuário.
     */
    public static function logout(): void
    {
        self::ensureSession();
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    /**
     * Verifica se há usuário autenticado.
     */
    public static function check(): bool
    {
        return self::id() !== null;
    }

    /**
     * ID do usuário autenticado (ou null).
     */
    public static function id(): ?int
    {
        self::ensureSession();

        $id = $_SESSION[self::SESSION_KEY] ?? null;

        return $id !== null ? (int) $id : null;
    }

    /**
     * Dados do usuário autenticado (sem o hash da senha).
     */
    public static function user(): ?array
    {
        $id = self::id();

        if ($id === null) {
            return null;
        }

        $userModel = new User();
        $user = $userModel->find($id);

        if ($user !== null) {
            unset($user['password']);
        }

        return $user;
    }

    private static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
