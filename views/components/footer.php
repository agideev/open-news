<?php
  $currentYear = date('Y');
?>

<footer class="mt-16 border-t border-[var(--line)] bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-12">

    <!-- Topo: Logo + Descrição + Colunas -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-8 mb-10">

      <!-- Logo + Descrição (ocupa 2 colunas em telas grandes) -->
      <div class="col-span-2 lg:col-span-2 space-y-4">
        <a href="<?= path('/') ?>" class="inline-flex items-center group">
          <img src="<?= asset('img/logo.png') ?>"
               alt="logo"
               class="h-8 w-auto object-contain transition-opacity group-hover:opacity-90">
        </a>

        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-sm">
          Acompanhe as últimas notícias, lançamentos e novidades do mundo dos jogos.
          Informação rápida, confiável e direto ao ponto.
        </p>
      </div>

      <!-- Coluna 1: Navegação -->
      <div>
        <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">
          Navegação
        </h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="<?= path('/') ?>"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Início
            </a>
          </li>
          <li>
            <a href="<?= path('/news') ?>"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Notícias
            </a>
          </li>
          <li>
            <a href="<?= path('/favorites') ?>"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Favoritos
            </a>
          </li>
          <li>
            <a href="<?= path('/notifications') ?>"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Notificações
            </a>
          </li>
        </ul>
      </div>

      <!-- Coluna 2: Suporte -->
      <div>
        <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">
          Suporte
        </h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="#"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Central de ajuda
            </a>
          </li>
          <li>
            <a href="#"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Como funciona
            </a>
          </li>
          <li>
            <a href="#"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Contato
            </a>
          </li>
          <li>
            <a href="#"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Reportar problema
            </a>
          </li>
        </ul>
      </div>

      <!-- Coluna 3: Legal -->
      <div>
        <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">
          Legal
        </h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="#"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Termos de uso
            </a>
          </li>
          <li>
            <a href="#"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Privacidade
            </a>
          </li>
          <li>
            <a href="#"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Cookies
            </a>
          </li>
          <li>
            <a href="#"
               class="text-slate-500 hover:text-[var(--brand)] transition-colors">
              Reembolsos
            </a>
          </li>
        </ul>
      </div>

    </div>

    <!-- Divisor -->
    <div class="border-t border-[var(--line)] pt-6">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

        <!-- Copyright -->
        <p class="text-xs text-slate-500 text-center sm:text-left">
          © <?= $currentYear ?> <span class="text-slate-700 font-medium">Agi Dev</span>.
          Todos os direitos reservados.
        </p>

        <!-- Info -->
        <p class="text-xs text-slate-500 flex items-center gap-1.5">
          <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[var(--brand)]"></i>
          Informação verificada · Atualizado diariamente
        </p>

      </div>
    </div>

  </div>
</footer>
