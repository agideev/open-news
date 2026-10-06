<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\JsonResponse;
use App\Core\Auth;


class AiController
{
    public function chatView(): void
    {
        view('chat.index', [
            'title' => 'Chat',
        ]);
    }

    public function chat(): void
    {
        verify_csrf();

        $usage_limit = env('GROQ_FREE_USAGE', 10); // Usuarios Gratuitos!
        $userId = (int) Auth::id();

        // ----------------------------------------------------------
        // 1) Verifica permissão (sem consumir)
        // ----------------------------------------------------------
        $status = ai_usage_status($userId, 10);

        if (!$status['allowed'] && !is_admin()) {
            JsonResponse::error(
                $status['error'] ?? 'Limite diário atingido. Tente novamente amanhã.',
                429,
                [
                    'used'      => $status['used']  ?? 0,
                    'limit'     => $status['limit'] ?? 0,
                    'remaining' => 0,
                ]
            );
        }


        // ----------------------------------------------------------
        // Entrada (JSON body ou form-data)
        // ----------------------------------------------------------
        $request = new Request();
        $data = $request->only(['question', 'history', 'excluded_ids']);

        $question = $data['question'] ?? '';
        $history  = $data['history']      ?? [];
        $excluded = $data['excluded_ids'] ?? [];

        if ($question === '') {
            JsonResponse::error('Pergunta vazia.', 422);
        }

        if (!is_array($history)) {
            $history = [];
        }

        if (!is_array($excluded)) {
            $excluded = [];
        }

        // ----------------------------------------------------------
        // Sanitiza o histórico vindo do frontend
        // ----------------------------------------------------------
        $cleanHistory = [];

        foreach ($history as $turn) {
            if (!is_array($turn)) continue;

            $role    = $turn['role']    ?? '';
            $content = trim((string) ($turn['content'] ?? ''));

            if (!in_array($role, ['user', 'assistant'], true)) continue;
            if ($content === '') continue;

            $cleanHistory[] = [
                'role'    => $role,
                'content' => mb_substr($content, 0, 1000),
            ];
        }

        // Limita o histórico aos últimos 20 turnos
        if (count($cleanHistory) > 20) {
            $cleanHistory = array_slice($cleanHistory, -20);
        }

        // ----------------------------------------------------------
        // Sanitiza os IDs já recomendados
        // ----------------------------------------------------------
        $excludedIds = array_values(array_unique(array_filter(
            array_map('intval', $excluded),
            fn ($id) => $id > 0
        )));

        // ----------------------------------------------------------
        // Contexto de notícias
        // ----------------------------------------------------------
        $context = ai_news_context(8);

        // ----------------------------------------------------------
        // Chama a IA — retorna array agora
        // ----------------------------------------------------------
        $reply = ai_chat($question, $cleanHistory, $context, $excludedIds);

        if (empty($reply['answer'])) {
            JsonResponse::error('Falha ao consultar a IA.', 500);
        }

        if(!is_admin()){
            ai_usage_consume($userId, $usage_limit);
        }

        // ----------------------------------------------------------
        // Resposta
        // ----------------------------------------------------------
        JsonResponse::success('OK', [
            'answer'           => $reply['answer'],
            'related_news'     => $reply['related_news'],
            'related_news_ids' => $reply['related_news_ids'],
        ]);
    }

    // ----------------------------------------------------------
    // Metodos Baixo Usandos exlusivamente para tests
    // ----------------------------------------------------------
    public function test(): void
    {
        header('Content-Type: text/plain; charset=utf-8');

        // ----------------------------------------------------------
        // Entrada
        // ----------------------------------------------------------
        $question = trim((string) ($_GET['q'] ?? ''));

        if ($question === '') {
            $question = 'Os jovens estao evoluindo?';
        }

        // Histórico de exemplo (opcional via ?history=1)
        $history = [];

        if (($_GET['history'] ?? '') === '1') {
            $history = [
                ['role' => 'user',      'content' => 'Olá!'],
                ['role' => 'assistant', 'content' => 'Olá! Como posso ajudar com as notícias?'],
            ];
        }

        // IDs já recomendados (opcional via ?excluded_ids=1,2,3)
        $excludedIds = [];

        if (!empty($_GET['excluded_ids'])) {
            $excludedIds = array_values(array_filter(
                array_map('intval', explode(',', (string) $_GET['excluded_ids'])),
                fn ($id) => $id > 0
            ));
        }

        // ----------------------------------------------------------
        // Contexto
        // ----------------------------------------------------------
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit'])
            ? (int) $_GET['limit']
            : 20;

        $context = ai_news_context($limit);

        // ----------------------------------------------------------
        // Cronômetro
        // ----------------------------------------------------------
        $startedAt = microtime(true);

        $reply = ai_chat($question, $history, $context, $excludedIds);

        $elapsed = round(microtime(true) - $startedAt, 2);

        // ----------------------------------------------------------
        // Cabeçalho do teste
        // ----------------------------------------------------------
        echo "=== AI CHAT — TESTE ===\n\n";

        echo "Pergunta     : {$question}\n";
        echo "Histórico    : " . (empty($history) ? 'não' : count($history) . ' turnos') . "\n";
        echo "Contexto     : " . strlen($context) . " bytes ({$limit} notícias)\n";
        echo "Excluídos    : " . (empty($excludedIds) ? '(nenhum)' : implode(', ', $excludedIds)) . "\n";
        echo "Tempo        : {$elapsed}s\n\n";

        // ----------------------------------------------------------
        // Erro
        // ----------------------------------------------------------
        if (empty($reply['answer'])) {
            echo "STATUS   : ERRO\n";
            echo "Mensagem : Falha ao consultar a IA ou resposta vazia.\n";
            return;
        }

        echo "STATUS   : OK\n";
        echo "Tamanho  : " . mb_strlen($reply['answer']) . " caracteres\n\n";

        // ----------------------------------------------------------
        // Resposta
        // ----------------------------------------------------------
        echo "--- Answer ---\n";
        echo $reply['answer'] . "\n\n";

        // ----------------------------------------------------------
        // Notícias relacionadas
        // ----------------------------------------------------------
        echo "--- Related News ---\n";

        if (empty($reply['related_news'])) {
            echo "(nenhuma)\n";
        } else {
            foreach ($reply['related_news'] as $i => $news) {
                $n = $i + 1;
                echo "[{$n}] id        : " . ($news['id']        ?? '') . "\n";
                echo "    title     : " . ($news['title']     ?? '') . "\n";
                echo "    image_url : " . ($news['image_url'] ?? '') . "\n";
                echo "    audio_url : " . ($news['audio_url'] ?? '') . "\n";
                echo "    url : " . ($news['url'] ?? '') . "\n";
            }
        }

        echo "\n";

        // ----------------------------------------------------------
        // IDs recomendados
        // ----------------------------------------------------------
        echo "--- Related News IDs ---\n";

        if (empty($reply['related_news_ids'])) {
            echo "(nenhum)\n";
        } else {
            echo implode(', ', $reply['related_news_ids']) . "\n";
        }

        echo "\n";

        // ----------------------------------------------------------
        // Debug: JSON bruto (opcional via ?debug=1)
        // ----------------------------------------------------------
        if (($_GET['debug'] ?? '') === '1') {
            echo "--- JSON bruto ---\n";
            echo json_encode(
                $reply,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ) . "\n";
        }
    }

    public function context(): void
    {
        header('Content-Type: text/plain; charset=utf-8');

        $context = ai_news_context(6);

        $size = strlen($context);

        echo "=== Contexto gerado ({$size} bytes) ===\n\n";
        echo $context;
        echo "\n\n=== Fim ===\n";
    }
}
