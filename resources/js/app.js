import { initParallax } from './parallax';
import { initNeonCursor } from './cursor-neon';

function boot() {
    initParallax();
    initNeonCursor();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
