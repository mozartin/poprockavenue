export function initNeonCursor() {
    if (typeof window === 'undefined') return;
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const dot = document.createElement('div');
    dot.id = 'neon-cursor';

    const ring = document.createElement('div');
    ring.id = 'neon-cursor-ring';

    document.body.appendChild(dot);
    document.body.appendChild(ring);

    let mx = -100, my = -100;
    let rx = -100, ry = -100;
    let raf;

    const move = (e) => {
        mx = e.clientX;
        my = e.clientY;
    };

    const tick = () => {
        dot.style.transform = `translate(calc(${mx}px - 50%), calc(${my}px - 50%))`;

        // ring follows with slight lag
        rx += (mx - rx) * 0.18;
        ry += (my - ry) * 0.18;
        ring.style.transform = `translate(calc(${rx}px - 50%), calc(${ry}px - 50%))`;

        raf = requestAnimationFrame(tick);
    };

    window.addEventListener('pointermove', move, { passive: true });
    raf = requestAnimationFrame(tick);

    return () => {
        window.removeEventListener('pointermove', move);
        cancelAnimationFrame(raf);
        dot.remove();
        ring.remove();
    };
}
