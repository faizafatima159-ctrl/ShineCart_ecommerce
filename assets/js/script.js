// assets/js/script.js
document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Slider Logic
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    if (slides.length > 0) {
        let currentSlide = 0;
        let slideInterval;

        const goToSlide = (n) => {
            slides[currentSlide].classList.remove('active');
            dots[currentSlide]?.classList.remove('active');
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
            dots[currentSlide]?.classList.add('active');
        };

        const nextSlide = () => goToSlide(currentSlide + 1);
        const prevSlide = () => goToSlide(currentSlide - 1);

        const startSlider = () => {
            slideInterval = setInterval(nextSlide, 5000);
        };

        const resetSlider = () => {
            clearInterval(slideInterval);
            startSlider();
        };

        nextBtn?.addEventListener('click', () => { nextSlide(); resetSlider(); });
        prevBtn?.addEventListener('click', () => { prevSlide(); resetSlider(); });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                goToSlide(index);
                resetSlider();
            });
        });

        startSlider();
    }

    // 2. Scroll Animations using IntersectionObserver
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };

    const scrollObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                // Optional: stop observing once it's visible if you only want it to animate once
                // observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    animatedElements.forEach(el => scrollObserver.observe(el));
});
