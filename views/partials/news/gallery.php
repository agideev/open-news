<?php
/**
 * Partial: news/gallery
 *
 * Espera:
 * @var array $images
 *
 * Cada imagem deve possuir:
 * - image_url
 * - caption (opcional)
 */

$images = $images ?? [];

$images = array_values(array_filter(
    $images,
    static fn ($img) => !empty($img['image_url'])
));

if (empty($images)) {
    return;
}

$galleryId = 'news-gallery-' . uniqid();
?>

<section
    id="<?= e($galleryId) ?>"
    class="mt-12 pt-8 border-t border-[var(--line)]"
    data-news-gallery
>
    <!-- Cabeçalho -->
    <div class="flex items-center justify-between gap-4 mb-5">
        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <i
                data-lucide="images"
                class="w-5 h-5 text-[var(--brand)]"
            ></i>

            Galeria
        </h2>

        <?php if (count($images) > 1): ?>
            <span
                class="text-xs font-medium text-slate-500 whitespace-nowrap"
                data-gallery-counter
            >
                1 / <?= count($images) ?>
            </span>
        <?php endif; ?>
    </div>

    <!-- Carrossel -->
    <div class="relative">

        <div
            class="overflow-hidden rounded-xl"
            data-gallery-viewport
        >
            <div
                class="flex transition-transform duration-300 ease-out"
                data-gallery-track
            >

                <?php foreach ($images as $index => $img): ?>

                    <button
                        type="button"
                        class="relative min-w-full block overflow-hidden rounded-xl
                               bg-slate-100 focus:outline-none
                               focus-visible:ring-2 focus-visible:ring-[var(--brand)]
                               cursor-zoom-in"
                        data-gallery-item
                        data-index="<?= $index ?>"
                        aria-label="Abrir imagem <?= $index + 1 ?>"
                    >

                        <!-- Área da imagem -->
                        <div
                            class="w-full flex items-center justify-center
                                   min-h-[220px] sm:min-h-[320px] lg:min-h-[420px]
                                   max-h-[650px]"
                        >
                            <img
                                src="<?= e($img['image_url']) ?>"
                                alt="<?= e($img['caption'] ?? 'Imagem da notícia') ?>"
                                class="block w-full h-auto max-h-[650px] object-contain"
                                loading="<?= $index === 0 ? 'eager' : 'lazy' ?>"
                                draggable="false"
                            >
                        </div>

                        <?php if (!empty($img['caption'])): ?>
                            <div
                                class="absolute inset-x-0 bottom-0
                                       bg-gradient-to-t from-slate-950/85
                                       via-slate-950/40 to-transparent
                                       px-4 pt-10 pb-4
                                       text-left"
                            >
                                <p class="text-white text-sm leading-relaxed">
                                    <?= e($img['caption']) ?>
                                </p>
                            </div>
                        <?php endif; ?>

                    </button>

                <?php endforeach; ?>

            </div>
        </div>

        <?php if (count($images) > 1): ?>

            <!-- Anterior -->
            <button
                type="button"
                data-gallery-prev
                aria-label="Imagem anterior"
                class="absolute left-2 sm:left-3 top-1/2 -translate-y-1/2
                       w-9 h-9 sm:w-10 sm:h-10
                       rounded-full
                       bg-slate-950/70 hover:bg-slate-950/90
                       text-white
                       flex items-center justify-center
                       backdrop-blur-sm
                       transition
                       disabled:opacity-30 disabled:cursor-not-allowed"
            >
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </button>

            <!-- Próxima -->
            <button
                type="button"
                data-gallery-next
                aria-label="Próxima imagem"
                class="absolute right-2 sm:right-3 top-1/2 -translate-y-1/2
                       w-9 h-9 sm:w-10 sm:h-10
                       rounded-full
                       bg-slate-950/70 hover:bg-slate-950/90
                       text-white
                       flex items-center justify-center
                       backdrop-blur-sm
                       transition
                       disabled:opacity-30 disabled:cursor-not-allowed"
            >
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </button>

        <?php endif; ?>

    </div>

    <?php if (count($images) > 1): ?>

        <!-- Indicadores -->
        <div
            class="flex justify-center items-center gap-1.5 mt-4"
            data-gallery-dots
        >
            <?php foreach ($images as $index => $img): ?>
                <button
                    type="button"
                    data-gallery-dot="<?= $index ?>"
                    aria-label="Ir para imagem <?= $index + 1 ?>"
                    class="h-1.5 rounded-full transition-all duration-300
                           <?= $index === 0
                               ? 'w-6 bg-[var(--brand)]'
                               : 'w-1.5 bg-slate-300' ?>"
                ></button>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <!-- =========================================================
         VISUALIZADOR DE IMAGEM
         ========================================================= -->

    <div
        class="fixed inset-0 z-[9999]
               hidden items-center justify-center
               bg-slate-950/90 backdrop-blur-sm
               p-3 sm:p-6"
        data-gallery-lightbox
        aria-hidden="true"
    >

        <!-- Área clicável para fechar -->
        <div
            class="absolute inset-0"
            data-lightbox-close
        ></div>

        <!-- Container da imagem -->
        <div
            class="relative z-10
                   w-full max-w-6xl
                   max-h-[92vh]
                   flex flex-col
                   items-center justify-center"
        >

            <!-- Fechar -->
            <button
                type="button"
                data-lightbox-close
                aria-label="Fechar imagem"
                class="absolute
                       -top-1 -right-1
                       sm:top-2 sm:right-2
                       z-20
                       w-11 h-11
                       rounded-full
                       bg-slate-950/80 hover:bg-slate-950
                       text-white
                       flex items-center justify-center
                       shadow-lg
                       backdrop-blur-sm
                       transition"
            >
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>

            <!-- Imagem ampliada -->
            <img
                src=""
                alt=""
                data-lightbox-image
                class="block
                       max-w-full
                       max-h-[82vh]
                       sm:max-h-[88vh]
                       w-auto h-auto
                       object-contain
                       rounded-lg
                       shadow-2xl
                       select-none"
            >

            <!-- Legenda -->
            <p
                class="mt-3
                       max-w-3xl
                       text-center
                       text-sm
                       leading-relaxed
                       text-white/90
                       px-4"
                data-lightbox-caption
            ></p>

        </div>
    </div>
</section>

<script>
(function () {
    const gallery = document.getElementById(
        <?= json_encode($galleryId) ?>
    );

    if (!gallery) {
        return;
    }

    const track = gallery.querySelector('[data-gallery-track]');
    const items = gallery.querySelectorAll('[data-gallery-item]');
    const counter = gallery.querySelector('[data-gallery-counter]');
    const prevButton = gallery.querySelector('[data-gallery-prev]');
    const nextButton = gallery.querySelector('[data-gallery-next]');
    const dots = gallery.querySelectorAll('[data-gallery-dot]');

    const lightbox = gallery.querySelector('[data-gallery-lightbox]');
    const lightboxImage = gallery.querySelector('[data-lightbox-image]');
    const lightboxCaption = gallery.querySelector('[data-lightbox-caption]');
    const closeButtons = gallery.querySelectorAll('[data-lightbox-close]');

    let currentIndex = 0;

    function updateGallery() {
        track.style.transform =
            `translateX(-${currentIndex * 100}%)`;

        if (counter) {
            counter.textContent =
                `${currentIndex + 1} / ${items.length}`;
        }

        if (prevButton) {
            prevButton.disabled = currentIndex === 0;
        }

        if (nextButton) {
            nextButton.disabled =
                currentIndex === items.length - 1;
        }

        dots.forEach((dot, index) => {
            if (index === currentIndex) {
                dot.classList.remove(
                    'w-1.5',
                    'bg-slate-300'
                );

                dot.classList.add(
                    'w-6',
                    'bg-[var(--brand)]'
                );
            } else {
                dot.classList.remove(
                    'w-6',
                    'bg-[var(--brand)]'
                );

                dot.classList.add(
                    'w-1.5',
                    'bg-slate-300'
                );
            }
        });
    }

    function goTo(index) {
        if (index < 0 || index >= items.length) {
            return;
        }

        currentIndex = index;
        updateGallery();
    }

    function next() {
        if (currentIndex < items.length - 1) {
            goTo(currentIndex + 1);
        }
    }

    function previous() {
        if (currentIndex > 0) {
            goTo(currentIndex - 1);
        }
    }

    function openLightbox(index) {
        const item = items[index];

        if (!item) {
            return;
        }

        const image = item.querySelector('img');

        if (!image) {
            return;
        }

        lightboxImage.src = image.currentSrc || image.src;
        lightboxImage.alt = image.alt || '';

        const caption = image.closest('[data-gallery-item]')
            ?.querySelector('p');

        lightboxCaption.textContent =
            caption ? caption.textContent.trim() : '';

        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');

        lightbox.setAttribute('aria-hidden', 'false');

        document.body.classList.add('overflow-hidden');

        requestAnimationFrame(() => {
            lightboxImage.focus?.();
        });
    }

    function closeLightbox() {
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');

        lightbox.setAttribute('aria-hidden', 'true');

        lightboxImage.src = '';
        lightboxCaption.textContent = '';

        document.body.classList.remove('overflow-hidden');
    }

    /*
     * Navegação
     */

    if (prevButton) {
        prevButton.addEventListener('click', previous);
    }

    if (nextButton) {
        nextButton.addEventListener('click', next);
    }

    dots.forEach((dot) => {
        dot.addEventListener('click', () => {
            goTo(Number(dot.dataset.galleryDot));
        });
    });

    /*
     * Clique nas imagens
     */

    items.forEach((item, index) => {
        item.addEventListener('click', () => {
            openLightbox(index);
        });
    });

    /*
     * Fechar Lightbox
     */

    closeButtons.forEach((button) => {
        button.addEventListener('click', closeLightbox);
    });

    /*
     * ESC
     */

    document.addEventListener('keydown', (event) => {
        if (
            lightbox.classList.contains('hidden')
        ) {
            return;
        }

        if (event.key === 'Escape') {
            closeLightbox();
        }

        if (event.key === 'ArrowRight') {
            next();
        }

        if (event.key === 'ArrowLeft') {
            previous();
        }
    });

    /*
     * Swipe no mobile
     */

    let touchStartX = 0;
    let touchEndX = 0;

    track.addEventListener(
        'touchstart',
        (event) => {
            touchStartX =
                event.changedTouches[0].screenX;
        },
        { passive: true }
    );

    track.addEventListener(
        'touchend',
        (event) => {
            touchEndX =
                event.changedTouches[0].screenX;

            const distance =
                touchStartX - touchEndX;

            /*
             * Ignora movimentos muito pequenos.
             */
            if (Math.abs(distance) < 50) {
                return;
            }

            if (distance > 0) {
                next();
            } else {
                previous();
            }
        },
        { passive: true }
    );

    /*
     * Inicialização
     */

    updateGallery();

    /*
     * Caso o sistema já utilize Lucide.
     */
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
})();
</script>
