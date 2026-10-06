<?php
class Database {
    private static ?PDO $pdo = null;

    public static function get(): PDO {
        if (self::$pdo === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                env('DB_HOST', '127.0.0.1'),
                env('DB_NAME', 'gamestore_db'),
                env('DB_CHARSET', 'utf8mb4')
            );
            self::$pdo = new PDO($dsn, env('DB_USER', 'root'), env('DB_PASS', ''), [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$pdo;
    }
}

// Helper usado pela class Model
function getDbConnection(): PDO {
    return Database::get();
}
