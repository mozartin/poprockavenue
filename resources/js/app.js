import { initParallax } from './parallax';
import { initCursorNotes } from './cursor-notes';

function boot() {
    initParallax();
    initCursorNotes();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
