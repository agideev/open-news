<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

  <!-- Cabeçalho -->
  <div class="flex items-center gap-3 mb-6">
    <a href="<?= path('/admin/news') ?>"
       class="grid place-items-center w-10 h-10 rounded-lg bg-white border border-[var(--line)]
              text-slate-500 hover:text-[var(--brand)] hover:border-[var(--brand)]/50 transition-colors">
      <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Editar Notícia</h1>
      <p class="text-sm text-slate-500 mt-0.5">Atualize as informações da notícia</p>
    </div>
  </div>

  <form id="news-form"
        data-action="<?= path('api/news/' . (int) $news['id'] .'/update') ?>"
        enctype="multipart/form-data"
        class="bg-white border border-[var(--line)] rounded-2xl p-6 sm:p-7 space-y-6 shadow-sm">

    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="_method" value="PUT">

    <!-- Erros -->
    <div id="form-errors"
         class="hidden rounded-lg border border-[var(--brand)]/40 bg-red-50 text-[var(--brand-dim)] text-sm px-4 py-3"></div>

    <?php partial('news.form-fields', ['news' => $news]); ?>
    <?php partial('news.form-upload-imagem', ['news' => $news]); ?>
    <?php partial('news.form-upload-audio', ['news' => $news]); ?>
    <?php partial('news.form-images-gallery', ['images' => $images ?? []]); ?>

    <!-- Ações -->
    <div class="flex items-center justify-end gap-3 pt-5 border-t border-[var(--line)]">
      <a href="<?= path('/news') ?>"
         class="px-4 py-2.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 text-sm font-medium transition-colors">
        Cancelar
      </a>

      <?php partial('submit-button', [
        'btnId'    => 'submit-btn',
        'btnLabel' => 'Salvar Alterações',
        'btnIcon'  => 'save',
        'loading'  => 'Salvando...',
      ]); ?>
    </div>
  </form>

</div>

<script>
(function () {
  // ==========================================================
  // Form (preview + submit AJAX)
  // ==========================================================
  const form     = document.getElementById('news-form');
  const errorsEl = document.getElementById('form-errors');
  const btn      = document.getElementById('submit-btn');

  function bindPreviewImage(inputId, imgId) {
    const input = document.getElementById(inputId);
    const img   = document.getElementById(imgId);
    if (!input || !img) return;

    const wrapper = input.closest('div.group');
    const empty   = wrapper.querySelector('[data-upload-empty]');
    const preview = wrapper.querySelector('[data-upload-preview]');
    const nameEl  = wrapper.querySelector('[data-upload-name]');

    input.addEventListener('change', () => {
      const file = input.files[0];
      if (!file) return;
      img.src = URL.createObjectURL(file);
      nameEl.textContent = file.name;
      empty.classList.add('hidden');
      preview.classList.remove('hidden');
      if (window.lucide) lucide.createIcons();
    });

    bindDropzone(input);
  }

  function bindPreviewAudio(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const wrapper = input.closest('div.group');
    const empty   = wrapper.querySelector('[data-upload-empty]');
    const preview = wrapper.querySelector('[data-upload-preview]');
    const nameEl  = wrapper.querySelector('[data-upload-name]');

    input.addEventListener('change', () => {
      const file = input.files[0];
      if (!file) return;
      nameEl.textContent = file.name;
      empty.classList.add('hidden');
      preview.classList.remove('hidden');
      if (window.lucide) lucide.createIcons();
    });

    bindDropzone(input);
  }

  function bindDropzone(input) {
    const dropzone = input.nextElementSibling;
    if (!dropzone) return;

    ['dragenter', 'dragover'].forEach((ev) => {
      dropzone.addEventListener(ev, (e) => {
        e.preventDefault();
        dropzone.classList.add('border-[var(--brand)]', 'bg-red-50');
      });
    });

    ['dragleave', 'drop'].forEach((ev) => {
      dropzone.addEventListener(ev, (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-[var(--brand)]', 'bg-red-50');
      });
    });

    dropzone.addEventListener('drop', (e) => {
      const file = e.dataTransfer.files[0];
      if (!file) return;
      const dt = new DataTransfer();
      dt.items.add(file);
      input.files = dt.files;
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });
  }

  bindPreviewImage('image', 'preview-main');
  bindPreviewAudio('audio');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    errorsEl.classList.add('hidden');

    window.setSubmitLoading('#submit-btn', true, 'Salvando...');

    const fd = new FormData(form);

    try {
      const res = await fetch(form.dataset.action, {
        method: 'POST',
        headers: { 'X-CSRF-Token': '<?= e(csrf_token()) ?>' },
        body: fd,
      });

      const json = await res.json();

      if (!res.ok || !json.success) {
        const messages = json.errors
          ? Object.values(json.errors).join('\n')
          : (json.message || 'Erro ao salvar.');
        errorsEl.textContent = messages;
        errorsEl.classList.remove('hidden');
        return;
      }

      window.location.href = '<?= path('/admin/news') ?>';
    } catch (err) {
      errorsEl.textContent = 'Falha de comunicação com o servidor.';
      errorsEl.classList.remove('hidden');
    } finally {
      btn.disabled = false;
      window.setSubmitLoading('#submit-btn', false, 'Salvar');
    }
  });
})();

// ==========================================================
// Repeater (somente imagens da galeria)
// ==========================================================
(function () {
  function initRepeater(cfg) {
    const container = document.getElementById(cfg.container);
    const addBtn    = document.getElementById(cfg.addBtn);
    const template  = document.getElementById(cfg.template);

    if (!container || !addBtn || !template) return;

    let index = container.querySelectorAll(cfg.itemSelector).length;

    function reindex() {
      const items = container.querySelectorAll(cfg.itemSelector);
      items.forEach((item, i) => {
        item.querySelectorAll('[name]').forEach((el) => {
          el.name = el.name.replace(/\[\d+]/, '[' + i + ']');
        });
        item.dataset.index = i;
        const posInput = item.querySelector('[data-position-input]');
        if (posInput) posInput.value = i;
      });
    }

    function bindFileName(node) {
      const input  = node.querySelector('input[type="file"]');
      const nameEl = node.querySelector('[data-file-name]');
      if (!input || !nameEl) return;

      input.addEventListener('change', () => {
        const file = input.files[0];
        nameEl.textContent = file ? file.name : 'Escolher';
        nameEl.classList.toggle('text-[var(--brand)]', !!file);
        nameEl.classList.toggle('font-medium', !!file);
      });
    }

    function bindRemove(node) {
      const remove = node.querySelector('[data-remove]');
      if (!remove) return;
      remove.addEventListener('click', () => {
        node.remove();
        reindex();
      });
    }

    function addItem() {
      const html = template.innerHTML.replace(/__INDEX__/g, String(index));
      const wrap = document.createElement('div');
      wrap.innerHTML = html.trim();
      const node = wrap.firstElementChild;
      container.appendChild(node);
      index++;
      reindex();
      bindRemove(node);
      bindFileName(node);
      if (window.lucide) lucide.createIcons();
    }

    container.querySelectorAll(cfg.itemSelector).forEach((node) => {
      bindRemove(node);
      bindFileName(node);
    });

    addBtn.addEventListener('click', addItem);
  }

  initRepeater({
    container:    'images-container',
    addBtn:       'images-add',
    template:     'images-template',
    itemSelector: '[data-image-item]',
  });
})();
</script>
