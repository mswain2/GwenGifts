const reportDescriptions = {
    volunteer_hours: `<strong>Volunteer Hours</strong> — Total hours served by volunteers, broken down by individual. Use filters to narrow by date range, event, or specific volunteer.`,
    volunteer_participation: `<strong>Volunteer Participation</strong> — Sign-up and attendance rates per event. Shows how many volunteers signed up versus how many actually attended.`,
    volunteer_growth: `<strong>Volunteer Growth</strong> — Tracks new volunteer registrations and churn (inactive volunteers) over time to reveal organizational trends.`,
    top_volunteers: `<strong>Top Volunteers</strong> — Leaderboard of volunteers ranked by total hours served or events attended within the selected time period.`
};

function updateFilters() {
    const type = document.getElementById('type').value;
    const dynamicFilters = document.getElementById('dynamic-filters');
    const formatSection = document.getElementById('format-section');
    const submitSection = document.getElementById('submit-section');
    const descriptionBox = document.getElementById('report-description');

    if (!type) {
        dynamicFilters.style.display = 'none';
        formatSection.style.display = 'none';
        submitSection.style.display = 'none';
        descriptionBox.style.display = 'none';
        return;
    }

    // Show metric description
    descriptionBox.innerHTML = reportDescriptions[type] || '';
    descriptionBox.style.display = 'block';

    // Show filter section
    dynamicFilters.style.display = 'block';
    formatSection.style.display = 'block';
    submitSection.style.display = 'block';

    // Toggle individual filter fields
    const filterFields = dynamicFilters.querySelectorAll('[data-reports]');
    filterFields.forEach(field => {
        const reports = field.getAttribute('data-reports').split(' ');
        if (reports.includes(type)) {
            field.classList.remove('filter-hidden');
            field.classList.add('filter-visible');
            field.querySelectorAll('select, input').forEach(el => el.disabled = false);
        } else {
            field.classList.remove('filter-visible');
            field.classList.add('filter-hidden');
            field.querySelectorAll('select, input').forEach(el => el.disabled = true);
        }
    });
}

document.getElementById('type').addEventListener('change', updateFilters);

// --- Filter persistence via sessionStorage ---
const STORAGE_KEY = 'report_filters';

function saveFiltersToStorage() {
    const form = document.getElementById('report-form');
    if (!form) return;
    const data = {};
    form.querySelectorAll('select, input').forEach(el => {
        if (el.name) data[el.name] = el.value;
    });
    // Also save the visible text for autocomplete search fields
    const eventSearch = document.getElementById('event_id_search');
    const volunteerSearch = document.getElementById('volunteer_search');
    if (eventSearch) data['_event_id_search'] = eventSearch.value;
    if (volunteerSearch) data['_volunteer_search'] = volunteerSearch.value;
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(data));
}

function restoreFiltersFromStorage() {
    const raw = sessionStorage.getItem(STORAGE_KEY);
    if (!raw) return;
    const data = JSON.parse(raw);
    const form = document.getElementById('report-form');
    if (!form) return;

    // Restore form fields
    form.querySelectorAll('select, input').forEach(el => {
        if (el.name && data[el.name] !== undefined) {
            el.value = data[el.name];
        }
    });

    // Restore autocomplete search text
    const eventSearch = document.getElementById('event_id_search');
    const volunteerSearch = document.getElementById('volunteer_search');
    if (eventSearch && data['_event_id_search'] !== undefined) {
        eventSearch.value = data['_event_id_search'];
    }
    if (volunteerSearch && data['_volunteer_search'] !== undefined) {
        volunteerSearch.value = data['_volunteer_search'];
    }
}

document.addEventListener('DOMContentLoaded', function () {
    restoreFiltersFromStorage();
    updateFilters();

    // Save on any change to form inputs
    const form = document.getElementById('report-form');
    if (form) {
        form.addEventListener('change', saveFiltersToStorage);
        form.addEventListener('input', saveFiltersToStorage);
    }
});

// Validate that start date is before end date
document.addEventListener('DOMContentLoaded', function () {
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');
    if (!dateFrom || !dateTo) return;

    function updateDateMin() {
        if (dateFrom.value) {
            // End date must be at least one day after start date
            const next = new Date(dateFrom.value);
            next.setDate(next.getDate() + 1);
            const yyyy = next.getFullYear();
            const mm = String(next.getMonth() + 1).padStart(2, '0');
            const dd = String(next.getDate()).padStart(2, '0');
            dateTo.min = yyyy + '-' + mm + '-' + dd;
        } else {
            dateTo.removeAttribute('min');
        }
        dateTo.setCustomValidity('');
    }

    dateFrom.addEventListener('input', updateDateMin);
    dateTo.addEventListener('input', function () {
        dateTo.setCustomValidity('');
    });

    updateDateMin();

    // On submit, if end date is invalid, scroll to it and show the browser tooltip
    const form = document.getElementById('report-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (dateFrom.value && dateTo.value && dateTo.validity && !dateTo.validity.valid) {
                e.preventDefault();
                dateTo.reportValidity();
            }
        });
    }
});

// Autocomplete for Event and Volunteer fields
const autocompleteValidators = [];

function initAutocomplete(searchId, hiddenId, listId, allValue, errorMessage) {
    const searchInput = document.getElementById(searchId);
    const hiddenInput = document.getElementById(hiddenId);
    const list = document.getElementById(listId);
    if (!searchInput || !list) return;

    const allLabel = searchInput.dataset.allLabel || 'All';
    const items = list.querySelectorAll('.autocomplete-item');

    function setInvalid() {
        searchInput.setCustomValidity(errorMessage);
    }

    function clearInvalid() {
        searchInput.setCustomValidity('');
    }

    function clearToBlank() {
        searchInput.value = '';
        hiddenInput.value = allValue;
        clearInvalid();
    }

    searchInput.addEventListener('focus', function () {
        filterItems('');
        list.style.display = 'block';
    });

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        hiddenInput.value = allValue;
        const hasMatch = filterItems(query);
        list.style.display = 'block';
        if (!query || hasMatch) {
            clearInvalid();
        } else {
            setInvalid();
        }
    });

    // Returns true if at least one non-"All" item matches the query
    function filterItems(query) {
        let hasVisible = false;
        let hasMatch = false;
        items.forEach(item => {
            // Always show the "All" option, even while filtering
            if (item.classList.contains('autocomplete-item-all')) {
                item.style.display = 'block';
                hasVisible = true;
                return;
            }
            const text = item.textContent.toLowerCase();
            if (!query || text.includes(query)) {
                item.style.display = 'block';
                hasVisible = true;
                hasMatch = true;
            } else {
                item.style.display = 'none';
            }
        });
        list.style.display = hasVisible ? 'block' : 'none';
        return hasMatch;
    }

    items.forEach(item => {
        item.addEventListener('mousedown', function (e) {
            e.preventDefault();
            searchInput.value = this.textContent;
            hiddenInput.value = this.getAttribute('data-value');
            list.style.display = 'none';
            clearInvalid();
            saveFiltersToStorage();
        });
    });

    searchInput.addEventListener('blur', function () {
        list.style.display = 'none';
        const value = searchInput.value.trim();
        if (!value) {
            clearToBlank();
            saveFiltersToStorage();
            return;
        }
        // Value present but nothing selected from the list → unmatched
        if (hiddenInput.value === allValue && value !== allLabel) {
            setInvalid();
        } else {
            clearInvalid();
        }
    });

    // Allow clearing to reset to "all"
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            clearToBlank();
            list.style.display = 'none';
            searchInput.blur();
            saveFiltersToStorage();
        }
    });

    // Return an object the form-submit handler can use to validate this field
    autocompleteValidators.push({
        isInvalid() {
            // Skip validation for fields hidden by the current report type
            if (searchInput.disabled) return false;
            const value = searchInput.value.trim();
            if (!value) return false;
            return hiddenInput.value === allValue && value !== allLabel;
        },
        setInvalid,
        element: searchInput
    });
}

// Close any open autocomplete dropdown when clicking outside its wrapper
document.addEventListener('click', function (e) {
    document.querySelectorAll('.autocomplete-wrap').forEach(wrap => {
        if (!wrap.contains(e.target)) {
            const list = wrap.querySelector('.autocomplete-list');
            if (list) list.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    initAutocomplete('event_id_search', 'event_id', 'event_id_list', 'all', 'No matching event — please select one from the list.');
    initAutocomplete('volunteer_search', 'volunteer', 'volunteer_list', 'all', 'No matching volunteer — please select one from the list.');

    const form = document.getElementById('report-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            let firstInvalid = null;
            autocompleteValidators.forEach(v => {
                if (v.isInvalid()) {
                    v.setInvalid();
                    if (!firstInvalid) firstInvalid = v;
                }
            });
            if (firstInvalid) {
                e.preventDefault();
                // Scroll the field into view without moving focus to it
                firstInvalid.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.element.reportValidity();
            }
        });
    }
});
