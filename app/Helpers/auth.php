<?php
use App\Models\User;
use App\Core\JsonResponse;
use App\Core\Auth;

function current_user(): ?array {
    static $user = null;
    static $loaded = false;
    if ($loaded) return $user;
    $loaded = true;
    if (Auth::check()) {

        $userModel = new User();

        $user = $userModel->find(Auth::id());
    }
    return $user;
}

/**
 * Detecta requisições AJAX / API (JSON).
 */
function is_api_request(): bool
{
    $accept        = $_SERVER['HTTP_ACCEPT'] ?? '';
    $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    $contentType   = $_SERVER['CONTENT_TYPE'] ?? '';

    return str_contains($accept, 'application/json')
        || strtolower($requestedWith) === 'xmlhttprequest'
        || str_contains($contentType, 'application/json');
}

/**
 * Exige usuário autenticado.
 * API  → 401 JSON
 * Web  → redirect /login
 */
function auth_only(): void
{
    if (Auth::check()) {
        return;
    }

    if (is_api_request()) {
        JsonResponse::error('Não autenticado.', 401);
    }

    redirect('login');
    exit;
}

/**
 * Permite apenas visitantes (não autenticados).
 * API  → 403 JSON
 * Web  → redirect /dashboard
 */
function guest_only(): void
{
    if (!Auth::check()) {
        return;
    }

    if (is_api_request()) {
        JsonResponse::error('Você já está autenticado.', 403);
    }

    redirect('news');
    exit;
}

/**
 * Exige usuário autenticado com role = 'admin'.
 */
function admin_only(): void
{
    if (!Auth::check()) {
        if (is_api_request()) {
            JsonResponse::error('Não autenticado.', 401);
        }
        header('Location: ' . path('/login'));
        exit;
    }

    $user = Auth::user();

    if (($user['role'] ?? 'user') !== 'admin') {
        if (is_api_request()) {
            JsonResponse::error('Acesso restrito.', 403);
        }
        http_response_code(403);
        view('errors.403', ['title' => 'Acesso negado']);
        exit;
    }
}

/**
 * Verifica se o usuário autenticado é admin.
 *
 * @return bool
 */
function is_admin(): bool
{
    $user = \App\Core\Auth::user();

    if ($user === null) {
        return false;
    }

    return ($user['role'] ?? 'user') === 'admin';
}
