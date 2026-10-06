<?php $news = $news ?? []; $hasImage = !empty($news['image']); ?>
<div class="group">
  <label class="block text-sm font-medium text-slate-700 mb-1.5">Imagem principal</label>

  <input type="file" id="image" name="image" accept="image/*" class="hidden">

  <label for="image"
         class="block cursor-pointer rounded-xl border-2 border-dashed border-slate-200 bg-slate-50
                hover:border-red-500/60 hover:bg-red-50 transition-colors p-6">

    <!-- Vazio -->
    <div data-upload-empty class="<?= $hasImage ? 'hidden' : '' ?> flex flex-col items-center justify-center gap-2 text-center py-4">
      <i data-lucide="image-plus" class="w-8 h-8 text-slate-400"></i>
      <p class="text-sm text-slate-600">Arraste uma imagem aqui ou <span class="text-red-500 font-medium">clique para escolher</span></p>
      <p class="text-xs text-slate-400">JPG, PNG ou WEBP</p>
    </div>

    <!-- Preview -->
    <div data-upload-preview class="<?= $hasImage ? '' : 'hidden' ?> flex items-center gap-4">
      <img id="preview-main"
           src="<?= $hasImage ? e(getupload($news['image'])) : '' ?>"
           alt="Pré-visualização"
           class="w-20 h-20 rounded-lg object-cover border border-slate-200">
      <div class="min-w-0">
        <p data-upload-name class="text-sm text-slate-700 truncate">
          <?= $hasImage ? e(basename($news['image'])) : '' ?>
        </p>
        <p class="text-xs text-slate-400 mt-0.5">Clique para substituir</p>
      </div>
    </div>
  </label>
</div>
