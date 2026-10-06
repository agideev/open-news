<?php

declare(strict_types=1);

if (!function_exists('requireDirectory')) {
    /**
     * Carrega todos os arquivos PHP de uma pasta e subpastas.
     *
     * @param string   $directory  Caminho absoluto ou relativo da pasta.
     * @param string[] $exceptions Nomes de arquivos a ignorar.
     */
    function requireDirectory(string $directory, array $exceptions = []): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $directory,
                RecursiveDirectoryIterator::SKIP_DOTS
            )
        );

        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            if (in_array($file->getFilename(), $exceptions, true)) {
                continue;
            }

            require_once $file->getPathname();
        }
    }
}
