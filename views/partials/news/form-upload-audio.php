<?php $news = $news ?? []; $hasAudio = !empty($news['audio']); ?>
<div class="group">
  <label class="block text-sm font-semibold text-slate-800 mb-1.5">Áudio</label>

  <input type="file" id="audio" name="audio" accept="audio/*" class="hidden">

  <label for="audio"
         class="block cursor-pointer rounded-xl border-2 border-dashed border-[var(--line)] bg-slate-50
                hover:border-[var(--brand)]/60 hover:bg-red-50 transition-colors p-6">

    <!-- Vazio -->
    <div data-upload-empty class="<?= $hasAudio ? 'hidden' : '' ?> flex flex-col items-center justify-center gap-2 text-center py-4">
      <i data-lucide="music" class="w-8 h-8 text-slate-400"></i>
      <p class="text-sm text-slate-600">
        Arraste um áudio aqui ou
        <span class="text-[var(--brand)] font-semibold">clique para escolher</span>
      </p>
      <p class="text-xs text-slate-400">MP3, OGG ou WAV</p>
    </div>

    <!-- Preview -->
    <div data-upload-preview class="<?= $hasAudio ? '' : 'hidden' ?> flex items-center gap-4">
      <div class="grid place-items-center w-12 h-12 rounded-lg bg-red-50 text-[var(--brand)] shrink-0">
        <i data-lucide="music" class="w-6 h-6"></i>
      </div>
      <div class="min-w-0">
        <p data-upload-name class="text-sm text-slate-700 truncate">
          <?= $hasAudio ? e(basename($news['audio'])) : '' ?>
        </p>
        <p class="text-xs text-slate-400 mt-0.5">Clique para substituir</p>
      </div>
    </div>
  </label>
</div>
