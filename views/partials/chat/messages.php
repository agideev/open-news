<!-- =====================================================
     CONTEÚDO
     HEADER E FOOTER SÃO FIXOS
     SOMENTE ESTA ÁREA POSSUI ROLAGEM
====================================================== -->

<main
    ref="messagesContainer"
    class="h-full overflow-y-auto pt-16 pb-36"
>

    <!-- Container principal -->
    <div
        class="mx-auto flex min-h-full w-full max-w-4xl flex-col px-4"
    >

        <!-- =================================================
             ESTADO INICIAL
        ================================================== -->
        <div
            v-if="messages.length === 0"
            class="flex flex-1 items-center justify-center py-8"
        >

            <div class="w-full max-w-md text-center">
                <!-- Título -->
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">
                    Olá, o que gostaria de saber?
                </h2>

                <!-- Descrição -->
                <p class="mx-auto mt-3 mb-3 max-w-sm text-sm leading-relaxed text-slate-500">
                    Sou o assistente do <span class="font-semibold text-slate-700">Open News</span>.
                    Pergunte sobre qualquer notícia publicada.
                </p>
                <!-- Sugestões -->
                <div class="mt-7">
                    <div class="flex flex-wrap justify-center gap-2">

                        <button
                            type="button"
                            @click="question = 'Quais são as principais notícias de hoje?'; input?.focus()"
                            class="group inline-flex items-center gap-1.5
                                   rounded-full border border-[var(--line)] bg-white
                                   px-3.5 py-2 text-xs font-medium text-slate-600
                                   hover:border-[var(--brand)]/40 hover:bg-red-50 hover:text-[var(--brand)]
                                   transition-colors"
                        >
                            <i data-lucide="flame" class="w-3.5 h-3.5 opacity-60 group-hover:opacity-100"></i>
                            Principais notícias
                        </button>

                        <button
                            type="button"
                            @click="question = 'Quais são as notícias mais recentes?'; input?.focus()"
                            class="group inline-flex items-center gap-1.5
                                   rounded-full border border-[var(--line)] bg-white
                                   px-3.5 py-2 text-xs font-medium text-slate-600
                                   hover:border-[var(--brand)]/40 hover:bg-red-50 hover:text-[var(--brand)]
                                   transition-colors"
                        >
                            <i data-lucide="clock" class="w-3.5 h-3.5 opacity-60 group-hover:opacity-100"></i>
                            Notícias recentes
                        </button>

                        <button
                            type="button"
                            @click="question = 'O que está acontecendo em Moçambique?'; input?.focus()"
                            class="group inline-flex items-center gap-1.5
                                   rounded-full border border-[var(--line)] bg-white
                                   px-3.5 py-2 text-xs font-medium text-slate-600
                                   hover:border-[var(--brand)]/40 hover:bg-red-50 hover:text-[var(--brand)]
                                   transition-colors"
                        >
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 opacity-60 group-hover:opacity-100"></i>
                            Moçambique
                        </button>

                    </div>
                </div>

            </div>

        </div>


        <!-- =================================================
             CONVERSA
        ================================================== -->
        <div
            v-else
            class="flex flex-col gap-6 py-6"
        >

            <!-- Mensagens -->
            <div
                v-for="(message, index) in messages"
                :key="index"
                class="flex w-full"
                :class="message.role === 'user'
                    ? 'justify-end'
                    : 'justify-start'"
            >

                <div class="max-w-[88%] md:max-w-[72%]">

                    <!-- Mensagem do usuário -->
                    <div
                        v-if="message.role === 'user'"
                        class="rounded-2xl rounded-br-md bg-red-600 px-4 py-3 text-sm leading-6 text-white"
                    >
                        {{ message.content }}
                    </div>


                    <!-- Mensagem da IA -->
                    <?php
                        partial('chat.message-ai');
                    ?>

                </div>

            </div>


            <!-- =================================================
                 LOADING
            ================================================== -->
            <div
                v-if="loading"
                class="flex justify-start"
            >

                <div
                    class="rounded-2xl rounded-bl-md bg-gray-100 px-4 py-3"
                >

                    <div class="flex items-center gap-1.5">

                        <span
                            class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400"
                        ></span>

                        <span
                            class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400"
                            style="animation-delay: 0.15s"
                        ></span>

                        <span
                            class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400"
                            style="animation-delay: 0.3s"
                        ></span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>
