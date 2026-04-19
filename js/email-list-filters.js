// Multi-select chip widgets for generateEmailList.php

document.addEventListener('DOMContentLoaded', () => {
    // Close any open dropdown when clicking outside its wrapper
    document.addEventListener('click', (e) => {
        document.querySelectorAll('.multi-autocomplete').forEach(wrap => {
            if (!wrap.contains(e.target)) {
                const list = wrap.querySelector('.autocomplete-list');
                if (list) list.style.display = 'none';
            }
        });
    });

    // Collect one validator per widget so the form-submit handler can check all of them
    const widgetValidators = [];

    document.querySelectorAll('.multi-autocomplete').forEach(wrap => {
        const hidden    = document.getElementById(wrap.dataset.hidden);
        const chipArea  = wrap.querySelector('.chip-area');
        const input     = wrap.querySelector('.multi-autocomplete-input');
        const list      = wrap.querySelector('.autocomplete-list');
        if (!hidden || !chipArea || !input || !list) return;

        // Build a value -> label map so chips show friendly text while the hidden input keeps filter values
        const valueToLabel = {};
        list.querySelectorAll('.autocomplete-item').forEach(item => {
            const v = item.dataset.value;
            valueToLabel[v] = item.dataset.label || item.textContent.trim() || v;
        });

        // Preserve the original placeholder so it can be restored when all chips are removed
        const originalPlaceholder = input.getAttribute('placeholder') || '';
        const syncPlaceholder = () => {
            const hasChips = chipArea.querySelector('.chip') !== null;
            input.setAttribute('placeholder', hasChips ? '' : originalPlaceholder);
        };

        // Hidden input holds comma-separated list of selected VALUES (not labels)
        const getSelected = () => {
            return hidden.value ? hidden.value.split(',').map(s => s.trim()).filter(Boolean) : [];
        };
        const setSelected = (values) => {
            hidden.value = values.join(',');
        };

        // Rebuild chip DOM from hidden input value, displaying labels
        const rebuildChips = () => {
            const selected = getSelected();
            chipArea.innerHTML = '';
            selected.forEach(val => {
                const label = valueToLabel[val] || val;
                const chip = document.createElement('span');
                chip.className = 'chip';
                chip.dataset.value = val;
                chip.textContent = label;
                const rm = document.createElement('button');
                rm.type = 'button';
                rm.className = 'chip-remove';
                rm.setAttribute('aria-label', 'Remove');
                rm.textContent = '×';
                chip.appendChild(rm);
                chipArea.appendChild(chip);
            });
            updateListVisibility();
            syncPlaceholder();
        };

        // Hide already-selected items from the dropdown
        const updateListVisibility = () => {
            const selected = getSelected();
            list.querySelectorAll('.autocomplete-item').forEach(item => {
                if (selected.includes(item.dataset.value)) {
                    item.classList.add('is-selected');
                } else {
                    item.classList.remove('is-selected');
                }
            });
        };

        const filterList = (query) => {
            const q = query.toLowerCase();
            let anyVisible = false;
            list.querySelectorAll('.autocomplete-item').forEach(item => {
                if (item.classList.contains('is-selected')) {
                    item.style.display = 'none';
                    return;
                }
                const text = (item.dataset.label || item.textContent).toLowerCase();
                if (!q || text.includes(q)) {
                    item.style.display = '';
                    anyVisible = true;
                } else {
                    item.style.display = 'none';
                }
            });
            list.style.display = anyVisible ? 'block' : 'none';
        };

        const addValue = (val) => {
            if (!val) return;
            const selected = getSelected();
            if (selected.includes(val)) return;
            selected.push(val);
            setSelected(selected);
            rebuildChips();
            input.value = '';
            input.setCustomValidity('');
            filterList('');
        };

        const removeValue = (val) => {
            const selected = getSelected().filter(v => v !== val);
            setSelected(selected);
            rebuildChips();
            filterList(input.value);
        };

        input.addEventListener('focus', () => {
            filterList(input.value);
            list.style.display = 'block';
        });

        input.addEventListener('input', () => {
            // Clear any previous validity tooltip while the user is typing
            input.setCustomValidity('');
            filterList(input.value);
        });

        input.addEventListener('blur', () => {
            setTimeout(() => { list.style.display = 'none'; }, 150);
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && input.value === '') {
                const selected = getSelected();
                if (selected.length > 0) {
                    selected.pop();
                    setSelected(selected);
                    rebuildChips();
                    filterList('');
                }
            }
            if (e.key === 'Escape') {
                list.style.display = 'none';
                input.blur();
            }
        });

        list.querySelectorAll('.autocomplete-item').forEach(item => {
            item.addEventListener('mousedown', (e) => {
                e.preventDefault();
                addValue(item.dataset.value);
            });
        });

        chipArea.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('.chip-remove');
            if (!removeBtn) return;
            const chip = removeBtn.closest('.chip');
            if (!chip) return;
            removeValue(chip.dataset.value);
        });

        // Initial state — rebuild chips using labels (replaces PHP-rendered raw-value chips if any)
        rebuildChips();

        // Register validator for the form-submit handler
        widgetValidators.push({
            element: input,
            hasUnmatchedText() { return input.value.trim() !== ''; },
            hasSelection() { return getSelected().length > 0; },
            setError(msg) { input.setCustomValidity(msg); },
            clearError() { input.setCustomValidity(''); }
        });
    });

    // Form-level validation: block submit on unmatched typed text OR no filters selected
    const form = document.getElementById('email-list-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            widgetValidators.forEach(v => v.clearError());

            // 1. Unmatched text — user typed but did not click an item
            const firstUnmatched = widgetValidators.find(v => v.hasUnmatchedText());
            if (firstUnmatched) {
                e.preventDefault();
                firstUnmatched.setError('Please select an option from the list or clear this field before generating.');
                firstUnmatched.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstUnmatched.element.reportValidity();
                return;
            }

            // 2. Nothing selected anywhere — must have at least one filter value
            const anySelection = widgetValidators.some(v => v.hasSelection());
            if (!anySelection && widgetValidators.length > 0) {
                e.preventDefault();
                const first = widgetValidators[0];
                first.setError('Select at least one filter before generating a list.');
                first.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                first.element.reportValidity();
            }
        });
    }
});
