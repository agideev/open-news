<?php $news = $news ?? []; ?>

<div class="space-y-5">

  <!-- =====================================================
       TÍTULO
  ====================================================== -->
  <div>
    <label for="title" class="block text-sm font-semibold text-slate-800 mb-1.5">
      Título <span class="text-[var(--brand)]">*</span>
    </label>

    <div class="relative">
      <i
        data-lucide="type"
        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
      ></i>

      <input
        type="text"
        id="title"
        name="title"
        required
        maxlength="180"
        value="<?= e($news['title'] ?? '') ?>"
        placeholder="Ex.: Novo lançamento chega ao catálogo"
        class="w-full pl-10 pr-3.5 py-2.5 rounded-lg bg-white border border-[var(--line)] text-slate-900
               placeholder-slate-400
               focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
               transition-all duration-200"
      >
    </div>
  </div>


  <!-- =====================================================
       RESUMO
  ====================================================== -->
  <div>
    <label for="summary" class="block text-sm font-semibold text-slate-800 mb-1.5">
      Resumo
    </label>

    <div class="relative">
      <i
        data-lucide="align-left"
        class="pointer-events-none absolute left-3 top-3 w-4 h-4 text-slate-400"
      ></i>

      <textarea
        id="summary"
        name="summary"
        rows="3"
        maxlength="500"
        placeholder="Um pequeno resumo para aparecer na listagem"
        class="w-full pl-10 pr-3.5 py-2.5 rounded-lg bg-white border border-[var(--line)] text-slate-900
               placeholder-slate-400
               focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
               transition-all duration-200 resize-y"
      ><?= e($news['summary'] ?? '') ?></textarea>
    </div>
  </div>


  <!-- =====================================================
       CONTEÚDO
  ====================================================== -->
  <div>
    <label for="content" class="block text-sm font-semibold text-slate-800 mb-1.5">
      Conteúdo <span class="text-[var(--brand)]">*</span>
    </label>

    <div class="relative">
      <i
        data-lucide="file-text"
        class="pointer-events-none absolute left-3 top-3 w-4 h-4 text-slate-400"
      ></i>

      <textarea
        id="content"
        name="content"
        rows="12"
        required
        placeholder="Escreva o conteúdo completo da notícia..."
        class="w-full pl-10 pr-3.5 py-2.5 rounded-lg bg-white border border-[var(--line)] text-slate-900
               placeholder-slate-400
               focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
               transition-all duration-200 resize-y"
      ><?= e($news['content'] ?? '') ?></textarea>
    </div>
  </div>


  <!-- =====================================================
       STATUS
  ====================================================== -->
  <div>
    <label for="status" class="block text-sm font-semibold text-slate-800 mb-1.5">
      Status <span class="text-[var(--brand)]">*</span>
    </label>

    <div class="relative">
      <i
        data-lucide="circle-dot"
        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
      ></i>

      <select
        id="status"
        name="status"
        required
        class="w-full pl-10 pr-10 py-2.5 rounded-lg bg-white border border-[var(--line)] text-slate-900
               focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
               transition-all duration-200 appearance-none"
      >
        <option
          value="draft"
          <?= ($news['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>
        >
          Rascunho
        </option>

        <option
          value="published"
          <?= ($news['status'] ?? '') === 'published' ? 'selected' : '' ?>
        >
          Publicado
        </option>
      </select>

      <i
        data-lucide="chevron-down"
        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
      ></i>
    </div>
  </div>


  <!-- =====================================================
       DATA DE PUBLICAÇÃO
  ====================================================== -->
  <div>
    <label for="published_at" class="block text-sm font-semibold text-slate-800 mb-1.5">
      Data de publicação
    </label>

    <div class="relative">
      <i
        data-lucide="calendar-clock"
        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
      ></i>

      <input
        type="datetime-local"
        id="published_at"
        name="published_at"
        value="<?= !empty($news['published_at'])
          ? date('Y-m-d\TH:i', strtotime($news['published_at']))
          : '' ?>"
        class="w-full pl-10 pr-3.5 py-2.5 rounded-lg bg-white border border-[var(--line)] text-slate-900
               focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
               transition-all duration-200"
      >
    </div>

    <p class="mt-1.5 text-xs text-slate-500">
      Defina quando a notícia deve ser considerada publicada.
    </p>
  </div>

</div>
