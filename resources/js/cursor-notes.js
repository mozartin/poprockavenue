const NOTES = ['♪', '♫', '♩', '♬'];
const COLORS = ['#22D3EE', '#A78BFA', '#FB7185', '#F8FAFC'];

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

    const offsetX = (Math.random() - 0.5) * 24;
    const offsetY = (Math.random() - 0.5) * 16;
    const driftX = (Math.random() - 0.5) * 36;
    const driftY = -16 - Math.random() * 28;
    const rotate = (Math.random() - 0.5) * 40;
    const size = 9 + Math.random() * 5;

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
    const throttleMs = 40;

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
