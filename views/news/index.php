<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">

  <!-- Cabeçalho -->
  <div class="mb-10">
    <div class="inline-flex items-center gap-2 py-1  text-[var(--brand)]
                text-xs font-semibold mb-4">
      <i data-lucide="newspaper" class="w-3 h-3"></i>
      Notícias
    </div>
    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl
           font-extrabold text-slate-900 tracking-tight
               leading-tight mb-2 sm:px-0">
      Últimas Notícias
    </h1>
    <p class="text-slate-500 max-w-2xl">
      Acompanhe as novidades, lançamentos e atualizações do nosso catálogo.
    </p>
  </div>

  <?php if (empty($items)): ?>
    <div class="bg-white border border-[var(--line)] rounded-2xl p-12 text-center shadow-sm">
      <div class="grid place-items-center w-14 h-14 mx-auto rounded-full bg-red-50 text-[var(--brand)] mb-4">
        <i data-lucide="newspaper" class="w-7 h-7"></i>
      </div>
      <h2 class="text-lg font-semibold text-slate-900 mb-1">Nenhuma notícia por enquanto</h2>
      <p class="text-sm text-slate-500">Volte em breve para conferir as novidades.</p>
    </div>
  <?php else: ?>

    <!-- Lista -->
    <div class="flex flex-col divide-y divide-[var(--line)] border-y border-[var(--line)]">
      <?php foreach ($items as $item): ?>
        <a href="<?= path('/news/' . e($item['slug'])) ?>"
           class="group flex flex-col gap-5 py-6 sm:py-8
                  hover:bg-slate-50/60 transition-colors px-2 sm:px-4 -mx-2 sm:-mx-4">

          <!-- Imagem -->
          <div class="relative w-full aspect-[16/9] sm:aspect-[21/9] bg-slate-100 overflow-hidden
                      rounded-2xl border border-[var(--line)]">
            <?php if (!empty($item['image_url'])): ?>
              <img src="<?= e($item['image_url']) ?>"
                   alt="<?= e($item['title']) ?>"
                   class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-500">
            <?php else: ?>
              <div class="grid place-items-center w-full h-full text-slate-300">
                <i data-lucide="image-off" class="w-10 h-10"></i>
              </div>
            <?php endif; ?>
          </div>

          <!-- Conteúdo -->
          <div class="flex flex-col gap-2">
            <p class="text-xs text-slate-400">
              <i data-lucide="calendar" class="inline w-3 h-3 -mt-0.5"></i>
              <?= e(date('d/m/Y', strtotime($item['published_at'] ?? $item['created_at'] ?? 'now'))) ?>
            </p>

            <h2 class="text-lg sm:text-xl font-bold text-slate-900 leading-tight line-clamp-2
                       group-hover:text-[var(--brand)] transition-colors">
              <?= e($item['title']) ?>
            </h2>

            <?php if (!empty($item['summary'])): ?>
              <p class="text-sm sm:text-base text-slate-500 leading-relaxed line-clamp-3">
                <?= e($item['summary']) ?>
              </p>
            <?php endif; ?>

            <div class="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--brand)] mt-1">
              Ler mais
              <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-0.5 transition-transform"></i>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Paginação -->
    <?php if (($paginator['total_pages'] ?? 1) > 1): ?>
      <nav class="flex items-center justify-center gap-2 mt-10">
        <?php if ($paginator['page'] > 1): ?>
          <a href="<?= path('/news?page=' . ($paginator['page'] - 1)) ?>"
             class="inline-flex items-center gap-1.5 px-3 h-9 rounded-lg bg-white border border-[var(--line)]
                    text-slate-500 hover:text-[var(--brand)] hover:border-[var(--brand)]/50
                    transition-colors text-sm font-medium">
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
            Anterior
          </a>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $paginator['total_pages']; $p++): ?>
          <a href="<?= path('/news?page=' . $p) ?>"
             class="grid place-items-center min-w-9 h-9 px-3 rounded-lg text-sm font-semibold transition-colors
                    <?= $p === $paginator['page']
                      ? 'bg-[var(--brand)] text-white'
                      : 'bg-white border border-[var(--line)] text-slate-600 hover:text-[var(--brand)] hover:border-[var(--brand)]/50' ?>">
            <?= $p ?>
          </a>
        <?php endfor; ?>

        <?php if ($paginator['page'] < $paginator['total_pages']): ?>
          <a href="<?= path('/news?page=' . ($paginator['page'] + 1)) ?>"
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
