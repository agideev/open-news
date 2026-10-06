<?php

namespace App\Core;

class Request
{
    private array $data = [];

    public function __construct()
    {
        $this->data = $this->getData();
    }

    /**
     * Retorna todos os dados enviados.
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Retorna um valor específico.
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Verifica se um campo existe.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Retorna apenas os campos informados.
     */
    public function only(array $keys): array
    {
        return array_intersect_key(
            $this->data,
            array_flip($keys)
        );
    }

    /**
     * Retorna o método HTTP.
     */
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Retorna o Content-Type.
     */
    public function contentType(): string
    {
        return $_SERVER['CONTENT_TYPE'] ?? '';
    }

    /**
     * Retorna os dados da requisição.
     */
    private function getData(): array
    {
        if ($this->isJson()) {
            return $this->getJson();
        }

        return $_POST;
    }

    /**
     * Verifica se a requisição contém JSON.
     */
    private function isJson(): bool
    {
        $contentType = strtolower($this->contentType());

        return str_contains(
            $contentType,
            'application/json'
        );
    }

    /**
     * Lê e decodifica o JSON.
     */
    private function getJson(): array
    {
        $content = file_get_contents('php://input');

        if (!$content) {
            return [];
        }

        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }
}
