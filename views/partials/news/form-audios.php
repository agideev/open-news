<?php $audios = $audios ?? []; ?>

<div class="space-y-3">
  <div class="flex items-center justify-between">
    <label class="block text-sm font-semibold text-slate-800">Áudios</label>
    <button type="button" id="audios-add"
            class="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--brand)] hover:text-[var(--brand-dim)] transition-colors">
      <i data-lucide="plus" class="w-4 h-4"></i>
      Adicionar áudio
    </button>
  </div>

  <div id="audios-container" class="space-y-3">
    <?php foreach ($audios as $i => $audio): ?>
      <div data-audio-item data-index="<?= (int) $i ?>"
           class="grid grid-cols-1 sm:grid-cols-[1fr_120px_auto] gap-3 items-end rounded-lg border border-[var(--line)] p-3 bg-slate-50">

        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Nome</label>
          <input type="text" name="audios[<?= (int) $i ?>][name]" maxlength="150"
                 value="<?= e($audio['name'] ?? '') ?>"
                 class="w-full px-3 py-2 rounded-lg bg-white border border-[var(--line)] text-slate-900 text-sm
                        focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
                        transition-all duration-200">
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Duração (s)</label>
          <input type="number" name="audios[<?= (int) $i ?>][duration]" min="0"
                 value="<?= e((string) ($audio['duration'] ?? '')) ?>"
                 class="w-full px-3 py-2 rounded-lg bg-white border border-[var(--line)] text-slate-900 text-sm
                        focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
                        transition-all duration-200">
        </div>

        <div class="flex items-center gap-2">
          <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border border-[var(--line)]
                        text-slate-700 text-sm cursor-pointer
                        hover:border-[var(--brand)] hover:text-[var(--brand)] transition-colors max-w-[180px]">
            <i data-lucide="upload" class="w-4 h-4 shrink-0"></i>
            <span data-file-name class="truncate">
              <?= !empty($audio['file']) ? e(basename($audio['file'])) : 'Arquivo' ?>
            </span>
            <input type="file" name="audios[<?= (int) $i ?>][file]" accept="audio/*" class="hidden">
          </label>

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
  <template id="audios-template">
    <div data-audio-item data-index="__INDEX__"
         class="grid grid-cols-1 sm:grid-cols-[1fr_120px_auto] gap-3 items-end rounded-lg border border-[var(--line)] p-3 bg-slate-50">

      <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Nome</label>
        <input type="text" name="audios[__INDEX__][name]" maxlength="150"
               class="w-full px-3 py-2 rounded-lg bg-white border border-[var(--line)] text-slate-900 text-sm
                      focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
                      transition-all duration-200">
      </div>

      <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Duração (s)</label>
        <input type="number" name="audios[__INDEX__][duration]" min="0"
               class="w-full px-3 py-2 rounded-lg bg-white border border-[var(--line)] text-slate-900 text-sm
                      focus:outline-none focus:border-[var(--brand)] focus:ring-4 focus:ring-[var(--brand)]/15
                      transition-all duration-200">
      </div>

      <div class="flex items-center gap-2">
        <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border border-[var(--line)]
                      text-slate-700 text-sm cursor-pointer
                      hover:border-[var(--brand)] hover:text-[var(--brand)] transition-colors max-w-[180px]">
          <i data-lucide="upload" class="w-4 h-4 shrink-0"></i>
          <span data-file-name class="truncate">Arquivo</span>
          <input type="file" name="audios[__INDEX__][file]" accept="audio/*" class="hidden">
        </label>

        <button type="button" data-remove
                class="grid place-items-center w-9 h-9 rounded-lg text-slate-400
                       hover:text-[var(--brand)] hover:bg-red-50 transition-colors">
          <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
      </div>
    </div>
  </template>
</div>
