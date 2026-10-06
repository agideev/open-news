<?php
$audioUrl = $audioUrl ?? null;

if (empty($audioUrl)) {
    return;
}
?>

<div
    id="news-audio-player"
    data-audio-player
    class="fixed
           z-50
           left-3 right-3 bottom-3
           sm:left-5 sm:right-5 sm:bottom-5
           lg:left-1/2 lg:right-auto lg:-translate-x-1/2
           lg:w-[min(800px,calc(100vw-40px))]
           bg-white
           text-slate-900
           border border-[var(--line)]
           rounded-2xl
           shadow-[0_12px_40px_-12px_rgba(15,23,42,0.25)]
           transition-all duration-300">

    <audio
        data-audio
        preload="metadata"
        src="<?= e($audioUrl) ?>">
    </audio>

    <!-- Conteúdo -->
    <div class="px-4 py-3.5 sm:px-5 sm:py-4">

        <!-- Cabeçalho -->
        <div class="flex items-center justify-between gap-3 mb-3">

            <p class="text-[11px]
                      font-semibold
                      uppercase
                      tracking-wider
                      text-[var(--brand)]">
                Resumo da notícia
            </p>

            <!-- Fechar -->
            <button
                type="button"
                data-close
                aria-label="Fechar player"
                class="grid
                       place-items-center
                       w-8 h-8
                       shrink-0
                       rounded-full
                       bg-slate-100
                       text-slate-500
                       hover:bg-red-50
                       hover:text-[var(--brand)]
                       active:scale-95
                       transition-all">

                <i
                    data-lucide="x"
                    class="w-4 h-4">
                </i>

            </button>

        </div>

        <!-- Controles -->
        <div class="flex items-center gap-2 sm:gap-3">

            <!-- -10 segundos -->
            <button
                type="button"
                data-skip="-10"
                aria-label="Voltar 10 segundos"
                class="grid
                       place-items-center
                       w-9 h-9
                       sm:w-10 sm:h-10
                       shrink-0
                       rounded-full
                       bg-slate-100
                       text-slate-600
                       hover:bg-red-50
                       hover:text-[var(--brand)]
                       active:scale-95
                       transition-all">

                <i
                    data-lucide="rotate-ccw"
                    class="w-4 h-4">
                </i>

            </button>

            <!-- Play / Pause -->
            <button
                type="button"
                data-play
                aria-label="Reproduzir áudio"
                class="grid
                       place-items-center
                       w-11 h-11
                       sm:w-12 sm:h-12
                       shrink-0
                       rounded-full
                       bg-[var(--brand)]
                       text-white
                       hover:bg-[var(--brand-dim)]
                       active:scale-95
                       shadow-md
                       shadow-[var(--brand)]/25
                       transition-all">

                <!-- Play -->
                <i
                    data-icon-play
                    data-lucide="play"
                    class="w-5 h-5 ml-0.5">
                </i>

                <!-- Pause -->
                <i
                    data-icon-pause
                    data-lucide="pause"
                    class="hidden w-5 h-5">
                </i>

            </button>

            <!-- +10 segundos -->
            <button
                type="button"
                data-skip="10"
                aria-label="Avançar 10 segundos"
                class="grid
                       place-items-center
                       w-9 h-9
                       sm:w-10 sm:h-10
                       shrink-0
                       rounded-full
                       bg-slate-100
                       text-slate-600
                       hover:bg-red-50
                       hover:text-[var(--brand)]
                       active:scale-95
                       transition-all">

                <i
                    data-lucide="rotate-cw"
                    class="w-4 h-4">
                </i>

            </button>

            <!-- Progresso -->
            <div class="flex-1 min-w-0">

                <div class="flex items-center gap-2">

                    <!-- Tempo atual -->
                    <span
                        data-current-time
                        class="text-[10px]
                               sm:text-xs
                               font-mono
                               text-slate-500
                               shrink-0
                               w-9">
                        00:00
                    </span>

                    <!-- Barra -->
                    <div
                        data-progress
                        role="slider"
                        aria-label="Progresso do áudio"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        aria-valuenow="0"
                        tabindex="0"
                        class="group
                               relative
                               h-2
                               flex-1
                               rounded-full
                               bg-slate-100
                               cursor-pointer
                               touch-none
                               focus:outline-none
                               focus:ring-4
                               focus:ring-[var(--brand)]/15">

                        <div
                            data-fill
                            class="absolute
                                   inset-y-0
                                   left-0
                                   rounded-full
                                   bg-[var(--brand)]
                                   transition-[width]
                                   duration-75"
                            style="width:0%">
                        </div>

                        <div
                            data-knob
                            class="absolute
                                   top-1/2
                                   -translate-y-1/2
                                   -translate-x-1/2
                                   w-3 h-3
                                   rounded-full
                                   bg-white
                                   border-2
                                   border-[var(--brand)]
                                   shadow-md
                                   opacity-0
                                   group-hover:opacity-100
                                   group-focus:opacity-100
                                   transition-opacity"
                            style="left:0%">
                        </div>

                    </div>

                    <!-- Duração -->
                    <span
                        data-duration
                        class="text-[10px]
                               sm:text-xs
                               font-mono
                               text-slate-500
                               shrink-0
                               w-9
                               text-right">
                        --:--
                    </span>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
(function () {

    if (window.__newsAudioPlayerInit) {
        return;
    }

    window.__newsAudioPlayerInit = true;

    function initAudioPlayer() {

        const root = document.querySelector(
            '[data-audio-player]'
        );

        if (!root) {
            return;
        }

        const audio = root.querySelector(
            '[data-audio]'
        );

        const playBtn = root.querySelector(
            '[data-play]'
        );

        const iconPlay = root.querySelector(
            '[data-icon-play]'
        );

        const iconPause = root.querySelector(
            '[data-icon-pause]'
        );

        const progress = root.querySelector(
            '[data-progress]'
        );

        const fill = root.querySelector(
            '[data-fill]'
        );

        const knob = root.querySelector(
            '[data-knob]'
        );

        const currentTime = root.querySelector(
            '[data-current-time]'
        );

        const duration = root.querySelector(
            '[data-duration]'
        );

        const closeBtn = root.querySelector(
            '[data-close]'
        );

        const skipButtons = root.querySelectorAll(
            '[data-skip]'
        );

        if (
            !audio ||
            !playBtn ||
            !progress
        ) {
            return;
        }

        /*
         * Formata o tempo
         */
        function formatTime(seconds) {

            if (
                !Number.isFinite(seconds) ||
                seconds < 0
            ) {
                return '00:00';
            }

            seconds = Math.floor(seconds);

            const minutes = Math.floor(
                seconds / 60
            );

            const remainingSeconds =
                seconds % 60;

            return (
                String(minutes).padStart(2, '0') +
                ':' +
                String(remainingSeconds).padStart(2, '0')
            );
        }

        /*
         * Atualiza Play / Pause
         */
        function updatePlayState(isPlaying) {

            iconPlay.classList.toggle(
                'hidden',
                isPlaying
            );

            iconPause.classList.toggle(
                'hidden',
                !isPlaying
            );

            playBtn.setAttribute(
                'aria-label',
                isPlaying
                    ? 'Pausar áudio'
                    : 'Reproduzir áudio'
            );
        }

        /*
         * Atualiza progresso
         */
        function updateProgress() {

            if (
                !Number.isFinite(audio.duration) ||
                audio.duration <= 0
            ) {
                return;
            }

            const percentage =
                (audio.currentTime /
                    audio.duration) * 100;

            const value = Math.max(
                0,
                Math.min(100, percentage)
            );

            fill.style.width =
                value + '%';

            knob.style.left =
                value + '%';

            progress.setAttribute(
                'aria-valuenow',
                String(Math.round(value))
            );

            currentTime.textContent =
                formatTime(audio.currentTime);
        }

        /*
         * Play / Pause
         */
        playBtn.addEventListener(
            'click',
            async () => {

                try {

                    if (audio.paused) {
                        await audio.play();
                    } else {
                        audio.pause();
                    }

                } catch (error) {

                    console.error(
                        'Erro ao reproduzir o áudio:',
                        error
                    );

                }

            }
        );

        /*
         * Eventos do áudio
         */
        audio.addEventListener(
            'play',
            () => {
                updatePlayState(true);
            }
        );

        audio.addEventListener(
            'pause',
            () => {
                updatePlayState(false);
            }
        );

        audio.addEventListener(
            'ended',
            () => {

                updatePlayState(false);

                audio.currentTime = 0;

                updateProgress();

            }
        );

        audio.addEventListener(
            'loadedmetadata',
            () => {

                duration.textContent =
                    formatTime(audio.duration);

                updateProgress();

            }
        );

        audio.addEventListener(
            'timeupdate',
            updateProgress
        );

        /*
         * +10 / -10 segundos
         */
        skipButtons.forEach((button) => {

            button.addEventListener(
                'click',
                () => {

                    if (
                        !Number.isFinite(
                            audio.duration
                        )
                    ) {
                        return;
                    }

                    const seconds =
                        Number(button.dataset.skip);

                    audio.currentTime =
                        Math.max(
                            0,
                            Math.min(
                                audio.duration,
                                audio.currentTime + seconds
                            )
                        );

                    updateProgress();

                }
            );

        });

        /*
         * Seek
         */
        function seek(clientX) {

            if (
                !Number.isFinite(audio.duration) ||
                audio.duration <= 0
            ) {
                return;
            }

            const rect =
                progress.getBoundingClientRect();

            const percentage =
                (clientX - rect.left) /
                rect.width;

            const value =
                Math.max(
                    0,
                    Math.min(1, percentage)
                );

            audio.currentTime =
                value * audio.duration;

            updateProgress();
        }

        /*
         * Clique na barra
         */
        progress.addEventListener(
            'click',
            (event) => {
                seek(event.clientX);
            }
        );

        /*
         * Arrastar barra
         */
        let dragging = false;

        progress.addEventListener(
            'pointerdown',
            (event) => {

                dragging = true;

                progress.setPointerCapture(
                    event.pointerId
                );

                seek(event.clientX);

            }
        );

        progress.addEventListener(
            'pointermove',
            (event) => {

                if (!dragging) {
                    return;
                }

                seek(event.clientX);

            }
        );

        progress.addEventListener(
            'pointerup',
            (event) => {

                dragging = false;

                if (
                    progress.hasPointerCapture(
                        event.pointerId
                    )
                ) {

                    progress.releasePointerCapture(
                        event.pointerId
                    );

                }

            }
        );

        progress.addEventListener(
            'pointercancel',
            () => {
                dragging = false;
            }
        );

        /*
         * Teclado
         */
        progress.addEventListener(
            'keydown',
            (event) => {

                if (
                    !Number.isFinite(
                        audio.duration
                    )
                ) {
                    return;
                }

                if (
                    event.key === 'ArrowRight'
                ) {

                    event.preventDefault();

                    audio.currentTime =
                        Math.min(
                            audio.duration,
                            audio.currentTime + 5
                        );

                }

                if (
                    event.key === 'ArrowLeft'
                ) {

                    event.preventDefault();

                    audio.currentTime =
                        Math.max(
                            0,
                            audio.currentTime - 5
                        );

                }

                if (
                    event.key === ' ' ||
                    event.key === 'Enter'
                ) {

                    event.preventDefault();

                    playBtn.click();

                }

            }
        );

        /*
         * Fechar player
         */
        closeBtn.addEventListener(
            'click',
            () => {

                audio.pause();

                root.style.opacity = '0';
                root.style.transform =
                    'translateY(20px)';

                setTimeout(() => {
                    root.remove();
                }, 250);

            }
        );

        /*
         * ESC fecha o player
         */
        document.addEventListener(
            'keydown',
            (event) => {

                if (event.key !== 'Escape') {
                    return;
                }

                if (!document.body.contains(root)) {
                    return;
                }

                audio.pause();

                root.style.opacity = '0';
                root.style.transform =
                    'translateY(20px)';

                setTimeout(() => {
                    root.remove();
                }, 250);

            }
        );

        /*
         * Estado inicial
         */
        updatePlayState(false);
        updateProgress();

        /*
         * Lucide
         */
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    if (
        document.readyState === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            initAudioPlayer
        );

    } else {

        initAudioPlayer();

    }

})();
</script>
