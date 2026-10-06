<?php
$publishedAt = $news['published_at'] ?? $news['created_at'] ?? null;
$hasGallery  = !empty($news['images']);
$hasAudio    = !empty($news['audio_url']);
?>

<article class="max-w-3xl mx-auto px-4 sm:px-6 py-10 pb-40 sm:pb-32">

  <!-- Voltar -->
  <a href="<?= path('/news') ?>"
     class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500
            hover:text-[var(--brand)] transition-colors mb-6">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar para notícias
  </a>

  <!-- Cabeçalho -->
  <header class="mb-8">
    <div class="flex items-center gap-2 flex-wrap mb-4">
      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full
                   text-[var(--brand)] text-xs font-semibold
                   ">
        <i data-lucide="newspaper" class="w-3 h-3"></i>
        Notícia
      </span>

      <!-- ⬇ Favoritar -->
      <?php partial('news.favorite-button', ['newsId' => (int) $news['id']]); ?>
    </div>

    <h1 class="w-full text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
      <?= e($news['title']) ?>
    </h1>

    <?php if (!empty($news['summary'])): ?>
      <p class="w-full text-base sm:text-lg md:text-xl text-slate-500 leading-relaxed mb-5 break-words">
        <?= e($news['summary']) ?>
      </p>
    <?php endif; ?>

    <?php if ($publishedAt): ?>
      <div class="flex items-center gap-4 text-sm text-slate-400">
        <span class="inline-flex items-center gap-1.5">
          <i data-lucide="calendar" class="w-4 h-4"></i>
          <?= e(date('d/m/Y', strtotime($publishedAt))) ?>
        </span>
        <span class="inline-flex items-center gap-1.5">
          <i data-lucide="clock" class="w-4 h-4"></i>
          <?= e(date('H:i', strtotime($publishedAt))) ?>
        </span>
      </div>
    <?php endif; ?>
  </header>

  <!-- Imagem de capa -->
  <?php if (!empty($news['image_url'])): ?>
    <figure class="rounded-2xl overflow-hidden border border-[var(--line)] shadow-sm">
      <img src="<?= e($news['image_url']) ?>"
           alt="<?= e($news['title']) ?>"
           class="w-full h-auto object-cover">
    </figure>
  <?php endif; ?>

 <div class="prose prose-slate max-w-none
            whitespace-pre-line
            text-justify
            prose-headings:font-bold prose-headings:text-slate-900
            prose-p:text-slate-700 prose-p:leading-relaxed
            prose-a:text-[var(--brand)] prose-a:no-underline hover:prose-a:underline
            prose-strong:text-slate-900
            prose-blockquote:border-l-[var(--brand)] prose-blockquote:text-slate-600
            prose-code:text-[var(--brand)] prose-code:bg-red-50 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded
            prose-img:rounded-xl prose-img:border prose-img:border-[var(--line)]">

    <?= htmlspecialchars($news['content'], ENT_QUOTES, 'UTF-8') ?>

</div>

  <!-- Galeria -->
  <?php

  partial('news.gallery', [
      'images' => $news['images'],
  ]);

  ?>

  <!-- Relacionadas -->
  <?php if (!empty($related)): ?>
    <section class="mt-12 pt-8 border-t border-[var(--line)]">
      <h2 class="text-lg font-bold text-slate-900 mb-5 flex items-center gap-2">
        Outras Notícias
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <?php foreach ($related as $rel): ?>
          <?php if ((int) $rel['id'] === (int) $news['id']) continue; ?>
          <a href="<?= path('/news/' . e($rel['slug'])) ?>"
             class="group flex flex-col bg-white border border-[var(--line)] rounded-xl overflow-hidden
                    hover:border-[var(--brand)]/60 hover:shadow-md transition-all duration-200">

            <div class="aspect-[16/9] bg-slate-100 overflow-hidden">
              <?php if (!empty($rel['image_url'])): ?>
                <img src="<?= e($rel['image_url']) ?>"
                     alt="<?= e($rel['title']) ?>"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
              <?php else: ?>
                <div class="grid place-items-center w-full h-full text-slate-300">
                  <i data-lucide="image-off" class="w-6 h-6"></i>
                </div>
              <?php endif; ?>
            </div>

            <div class="p-3">
              <h3 class="text-sm font-semibold text-slate-900 line-clamp-2
                         group-hover:text-[var(--brand)] transition-colors">
                <?= e($rel['title']) ?>
              </h3>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</article>

<!-- Player de áudio flutuante -->
<?php if ($hasAudio): ?>
  <?php partial('news/news-audio-player', [
      'audioUrl' => $news['audio_url'],
      'subtitle' => $news['title'],
  ]); ?>
<?php endif; ?>

<script>
(function () {
  // Copiar link
  const copyBtn = document.querySelector('[data-copy-link]');
  if (copyBtn) {
    copyBtn.addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText(copyBtn.dataset.url);
        const icon = copyBtn.querySelector('i');
        const original = icon.getAttribute('data-lucide');
        icon.setAttribute('data-lucide', 'check');
        if (window.lucide) lucide.createIcons();
        setTimeout(() => {
          icon.setAttribute('data-lucide', original);
          if (window.lucide) lucide.createIcons();
        }, 1500);
      } catch (e) {
        alert('Não foi possível copiar o link.');
      }
    });
  }

  // Botão "Ouvir resumo" do header — dá play no player e rola até ele
  const openAudioBtn = document.querySelector('[data-open-audio]');
  if (openAudioBtn) {
    openAudioBtn.addEventListener('click', () => {
      const root = document.querySelector('[data-audio-player]');
      if (!root) return;
      const audio = root.querySelector('[data-audio]');
      if (audio) audio.play();
    });
  }
})();
</script>
