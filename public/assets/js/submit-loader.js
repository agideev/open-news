(function () {
  /**
   * Ativa/desativa o estado de loading de um botão [data-submit].
   *
   * @param {HTMLElement|string} target Elemento, seletor, ou id
   * @param {boolean}            loading
   * @param {string}            label    Texto customizado (opcional)
   */
  function setSubmitLoading(target, loading, label) {
    const btn = typeof target === 'string'
      ? document.querySelector(target.startsWith('#') || target.startsWith('.') ? target : '#' + target)
      : target;

    if (!btn) return;

    const icon    = btn.querySelector('.submit-icon');
    const spinner = btn.querySelector('.submit-spinner');
    const text    = btn.querySelector('.submit-text');

    const defaultLabel = btn.dataset.labelDefault || 'Salvar';
    const loadingLabel = label || btn.dataset.labelLoading || 'Salvando...';

    if (loading) {
      btn.disabled = true;
      icon?.classList.add('hidden');
      spinner?.classList.remove('hidden');
      if (text) text.textContent = loadingLabel;
    } else {
      btn.disabled = false;
      icon?.classList.remove('hidden');
      spinner?.classList.add('hidden');
      if (text) text.textContent = defaultLabel;
    }
  }

  // Expõe globalmente
  window.setSubmitLoading = setSubmitLoading;
})();
