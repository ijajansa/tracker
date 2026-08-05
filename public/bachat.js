(() => {
    const baseUrlMeta = document.querySelector('meta[name="app-base-url"]');
    const baseUrl = (baseUrlMeta?.content || window.location.origin).replace(/\/$/, '');

    const updateNetworkBadge = () => {
        document.querySelectorAll('[data-network-status]').forEach((badge) => {
            badge.textContent = navigator.onLine ? 'Online' : 'Offline';
            badge.classList.toggle('is-offline', !navigator.onLine);
        });
    };

    window.addEventListener('online', updateNetworkBadge);
    window.addEventListener('offline', updateNetworkBadge);
    updateNetworkBadge();

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register(`${baseUrl}/sw.js`).catch(() => {});
        });
    }

    document.querySelectorAll('[data-autofocus-amount]').forEach((input) => {
        window.setTimeout(() => input.focus(), 150);
    });

    const syncGroup = (group) => {
        const boxes = Array.from(group.querySelectorAll('[data-pin-box]'));
        const target = group.querySelector('[data-pin-target]');
        const autoSubmit = group.dataset.autoSubmit === '1';

        const normalize = () => {
            const value = boxes.map((box) => (box.value || '').replace(/\D/g, '').slice(0, 1)).join('');
            target.value = value;
            return value;
        };

        const focusBox = (index) => {
            if (boxes[index]) {
                boxes[index].focus();
                boxes[index].select();
            }
        };

        boxes.forEach((box, index) => {
            box.addEventListener('input', () => {
                box.value = (box.value || '').replace(/\D/g, '').slice(0, 1);
                if (box.value && boxes[index + 1]) {
                    boxes[index + 1].focus();
                }
                const value = normalize();
                if (autoSubmit && value.length === 4) {
                    group.closest('form')?.requestSubmit();
                }
            });

            box.addEventListener('keydown', (event) => {
                if (event.key === 'Backspace' && !box.value && boxes[index - 1]) {
                    boxes[index - 1].focus();
                    boxes[index - 1].value = '';
                    normalize();
                }
            });

            box.addEventListener('paste', (event) => {
                event.preventDefault();
                const pasted = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 4);
                pasted.split('').forEach((digit, offset) => {
                    if (boxes[index + offset]) {
                        boxes[index + offset].value = digit;
                    }
                });
                normalize();
                const nextEmpty = boxes.findIndex((entry) => !entry.value);
                focusBox(nextEmpty === -1 ? boxes.length - 1 : nextEmpty);
                if (autoSubmit && target.value.length === 4) {
                    group.closest('form')?.requestSubmit();
                }
            });
        });

        normalize();
    };

    document.querySelectorAll('[data-pin-group]').forEach(syncGroup);

    document.querySelectorAll('[data-pin-keypad]').forEach((keypad) => {
        const form = keypad.closest('form');
        const group = form?.querySelector('[data-pin-group]');
        if (!group) {
            return;
        }

        const boxes = Array.from(group.querySelectorAll('[data-pin-box]'));
        const target = group.querySelector('[data-pin-target]');

        const fill = (digit) => {
            const box = boxes.find((entry) => !entry.value) || boxes[boxes.length - 1];
            if (box) {
                box.value = digit;
                box.dispatchEvent(new Event('input', { bubbles: true }));
            }
        };

        keypad.addEventListener('click', (event) => {
            const button = event.target.closest('[data-pin-key]');
            if (!button) {
                return;
            }

            const key = button.dataset.pinKey;
            if (/^\d$/.test(key)) {
                fill(key);
                return;
            }

            if (key === 'back') {
                const lastFilled = [...boxes].reverse().find((entry) => entry.value);
                if (lastFilled) {
                    lastFilled.value = '';
                    target.value = boxes.map((entry) => entry.value).join('');
                    const previous = boxes[Math.max(0, boxes.indexOf(lastFilled) - 1)];
                    previous?.focus();
                }
                return;
            }

            if (key === 'clear') {
                boxes.forEach((entry) => (entry.value = ''));
                target.value = '';
                boxes[0]?.focus();
            }
        });
    });

    document.querySelectorAll('[data-draft-form]').forEach((form) => {
        const draftKey = `bachat-draft:${form.dataset.draftKey || form.getAttribute('action') || window.location.pathname}`;
        const inputs = Array.from(form.querySelectorAll('input, textarea, select')).filter((element) => element.name && element.type !== 'password');

        const restore = () => {
            try {
                const stored = JSON.parse(localStorage.getItem(draftKey) || 'null');
                if (!stored) {
                    return;
                }
                inputs.forEach((input) => {
                    if (stored[input.name] !== undefined && input.type !== 'file') {
                        input.value = stored[input.name];
                    }
                });
            } catch {
                localStorage.removeItem(draftKey);
            }
        };

        const save = () => {
            const payload = {};
            inputs.forEach((input) => {
                payload[input.name] = input.value;
            });
            localStorage.setItem(draftKey, JSON.stringify(payload));
        };

        restore();
        inputs.forEach((input) => input.addEventListener('input', save));
        form.addEventListener('submit', () => localStorage.removeItem(draftKey));
    });

    document.querySelectorAll('[data-confirm-delete]').forEach((button) => {
        button.addEventListener('click', (event) => {
            if (!window.confirm(button.dataset.confirmDelete || 'Delete this record?')) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
})();
