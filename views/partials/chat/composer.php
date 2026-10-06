<!-- =====================================================
     FOOTER / COMPOSER
====================================================== -->
<footer
    class="pointer-events-none fixed inset-x-0 bottom-0 z-[100]"
>

    <!-- =================================================
         FADE / ÁREA DE DESAPARECIMENTO DAS MENSAGENS

         A parte inferior é totalmente branca.
         Conforme sobe, o fundo vai ficando transparente.
    ================================================== -->
    <div
        class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-56
               bg-gradient-to-t
               from-white
               via-white
               to-transparent"
        aria-hidden="true"
    >
        <!-- Fade intermediário para deixar a transição
             mais suave e longa (altura reduzida em 20%) -->
        <div
            class="absolute inset-x-0 bottom-0 h-[8.8rem]
                   bg-gradient-to-t
                   from-white
                   via-white/95
                   to-transparent"
        ></div>

        <!-- Região branca adicional atrás do composer (altura reduzida em 20%) -->
        <div
            class="absolute inset-x-0 bottom-0 h-[4.8rem]
                   bg-gradient-to-t
                   from-white
                   via-white
                   to-white/0"
        ></div>
    </div>


    <!-- =================================================
         COMPOSER
    ================================================== -->
    <div
        class="relative mx-auto w-full max-w-4xl px-4 pb-4"
    >

        <form
            @submit.prevent="sendMessage"
            class="pointer-events-auto"
        >

            <!-- Contorno único do composer -->
            <div
                class="composer-shell
                       flex min-h-15 w-full items-center gap-3
                       rounded-full border border-[var(--line)]
                       bg-white px-3 py-2
                       shadow-[0_2px_10px_-4px_rgba(15,23,42,0.08)]
                       focus-within:border-[var(--brand)]/40
                       focus-within:shadow-[0_6px_20px_-6px_rgba(220,38,38,0.18)]
                       transition-all duration-200"
            >

                <!-- Input -->
                <textarea
                    ref="input"
                    v-model="question"
                    @keydown.enter.exact.prevent="sendMessage"
                    @keydown.shift.enter.stop
                    rows="1"
                    placeholder="Pergunte sobre as notícias..."
                    style="outline: none !important;
                           outline-offset: 0 !important;
                           box-shadow: none !important;
                           border: 0 !important;
                           -webkit-focus-ring-color: transparent !important;
                           margin-top: 1px;
                           margin-left: 8px;"
                    class="composer-input
                           max-h-32 min-h-[28px] flex-1 resize-none
                           appearance-none border-0 bg-transparent p-0
                           text-sm leading-6 text-slate-900 caret-[var(--brand)]
                           outline-none ring-0 shadow-none
                           focus:border-0 focus:outline-none
                           focus:ring-0 focus:shadow-none
                           focus-visible:outline-none focus-visible:ring-0
                           placeholder:text-slate-400"
                ></textarea>


                <!-- Botão -->
                <button
                    type="submit"
                    :disabled="loading || !question.trim()"
                    aria-label="Enviar mensagem"
                    class="flex h-9 w-9 shrink-0 items-center justify-center
                           rounded-full
                           bg-[var(--brand)] text-white
                           shadow-md shadow-red-500/25
                           transition-all duration-200
                           hover:bg-[var(--brand-dim)] hover:shadow-red-500/40
                           active:scale-95
                           outline-none
                           focus:outline-none
                           focus-visible:outline-none
                           focus-visible:ring-0
                           disabled:cursor-not-allowed
                           disabled:opacity-40
                           disabled:shadow-none"
                >
                    <i
                        data-lucide="send"
                        class="h-4 w-4"
                    ></i>
                </button>

            </div>


            <!-- Aviso -->
            <p
                class="mt-2 px-2 text-center text-[11px] leading-4 text-slate-400"
            >
                O Open News AI pode cometer erros. Verifique informações importantes.
            </p>

        </form>

    </div>

</footer>


<!-- =====================================================
     RESET TOTAL DO CONTORNO NATIVO + FOCO MODERNO NO PAI
====================================================== -->
<style>
/* ---------------------------------------------------------
   INPUT — remove completamente o comportamento nativo
--------------------------------------------------------- */

textarea.composer-input,
textarea.composer-input:hover,
textarea.composer-input:focus,
textarea.composer-input:focus-visible,
textarea.composer-input:focus-within,
textarea.composer-input:active {
    outline: 0 !important;
    outline-width: 0 !important;
    outline-style: none !important;
    outline-color: transparent !important;
    outline-offset: 0 !important;

    box-shadow: none !important;

    border: 0 !important;
    border-width: 0 !important;
    border-style: none !important;
    border-color: transparent !important;

    background-color: transparent !important;

    -webkit-focus-ring-color: transparent !important;
    -webkit-tap-highlight-color: transparent !important;

    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;

    -webkit-border-radius: 0 !important;
    border-radius: 0 !important;
}


/* Firefox */

textarea.composer-input::-moz-focus-inner {
    border: 0 !important;
    padding: 0 !important;
}


/* Safari / iOS */

textarea.composer-input {
    -webkit-text-fill-color: #111827;
}


/* Placeholder */

textarea.composer-input::placeholder {
    color: #9ca3af;
    opacity: 1;
}


/* Autofill */

textarea.composer-input:-webkit-autofill,
textarea.composer-input:-webkit-autofill:hover,
textarea.composer-input:-webkit-autofill:focus {
    -webkit-text-fill-color: #111827;

    -webkit-box-shadow:
        0 0 0 1000px #ffffff inset !important;

    box-shadow:
        0 0 0 1000px #ffffff inset !important;

    transition:
        background-color 9999s ease-out 0s;
}


/* Scrollbar */

textarea.composer-input::-webkit-scrollbar {
    width: 6px;
}

textarea.composer-input::-webkit-scrollbar-track {
    background: transparent;
}

textarea.composer-input::-webkit-scrollbar-thumb {
    background-color: #e5e7eb;
    border-radius: 9999px;
}


/* ---------------------------------------------------------
   SHELL — mantém exatamente o efeito atual
--------------------------------------------------------- */

.composer-shell {
    border-color: #d1d5db;

    box-shadow:
        0 1px 2px rgba(0, 0, 0, 0.02);

    transition:
        border-color 200ms cubic-bezier(0.4, 0, 0.2, 1),
        box-shadow 200ms cubic-bezier(0.4, 0, 0.2, 1),
        background-color 200ms cubic-bezier(0.4, 0, 0.2, 1);

    will-change: border-color, box-shadow;
}


.composer-shell:focus-within {
    border-color: #dc2626;

    box-shadow:
        0 0 0 4px rgba(220, 38, 38, 0.10),
        0 4px 16px -4px rgba(220, 38, 38, 0.18),
        0 1px 2px rgba(0, 0, 0, 0.04);
}


@supports selector(:has(*)) {

    .composer-shell:focus-within {
        border-color: #d1d5db;

        box-shadow:
            0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .composer-shell:has(.composer-input:focus) {
        border-color: #dc2626;

        box-shadow:
            0 0 0 4px rgba(220, 38, 38, 0.10),
            0 4px 16px -4px rgba(220, 38, 38, 0.18),
            0 1px 2px rgba(0, 0, 0, 0.04);
    }
}
</style>
