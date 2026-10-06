<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
  <div class="max-w-md w-full text-center">
    <!-- Código -->
    <p class="text-6xl font-bold text-slate-200 tracking-tight mb-2 select-none">403</p>

    <!-- Título -->
    <h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-3">
      Acesso negado
    </h1>

    <!-- Descrição -->
    <p class="text-sm text-slate-500 leading-relaxed mb-8">
      Você não tem permissão para acessar esta página.
      Se acredita que isso é um erro, entre em contato com o suporte.
    </p>

    <!-- Ações -->
    <div class="flex items-center justify-center gap-3">
      <a href="<?= path('/') ?>"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg
                bg-white border border-[var(--line)]
                hover:border-[var(--brand)]/50 text-slate-600 hover:text-[var(--brand)]
                text-sm font-medium transition">
        <i data-lucide="home" class="w-4 h-4"></i>
        <span>Voltar ao início</span>
      </a>

      <a href="javascript:history.back()"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg
                bg-[var(--brand)] hover:bg-[var(--brand-dim)] text-white
                text-sm font-semibold shadow-lg shadow-red-500/25 transition">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Voltar</span>
      </a>
    </div>

  </div>
</div>
