function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenu.classList.toggle('active');
    }

    // Oggetto per gestire gli indici di ciascun carosello
    const carouselIndices = {
        'carousel-nuovo': 0,
        'carousel-usato': 0
    };

    function moveCarousel(carouselId, direction) {
        const carouselContainer = document.getElementById(carouselId);
        const carouselItems = carouselContainer.querySelector('.carousel-items');
        const items = carouselItems.getElementsByClassName('carousel-item');
        const totalItems = items.length;

        // Aggiorna l'indice del carosello specifico
        carouselIndices[carouselId] = (carouselIndices[carouselId] + direction + totalItems) % totalItems;

        // Sposta il carosello
        carouselItems.style.transform = `translateX(-${carouselIndices[carouselId] * 100}%)`;
    }

    // Gestione delle tendine nel menu mobile
    const dropdowns = document.querySelectorAll('.dropdown-mobile');

    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', () => {
            dropdown.classList.toggle('active');
        });
    });

    // Reset i caroselli quando il layout cambia (resize della finestra)
    window.addEventListener('resize', function () {
        const isDesktop = window.innerWidth >= 768;
        if (isDesktop) {
            // Se siamo su desktop, resettare la posizione dei caroselli
            Object.keys(carouselIndices).forEach(carouselId => {
                carouselIndices[carouselId] = 0;
                const carouselContainer = document.getElementById(carouselId);
                const carouselItems = carouselContainer.querySelector('.carousel-items');
                carouselItems.style.transform = `translateX(0%)`; // Allineamento iniziale
            });
        }
    });
