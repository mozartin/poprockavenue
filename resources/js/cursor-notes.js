const NOTES = ['♪', '♫', '♩', '♬'];
const COLORS = ['#22D3EE', '#7C3AED', '#F43F5E', '#F8FAFC'];

function canUseNotesCursor() {
    if (typeof window === 'undefined') {
        return false;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return false;
    }

    return window.matchMedia('(hover: hover) and (pointer: fine)').matches;
}

function spawnNote(x, y) {
    const note = document.createElement('span');
    note.className = 'cursor-note';
    note.textContent = NOTES[Math.floor(Math.random() * NOTES.length)];
    note.setAttribute('aria-hidden', 'true');

    const offsetX = (Math.random() - 0.5) * 28;
    const offsetY = (Math.random() - 0.5) * 18;
    const driftX = (Math.random() - 0.5) * 40;
    const driftY = -20 - Math.random() * 36;
    const rotate = (Math.random() - 0.5) * 50;
    const size = 10 + Math.random() * 8;

    note.style.setProperty('--note-x', `${offsetX}px`);
    note.style.setProperty('--note-y', `${offsetY}px`);
    note.style.setProperty('--note-drift-x', `${driftX}px`);
    note.style.setProperty('--note-drift-y', `${driftY}px`);
    note.style.setProperty('--note-rotate', `${rotate}deg`);
    note.style.setProperty('--note-size', `${size}px`);
    note.style.setProperty('--note-color', COLORS[Math.floor(Math.random() * COLORS.length)]);
    note.style.left = `${x}px`;
    note.style.top = `${y}px`;

    document.body.appendChild(note);

    note.addEventListener('animationend', () => note.remove(), { once: true });
}

export function initCursorNotes() {
    if (!canUseNotesCursor()) {
        return;
    }

    document.documentElement.classList.add('has-cursor-notes');

    let lastSpawn = 0;
    const throttleMs = 55;

    const onMove = (event) => {
        const now = performance.now();

        if (now - lastSpawn < throttleMs) {
            return;
        }

        lastSpawn = now;
        spawnNote(event.clientX, event.clientY);
    };

    window.addEventListener('pointermove', onMove, { passive: true });
}
