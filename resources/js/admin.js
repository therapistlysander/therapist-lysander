// Admin panel JavaScript — compiled by Vite

// Auto-dismiss alert messages after 4 seconds
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.admin-alert').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });
});

// ─── Table Dropdown Filters ───
function toggleTableDropdown(id) {
    const dropdown = document.getElementById(id);
    const menu = dropdown.querySelector('.table-dropdown__menu');
    const isOpen = menu.classList.contains('open');

    document.querySelectorAll('.table-dropdown__menu').forEach(m => m.classList.remove('open'));

    if (!isOpen) {
        menu.classList.add('open');
    }
}

function selectTableDropdown(id, value, label) {
    const dropdown = document.getElementById(id);
    const input = dropdown.querySelector('input[type="hidden"]');
    const labelEl = dropdown.querySelector('[id$="-label"]');

    input.value = value;
    labelEl.textContent = label;

    dropdown.querySelectorAll('.table-dropdown__item').forEach(item => {
        item.classList.remove('active');
    });
    const clickedItem = event.target.closest('.table-dropdown__item');
    if (clickedItem) clickedItem.classList.add('active');

    dropdown.querySelector('.table-dropdown__menu').classList.remove('open');

    // Submit the filter form
    const form = document.getElementById('table-filter-form');
    if (form) form.submit();
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.table-dropdown')) {
        document.querySelectorAll('.table-dropdown__menu').forEach(m => m.classList.remove('open'));
    }
});

// ─── Table Bulk Selection ───
function toggleAllTableChecks(master) {
    document.querySelectorAll('.row-check').forEach(cb => {
        cb.checked = master.checked;
        const row = cb.closest('tr');
        if (row) row.classList.toggle('selected', master.checked);
    });
    updateTableBulkBar();
}

function updateTableBulkBar() {
    const checked = document.querySelectorAll('.row-check:checked');
    const bar = document.getElementById('bulk-bar');
    const count = document.getElementById('bulk-count');
    if (!bar || !count) return;

    if (checked.length > 0) {
        bar.classList.add('visible');
        count.textContent = checked.length + ' selected';
    } else {
        bar.classList.remove('visible');
    }
    document.querySelectorAll('.row-check').forEach(cb => {
        const row = cb.closest('tr');
        if (row) row.classList.toggle('selected', cb.checked);
    });

    // Update check-all state
    const allChecks = document.querySelectorAll('.row-check');
    const checkedAll = document.querySelectorAll('.row-check:checked');
    const checkAll = document.querySelector('.table-check-all');
    if (checkAll) {
        checkAll.checked = allChecks.length > 0 && allChecks.length === checkedAll.length;
    }
}

function clearTableSelection() {
    document.querySelectorAll('.row-check').forEach(cb => {
        cb.checked = false;
        const row = cb.closest('tr');
        if (row) row.classList.remove('selected');
    });
    const checkAll = document.querySelector('.table-check-all');
    if (checkAll) checkAll.checked = false;
    const bar = document.getElementById('bulk-bar');
    if (bar) bar.classList.remove('visible');
}

function confirmBulkAction(label, url, action) {
    const checked = document.querySelectorAll('.row-check:checked');
    if (checked.length === 0) return;

    const msg = action === 'delete'
        ? 'Are you sure you want to delete ' + checked.length + ' item(s)? This cannot be undone.'
        : 'Apply "' + label + '" to ' + checked.length + ' item(s)?';

    if (typeof showConfirmModal === 'function') {
        showConfirmModal(label, msg, function() {
            submitBulkForm(url, checked, action);
        });
    } else {
        if (confirm(msg)) {
            submitBulkForm(url, checked, action);
        }
    }
}

function submitBulkForm(url, checked, action) {
    const form = document.getElementById('bulk-form');
    if (!form) return;

    form.action = url;
    form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
    form.querySelectorAll('input[name="action"]').forEach(el => el.remove());

    checked.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = cb.value;
        form.appendChild(input);
    });

    if (action && action !== 'delete') {
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
    }

    form.submit();
}

// ─── Search Clear ──
function clearSearch() {
    const input = document.querySelector('.table-search-input__field');
    if (input) {
        input.value = '';
        const form = document.getElementById('table-filter-form');
        if (form) form.submit();
    }
}

// ─── Debounced Search (300ms) ───
(function() {
    let searchTimeout;
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.table-search-input__field');
        if (!searchInput) return;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                const form = document.getElementById('table-filter-form');
                if (form) form.submit();
            }, 300);
        });
    });
})();
// Admin panel JavaScript — compiled by Vite

// Auto-dismiss alert messages after 4 seconds
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.admin-alert').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });
});
