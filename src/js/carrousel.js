
document.addEventListener("DOMContentLoaded", function () {
    var carousel = document.getElementById('indicators-carousel');
    var carouselItems = carousel.querySelectorAll('[data-carousel-item]');
    var carouselPrev = carousel.querySelector('[data-carousel-prev]');
    var carouselNext = carousel.querySelector('[data-carousel-next]');
    var carouselIndicators = carousel.querySelectorAll('[data-carousel-slide-to]');

    var currentSlide = 0;
    var interval = 5000; // Intervalo de cambio en milisegundos

    function showSlide(index) {
        if (index < 0) {
            currentSlide = carouselItems.length - 1;
        } else if (index >= carouselItems.length) {
            currentSlide = 0;
        } else {
            currentSlide = index;
        }

        carouselItems.forEach(item => {
            item.classList.add('hidden');
        });

        carouselItems[currentSlide].classList.remove('hidden');
        carouselIndicators.forEach((indicator, i) => {
            if (i === currentSlide) {
                indicator.setAttribute('aria-current', 'true');
            } else {
                indicator.setAttribute('aria-current', 'false');
            }
        });
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    carouselPrev.addEventListener('click', function () {
        showSlide(currentSlide - 1);
    });

    carouselNext.addEventListener('click', function () {
        nextSlide();
    });

    carouselIndicators.forEach((indicator, i) => {
        indicator.addEventListener('click', function () {
            showSlide(i);
        });
    });

    // Funci¨®n para pasar autom¨¢ticamente al siguiente slide
    setInterval(nextSlide, interval);

    // Mostrar slide inicial
    showSlide(0);
});


document.addEventListener("DOMContentLoaded", function () {
    var images = document.querySelectorAll(".zoom-image");
    var imageStates = [];

    images.forEach(function (image) {
        imageStates.push({
            element: image,
            zoomed: false
        });
    });

    var ticking = false;
    var lastScrollPosition = 0; // Definir la variable lastScrollPosition

    function handleScroll() {
        var scrollPosition = window.scrollY;
        var windowHeight = window.innerHeight;

        imageStates.forEach(function (state) {
            var image = state.element;
            var imageOffsetTop = image.offsetTop;
            var triggerPoint = imageOffsetTop - windowHeight / 2;

            if (scrollPosition > triggerPoint && !state.zoomed) {
                // Zoom the image
                image.style.transform = "scale(1.1)"; // Aumentar el factor de escala
                state.zoomed = true;
               
            } else if (scrollPosition <= triggerPoint && state.zoomed) {
                // Unzoom the image
                image.style.transform = "scale(1)";
                state.zoomed = false;
         
            }
        });

        // Registro de consola para direcci¨®n de desplazamiento
        if (scrollPosition > lastScrollPosition) {

        } else if (scrollPosition < lastScrollPosition) {
           
            // Si el usuario est¨¢ desplazando hacia arriba, restablecer el zoom
            imageStates.forEach(function (state) {
                if (state.zoomed) {
                    var image = state.element;
                    image.style.transform = "scale(1)";
                    state.zoomed = false;
                 
                }
            });
        }

        lastScrollPosition = scrollPosition; // Actualizar posici¨®n de desplazamiento anterior

        ticking = false;
    }

    window.addEventListener("scroll", function () {
        if (!ticking) {
            window.requestAnimationFrame(handleScroll);
            ticking = true;
        }
    });
});