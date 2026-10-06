<?php $user = current_user(); ?>
<header class="sticky top-0 z-40 bg-white/80 border-b border-[var(--line)]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center gap-2">

    <!-- Logo -->
    <a href="<?= path('/') ?>" class="flex items-center shrink-0 group">
      <img src="<?= asset('img/logo.png') ?>"
           alt="GameStore"
           class="h-12 w-auto object-contain transition-opacity group-hover:opacity-90">
    </a>

    <!-- Nav Desktop (colado na logo) -->
    <nav class="hidden md:flex items-center gap-1 text-sm ml-2">
      <a href="<?= path('/news') ?>"
         class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-slate-500 hover:text-[var(--brand)] hover:bg-red-50 transition-colors">
        <i data-lucide="newspaper" class="w-4 h-4"></i>
        <span>Notícias</span>
      </a>

      <?php if ($user): ?>
        <?php if (($user['role'] ?? 'user') === 'admin'): ?>
          <a href="<?= path('/admin/news') ?>"
             class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-slate-500 hover:text-[var(--brand)] hover:bg-red-50 transition-colors">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            <span>Gerenciar Notícias</span>
          </a>
        <?php endif; ?>
      <?php endif; ?>
    </nav>

        <!-- Spacer -->
    <div class="flex-1"></div>

    <?php if ($user): ?>
      <?php partial('nav-buttons/news'); ?>
      <?php partial('nav-buttons/favorites'); ?>
      <?php partial('nav-buttons/chat'); ?>
    <?php endif; ?>

    <!-- Ações Desktop -->
    <div class="hidden md:flex items-center gap-2">
      <?php if ($user): ?>
        <div class="flex items-center gap-2.5 pl-1 pr-3 py-1 rounded-full bg-red-50 border border-[var(--brand)]/20
            hover:border-[var(--brand)]/50 transition-colors">
          <span class="grid place-items-center w-8 h-8 rounded-full
                       bg-gradient-to-br from-[var(--brand)] to-[var(--brand-dim)]
                       text-white font-bold text-xs shadow-sm shadow-red-500/30">
            <?= strtoupper(substr($user['username'], 0, 1)) ?>
          </span>
          <span class="text-sm text-slate-700 font-semibold"><?= htmlspecialchars($user['username']) ?></span>
        </div>

        <button data-logout
                class="grid place-items-center w-9 h-9 rounded-lg text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-colors"
                title="Sair">
          <i data-lucide="log-out" class="w-4 h-4"></i>
        </button>
      <?php else: ?>
       <a href="<?= path('/login') ?>" class="px-4 py-2 text-sm text-slate-500 hover:text-[var(--brand)] transition-colors">Entrar</a>
        <a href="<?= path('/register') ?>"
           class="px-4 py-2 text-sm font-semibold bg-[var(--brand)] text-white rounded-lg hover:bg-[var(--brand-dim)] shadow-lg shadow-red-500/20 transition-all hover:shadow-red-500/40">
          Criar conta
        </a>
      <?php endif; ?>
    </div>

    <!-- Botão Mobile -->
    <button id="mobile-menu-btn"
            class="md:hidden grid place-items-center w-10 h-10 rounded-lg text-slate-700 hover:text-[var(--brand)] hover:bg-red-50 transition-colors"
            aria-label="Menu"
            aria-expanded="false">
      <i data-lucide="menu" class="w-6 h-6"></i>
    </button>
  </div>

  <!-- Menu Mobile -->
  <div id="mobile-menu" class="md:hidden transition bg-white border-t border-[var(--line)] overflow-hidden">
    <div class="px-4 py-4 space-y-1 text-sm">

      <?php if ($user): ?>
        <!-- Perfil Mobile -->
        <div class="flex items-center gap-3 p-3 mb-3 rounded-xl bg-gradient-to-r from-red-50 to-transparent border border-[var(--brand)]/20">
          <span class="grid place-items-center w-10 h-10 rounded-full bg-gradient-to-br from-[var(--brand)] to-[var(--brand-dim)] text-white font-bold">
            <?= strtoupper(substr($user['username'], 0, 1)) ?>
          </span>
          <div class="min-w-0">
            <p class="text-slate-900 font-semibold truncate"><?= htmlspecialchars($user['username']) ?></p>
            <p class="text-[var(--brand)] text-xs">Conectado</p>
          </div>
        </div>
      <?php endif; ?>

      <a href="<?= path('/news') ?>"
         class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-slate-500 hover:text-[var(--brand)] hover:bg-red-50 transition-colors">
        <i data-lucide="newspaper" class="w-4 h-4 text-[var(--brand)]"></i>
        Notícias
      </a>

      <?php if ($user): ?>
      <?php if (($user['role'] ?? 'user') === 'admin'): ?>
        <a href="<?= path('/admin/news') ?>"
           class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-slate-500 hover:text-[var(--brand)] hover:bg-red-50 transition-colors">
          <i data-lucide="newspaper" class="w-4 h-4 text-[var(--brand)]"></i>
          Gerenciar Notícias
        </a>
      <?php endif; ?>

      <div class="pt-3 mt-3 border-t border-[var(--line)]">
        <button data-logout
                class="w-full flex items-center gap-3 px-3.5 py-3 rounded-xl text-[var(--brand)] hover:text-[var(--brand-dim)] hover:bg-red-50 transition-colors">
          <i data-lucide="log-out" class="w-4 h-4"></i> Sair
        </button>
      </div>
    <?php else: ?>
        <div class="pt-3 mt-3 border-t border-[var(--line)] space-y-2">
          <a href="<?= path('/login') ?>"
             class="block px-3.5 py-3 rounded-xl text-center text-slate-500 hover:text-[var(--brand)] hover:bg-red-50 border border-[var(--line)] transition-colors">
            Entrar
          </a>
          <a href="<?= path('/register') ?>"
             class="block px-3.5 py-3 rounded-xl text-center font-semibold bg-[var(--brand)] text-white hover:bg-[var(--brand-dim)] shadow-lg shadow-red-500/20 transition-all">
            Criar conta
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- Modal de Logout -->
<div id="logout-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
  <div class="bg-white border border-[var(--line)] rounded-2xl p-6 w-full max-w-sm shadow-2xl">
    <h3 class="text-lg font-semibold text-slate-900 text-center mb-2">Sair da conta?</h3>
    <p class="text-sm text-slate-500 text-center mb-6">
      Você precisará fazer login novamente para continuar.
    </p>

    <div class="flex items-center gap-3">
      <button type="button" id="logout-cancel"
              class="flex-1 px-4 py-2.5 rounded-lg bg-white border border-[var(--line)]
                     hover:border-slate-300 hover:bg-slate-50 text-slate-600 text-sm font-medium transition">
        Cancelar
      </button>
      <button type="button" id="logout-confirm"
              class="flex-1 px-4 py-2.5 rounded-lg bg-[var(--brand)] hover:bg-[var(--brand-dim)] text-white
                     text-sm font-semibold shadow-lg shadow-red-500/25 transition">
        Sair
      </button>
    </div>
  </div>
</div>

<style>
  #mobile-menu { display: none; }
  #mobile-menu.is-open { display: block; }
</style>

<script>
(function () {
  /* ---------- Menu Mobile ---------- */
  const btn  = document.getElementById('mobile-menu-btn');
  const menu = document.getElementById('mobile-menu');

  function setIcon(open) {
  btn.innerHTML = open
    ? '<i data-lucide="x" class="w-6 h-6"></i>'
    : '<i data-lucide="menu" class="w-6 h-6"></i>';
  if (window.lucide) window.lucide.createIcons();
}

function closeMenu() {
  menu.classList.remove('is-open');
  btn.setAttribute('aria-expanded', 'false');
  setIcon(false);
}

if (btn && menu) {
  btn.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const willOpen = !menu.classList.contains('is-open');
    menu.classList.toggle('is-open', willOpen);
    btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    setIcon(willOpen);

    // Gira o botão
    btn.classList.toggle('rotate-90', willOpen);
  });

    menu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', closeMenu);
    });

    document.addEventListener('click', function (e) {
      if (!menu.contains(e.target) && !btn.contains(e.target)) closeMenu();
    });

    window.addEventListener('resize', function () {
      if (window.innerWidth >= 768) closeMenu();
    });
  }

  /* ---------- Modal de Logout ---------- */
  const logoutModal  = document.getElementById('logout-modal');
  const logoutCancel = document.getElementById('logout-cancel');
  const logoutOk     = document.getElementById('logout-confirm');

  document.querySelectorAll('[data-logout]').forEach(function (trigger) {
    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      logoutModal.classList.remove('hidden');
    });
  });

  logoutCancel.addEventListener('click', function () {
    logoutModal.classList.add('hidden');
  });

  logoutModal.addEventListener('click', function (e) {
    if (e.target === logoutModal) logoutModal.classList.add('hidden');
  });

  logoutOk.addEventListener('click', async function () {
    logoutOk.disabled = true;

    try {
      await fetch('<?= path('/api/auth/logout') ?>', {
        method: 'POST',
        headers: {
          'X-CSRF-Token': '<?= e(csrf_token()) ?>',
          'Accept': 'application/json',
        },
        credentials: 'same-origin',
      });
    } catch (_) {
      // segue o fluxo mesmo em falha
    }

    window.location.href = '<?= path('/') ?>';
  });
})();
</script>
