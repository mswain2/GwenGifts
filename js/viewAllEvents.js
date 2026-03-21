/**
 * viewAllEvents.js — Client-side filtering, sorting, tab/section switching,
 * Card / Grid / List view toggle, and pagination for the Browse Events page.
 */
document.addEventListener('DOMContentLoaded', function () {

    // ---- Element references ----
    var searchInput    = document.getElementById('search-name');
    var dateFrom       = document.getElementById('filter-date-from');
    var dateTo         = document.getElementById('filter-date-to');
    var locationSelect = document.getElementById('filter-location');
    var timeSelect     = document.getElementById('filter-time');
    var sortSelect     = document.getElementById('sort-by');
    var clearBtn       = document.getElementById('clear-filters');
    var moreFiltersBtn = document.getElementById('more-filters-btn');
    var extraFilters   = document.getElementById('extra-filters');
    var tabSelect      = document.getElementById('filter-tab');
    var resultsCount   = document.getElementById('results-count');
    var pageInfo       = document.getElementById('page-info');
    var paginationEl   = document.getElementById('pagination');
    var viewBtns       = document.querySelectorAll('.vt-btn');

    var activeTab   = 'upcoming';
    var activeView  = 'card';
    var activeTypeFilter = '';
    var currentPage = 1;
    var itemsPerPage = 9;

    // View suffixes for container IDs
    var viewSuffixes = { card: '-cards', grid: '-grid', list: '-list' };

    // ---- Filtering ----

    function getVisibleContainer() {
        return document.getElementById(activeTab + viewSuffixes[activeView]);
    }

    function getAllContainersForTab(tab) {
        var ids = [tab + '-cards', tab + '-grid', tab + '-list'];
        var containers = [];
        for (var i = 0; i < ids.length; i++) {
            var el = document.getElementById(ids[i]);
            if (el) containers.push(el);
        }
        return containers;
    }

    function applyFilters() {
        var search   = searchInput.value.toLowerCase().trim();
        var fromDate = dateFrom.value;
        var toDate   = dateTo.value;
        var location = locationSelect.value.toLowerCase();
        var time     = timeSelect.value;

        // Get section for active tab
        var section = document.getElementById(activeTab + '-section');
        if (!section) return;

        // Get all event items across all 3 views in this section
        var allItems = section.querySelectorAll('.event-item');
        var visible = 0;
        var seenNames = {}; // track unique events to count correctly

        for (var i = 0; i < allItems.length; i++) {
            var item = allItems[i];
            var show = true;

            var name = item.getAttribute('data-name') || '';
            var desc = item.getAttribute('data-desc') || '';

            // Search by name, description, or location
            if (search) {
                var loc = item.getAttribute('data-location') || '';
                if (name.indexOf(search) === -1 && desc.indexOf(search) === -1 && loc.indexOf(search) === -1) {
                    show = false;
                }
            }

            // Date range
            if (show && fromDate && item.getAttribute('data-start-date') < fromDate) {
                show = false;
            }
            if (show && toDate && item.getAttribute('data-start-date') > toDate) {
                show = false;
            }

            // Location
            if (show && location && item.getAttribute('data-location') !== location) {
                show = false;
            }

            // Event type (from category dropdown)
            if (show && activeTypeFilter && item.getAttribute('data-type') !== activeTypeFilter) {
                show = false;
            }

            // Past events filter
            if (show) {
                var today = new Date().toISOString().split('T')[0];
                var eventDate = item.getAttribute('data-start-date') || '';
                if (activePastFilter) {
                    // Show only past events
                    if (eventDate >= today) {
                        show = false;
                    }
                } else {
                    // Hide past events by default
                    if (eventDate < today) {
                        show = false;
                    }
                }
            }

            // Time of day
            if (show && time) {
                var hour = parseInt((item.getAttribute('data-start-time') || '00:00').split(':')[0], 10);
                if (time === 'morning'   && hour >= 12)                show = false;
                if (time === 'afternoon' && (hour < 12 || hour >= 17)) show = false;
                if (time === 'evening'   && hour < 17)                 show = false;
            }

            item.setAttribute('data-filtered', show ? 'visible' : 'hidden');

            // Count unique visible events (items repeat across card/grid/list)
            if (show) {
                var key = name + '|' + item.getAttribute('data-start-date');
                if (!seenNames[key]) {
                    seenNames[key] = true;
                    visible++;
                }
            }
        }

        currentPage = 1;
        applyPagination(visible);

        // No-results messages
        var tabIds = ['upcoming', 'board', 'archived'];
        for (var n = 0; n < tabIds.length; n++) {
            var noMsg = document.getElementById('no-' + tabIds[n]);
            if (noMsg) {
                if (tabIds[n] === activeTab) {
                    var hasItems = section.querySelectorAll('.event-item').length > 0;
                    noMsg.classList.toggle('hidden', !(hasItems && visible === 0));
                } else {
                    noMsg.classList.add('hidden');
                }
            }
        }
    }

    // ---- Sorting ----

    function applySort() {
        var sortVal = sortSelect.value;
        var containers = getAllContainersForTab(activeTab);
        for (var i = 0; i < containers.length; i++) {
            sortContainer(containers[i], '.event-item', sortVal);
        }
    }

    function sortContainer(container, selector, sortVal) {
        var items = [];
        var nodeList = container.querySelectorAll(selector);
        for (var i = 0; i < nodeList.length; i++) {
            items.push(nodeList[i]);
        }

        items.sort(function (a, b) {
            switch (sortVal) {
                case 'date-asc':
                    return (a.getAttribute('data-start-date') + a.getAttribute('data-start-time'))
                        .localeCompare(b.getAttribute('data-start-date') + b.getAttribute('data-start-time'));
                case 'date-desc':
                    return (b.getAttribute('data-start-date') + b.getAttribute('data-start-time'))
                        .localeCompare(a.getAttribute('data-start-date') + a.getAttribute('data-start-time'));
                case 'slots-desc':
                    return parseInt(b.getAttribute('data-slots-remaining'), 10)
                         - parseInt(a.getAttribute('data-slots-remaining'), 10);
                case 'slots-asc':
                    return parseInt(a.getAttribute('data-slots-remaining'), 10)
                         - parseInt(b.getAttribute('data-slots-remaining'), 10);
                case 'name-asc':
                    return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
                case 'name-desc':
                    return (b.getAttribute('data-name') || '').localeCompare(a.getAttribute('data-name') || '');
                default:
                    return 0;
            }
        });

        for (var j = 0; j < items.length; j++) {
            container.appendChild(items[j]);
        }
    }

    // ---- Pagination ----

    function applyPagination(visibleCount) {
        var totalPages = Math.max(1, Math.ceil(visibleCount / itemsPerPage));
        if (currentPage > totalPages) currentPage = totalPages;

        // Paginate the visible container only
        var container = getVisibleContainer();
        if (container) {
            paginateContainer(container);
        }

        // Update count text
        var start = visibleCount > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
        var end   = Math.min(currentPage * itemsPerPage, visibleCount);
        if (pageInfo) {
            pageInfo.textContent = visibleCount === 0
                ? ''
                : 'Showing ' + start + ' to ' + end + ' of ' + visibleCount + ' events';
        }
        if (resultsCount) {
            resultsCount.textContent = visibleCount === 0
                ? 'No events to show'
                : 'Showing ' + visibleCount + ' events';
        }

        renderPagination(paginationEl, totalPages, visibleCount);
    }

    function paginateContainer(container) {
        var items = container.querySelectorAll('.event-item');
        var visIndex = 0;
        var startIdx = (currentPage - 1) * itemsPerPage;
        var endIdx   = startIdx + itemsPerPage;

        for (var i = 0; i < items.length; i++) {
            if (items[i].getAttribute('data-filtered') === 'hidden') {
                items[i].style.display = 'none';
            } else {
                if (visIndex >= startIdx && visIndex < endIdx) {
                    items[i].style.display = '';
                } else {
                    items[i].style.display = 'none';
                }
                visIndex++;
            }
        }
    }

    function renderPagination(target, totalPages, visibleCount) {
        if (!target) return;
        target.innerHTML = '';
        if (totalPages <= 1) return;

        // « Prev
        var prev = document.createElement('button');
        prev.innerHTML = '&laquo;';
        prev.disabled = currentPage <= 1;
        prev.addEventListener('click', function () {
            if (currentPage > 1) { currentPage--; applyPagination(visibleCount); scrollToResults(); }
        });
        target.appendChild(prev);

        // Page numbers
        var pages = buildPageNumbers(currentPage, totalPages);
        for (var p = 0; p < pages.length; p++) {
            if (pages[p] === '...') {
                var dots = document.createElement('span');
                dots.className = 'page-ellipsis';
                dots.textContent = '...';
                target.appendChild(dots);
            } else {
                (function (pageNum) {
                    var btn = document.createElement('button');
                    btn.textContent = pageNum;
                    if (pageNum === currentPage) btn.className = 'active';
                    btn.addEventListener('click', function () {
                        currentPage = pageNum;
                        applyPagination(visibleCount);
                        scrollToResults();
                    });
                    target.appendChild(btn);
                })(pages[p]);
            }
        }

        // Next »
        var next = document.createElement('button');
        next.innerHTML = '&raquo;';
        next.disabled = currentPage >= totalPages;
        next.addEventListener('click', function () {
            if (currentPage < totalPages) { currentPage++; applyPagination(visibleCount); scrollToResults(); }
        });
        target.appendChild(next);
    }

    function buildPageNumbers(current, total) {
        if (total <= 7) {
            var arr = [];
            for (var i = 1; i <= total; i++) arr.push(i);
            return arr;
        }
        var pages = [1];
        if (current > 3) pages.push('...');
        var start = Math.max(2, current - 1);
        var end   = Math.min(total - 1, current + 1);
        for (var j = start; j <= end; j++) pages.push(j);
        if (current < total - 2) pages.push('...');
        pages.push(total);
        return pages;
    }

    function scrollToResults() {
        var el = document.querySelector('.toolbar');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // ---- View Toggle (Card / Grid / List) ----

    function setView(view) {
        activeView = view;

        // Update toggle buttons
        for (var v = 0; v < viewBtns.length; v++) {
            viewBtns[v].classList.toggle('active', viewBtns[v].getAttribute('data-view') === view);
        }

        // Show/hide containers for all sections
        var sectionIds = ['upcoming', 'board', 'archived'];
        for (var s = 0; s < sectionIds.length; s++) {
            var cards = document.getElementById(sectionIds[s] + '-cards');
            var grid  = document.getElementById(sectionIds[s] + '-grid');
            var list  = document.getElementById(sectionIds[s] + '-list');
            if (cards) cards.classList.toggle('hidden', view !== 'card');
            if (grid)  grid.classList.toggle('hidden', view !== 'grid');
            if (list)  list.classList.toggle('hidden', view !== 'list');
        }

        currentPage = 1;
        applySort();
        applyFilters();

        try { sessionStorage.setItem('viewAllEvents_view', view); } catch (e) {}
    }

    // Restore saved view preference
    try {
        var saved = sessionStorage.getItem('viewAllEvents_view');
        if (saved === 'card' || saved === 'grid' || saved === 'list') activeView = saved;
    } catch (e) {}

    // ---- Tab / Section Switching ----

    var activePastFilter = false;

    function switchTab(tab) {
        activeTab = (tab === 'normal' || tab === 'past') ? 'upcoming' : tab;
        activeTypeFilter = (tab === 'normal') ? 'Normal' : '';
        activePastFilter = (tab === 'past');

        var sectionIds = ['upcoming', 'board', 'archived'];
        for (var s = 0; s < sectionIds.length; s++) {
            var sec = document.getElementById(sectionIds[s] + '-section');
            if (sec) {
                sec.classList.toggle('hidden', sectionIds[s] !== activeTab);
            }
        }

        currentPage = 1;
        applySort();
        applyFilters();
    }

    // ---- More Filters Toggle ----

    if (moreFiltersBtn && extraFilters) {
        moreFiltersBtn.addEventListener('click', function () {
            extraFilters.classList.toggle('hidden');
        });
    }

    // ---- Event Listeners ----

    // Filter inputs
    var filterControls = [searchInput, dateFrom, dateTo, locationSelect, timeSelect];
    for (var k = 0; k < filterControls.length; k++) {
        if (filterControls[k]) {
            (function (el) {
                el.addEventListener('input',  function () { applyFilters(); });
                el.addEventListener('change', function () { applyFilters(); });
            })(filterControls[k]);
        }
    }

    // Tab select
    if (tabSelect) {
        tabSelect.addEventListener('change', function () {
            switchTab(tabSelect.value);
        });
    }

    // Sort change
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            applySort();
            applyFilters();
        });
    }

    // Clear all filters
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            searchInput.value    = '';
            dateFrom.value       = '';
            dateTo.value         = '';
            locationSelect.value = '';
            timeSelect.value     = '';
            sortSelect.value     = 'date-asc';
            if (tabSelect) tabSelect.value = 'upcoming';
            currentPage = 1;
            switchTab('upcoming');
        });
    }

    // View toggle buttons
    for (var v = 0; v < viewBtns.length; v++) {
        (function (btn) {
            btn.addEventListener('click', function () {
                setView(btn.getAttribute('data-view'));
            });
        })(viewBtns[v]);
    }

    // ---- Bulk Signup ----

    var bulkBar = document.getElementById('bulk-bar');
    var bulkCount = document.getElementById('bulk-count');
    var bulkSignupBtn = document.getElementById('bulk-signup-btn');
    var bulkClearBtn = document.getElementById('bulk-clear-btn');
    var selectedEventIds = {};

    function updateBulkBar() {
        var count = Object.keys(selectedEventIds).length;
        if (!bulkBar) return;
        if (count > 0) {
            bulkBar.classList.remove('hidden');
            bulkCount.textContent = count + ' event' + (count > 1 ? 's' : '') + ' selected';
        } else {
            bulkBar.classList.add('hidden');
        }
    }

    function syncCheckboxes() {
        var allCbs = document.querySelectorAll('.bulk-signup-cb');
        for (var i = 0; i < allCbs.length; i++) {
            var eid = allCbs[i].getAttribute('data-event-id');
            allCbs[i].checked = !!selectedEventIds[eid];
        }
    }

    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('bulk-signup-cb')) return;
        var eid = e.target.getAttribute('data-event-id');
        if (e.target.checked) {
            selectedEventIds[eid] = true;
        } else {
            delete selectedEventIds[eid];
        }
        syncCheckboxes();
        updateBulkBar();
    });

    if (bulkClearBtn) {
        bulkClearBtn.addEventListener('click', function () {
            selectedEventIds = {};
            syncCheckboxes();
            updateBulkBar();
        });
    }

    if (bulkSignupBtn) {
        bulkSignupBtn.addEventListener('click', function () {
            var ids = Object.keys(selectedEventIds).map(function (id) { return parseInt(id, 10); });
            if (ids.length === 0) return;

            bulkSignupBtn.disabled = true;
            bulkSignupBtn.textContent = 'Signing up...';

            fetch('bulkSignUp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({ event_ids: ids })
            })
            .then(function (res) {
                return res.text().then(function (text) {
                    try { return JSON.parse(text); }
                    catch (e) { throw new Error(text.substring(0, 200)); }
                });
            })
            .then(function (data) {
                if (data.success && data.success.length > 0) {
                    window.location.reload();
                } else {
                    alert(data.error || data.message || 'Signup failed. Please try again.');
                    bulkSignupBtn.disabled = false;
                    bulkSignupBtn.textContent = 'Sign Up All';
                }
            })
            .catch(function (err) {
                alert('Error: ' + (err.message || err));
                bulkSignupBtn.disabled = false;
                bulkSignupBtn.textContent = 'Sign Up All';
            });
        });
    }

    // ---- Initial run ----
    setView(activeView);
});
