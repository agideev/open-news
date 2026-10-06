<?php

/**
 * Redireciona para uma URL e encerra a execução.
 *
 * @param string $path   Caminho interno (ex.: '/games') ou URL completa (http://...)
 * @param int    $status Código HTTP (302 por padrão)
 */
function redirect(string $path = '/', int $status = 302): never
{
    // Se já é URL absoluta (http:// ou https://), usa direto
    $isAbsolute = preg_match('#^https?://#i', $path) === 1;

    $url = $isAbsolute ? $path : path($path);

    header('Location: ' . $url, true, $status);
    exit;
}
