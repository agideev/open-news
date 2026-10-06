<?php
function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['_csrf'];
}

function verify_csrf(): void {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['_csrf'] ?? '');

    if (!hash_equals($_SESSION['_csrf'] ?? '', (string)$token)) {
        \App\Core\JsonResponse::error('CSRF token inválido.', 419);
    }
}

function e(string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
