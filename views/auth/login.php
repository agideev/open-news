<section class="max-w-md mx-auto px-4 py-16">
  <!-- Header da página -->
  <div class="text-center mb-8">
    <a href="<?= path('/') ?>" class="inline-block mb-4 group">
      <img src="<?= asset('img/logo.png') ?>"
           alt="GameStore"
           class="h-16 w-auto object-contain mx-auto transition-opacity group-hover:opacity-90">
    </a>
    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Bem-vindo de volta</h1>
    <p class="text-sm text-slate-500 mt-2">Entre na sua conta para continuar</p>
  </div>

  <form id="login-form" novalidate
        class="bg-white rounded-2xl border border-[var(--line)] p-6 sm:p-8 space-y-5 shadow-sm">

    <!-- CSRF (o controller chama verify_csrf()) -->
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

    <!-- Email -->
    <div>
      <label for="email" class="block text-sm font-semibold text-slate-800 mb-2">Email</label>
      <div class="relative">
        <i data-lucide="mail"
           class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
        <input id="email" type="email" name="email" required
               placeholder="voce@email.com"
               class="w-full pl-10 pr-3 py-2.5 bg-white border border-[var(--line)] rounded-lg text-slate-900
                      placeholder:text-slate-400
                      focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
                      transition-all duration-200">
      </div>
      <p class="text-xs text-[var(--brand)] mt-1.5 hidden" data-error="email"></p>
    </div>

    <!-- Senha -->
    <div>
      <label for="password" class="block text-sm font-semibold text-slate-800 mb-2">Senha</label>
      <div class="relative">
        <i data-lucide="lock"
           class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
        <input id="password" type="password" name="password" required
               placeholder="••••••••"
               class="w-full pl-10 pr-10 py-2.5 bg-white border border-[var(--line)] rounded-lg text-slate-900
                      placeholder:text-slate-400
                      focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
                      transition-all duration-200">
        <button type="button" data-toggle="password" data-target="password"
                class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 rounded-md text-slate-400 hover:text-[var(--brand)] hover:bg-red-50 transition-colors">
          <i data-lucide="eye" class="w-4 h-4"></i>
        </button>
      </div>
      <p class="text-xs text-[var(--brand)] mt-1.5 hidden" data-error="password"></p>
    </div>

    <!-- Submit -->
    <?php partial('submit-button-full', [
      'btnId'    => 'submit-btn',
      'btnLabel' => 'Entrar',
      'btnIcon'  => 'log-in',
      'loading'  => 'Entrando...',
    ]); ?>

    <p class="text-sm text-center text-slate-500 pt-2">
      Não tem conta?
      <a href="<?= path('/register') ?>" class="text-[var(--brand)] font-medium hover:text-[var(--brand-dim)] transition-colors">Criar conta</a>
    </p>
  </form>
</section>
<script>
(() => {
  const form = document.getElementById('login-form');
  if (!form) return;

  const endpoint   = '<?= url('api/auth/login') ?>';
  const csrfHeader = '<?= csrf_token() ?>';
  const redirectTo = '<?= url('news') ?>';

  // 1) Mostrar/ocultar senha
  form.querySelectorAll('[data-toggle="password"]').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.getElementById(btn.dataset.target);
      const isPwd = input.type === 'password';
      input.type = isPwd ? 'text' : 'password';
      btn.innerHTML = `<i data-lucide="${isPwd ? 'eye-off' : 'eye'}" class="w-4 h-4"></i>`;
      window.lucide?.createIcons();
    });
  });

  // 2) Helpers de erro
  const clearErrors = () => {
    form.querySelectorAll('[data-error]').forEach(el => {
      el.textContent = '';
      el.classList.add('hidden');
    });
  };
  const showErrors = (errors = {}) => {
    Object.entries(errors).forEach(([field, msg]) => {
      const el = form.querySelector(`[data-error="${field}"]`);
      if (el) {
        el.textContent = Array.isArray(msg) ? msg[0] : msg;
        el.classList.remove('hidden');
      }
    });
  };

  // 3) Submit via AJAX
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();
    window.setSubmitLoading('submit-btn', true, 'Entrando...');

    try {
      const res = await fetch(endpoint, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept':           'application/json',
          'X-CSRF-Token':     csrfHeader,
        },
        credentials: 'same-origin',
        body: new FormData(form),
      });

      const data = await res.json().catch(() => ({}));

      if (res.ok && data.success) {
        // json_success(['redirect' => '/dashboard'], 'Bem-vindo de volta!')
        window.location.href = redirectTo;
        return;
      }

      // json_error('Email ou senha incorretos.', 401) — sem $errors
      // json_error('Verifique os dados.', 422, $v->errors()) — com $errors
      if (data.errors) {
        showErrors(data.errors);
      } else {
        // sem chave específica: cai no campo "password" por padrão (mensagem genérica de credenciais)
        showErrors({ password: data.message ?? 'Email ou senha incorretos.' });
      }
    } catch (err) {
      console.error(err);
      alert('Erro de conexão. Tente novamente.');
    } finally {
      window.setSubmitLoading('submit-btn', false, 'Entrar');
    }
  });
})();
</script>
