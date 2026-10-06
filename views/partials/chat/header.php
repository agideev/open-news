<!-- views/partials/chat/header.php -->
<header class="fixed inset-x-0 top-0 z-50 h-16 border-b border-[var(--line)] bg-white">

    <div class="mx-auto flex h-full max-w-4xl items-center justify-between px-4">

        <div class="flex items-center gap-3">
            <a href="<?= path('/') ?>" class="flex items-center shrink-0 group">
                <img src="<?= asset('img/assistente.png') ?>"
                     alt="Open News"
                     class="h-14 w-auto object-contain transition-opacity group-hover:opacity-90">
            </a>
        </div>

       <a href="<?= path('/news') ?>"
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg
                  text-xs font-semibold text-white
                  bg-[var(--brand)] hover:bg-[var(--brand-dim)]
                  shadow-md shadow-red-500/25 hover:shadow-red-500/40
                  active:scale-95
                  transition-all duration-200">
            <i data-lucide="newspaper" class="w-4 h-4"></i>
            <span>Notícias</span>
        </a>

    </div>

</header>
