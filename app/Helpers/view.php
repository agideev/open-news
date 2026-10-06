<?php
function view(string $name, array $data = []): void {
    $viewFile = BASE_PATH . '/views/' . str_replace('.', '/', $name) . '.php';
    if (!is_file($viewFile)) throw new Exception("View não encontrada: $name");

    extract($data, EXTR_SKIP);
    ob_start();
    require $viewFile;
    $content = ob_get_clean();

    require BASE_PATH . '/views/layouts/app.php';
}

/**
 * Renderiza um partial da pasta /views/partials/.
 *
 * @param string $name Nome do partial (ex.: 'notifications/bell')
 * @param array  $data Variáveis disponíveis dentro do partial
 */
function partial(string $name, array $data = []): void
{
    $file = BASE_PATH . '/views/partials/' . str_replace('.', '/', $name) . '.php';

    if (!is_file($file)) {
        throw new Exception("Partial não encontrado: {$name}");
    }

    extract($data, EXTR_SKIP);
    require $file;
}

function asset(string $path): string { return url("public/assets/") . ltrim($path, '/'); }


function getupload(string $path): string
{
    $path = ltrim($path, '/');
    $path = preg_replace('#^uploads/#', '', $path);
    return url("public/uploads/" . $path);
}

function url(string $path = ''): string {
    $base = rtrim((string) env('APP_URL', '/'), '/');
    $path = ltrim($path, '/');
    return $path === '' ? $base . '/' : $base . '/' . $path;
}


function path(string $p = ''): string {
    $base = parse_url((string) env('APP_URL', '/'), PHP_URL_PATH) ?: '/';
    $base = '/' . trim($base, '/');
    $p = '/' . ltrim($p, '/');
    return $base === '/' ? $p : $base . $p;
}
