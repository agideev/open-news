<?php
  $btnId    = $btnId    ?? 'submit-btn';
  $btnLabel = $btnLabel ?? 'Salvar';
  $btnIcon  = $btnIcon  ?? 'save';
  $loading  = $loading  ?? 'Salvando...';
  $btnColor = $btnColor ?? 'red';

  // Mapa de cores disponíveis
  $colors = [
      'red' => [
          'bg'     => 'bg-[var(--brand)]',
          'hover'  => 'hover:bg-[var(--brand-dim)]',
          'shadow' => 'shadow-red-500/25',
          'hoverShadow' => 'hover:shadow-red-500/40',
      ],
      'green' => [
          'bg'     => 'bg-emerald-500',
          'hover'  => 'hover:bg-emerald-400',
          'shadow' => 'shadow-emerald-500/25',
          'hoverShadow' => 'hover:shadow-emerald-500/40',
      ],
      'slate' => [
          'bg'     => 'bg-slate-800',
          'hover'  => 'hover:bg-slate-700',
          'shadow' => 'shadow-slate-900/25',
          'hoverShadow' => 'hover:shadow-slate-900/40',
      ],
      'dark' => [
          'bg'     => 'bg-slate-900',
          'hover'  => 'hover:bg-slate-800',
          'shadow' => 'shadow-slate-900/25',
          'hoverShadow' => 'hover:shadow-slate-900/40',
      ],
  ];

  $c = $colors[$btnColor] ?? $colors['red'];
?>

<button type="submit"
        id="<?= e($btnId) ?>"
        data-submit
        data-label-loading="<?= e($loading) ?>"
        data-label-default="<?= e($btnLabel) ?>"
        class="group w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg
               <?= $c['bg'] ?> text-white text-sm font-semibold
               shadow-lg <?= $c['shadow'] ?>
               <?= $c['hover'] ?> <?= $c['hoverShadow'] ?>
               transition-all
               disabled:opacity-60 disabled:cursor-not-allowed
               disabled:hover:<?= $c['bg'] ?> disabled:hover:shadow-none">

  <i data-lucide="<?= e($btnIcon) ?>"
     class="submit-icon w-4 h-4 transition-opacity duration-200"></i>

  <span class="submit-spinner loader-ring hidden w-4 h-4 shrink-0">
    <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
      <circle cx="12" cy="12" r="10"
              stroke="currentColor" stroke-width="3" opacity="0.25"/>
      <path d="M22 12a10 10 0 0 1-10 10"
            stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
    </svg>
  </span>

  <span class="submit-text"><?= e($btnLabel) ?></span>
</button>
