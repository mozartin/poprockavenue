export function initParallax() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const containers = document.querySelectorAll('[data-parallax]');

    if (! containers.length) {
        return;
    }

    let ticking = false;

    const update = () => {
        const viewportHeight = window.innerHeight;

        containers.forEach((container) => {
            const target = container.querySelector('[data-parallax-target]');

            if (! target) {
                return;
            }

            const rect = container.getBoundingClientRect();
            const speed = parseFloat(container.dataset.parallaxSpeed || '0.2');

            if (rect.bottom < 0 || rect.top > viewportHeight) {
                return;
            }

            const centerOffset = (rect.top + rect.height / 2) - (viewportHeight / 2);
            const translateY = centerOffset * speed * -1;

            target.style.transform = `translate3d(0, ${translateY}px, 0)`;
        });

        ticking = false;
    };

    const requestUpdate = () => {
        if (! ticking) {
            requestAnimationFrame(update);
            ticking = true;
        }
    };

    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate, { passive: true });

    requestUpdate();
}
