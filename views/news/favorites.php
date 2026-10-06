<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

  <!-- Cabeçalho -->
  <div class="mb-10">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-[var(--brand)]
                text-xs font-semibold border border-[var(--brand)]/30 mb-4">
      <i data-lucide="heart" class="w-3 h-3 fill-current"></i>
      Favoritos
    </div>
    <h1 class="w-full text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-2">
      Meus Favoritos
    </h1>
    <p class="text-slate-500 max-w-2xl">
      <?php if (($paginator['total'] ?? 0) > 0): ?>
        Você tem <strong class="text-slate-700"><?= (int) $paginator['total'] ?></strong>
        notícia<?= $paginator['total'] === 1 ? '' : 's' ?> salva<?= $paginator['total'] === 1 ? '' : 's' ?>.
      <?php else: ?>
        Suas notícias favoritas ficarão guardadas aqui.
      <?php endif; ?>
    </p>
  </div>

  <!-- Estado vazio -->
  <?php if (empty($items)): ?>
    <div class="bg-white border border-[var(--line)] rounded-2xl p-12 text-center shadow-sm">
      <div class="grid place-items-center w-14 h-14 mx-auto rounded-full bg-red-50 text-[var(--brand)] mb-4">
        <i data-lucide="heart" class="w-7 h-7"></i>
      </div>
      <h2 class="text-lg font-semibold text-slate-900 mb-1">Você ainda não tem favoritos</h2>
      <p class="text-sm text-slate-500 mb-6">
        Explore as notícias e toque no coração para salvá-las aqui.
      </p>
      <a href="<?= path('/news') ?>"
         class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg
                bg-[var(--brand)] text-white text-sm font-semibold
                hover:bg-[var(--brand-dim)] transition-colors">
        <i data-lucide="newspaper" class="w-4 h-4"></i>
        Ver Notícias
      </a>
    </div>
  <?php else: ?>

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php foreach ($items as $item): ?>
        <article data-news-card="<?= (int) $item['id'] ?>"
                 class="group flex flex-col bg-white border border-[var(--line)] rounded-2xl overflow-hidden
                        hover:border-[var(--brand)]/60 hover:shadow-lg hover:shadow-red-500/5
                        transition-all duration-200">

          <a href="<?= path('/news/' . e($item['slug'])) ?>" class="block">
            <!-- Capa -->
            <div class="relative aspect-[16/9] bg-slate-100 overflow-hidden">
              <?php if (!empty($item['image_url'])): ?>
                <img src="<?= e($item['image_url']) ?>"
                     alt="<?= e($item['title']) ?>"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
              <?php else: ?>
                <div class="grid place-items-center w-full h-full text-slate-300">
                  <i data-lucide="image-off" class="w-8 h-8"></i>
                </div>
              <?php endif; ?>
            </div>

            <!-- Conteúdo -->
            <div class="p-5">
              <p class="text-xs text-slate-400 mb-2">
                <i data-lucide="calendar" class="inline w-3 h-3 -mt-0.5"></i>
                <?= e(date('d/m/Y', strtotime($item['published_at'] ?? $item['created_at'] ?? 'now'))) ?>
              </p>

              <h2 class="text-base font-semibold text-slate-900 mb-2 line-clamp-2
                         group-hover:text-[var(--brand)] transition-colors">
                <?= e($item['title']) ?>
              </h2>

              <?php if (!empty($item['summary'])): ?>
                <p class="text-sm text-slate-500 line-clamp-3">
                  <?= e($item['summary']) ?>
                </p>
              <?php endif; ?>
            </div>
          </a>

          <!-- Ações -->
          <div class="mt-auto flex items-center justify-between gap-2 px-5 pb-4 pt-0">
            <?php partial('news.favorite-button', ['newsId' => (int) $item['id']]); ?>

            <a href="<?= path('/news/' . e($item['slug'])) ?>"
               class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500
                      hover:text-[var(--brand)] transition-colors">
              Ler
              <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <!-- Paginação -->
    <?php if (($paginator['total_pages'] ?? 1) > 1): ?>
      <nav class="flex items-center justify-center gap-2 mt-10">
        <?php if ($paginator['page'] > 1): ?>
          <a href="<?= path('/favorites?page=' . ($paginator['page'] - 1)) ?>"
             class="inline-flex items-center gap-1.5 px-3 h-9 rounded-lg bg-white border border-[var(--line)]
                    text-slate-500 hover:text-[var(--brand)] hover:border-[var(--brand)]/50
                    transition-colors text-sm font-medium">
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
            Anterior
          </a>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $paginator['total_pages']; $p++): ?>
          <a href="<?= path('/favorites?page=' . $p) ?>"
             class="grid place-items-center min-w-9 h-9 px-3 rounded-lg text-sm font-semibold transition-colors
                    <?= $p === $paginator['page']
                      ? 'bg-[var(--brand)] text-white'
                      : 'bg-white border border-[var(--line)] text-slate-600 hover:text-[var(--brand)] hover:border-[var(--brand)]/50' ?>">
            <?= $p ?>
          </a>
        <?php endfor; ?>

        <?php if ($paginator['page'] < $paginator['total_pages']): ?>
          <a href="<?= path('/favorites?page=' . ($paginator['page'] + 1)) ?>"
             class="inline-flex items-center gap-1.5 px-3 h-9 rounded-lg bg-white border border-[var(--line)]
                    text-slate-500 hover:text-[var(--brand)] hover:border-[var(--brand)]/50
                    transition-colors text-sm font-medium">
            Próxima
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
          </a>
        <?php endif; ?>
      </nav>
    <?php endif; ?>

  <?php endif; ?>
</div>

<script>
(function () {
  // Remove o card da lista quando desfavoritar (sem reload)
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-favorite-btn]');
    if (!btn) return;

    const newsId = btn.dataset.newsId;
    if (!newsId) return;

    // Guarda estado anterior — se estava favoritado e vai desfavoritar
    const wasFavorited = btn.dataset.favorited === '1';

    // Observa a mudança de estado do próprio botão (ele atualiza data-favorited)
    const observer = new MutationObserver(() => {
      const nowFavorited = btn.dataset.favorited === '1';

      // Se estava favoritado e agora não está → remove o card
      if (wasFavorited && !nowFavorited) {
        const card = document.querySelector('[data-news-card="' + newsId + '"]');
        if (card) {
          card.style.transition = 'opacity .2s, transform .2s';
          card.style.opacity = '0';
          card.style.transform = 'scale(.98)';
          setTimeout(() => card.remove(), 200);
        }
      }

      observer.disconnect();
    });

    observer.observe(btn, { attributes: true, attributeFilter: ['data-favorited'] });
  });
})();
</script>
