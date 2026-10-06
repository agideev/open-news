<?php

namespace App\Services;

use Exception;

class UploadService
{
    private const ALLOWED = [
        // Imagens
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        'image/avif' => 'avif',

        // Áudios
        'audio/mpeg'  => 'mp3',
        'audio/mp3'   => 'mp3',
        'audio/mp4'   => 'm4a',
        'audio/x-m4a' => 'm4a',
        'audio/aac'   => 'aac',
        'audio/ogg'   => 'ogg',
        'audio/opus'  => 'opus',
        'audio/wav'   => 'wav',
        'audio/x-wav' => 'wav',
        'audio/webm'  => 'webm',
        'audio/flac'  => 'flac',
        'audio/x-flac'=> 'flac',
    ];

    /**
     * Salva um arquivo enviado.
     *
     * @param array  $file       Arquivo vindo de $_FILES.
     * @param string $folder     Pasta dentro de /public/uploads/.
     * @param string $name       Nome base do arquivo.
     * @param int    $maxSize    Tamanho máximo em bytes.
     *
     * @return string Caminho público relativo do arquivo.
     */
    public static function store(
        array $file,
        string $folder,
        string $name,
        ?int $maxSize = null
    ): string {
        if (
            !isset($file['tmp_name']) ||
            !is_uploaded_file($file['tmp_name'])
        ) {
            throw new Exception('Arquivo inválido.');
        }

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new Exception(
                'Erro no upload (código ' . $file['error'] . ').'
            );
        }

        $maxSize ??= (int) env(
            'UPLOAD_MAX_SIZE',
            5242880
        );

        if ($file['size'] > $maxSize) {
            throw new Exception(
                'Arquivo muito grande (máx. ' .
                round($maxSize / 1048576, 1) .
                'MB).'
            );
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if ($finfo === false) {
            throw new Exception(
                'Não foi possível verificar o arquivo.'
            );
        }

        $mime = finfo_file(
            $finfo,
            $file['tmp_name']
        );

        finfo_close($finfo);

        if ($mime === false || !isset(self::ALLOWED[$mime])) {
            throw new Exception(
                'Formato não suportado. Use JPG, PNG, WEBP ou GIF.'
            );
        }

        $extension = self::ALLOWED[$mime];

        // Remove caracteres problemáticos do nome.
        $safeName = preg_replace(
            '/[^a-zA-Z0-9_-]/',
            '_',
            $name
        );

        $safeName = trim($safeName, '_');

        if ($safeName === '') {
            $safeName = 'file';
        }

        // Normaliza a pasta para evitar barras duplicadas.
        $folder = trim($folder, '/');

        $directory = BASE_PATH . '/public/uploads';

        if ($folder !== '') {
            $directory .= '/' . $folder;
        }

        if (!is_dir($directory) && !mkdir($directory, 0775, true)) {
            throw new Exception(
                'Não foi possível criar o diretório de upload.'
            );
        }

        $filename =
            $safeName .
            '_' .
            bin2hex(random_bytes(4)) .
            '.' .
            $extension;

        $destination = $directory . '/' . $filename;

        if (!move_uploaded_file(
            $file['tmp_name'],
            $destination
        )) {
            throw new Exception(
                'Falha ao salvar o arquivo.'
            );
        }

        $publicPath = '/uploads';

        if ($folder !== '') {
            $publicPath .= '/' . $folder;
        }

        return $publicPath . '/' . $filename;
    }

    /**
     * Remove um arquivo usando seu caminho público.
     */
    public static function deleteFile(string $relativePath): void
    {
        $relativePath = '/' . ltrim($relativePath, '/');

        $path = BASE_PATH . '/public' . $relativePath;

        if (is_file($path)) {
            @unlink($path);
        }
    }

    /**
     * Remove uma pasta de uploads e todos os seus arquivos.
     */
    public static function deleteFolder(string $folder): void
    {
        $folder = trim($folder, '/');

        $directory = BASE_PATH . '/public/uploads/' . $folder;

        if (!is_dir($directory)) {
            return;
        }

        foreach (glob($directory . '/*') ?: [] as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        @rmdir($directory);
    }
}
