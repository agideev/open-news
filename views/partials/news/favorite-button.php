<?php
$newsId = $newsId ?? null;
if (!$newsId) return;

$userId = \App\Core\Auth::id();

$favoriteModel = new \App\Models\NewsFavorite();
$isFavorited   = $userId !== null
    ? $favoriteModel->findByUserAndNews((int) $userId, (int) $newsId) !== null
    : false;
$count         = $favoriteModel->countByNews((int) $newsId);
?>

<button type="button"
        data-favorite-btn
        data-news-id="<?= (int) $newsId ?>"
        data-action="<?= path('api/news/' . (int) $newsId . '/favorite') ?>"
        data-favorited="<?= $isFavorited ? '1' : '0' ?>"
        data-login-required="<?= $userId === null ? '1' : '0' ?>"
        aria-pressed="<?= $isFavorited ? 'true' : 'false' ?>"
        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-full border text-sm font-semibold
               transition-all duration-200 active:scale-[0.97]
               <?= $isFavorited
                 ? 'bg-red-50 border-[var(--brand)]/60 text-[var(--brand)]'
                 : 'bg-white border-[var(--line)] text-slate-500 hover:text-[var(--brand)] hover:border-[var(--brand)]/50' ?>">

  <i data-lucide="heart" class="w-4 h-4 <?= $isFavorited ? 'fill-current' : '' ?>"></i>

  <span data-favorite-label>
    <?= $isFavorited ? 'Favoritado' : 'Favoritar' ?>
  </span>

  <span data-favorite-count
        class="text-xs font-mono px-1.5 py-0.5 rounded
               <?= $isFavorited
                 ? 'bg-[var(--brand)]/10 text-[var(--brand)]'
                 : 'bg-slate-100 text-slate-400' ?>">
    <?= (int) $count ?>
  </span>
</button>

<script>
(function () {
  const btn = document.querySelector('[data-favorite-btn][data-news-id="<?= (int) $newsId ?>"]:not([data-bound])');
  if (!btn) return;
  btn.setAttribute('data-bound', '1');

  btn.addEventListener('click', async () => {
    // Usuário não logado → redireciona para login
    if (btn.dataset.loginRequired === '1') {
      window.location.href = '<?= path('/login') ?>';
      return;
    }

    if (btn.disabled) return;
    btn.disabled = true;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    try {
      const res = await fetch(btn.dataset.action, {
        method: 'POST',
        headers: { 'X-CSRF-Token': csrf },
      });

      const json = await res.json();

      if (res.status === 401) {
        window.location.href = '<?= path('/login') ?>';
        return;
      }

      if (!res.ok || !json.success) {
        alert(json.message || 'Erro ao favoritar.');
        return;
      }

      const favorited = !!json.data.favorited;
      const count     = parseInt(json.data.count, 10) || 0;

      // Estado
      btn.dataset.favorited = favorited ? '1' : '0';
      btn.setAttribute('aria-pressed', favorited ? 'true' : 'false');

      // Classes do botão
      btn.classList.toggle('bg-red-50', favorited);
      btn.classList.toggle('border-[var(--brand)]/60', favorited);
      btn.classList.toggle('text-[var(--brand)]', favorited);
      btn.classList.toggle('bg-white', !favorited);
      btn.classList.toggle('border-[var(--line)]', !favorited);
      btn.classList.toggle('text-slate-500', !favorited);

      // Ícone
      const icon = btn.querySelector('[data-lucide]');
      if (icon) icon.classList.toggle('fill-current', favorited);

      // Label
      const label = btn.querySelector('[data-favorite-label]');
      if (label) label.textContent = favorited ? 'Favoritado' : 'Favoritar';

      // Contador
      const countEl = btn.querySelector('[data-favorite-count]');
      if (countEl) {
        countEl.textContent = count;
        countEl.classList.toggle('bg-[var(--brand)]/10', favorited);
        countEl.classList.toggle('text-[var(--brand)]', favorited);
        countEl.classList.toggle('bg-slate-100', !favorited);
        countEl.classList.toggle('text-slate-400', !favorited);
      }

      if (window.lucide) lucide.createIcons();
    } catch (e) {
      alert('Falha de comunicação com o servidor.');
    } finally {
      btn.disabled = false;
    }
  });
})();
</script>
