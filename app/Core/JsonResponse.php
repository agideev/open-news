<?php

namespace App\Core;

class JsonResponse
{
    /**
     * Retorna uma resposta JSON de sucesso.
     */
    public static function success(
        string $message = 'Operação realizada com sucesso.',
        mixed $data = null,
        int $status = 200
    ): never {
        self::send([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * Retorna uma resposta JSON de erro.
     */
    public static function error(
        string $message = 'Ocorreu um erro.',
        int $status = 400,
        mixed $errors = null
    ): never {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        self::send($response, $status);
    }

    /**
     * Envia a resposta JSON.
     */
    private static function send(array $response, int $status): never
    {
        http_response_code($status);

        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode(
            $response,
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES |
            JSON_THROW_ON_ERROR
        );

        exit;
    }
}
