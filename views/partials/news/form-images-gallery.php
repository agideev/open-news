<?php

$images = $images ?? [];

// die(var_dump($images));

?>

<div class="space-y-3">
  <div class="flex items-center justify-between">
    <label class="block text-sm font-semibold text-slate-800">Imagens</label>
    <button type="button" id="images-add"
            class="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--brand)] hover:text-[var(--brand-dim)] transition-colors">
      <i data-lucide="plus" class="w-4 h-4"></i>
      Adicionar imagem
    </button>
  </div>

  <div id="images-container" class="space-y-3">
    <?php foreach ($images as $i => $img): ?>
      <div data-image-item data-index="<?= (int) $i ?>"
           class="grid grid-cols-1 sm:grid-cols-[160px_1fr_auto] gap-3 items-end rounded-lg border border-[var(--line)] p-3 bg-slate-50">

        <!-- ID da imagem (para o update saber qual atualizar) -->
        <?php if (!empty($img['id'])): ?>
          <input type="hidden" name="images[<?= (int) $i ?>][id]" value="<?= (int) $img['id'] ?>">
        <?php endif; ?>

        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Arquivo</label>
          <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border border-[var(--line)]
                        text-slate-700 text-sm cursor-pointer w-full justify-center
                        hover:border-[var(--brand)] hover:text-[var(--brand)] transition-colors">
            <i data-lucide="image" class="w-4 h-4 shrink-0"></i>
            <span data-file-name class="truncate">
              <?= !empty($img['image']) ? e(basename($img['image'])) : 'Escolher' ?>
            </span>
            <input type="file" name="images[<?= (int) $i ?>][image]" accept="image/*" class="hidden">
          </label>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Legenda</label>
          <input type="text" name="images[<?= (int) $i ?>][caption]" maxlength="255"
                 value="<?= e($img['caption'] ?? '') ?>"
                 class="w-full px-3 py-2 rounded-lg bg-white border border-[var(--line)] text-slate-900 text-sm
                        focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
                        transition-all duration-200">
          <input type="hidden" name="images[<?= (int) $i ?>][position]" data-position-input value="<?= (int) ($img['position'] ?? $i) ?>">
        </div>

        <div>
          <button type="button" data-remove
                  class="grid place-items-center w-9 h-9 rounded-lg text-slate-400
                         hover:text-[var(--brand)] hover:bg-red-50 transition-colors">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Template -->
  <template id="images-template">
    <div data-image-item data-index="__INDEX__"
         class="grid grid-cols-1 sm:grid-cols-[160px_1fr_auto] gap-3 items-end rounded-lg border border-[var(--line)] p-3 bg-slate-50">

      <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Arquivo</label>
        <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border border-[var(--line)]
                      text-slate-700 text-sm cursor-pointer w-full justify-center
                      hover:border-[var(--brand)] hover:text-[var(--brand)] transition-colors">
          <i data-lucide="image" class="w-4 h-4 shrink-0"></i>
          <span data-file-name class="truncate">Escolher</span>
          <input type="file" name="images[__INDEX__][image]" accept="image/*" class="hidden">
        </label>
      </div>

      <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Legenda</label>
        <input type="text" name="images[__INDEX__][caption]" maxlength="255"
               class="w-full px-3 py-2 rounded-lg bg-white border border-[var(--line)] text-slate-900 text-sm
                      focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
                      transition-all duration-200">
        <input type="hidden" name="images[__INDEX__][position]" data-position-input value="0">
      </div>

      <div>
        <button type="button" data-remove
                class="grid place-items-center w-9 h-9 rounded-lg text-slate-400
                       hover:text-[var(--brand)] hover:bg-red-50 transition-colors">
          <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
      </div>
    </div>
  </template>
</div>
