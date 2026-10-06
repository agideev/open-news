<?php

declare(strict_types=1);

/**
 * Autoloader PSR-4 simples para o namespace App\.
 * Executado uma única vez no bootstrap.
 */
spl_autoload_register(static function (string $class): void {
    $prefix  = 'App\\';
    $baseDir = BASE_PATH . '/app/';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file     = $baseDir . str_replace('\\', '/', $relative) . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});
