import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

if (typeof window.deferLoadingAlpine === 'undefined') {
    window.deferLoadingAlpine = (callback) => {
        document.addEventListener('alpine:initialized', () => {
            callback(window.Alpine);
        });
    };
}

window.addEventListener('DOMContentLoaded', () => {
    if (!window.Alpine || typeof window.Alpine.start !== 'function') {
        return;
    }

    // Prevent starting multiple Alpine instances.
    if (!window.Alpine._started) {
        window.Alpine.start();
        window.Alpine._started = true;
    }
});

