// Galería de Imágenes
window.addEventListener('load', function () {
    const gallery = document.querySelector('.gallery_content');
    const items = [...document.querySelectorAll('.item_gallery')];

    const prevCarruselBtn = document.querySelector(".arrow-left-carrusel");
    const nextCarruselBtn = document.querySelector(".arrow-right-carrusel");

    const lightbox = document.querySelector('.lightbox-carrusel');
    const lightboxImg = document.querySelector('.lightbox-img img');
    const closeBtn = document.querySelector('.control-close span');

    const prevLightboxBtn = document.querySelector(".arrow-left-lightbox");
    const nextLightboxBtn = document.querySelector(".arrow-right-lightbox");

    let currentIndex = 0;
    let autoSlideInterval;
    let scrollAmount = 1; // Velocidad de desplazamiento
    let position = 0; // Posición inicial del carrusel

    // 🔹 Clonar imágenes para loop infinito
    function duplicateImages() {
        items.forEach(item => {
            const clone = item.cloneNode(true);

            gallery.appendChild(clone);
            items.push(clone)
        });

    }

    duplicateImages();

    // 🔹 Configurar carrusel con `left`
    gallery.style.position = "relative";
    gallery.style.left = "0px";

    // 🔹 Animación automática con `left`
    function startAutoScroll() {
        stopAutoScroll(); // Evita múltiples intervalos

        autoSlideInterval = setInterval(() => {
            position -= scrollAmount; // Mover a la izquierda
            gallery.style.left = `${position}px`;

            // Si llega al final del clon, reiniciar sin parpadeo
            if (Math.abs(position) >= gallery.scrollWidth / 2) {
                position = 0;
            }
        }, 20);
    }

    function stopAutoScroll() {
        clearInterval(autoSlideInterval);
    }

    // Flechas de navegación
    prevCarruselBtn.addEventListener("click", () => {
        // stopAutoScroll();
        position += 220;
        gallery.style.left = `${position}px`;
        setTimeout(startAutoScroll, 3000);
    });

    nextCarruselBtn.addEventListener("click", () => {
        // stopAutoScroll();
        position -= 220;
        gallery.style.left = `${position}px`;
        setTimeout(startAutoScroll, 3000);
    });

    startAutoScroll(); // Iniciar el desplazamiento automático
});