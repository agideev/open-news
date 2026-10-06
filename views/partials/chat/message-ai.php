<!-- views/partials/chat/message-ai.php -->

<div
    v-else
    class="rounded-2xl rounded-bl-md bg-gray-100 px-4 py-3 text-sm leading-6 text-gray-800"
>

<!-- Cabeçalho: ícone + nome da IA -->
<div class="mb-1.5 flex items-center gap-1.5">
    <span
        class="flex h-8 w-8 items-center justify-center
               rounded-full bg-red-600 text-white"
    >
        <i
            data-lucide="bot"
            class="h-5 w-5"
        ></i>
    </span>
    <span
        class="text-[11px] font-semibold
               uppercase tracking-wide text-gray-500"
    >
        Opens News IA
    </span>
</div>

<!-- Conteúdo -->
<div>
    {{ message.content }}

    <!-- Cursor durante a escrita -->
    <span
        v-if="loading && index === messages.length - 1"
        class="ml-0.5 inline-block h-4 w-0.5 animate-pulse bg-gray-500 align-middle"
    ></span>
</div>

<!-- ===================================================== -->
<!-- NOTÍCIAS RELACIONADAS — recomendação da IA            -->
<!-- Aparece somente após o typeMessage terminar           -->
<!-- ===================================================== -->
<div
    v-if="message.related_news && message.related_news.length"
    class="mt-3 space-y-2"
>
    <div
        v-for="news in message.related_news"
        :key="news.id"
        class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-3 transition-all duration-300 hover:border-red-200 hover:shadow-lg hover:shadow-red-100/50"
    >

        <!-- Faixa de destaque no topo -->
        <div
            class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-red-500 via-red-400 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
        ></div>

        <div class="flex items-center gap-3">

            <!-- Container da imagem — imagem centralizada -->
            <div
                class="relative flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200/60"
            >
                <img
                    v-if="news.image_url"
                    :src="news.image_url"
                    :alt="news.title"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                    loading="lazy"
                >
                <i
                    v-else
                    data-lucide="image"
                    class="h-5 w-5 text-gray-300"
                ></i>
            </div>

            <!-- Conteúdo -->
            <div class="flex min-w-0 flex-1 flex-col gap-1.5">

                <!-- Título -->
                <a
                    :href="news.url"
                    class="line-clamp-2 text-[13px] font-semibold leading-5 text-gray-900 transition-colors hover:text-red-600"
                >
                    {{ news.title }}
                </a>

                <!-- Ações -->
                <div class="flex items-center gap-2">

                    <!-- Botão: ler notícia -->
                    <a
                        :href="news.url"
                        class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-semibold text-gray-700 transition hover:bg-gray-200"
                    >
                        <i data-lucide="newspaper" class="h-3 w-3"></i>
                        Ler
                    </a>

                    <!-- Botão: play/pause do áudio -->
                    <button
                        v-if="news.audio_url"
                        type="button"
                        @click.stop.prevent="toggleAudio(news)"
                        class="inline-flex items-center gap-1 rounded-full bg-red-600 px-2.5 py-1 text-[11px] font-semibold text-white shadow-sm transition hover:bg-red-700 active:scale-95"
                        :aria-label="isPlaying(news) ? 'Pausar áudio' : 'Ouvir resumo da notícia'"
                    >
                        <i
                            :data-lucide="isPlaying(news) ? 'pause' : 'play'"
                            class="h-3 w-3 fill-current"
                        ></i>
                        {{ isPlaying(news) ? 'Pausar' : 'Ouvir' }}
                    </button>

                </div>

            </div>

        </div>

    </div>
</div>
<!-- ===================================================== -->
<!-- FIM — NOTÍCIAS RELACIONADAS                           -->
<!-- ===================================================== -->
</div>
