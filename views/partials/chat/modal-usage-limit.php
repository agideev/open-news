<!-- views/partials/chat/modal-usage-limit.php -->
<div v-if="limitModal.open"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4"
     style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999;"
     @click.self="limitModal.open = false">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 border border-[var(--line)] relative z-[10000]">
      <h3 class="text-lg font-semibold text-slate-900 text-center mb-1">
        Limite diário atingido
      </h3>

      <p class="text-sm text-slate-500 text-center mb-6">
        Você usou <strong class="text-slate-700">{{ limitModal.used }}</strong>
        de <strong class="text-slate-700">{{ limitModal.limit }}</strong> créditos.
        Tente novamente amanhã.
      </p>

      <div class="flex items-center justify-end gap-2">
        <button type="button"
                @click="limitModal.open = false"
                class="px-4 py-2 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100
                       text-sm font-medium transition-colors">
          Fechar
        </button>
      </div>
    </div>
</div>
