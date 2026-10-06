<?php

/**
 * Define uma mensagem flash para a próxima view.
 */
function setMessage(string $type, string $text): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['_flash_message'] = [
        'type' => $type,
        'text' => $text,
    ];
}

/**
 * Renderiza e limpa a mensagem flash atual.
 */
function message(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $msg = $_SESSION['_flash_message'] ?? null;
    unset($_SESSION['_flash_message']);

    if (!is_array($msg) || empty($msg['text'])) {
        return;
    }

    $type = in_array($msg['type'] ?? '', ['success', 'error', 'warning'], true)
        ? $msg['type']
        : 'success';

    $map = [
        'success' => [
            'icon'  => 'check-circle-2',
            'class' => 'border-emerald-500/40 bg-emerald-500/10 text-emerald-200',
        ],
        'error' => [
            'icon'  => 'x-circle',
            'class' => 'border-red-500/40 bg-red-500/10 text-red-200',
        ],
        'warning' => [
            'icon'  => 'alert-triangle',
            'class' => 'border-amber-500/40 bg-amber-500/10 text-amber-200',
        ],
    ];

    $conf = $map[$type];
    ?>

    <div class="flash-message flex items-start gap-3 min-w-[280px] max-w-sm px-4 py-3 rounded-xl border backdrop-blur shadow-lg <?= $conf['class'] ?>">
        <i data-lucide="<?= $conf['icon'] ?>" class="w-5 h-5 mt-0.5 shrink-0"></i>
        <p class="text-sm leading-snug flex-1"><?= e($msg['text']) ?></p>
        <button type="button" class="flash-close opacity-70 hover:opacity-100 transition">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>

    <script>
    (function () {
        document.querySelectorAll('.flash-message').forEach(function (el) {
            var close = function () {
                el.style.transition = 'opacity .3s ease, transform .3s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                setTimeout(function () { el.remove(); }, 300);
            };
            var timer = setTimeout(close, 5000);
            var btn = el.querySelector('.flash-close');
            if (btn) {
                btn.addEventListener('click', function () {
                    clearTimeout(timer);
                    close();
                });
            }
        });
    })();
    </script>

    <?php
}
