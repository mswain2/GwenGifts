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
document.addEventListener('DOMContentLoaded', updateFilters);

// Autocomplete for Event and Volunteer fields
function initAutocomplete(searchId, hiddenId, listId, allValue, allPlaceholder) {
    const searchInput = document.getElementById(searchId);
    const hiddenInput = document.getElementById(hiddenId);
    const list = document.getElementById(listId);
    if (!searchInput || !list) return;

    const items = list.querySelectorAll('.autocomplete-item');

    searchInput.addEventListener('focus', function () {
        filterItems('');
        list.style.display = 'block';
    });

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        hiddenInput.value = allValue;
        filterItems(query);
        list.style.display = 'block';
    });

    function filterItems(query) {
        let hasVisible = false;
        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (!query || text.includes(query)) {
                item.style.display = 'block';
                hasVisible = true;
            } else {
                item.style.display = 'none';
            }
        });
        list.style.display = hasVisible ? 'block' : 'none';
    }

    items.forEach(item => {
        item.addEventListener('mousedown', function (e) {
            e.preventDefault();
            searchInput.value = this.textContent;
            hiddenInput.value = this.getAttribute('data-value');
            list.style.display = 'none';
        });
    });

    searchInput.addEventListener('blur', function () {
        list.style.display = 'none';
        if (!searchInput.value.trim()) {
            hiddenInput.value = allValue;
        }
    });

    // Allow clearing to reset to "all"
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            searchInput.value = '';
            hiddenInput.value = allValue;
            list.style.display = 'none';
            searchInput.blur();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initAutocomplete('event_id_search', 'event_id', 'event_id_list', 'all', 'All Events');
    initAutocomplete('volunteer_search', 'volunteer', 'volunteer_list', 'all', 'All Volunteers');
});
