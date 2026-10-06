<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="<?= e(csrf_token()) ?>"
    >

    <title>
        <?= e($title ?? 'Open News') ?> — Open News
    </title>

    <!-- Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- Tailwind CSS 4 -->
    <link
        rel="stylesheet"
        href="<?= asset('/css/app.css') ?>"
    >

    <!-- Favicon -->
    <link
        rel="icon"
        type="image/x-icon"
        href="<?= asset('/img/logo.ico') ?>"
    >
    <!-- Lucide -->
    <script src="https://unpkg.com/lucide@0.468.0"></script>
    <script src="<?= asset('js/submit-loader.js') ?>"></script>

    <style>
        :root {
            --brand:      #dc2626;
            --brand-soft: #ef4444;
            --brand-dim:  #b91c1c;
            --ink:        #0f172a;
            --ink-soft:   #475569;
            --line:       #e5e7eb;
        }

        html, body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; }

        body {
            background: #ffffff;
            color: var(--ink);
        }

        /* Scrollbar premium */
        ::-webkit-scrollbar              { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track        { background: #f8fafc; }
        ::-webkit-scrollbar-thumb        { background: #e2e8f0; border-radius: 999px; border: 2px solid #f8fafc; }
        ::-webkit-scrollbar-thumb:hover  { background: var(--brand-soft); }

        /* Seleção */
        ::selection { background: rgba(220, 38, 38, 0.18); color: var(--brand-dim); }

        /*/* Focus ring global */
        :focus-visible {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
            border-radius: 6px;
        }

        /* Loader ring (usado pelo submit-loader) */
        .loader-ring {
            animation: spin 0.6s linear infinite;
            opacity: 0.9;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>

<body class="min-h-screen flex flex-col antialiased bg-white text-slate-900">

<?php
$route = \App\Core\Router::currentRoute();
$hideChrome = in_array($route, ['login', 'register'], true);
$hideFooter = in_array($route, ['chat'], true);
?>

<?php if (!$hideChrome && !$hideFooter): ?>
    <?php require BASE_PATH . '/views/components/navbar.php'; ?>
<?php endif; ?>

<main class="flex-1">
    <?= $content ?>
</main>

<?php if (!$hideChrome && !$hideFooter): ?>
    <?php require BASE_PATH . '/views/components/footer.php'; ?>
<?php endif; ?>

<script>
    lucide.createIcons();
</script>

</body>
</html>
