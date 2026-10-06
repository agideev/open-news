<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

  <!-- Cabeçalho -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
      <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl
           font-extrabold text-slate-900 tracking-tight
               leading-tight mb-2 sm:px-0">
      Gerenciar Notícias
      </h1>
      <p class="text-sm text-slate-500 mt-0.5">
        <?= (int) $paginator['total'] ?> notícia<?= $paginator['total'] === 1 ? '' : 's' ?> no total
      </p>
    </div>

    <a href="<?= path('/admin/news/create') ?>"
       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg
              bg-[var(--brand)] text-white text-sm font-semibold
              hover:bg-[var(--brand-dim)] transition-colors shadow-sm">
      <i data-lucide="plus" class="w-4 h-4"></i>
      Nova Notícia
    </a>
  </div>

  <!-- Flash -->
  <?php if (!empty($_SESSION['flash'])): ?>
    <?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="mb-6 rounded-lg border px-4 py-3 text-sm
      <?= $flash['type'] === 'success'
        ? 'border-green-500/40 bg-green-50 text-green-700'
        : ($flash['type'] === 'warning'
          ? 'border-amber-500/40 bg-amber-50 text-amber-700'
          : 'border-[var(--brand)]/40 bg-red-50 text-[var(--brand-dim)]') ?>">
      <?= e($flash['message'] ?? '') ?>
    </div>
  <?php endif; ?>

  <?php if (empty($items)): ?>
    <div class="bg-white border border-[var(--line)] rounded-2xl p-12 text-center shadow-sm">
      <div class="grid place-items-center w-14 h-14 mx-auto rounded-full bg-red-50 text-[var(--brand)] mb-4">
        <i data-lucide="newspaper" class="w-7 h-7"></i>
      </div>
      <h2 class="text-lg font-semibold text-slate-900 mb-1">Nenhuma notícia cadastrada</h2>
      <p class="text-sm text-slate-500 mb-6">Comece criando sua primeira notícia.</p>
      <a href="<?= path('/admin/news/create') ?>"
         class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg
                bg-[var(--brand)] text-white text-sm font-semibold
                hover:bg-[var(--brand-dim)] transition-colors">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Criar Notícia
      </a>
    </div>
  <?php else: ?>

    <div class="bg-white border border-[var(--line)] rounded-2xl overflow-hidden shadow-sm">
      <ul class="divide-y divide-[var(--line)]">
        <?php foreach ($items as $item): ?>
          <li data-news-row="<?= (int) $item['id'] ?>"
              class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 sm:p-5
                     hover:bg-slate-50 transition-colors">

            <!-- Thumbnail -->
            <div class="shrink-0 w-full sm:w-24 h-32 sm:h-16 rounded-lg overflow-hidden bg-slate-100
                        border border-[var(--line)]">
              <?php if (!empty($item['image_url'])): ?>
                <img src="<?= e($item['image_url']) ?>"
                     alt="<?= e($item['title']) ?>"
                     class="w-full h-full object-cover">
              <?php else: ?>
                <div class="grid place-items-center w-full h-full text-slate-400">
                  <i data-lucide="image-off" class="w-5 h-5"></i>
                </div>
              <?php endif; ?>
            </div>

            <!-- Conteúdo -->
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 flex-wrap mb-1">
                <?php if (($item['status'] ?? 'draft') === 'published'): ?>
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                               bg-green-50 text-green-700 text-xs font-semibold border border-green-500/30">
                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                    Publicado
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                               bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-300">
                    <i data-lucide="pencil" class="w-3 h-3"></i>
                    Rascunho
                  </span>
                <?php endif; ?>

                <?php if (!empty($item['audio'])): ?>
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                               bg-red-50 text-[var(--brand)] text-xs font-semibold border border-[var(--brand)]/30">
                    <i data-lucide="music" class="w-3 h-3"></i>
                    Áudio
                  </span>
                <?php endif; ?>
              </div>

              <h3 class="text-base font-semibold text-slate-900 truncate">
                <?= e($item['title']) ?>
              </h3>

              <?php if (!empty($item['summary'])): ?>
                <p class="text-sm text-slate-500 mt-0.5 line-clamp-2">
                  <?= e($item['summary']) ?>
                </p>
              <?php endif; ?>

              <p class="text-xs text-slate-400 mt-1.5">
                <i data-lucide="clock" class="inline w-3 h-3 -mt-0.5"></i>
                <?= e(date('d/m/Y H:i', strtotime($item['created_at'] ?? 'now'))) ?>
              </p>
            </div>

            <!-- Ações -->
            <div class="flex items-center gap-2 shrink-0">
              <a href="<?= path('/news/' . (string) $item['slug']) ?>"
                 target="_blank" title="Ver"
                 class="grid place-items-center w-9 h-9 rounded-lg text-slate-400
                        hover:text-slate-900 hover:bg-slate-100 transition-colors">
                <i data-lucide="eye" class="w-4 h-4"></i>
              </a>

              <a href="<?= path('/admin/news/' . (int) $item['id'] . '/edit') ?>"
                 title="Editar"
                 class="grid place-items-center w-9 h-9 rounded-lg text-slate-400
                        hover:text-[var(--brand)] hover:bg-red-50 transition-colors">
                <i data-lucide="pencil" class="w-4 h-4"></i>
              </a>

              <button type="button"
                      data-delete-news
                      data-id="<?= (int) $item['id'] ?>"
                      data-title="<?= e($item['title']) ?>"
                      title="Excluir"
                      class="grid place-items-center w-9 h-9 rounded-lg text-slate-400
                             hover:text-[var(--brand)] hover:bg-red-50 transition-colors">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
              </button>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Paginação -->
    <?php if (($paginator['total_pages'] ?? 1) > 1): ?>
      <nav class="flex items-center justify-center gap-2 mt-8">
        <?php if ($paginator['page'] > 1): ?>
          <a href="<?= path('/admin/news?page=' . ($paginator['page'] - 1)) ?>"
             class="grid place-items-center w-9 h-9 rounded-lg bg-white border border-[var(--line)]
                    text-slate-500 hover:text-[var(--brand)] hover:border-[var(--brand)]/50 transition-colors">
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
          </a>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $paginator['total_pages']; $p++): ?>
          <a href="<?= path('/admin/news?page=' . $p) ?>"
             class="grid place-items-center min-w-9 h-9 px-3 rounded-lg text-sm font-semibold transition-colors
                    <?= $p === $paginator['page']
                      ? 'bg-[var(--brand)] text-white'
                      : 'bg-white border border-[var(--line)] text-slate-600 hover:text-[var(--brand)] hover:border-[var(--brand)]/50' ?>">
            <?= $p ?>
          </a>
        <?php endfor; ?>

        <?php if ($paginator['page'] < $paginator['total_pages']): ?>
          <a href="<?= path('/admin/news?page=' . ($paginator['page'] + 1)) ?>"
             class="grid place-items-center w-9 h-9 rounded-lg bg-white border border-[var(--line)]
                    text-slate-500 hover:text-[var(--brand)] hover:border-[var(--brand)]/50 transition-colors">
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
          </a>
        <?php endif; ?>
      </nav>
    <?php endif; ?>

  <?php endif; ?>

</div>

<!-- Modal de confirmação -->
<div id="confirm-modal"
     class="hidden fixed inset-0 z-[100] items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 border border-[var(--line)]">
    <h3 class="text-lg font-semibold text-slate-900 text-center mb-1">Excluir notícia?</h3>
    <p class="text-sm text-slate-500 text-center mb-6">
      Tem certeza que deseja excluir
      <strong id="confirm-title" class="text-slate-700"></strong>?
      Esta ação não pode ser desfeita.
    </p>

    <div class="flex items-center justify-end gap-2">
      <button type="button" data-confirm-cancel
              class="px-4 py-2 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 text-sm font-medium transition-colors">
        Cancelar
      </button>
      <!-- =====================================================
           DELETE NEWS BUTTON
      ====================================================== -->
      <button
          type="button"
          data-delete-news
          data-id="<?= (int) $item['id'] ?>"
          data-title="<?= e($item['title']) ?>"
          title="Excluir"
          class="bg-red-50 grid place-items-center w-9 h-9 rounded-lg text-red-400
                 hover:text-red-500 hover:bg-red-50 transition-colors"
      >
          <i data-lucide="trash-2" class="w-4 h-4"></i>
      </button>
      <!-- =====================================================
           END DELETE NEWS BUTTON
      ====================================================== -->
    </div>
  </div>
</div>

<script>
(function () {

    // =====================================================
    // MODAL ELEMENTS
    // =====================================================

    const modal = document.getElementById('confirm-modal');
    const titleEl = document.getElementById('confirm-title');
    const cancelBtn = modal.querySelector('[data-confirm-cancel]');
    const okBtn = modal.querySelector('[data-confirm-ok]');
    const okLabel = modal.querySelector('[data-confirm-label]');


    // =====================================================
    // DELETE STATE
    // =====================================================

    let pendingId = null;


    // =====================================================
    // OPEN DELETE MODAL
    // =====================================================

    function openDeleteModal(id, title) {

        pendingId = id;

        titleEl.textContent = `"${title}"`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.style.overflow = 'hidden';

        if (window.lucide) {
            lucide.createIcons();
        }
    }


    // =====================================================
    // CLOSE DELETE MODAL
    // =====================================================

    function closeDeleteModal() {

        pendingId = null;

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.style.overflow = '';

        okBtn.disabled = false;
        okLabel.textContent = 'Excluir';
    }


    // =====================================================
    // OPEN MODAL FROM DELETE BUTTON
    // =====================================================

    document.querySelectorAll('[data-delete-news]').forEach((button) => {

        button.addEventListener('click', function () {

            const id = this.dataset.id;
            const title = this.dataset.title;

            openDeleteModal(id, title);
        });
    });


    // =====================================================
    // CANCEL DELETE
    // =====================================================

    cancelBtn.addEventListener('click', closeDeleteModal);


    // =====================================================
    // CLOSE MODAL WHEN CLICKING OUTSIDE
    // =====================================================

    modal.addEventListener('click', function (event) {

        if (event.target === modal) {
            closeDeleteModal();
        }
    });


    // =====================================================
    // CLOSE MODAL WITH ESCAPE
    // =====================================================

    document.addEventListener('keydown', function (event) {

        if (
            event.key === 'Escape' &&
            !modal.classList.contains('hidden')
        ) {
            closeDeleteModal();
        }
    });


    // =====================================================
    // CONFIRM DELETE
    // =====================================================

    okBtn.addEventListener('click', async function () {

        if (!pendingId) {
            return;
        }

        okBtn.disabled = true;
        okLabel.textContent = 'Excluindo...';

        const formData = new FormData();

        formData.append('id', pendingId);


        // =================================================
        // SEND DELETE REQUEST
        // =================================================

        try {

            const response = await fetch(
                '<?= path('api/admin/news/delete') ?>',
                {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': '<?= e(csrf_token()) ?>'
                    },
                    body: formData
                }
            );


            // =============================================
            // PARSE RESPONSE
            // =============================================

            const json = await response.json();


            // =============================================
            // HANDLE SERVER ERROR
            // =============================================

            if (!response.ok || !json.success) {

                alert(
                    json.message ||
                    'Não foi possível excluir a notícia.'
                );

                return;
            }


            // =============================================
            // REMOVE NEWS ROW
            // =============================================

            const row = document.querySelector(
                '[data-news-row="' + pendingId + '"]'
            );

            if (row) {

                row.style.transition =
                    'opacity .2s ease, transform .2s ease';

                row.style.opacity = '0';
                row.style.transform = 'translateX(12px)';

                setTimeout(function () {
                    row.remove();
                }, 200);
            }


            // =============================================
            // CLOSE MODAL
            // =============================================

            closeDeleteModal();

        } catch (error) {

            alert(
                'Falha de comunicação com o servidor.'
            );

        } finally {

            okBtn.disabled = false;
            okLabel.textContent = 'Excluir';
        }
    });

})();
</script>
