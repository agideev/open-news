<!--
============================================================
Vue.js
============================================================
-->

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<!--
============================================================
 Fim: Vue.js
============================================================
-->

<div id="app" class="h-screen overflow-hidden bg-white text-gray-900">

    <!-- Áudio global — controla todas as notícias -->
    <audio
        ref="audioEl"
        @ended="onAudioEnded"
        @pause="onAudioPause"
        @play="onAudioPlay"
        class="hidden"
    ></audio>


    <!-- Modal Limite Usage -->
    <?php
        partial('chat.modal-usage-limit');
    ?>

    <!--
    ============================================================
     Estrutura principal do chat
    ============================================================
    -->
    <div class="flex h-full flex-col">

        <!-- Header do chat -->
        <?php
            partial('chat.header');
        ?>

        <!-- Área de mensagens -->
        <?php
            partial('chat.messages');
        ?>

        <!-- Composer: campo para escrever e enviar mensagens -->
        <?php
            partial('chat.composer');
        ?>

    </div>

</div>

<script>

const { createApp, ref, nextTick } = Vue;

createApp({

    setup() {
        // ============================================================
        // Configurações da API
        // ============================================================

        const API_URL = '<?= url('api/ai/chat') ?>';
        const CSRF_TOKEN = '<?= csrf_token() ?>';

        // ============================================================
        // Estado principal do chat
        // ============================================================

        const question = ref('');
        const messages = ref([]);
        const loading = ref(false);

        // ============================================================
        // IDs já recomendados nesta conversa
        // ============================================================

        const excludedIds = ref([]);

        // ============================================================
        // Referências dos elementos do chat
        // ============================================================

        const messagesContainer = ref(null);
        const input = ref(null);

        // ============================================================
        // Fim: Configurações e estado inicial do chat
        // ============================================================

        // ============================================================
        // Modal de limite de mensagens
        // ============================================================

        const limitModal = ref({ open: false, used: 0, limit: 10 });

        function handleLimit(errors) {
            console.log(errors);

            limitModal.value = {
                open: true,
                used: errors?.used ?? 0,
                limit: errors?.limit ?? 20,
            };

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        // ============================================================
        // Fim: Modal de limite de mensagens
        // ============================================================

        // ============================================================
        // Áudio global
        // ============================================================

        const audioEl       = ref(null);
        const currentAudioId = ref(null);
        const audioPlaying  = ref(false);

        const isPlaying = (news) => {
            return audioPlaying.value && currentAudioId.value === news.id;
        };

        const toggleAudio = (news) => {
            const el = audioEl.value;
            if (!el || !news.audio_url) return;

            // Mesma notícia → play/pause
            if (currentAudioId.value === news.id) {
                if (el.paused) {
                    el.play();
                } else {
                    el.pause();
                }
                return;
            }

            // Nova notícia → troca a fonte e toca
            currentAudioId.value = news.id;
            el.src = news.audio_url;
            el.currentTime = 0;
            el.play();
        };

        const onAudioPlay  = () => { audioPlaying.value = true;  refreshIcons(); };
        const onAudioPause = () => { audioPlaying.value = false; refreshIcons(); };
        const onAudioEnded = () => {
            audioPlaying.value  = false;
            currentAudioId.value = null;
            refreshIcons();
        };

        // ============================================================
        // Fim: Áudio global
        // ============================================================

        const scrollToBottom = async () => {

            await nextTick();

            if (messagesContainer.value) {
                messagesContainer.value.scrollTop =
                    messagesContainer.value.scrollHeight;
            }

        };


        const refreshIcons = async () => {

            await nextTick();

            if (window.lucide) {
                lucide.createIcons();
            }

        };

        // ============================================================
        // Envia a mensagem e processa a resposta do assistente
        // ============================================================
        const sendMessage = async () => {

            const text = question.value.trim();

            if (!text || loading.value) {
                return;
            }

            messages.value.push({
                role: 'user',
                content: text
            });

            question.value = '';
            loading.value = true;

            await scrollToBottom();

            try {

                const response = await fetch(API_URL, {

                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',

                        // CSRF
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },

                    body: JSON.stringify({

                        question: text,

                        history: messages.value.map(message => ({
                            role: message.role,
                            content: message.content
                        })),

                        // IDs já recomendados → evita repetição
                        excluded_ids: excludedIds.value

                    })

                });

                const data = await response.json();

                // Trata erros HTTP
                if (!response.ok) {

                    throw {
                        status: response.status,

                        message:
                            data?.message ||
                            data?.error ||
                            'Não foi possível obter uma resposta.',

                        errors: data?.errors || null
                    };

                }

                const answer =
                    data?.answer ||
                    data?.data?.answer ||
                    data?.content ||
                    'Não foi possível obter uma resposta.';

                // Notícias relacionadas
                const relatedNews =
                    data?.related_news ||
                    data?.data?.related_news ||
                    [];

                // IDs das notícias relacionadas
                const relatedIds =
                    data?.related_news_ids ||
                    data?.data?.related_news_ids ||
                    [];

                loading.value = false;

                await typeMessage(answer);

                // Anexa recomendações após terminar a digitação
                if (Array.isArray(relatedNews) && relatedNews.length > 0) {

                    const last = messages.value[messages.value.length - 1];

                    if (last && last.role === 'assistant') {
                        last.related_news = relatedNews;
                    }

                    await nextTick();
                    await scrollToBottom();
                }

                // Acumula IDs para a próxima requisição
                if (Array.isArray(relatedIds) && relatedIds.length > 0) {

                    excludedIds.value = [
                        ...excludedIds.value,
                        ...relatedIds
                    ];

                }

            } catch (error) {

                console.error(error);

                // Limite diário de créditos
                if (error.status === 429) {

                    handleLimit(error.errors);

                    return;
                }

                // Outros erros
                messages.value.push({

                    role: 'assistant',

                    content:
                        error.message ||
                        'Ocorreu um erro ao comunicar com o assistente.'

                });

            } finally {

                loading.value = false;

                await scrollToBottom();

                // input.value?.focus();

                refreshIcons();

            }

        };

        // ============================================================
        // Fim: Envio e processamento da mensagem
        // ============================================================


        // ============================================================
        // Exibe a resposta do assistente com efeito de digitação
        // ============================================================

        const typeMessage = async (content) => {

            refreshIcons();
            let typedContent = '';

            // Cria a mensagem somente depois do primeiro caractere
            for (let i = 0; i < content.length; i++) {

                typedContent += content[i];

                // Na primeira iteração, cria a mensagem já com conteúdo
                if (i === 0) {

                    messages.value.push({
                        role: 'assistant',
                        content: typedContent
                    });

                } else {

                    messages.value[messages.value.length - 1].content = typedContent;

                }

                await nextTick();
                await scrollToBottom();

                await new Promise(resolve => {
                    setTimeout(resolve, 5);
                });
            }
        };

        // ============================================================
        // Fim: Efeito de digitação da resposta
        // ============================================================

        return {
            question,
            messages,
            loading,
            excludedIds,
            messagesContainer,
            input,
            sendMessage,
            // áudio
            audioEl,
            isPlaying,
            toggleAudio,
            onAudioPlay,
            onAudioPause,
            onAudioEnded,
            limitModal,
            handleLimit
        };

    }

}).mount('#app');
</script>
