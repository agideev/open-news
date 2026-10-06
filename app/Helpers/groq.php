<?php

/**
 * Helper de integração com a API Groq (Responses API).
 *
 * Uso básico:
 *   $text = groq_ask('Explique o que é PHP.');
 *
 * Com system prompt e parâmetros:
 *   $text = groq_ask('Resuma esta notícia.', [
 *       'instructions' => 'Você é um jornalista. Responda em português.',
 *       'temperature'  => 0.3,
 *       'max_tokens'   => 200,
 *   ]);
 */

if (!function_exists('groq_ask')) {

    /**
     * Envia um prompt para a Groq e retorna o texto da resposta.
     *
     * @param string $input   O prompt do usuário.
     * @param array  $options Opções adicionais:
     *   - string $model            (default: 'openai/gpt-oss-20b')
     *   - string $instructions     System prompt (opcional)
     *   - int    $max_tokens       Limite de tokens de saída (opcional)
     *   - float  $temperature      Criatividade (0 a 2, default: 1)
     *   - float  $top_p            Nucleus sampling (0 a 1, default: 1)
     *   - bool   $stream           Streaming (default: false) — não suportado neste helper
     *
     * @return string|null Texto da resposta ou null em caso de erro.
     */
    function groq_ask(string $input, array $options = []): ?string
    {

        $response = groq_response($input, $options);

        if ($response === null) {
            return null;
        }

        // A API de Responses retorna `output_text` (string) quando disponível.
        if (!empty($response['output_text'])) {
            return (string) $response['output_text'];
        }

        // Fallback: extrai o texto do array `output` (formato bruto)
        if (!empty($response['output']) && is_array($response['output'])) {
            $text = '';
            foreach ($response['output'] as $item) {
                if (($item['type'] ?? '') === 'message' && !empty($item['content'])) {
                    foreach ($item['content'] as $part) {
                        if (($part['type'] ?? '') === 'output_text') {
                            $text .= $part['text'] ?? '';
                        }
                    }
                }
            }
            return $text !== '' ? $text : null;
        }

        return null;
    }

    /**
     * Envia um prompt para a Groq e retorna o array completo de resposta.
     *
     * @return array|null
     */
    function groq_response(string $input, array $options = []): ?array
    {
        // ----------------------------------------------------------
        // API key
        // ----------------------------------------------------------
        $apiKey = $_ENV['GROQ_API_KEY']
            ?? getenv('GROQ_API_KEY')
            ?: null;

        if (empty($apiKey)) {
            error_log('[groq] GROQ_API_KEY não definida.');
            return null;
        }

        // ----------------------------------------------------------
        // Modelo (prioridade: options > env > fallback)
        // ----------------------------------------------------------
        $model = $options['model']
            ?? $_ENV['GROQ_MODEL']
            ?? getenv('GROQ_MODEL')
            ?: 'qwen/qwen3.8-27b';

        // ----------------------------------------------------------
        // Demais opções
        // ----------------------------------------------------------
        $instructions = $options['instructions'] ?? null;
        $maxTokens    = $options['max_tokens']   ?? null;
        $temperature  = $options['temperature']  ?? null;
        $topP         = $options['top_p']        ?? null;

        $payload = [
            'model' => $model,
            'input' => $input,
        ];

        if ($instructions !== null) {
            $payload['instructions'] = $instructions;
        }
        if ($maxTokens !== null) {
            $payload['max_output_tokens'] = (int) $maxTokens;
        }
        if ($temperature !== null) {
            $payload['temperature'] = (float) $temperature;
        }
        if ($topP !== null) {
            $payload['top_p'] = (float) $topP;
        }

        $ch = curl_init('https://api.groq.com/openai/v1/responses');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $raw  = curl_exec($ch);
        $err  = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($raw === false) {
            error_log('[groq] cURL erro: ' . $err);
            return null;
        }

        $data = json_decode($raw, true);

        if (!is_array($data)) {
            error_log('[groq] Resposta inválida: ' . $raw);
            return null;
        }

        if ($code >= 400) {
            $msg = $data['error']['message'] ?? ('HTTP ' . $code);
            error_log('[groq] Erro API: ' . $msg);
            return null;
        }

        return $data;
    }
}


if (!function_exists('ai_news_context')) {

/**
 * Busca as notícias publicadas no banco via Model News e retorna
 * um texto formatado como base de conhecimento para a IA.
 *
 * Formato:
 *   Notícias:
 *
 *   title: ...
 *   content: ...
 *   image_url: ...
 *   audio_url: ...
 *   url: ...
 *
 * @param int|null $limit  Limita a quantidade de notícias (null = todas)
 * @return string
 */
function ai_news_context(?int $limit = null): string
    {
        $newsModel = new \App\Models\News();

        // recent() limita o perPage a 100 no model — respeitamos isso
        $perPage = $limit !== null && $limit > 0
            ? min(100, $limit)
            : 100;

        $result = $newsModel->recent(1, $perPage, 'published');
        $rows   = $result['items'] ?? [];

        if (empty($rows)) {
            return 'Notícias: (nenhuma notícia publicada)';
        }

        $lines = ['Notícias:', ''];

        foreach ($rows as $row) {
            $id      = trim((string) ($row['id']      ?? ''));
            $title   = trim((string) ($row['title']   ?? ''));
            $content = trim((string) ($row['content'] ?? ''));
            $slug    = trim((string) ($row['slug']    ?? ''));
            $audio   = trim((string) ($row['audio']   ?? ''));

            // Limita o content a 300 caracteres (multibyte-safe)
            if (mb_strlen($content) > 250) {
                $content = mb_substr($content, 0, 250) . '…';
            }

            if ($id !== '') {
                $lines[] = 'id: '      . $id;
            }

            $lines[] = 'title: '   . $title;
            $lines[] = 'content: ' . $content;

            // Imagem principal (já vem com image_url pronta do model)
            if (!empty($row['image_url'])) {
                $lines[] = 'image_url: ' . $row['image_url'];
            }

            // Áudio principal
            if ($audio !== '') {
                $lines[] = 'audio_url: ' . url('/' . ltrim($audio, '/'));
            }

            // URL pública da notícia
            if ($slug !== '') {
                $lines[] = 'url: ' . url('/news/' . $slug);
            }

            $lines[] = '';
        }

        return rtrim(implode("\n", $lines));
    }
}


function ai_chat(
    string $question,
    array  $history = [],
    string $context = '',
    array  $excludedIds = []
): array {
    $question = trim($question);

    $fallback = [
        'answer'           => '',
        'related_news'     => [],
        'related_news_ids' => [],
    ];

    if ($question === '') {
        return $fallback;
    }

    // ----------------------------------------------------------
    // Normaliza os IDs já recomendados
    // ----------------------------------------------------------
    $excludedIds = array_values(array_unique(array_filter(
        array_map('intval', $excludedIds),
        fn ($id) => $id > 0
    )));

    $excludedList = !empty($excludedIds)
        ? implode(', ', $excludedIds)
        : '(nenhum)';

    // ----------------------------------------------------------
    // Personalidade + regras + formato JSON
    // ----------------------------------------------------------

    // Prompt via config
    $instructions = ai_prompt($context, $excludedList);

    // ----------------------------------------------------------
    // Monta o input: histórico + pergunta atual
    // ----------------------------------------------------------
    $input = '';

    if (!empty($history)) {
        $input .= "HISTÓRICO DA CONVERSA:\n";

        foreach ($history as $turn) {
            $role    = $turn['role'] ?? 'user';
            $content = trim((string) ($turn['content'] ?? ''));

            if ($content === '') continue;

            $label  = $role === 'assistant' ? 'Assistente' : 'Usuário';
            $input .= "{$label}: {$content}\n";
        }

        $input .= "\n";
    }

    $input .= "PERGUNTA ATUAL: {$question}";

    // ----------------------------------------------------------
    // Chama a IA forçando JSON
    // ----------------------------------------------------------
    $raw = groq_ask($input, [
        'instructions'    => $instructions,
        'temperature'     => 0.3,
        'max_tokens'      => 700,
        'response_format' => ['type' => 'json_object'],
    ]);

    if ($raw === null || trim($raw) === '') {
        return $fallback;
    }

    // ----------------------------------------------------------
    // Extrai e decodifica o JSON
    // ----------------------------------------------------------
    $clean = trim($raw);

    // Remove cercas de código, se aparecerem
    $clean = preg_replace('/^```(?:json|text)?\s*/i', '', $clean);
    $clean = preg_replace('/\s*```$/', '', $clean);
    $clean = trim($clean);

    // Se a IA adicionou texto em volta, isola o bloco { ... }
    if ($clean !== '' && $clean[0] !== '{') {
        $start = strpos($clean, '{');
        $end   = strrpos($clean, '}');

        if ($start !== false && $end !== false && $end > $start) {
            $clean = substr($clean, $start, $end - $start + 1);
        }
    }

    $data = json_decode($clean, true);

    if (!is_array($data)) {
        // Fallback: veio texto puro em vez de JSON
        return [
            'answer'           => mb_substr(trim($raw), 0, 400),
            'related_news'     => [],
            'related_news_ids' => [],
        ];
    }

    // ----------------------------------------------------------
    // Sanitiza o retorno
    // ----------------------------------------------------------
    $answer = trim((string) ($data['answer'] ?? ''));
    $answer = mb_substr($answer, 0, 400);

    $relatedNews = [];
    $relatedIds  = [];

    // Aceita apenas 1 notícia (a primeira) e valida contra os excluídos
    $candidates = $data['related_news'] ?? [];

    if (is_array($candidates) && !empty($candidates)) {
        $item = $candidates[0];

        if (is_array($item)) {
            $id = (int) ($item['id'] ?? 0);

            // Rejeita se id inválido ou se já foi recomendado antes
            if ($id > 0 && !in_array($id, $excludedIds, true)) {
                $relatedNews[] = [
                    'id'        => $id,
                    'title'     => trim((string) ($item['title']     ?? '')),
                    'audio_url' => trim((string) ($item['audio_url'] ?? '')),
                    'image_url' => trim((string) ($item['image_url'] ?? '')),
                    'url' => trim((string) ($item['url'] ?? '')),
                ];

                $relatedIds[] = $id;
            }
        }
    }

    return [
        'answer'           => $answer,
        'related_news'     => $relatedNews,
        'related_news_ids' => $relatedIds,
    ];
}


if (!function_exists('ai_usage_status')) {

    /**
     * Verifica o status de uso do usuário hoje (sem consumir).
     *
     * @return array {
     *     allowed:   bool,
     *     used:      int,
     *     limit:     int,
     *     remaining: int,
     *     error:     string|null
     * }
     */
    function ai_usage_status(int $userId, int $dailyLimit = 20): array
    {
        $model = new \App\Models\AiUsage();
        $today = date('Y-m-d');

        $row = $model->findByUserAndDate($userId, $today);

        // Primeiro uso do dia
        if ($row === null) {
            return [
                'allowed'   => true,
                'used'      => 0,
                'limit'     => $dailyLimit,
                'remaining' => $dailyLimit,
                'error'     => null,
            ];
        }

        $used  = (int) $row['credits_used'];
        $limit = (int) $row['daily_limit'];

        if ($used >= $limit) {
            return [
                'allowed'   => false,
                'used'      => $used,
                'limit'     => $limit,
                'remaining' => 0,
                'error'     => 'Limite diário atingido. Tente novamente amanhã.',
            ];
        }

        return [
            'allowed'   => true,
            'used'      => $used,
            'limit'     => $limit,
            'remaining' => $limit - $used,
            'error'     => null,
        ];
    }
}

if (!function_exists('ai_usage_consume')) {

    /**
     * Consome 1 crédito do usuário (chamar SOMENTE após sucesso).
     *
     * @return array {
     *     used:      int,
     *     limit:     int,
     *     remaining: int
     * }
     */
    function ai_usage_consume(int $userId, int $dailyLimit = 20): array
    {
        $model = new \App\Models\AiUsage();
        $today = date('Y-m-d');

        $row = $model->findByUserAndDate($userId, $today);

        // Primeira vez do dia → cria
        if ($row === null) {
            $model->create([
                'user_id'      => $userId,
                'usage_date'   => $today,
                'credits_used' => 1,
                'daily_limit'  => $dailyLimit,
            ]);

            return [
                'used'      => 1,
                'limit'     => $dailyLimit,
                'remaining' => $dailyLimit - 1,
            ];
        }

        $used  = (int) $row['credits_used'];
        $limit = (int) $row['daily_limit'];
        $new   = $used + 1;

        $model->update((int) $row['id'], [
            'credits_used' => $new,
        ]);

        return [
            'used'      => $new,
            'limit'     => $limit,
            'remaining' => max(0, $limit - $new),
        ];
    }
}
