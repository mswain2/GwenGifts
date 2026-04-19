// Multi-select chip widgets for generateEmailList.php

document.addEventListener('DOMContentLoaded', () => {
    // --- Checkbox-backed multi-select (Role, Status) ---
    document.querySelectorAll('.multi-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            const hidden = document.getElementById(cb.dataset.target);
            if (!hidden) return;
            const values = [];
            document.querySelectorAll(`.multi-checkbox[data-target="${cb.dataset.target}"]:checked`)
                .forEach(el => values.push(el.value));
            hidden.value = values.join(',');
        });
    });

    // --- Autocomplete multi-select (Event, Email, Name, Username) ---
    document.querySelectorAll('.multi-autocomplete').forEach(wrap => {
        const hidden    = document.getElementById(wrap.dataset.hidden);
        const chipArea  = wrap.querySelector('.chip-area');
        const input     = wrap.querySelector('.multi-autocomplete-input');
        const list      = wrap.querySelector('.autocomplete-list');
        if (!hidden || !chipArea || !input || !list) return;

        // Hidden input holds comma-separated list of selected VALUES (not labels)
        const getSelected = () => {
            return hidden.value ? hidden.value.split(',').map(s => s.trim()).filter(Boolean) : [];
        };
        const setSelected = (values) => {
            hidden.value = values.join(',');
        };

        // Rebuild chip DOM from hidden input value
        const rebuildChips = () => {
            const selected = getSelected();
            chipArea.innerHTML = '';
            selected.forEach(val => {
                const chip = document.createElement('span');
                chip.className = 'chip';
                chip.dataset.value = val;
                chip.textContent = val;
                const rm = document.createElement('button');
                rm.type = 'button';
                rm.className = 'chip-remove';
                rm.setAttribute('aria-label', 'Remove');
                rm.textContent = '×';
                chip.appendChild(rm);
                chipArea.appendChild(chip);
            });
            updateListVisibility();
        };

        // Mark items as selected (highlight + hide from dropdown) based on current selection
        const updateListVisibility = () => {
            const selected = getSelected();
            list.querySelectorAll('.autocomplete-item').forEach(item => {
                const val = item.dataset.value;
                if (selected.includes(val)) {
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

        // Add a value to the selection (either from click or from Enter key)
        const addValue = (val) => {
            if (!val) return;
            const selected = getSelected();
            if (selected.includes(val)) return;
            selected.push(val);
            setSelected(selected);
            rebuildChips();
            input.value = '';
            filterList('');
        };

        // Remove a value from the selection
        const removeValue = (val) => {
            const selected = getSelected().filter(v => v !== val);
            setSelected(selected);
            rebuildChips();
            filterList(input.value);
        };

        // --- Events ---
        input.addEventListener('focus', () => {
            filterList(input.value);
            list.style.display = 'block';
        });

        input.addEventListener('input', () => filterList(input.value));

        input.addEventListener('blur', () => {
            // delay so mousedown on items fires first
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

        // Initial state
        updateListVisibility();
    });
});
