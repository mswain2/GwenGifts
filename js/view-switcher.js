$(document).ready(function () {
    // Helper: normalize month param
    function normalizeMonthParam(val) {
        if (!val) return null;
        if (/^\d{4}-\d{2}-\d{2}$/.test(val)) return val;
        if (/^\d{4}-\d{2}$/.test(val)) return val;
        const d = new Date(val);
        if (!isNaN(d)) {
            return d.toISOString().slice(0,10);
        }
        return null;
    }

    let urlMonth = new URLSearchParams(window.location.search).get('month');
    let attrMonth = $('#calendar').data('current-month');
    let paramToUse = urlMonth || attrMonth;
    let currentMonth = normalizeMonthParam(paramToUse) || new Date().toISOString().slice(0,10);

    // SCRUM-16: get current event filter (default: public)
    let currentFilter = new URLSearchParams(window.location.search).get('event_filter') || 'public';

    function monthOnlyFrom(val) {
        if (!val) return new Date().toISOString().slice(0,7);
        return val.slice(0,7);
    }

    initializeFilters();

    // Load initial calendar view
    loadView(`calendar-view.php?month=${encodeURIComponent(monthOnlyFrom(currentMonth))}&event_filter=${currentFilter}`);

    // Switch to calendar (monthly) view
    $("#calendar-view-button").click(function (e) {
        e.preventDefault();
        loadView(`calendar-view.php?month=${encodeURIComponent(monthOnlyFrom(currentMonth))}&event_filter=${currentFilter}`);
    });

    // SCRUM-116: list view button removed — no handler

    // Switch to weekly view
    $("#calendar-weekly-view-button").click(function (e) {
        e.preventDefault();
        let dayParam = currentMonth;
        if (/^\d{4}-\d{2}$/.test(currentMonth)) dayParam = currentMonth + '-01';
        loadView(`calendar-view_weekly.php?month=${encodeURIComponent(dayParam)}&event_filter=${currentFilter}`);
    });

    // Switch to daily view
    $("#calendar-day-view-button").click(function (e) {
        e.preventDefault();
        let dayParam = currentMonth;
        if (/^\d{4}-\d{2}$/.test(currentMonth)) dayParam = currentMonth + '-01';
        loadView(`calendar-view_daily.php?month=${encodeURIComponent(dayParam)}&event_filter=${currentFilter}`);
    });

    // Navigate to previous month
    $(document).on("click", "#previous-month-button", function (e) {
        e.preventDefault();
        const raw = $(this).data('month') || $('#calendar').data('prev-month');
        const normalized = normalizeMonthParam(raw);
        if (normalized) {
            currentMonth = normalized;
            loadView(`calendar-view.php?month=${encodeURIComponent(monthOnlyFrom(normalized))}&event_filter=${currentFilter}`);
        }
    });

    // Navigate to next month
    $(document).on("click", "#next-month-button", function (e) {
        e.preventDefault();
        const raw = $(this).data('month') || $('#calendar').data('next-month');
        const normalized = normalizeMonthParam(raw);
        if (normalized) {
            currentMonth = normalized;
            loadView(`calendar-view.php?month=${encodeURIComponent(monthOnlyFrom(normalized))}&event_filter=${currentFilter}`);
        }
    });

    // SCRUM-16: Event filter dropdown change handler
    $(document).on("click", "#apply-filter-btn", function() {
        currentFilter = $("#event-filter-select").val();
        const currentView = getCurrentView();
        if (currentView === 'weekly') {
            let dayParam = currentMonth;
            if (/^\d{4}-\d{2}$/.test(currentMonth)) dayParam = currentMonth + '-01';
            loadView(`calendar-view_weekly.php?month=${encodeURIComponent(dayParam)}&event_filter=${currentFilter}`);
        } else {
            loadView(`calendar-view.php?month=${encodeURIComponent(monthOnlyFrom(currentMonth))}&event_filter=${currentFilter}`);
        }
    });

    function getCurrentView() {
        // Simple heuristic based on which view is currently loaded
        return window._currentCalView || 'monthly';
    }
});

function loadView(viewFile) {
    // Track which view is loaded
    if (viewFile.includes('weekly')) window._currentCalView = 'weekly';
    else if (viewFile.includes('daily')) window._currentCalView = 'daily';
    else window._currentCalView = 'monthly';

    $.ajax({
        url: viewFile,
        method: "GET",
        beforeSend: function () {
            $("#event-viewer").html("<em>Loading events...</em>");
        },
        success: function (response) {
            $("#event-viewer").html(response);
            initializeFilters();
        },
        error: function () {
            $("#event-viewer").html("<p>Error loading events.</p>");
        },
    });
}

function initializeFilters() {
    $('.filter-wrapper input').on('change', function() {
        const popout = $(this).siblings('.calendar-filter');
        if (this.checked) {
            popout.addClass('open');
        } else {
            popout.removeClass('open');
        }
    });
}
