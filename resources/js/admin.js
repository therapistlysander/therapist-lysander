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

/* ─── Data Table: Dropdown Toggle ─── */
window.dtToggleDropdown = function(trigger) {
    const menu = trigger.nextElementSibling;
    const wasOpen = menu.classList.contains('open');
    // Close all open dropdowns
    document.querySelectorAll('.dt-dropdown__menu.open').forEach(m => m.classList.remove('open'));
    if (!wasOpen) menu.classList.add('open');
}

window.dtSelectFilter = function(name, value, btn) {
    const dropdown = btn.closest('.dt-dropdown');
    const hidden = dropdown.querySelector('input[type="hidden"]');
    const label = dropdown.querySelector('.dt-dropdown__trigger span');
    hidden.value = value;
    label.textContent = btn.textContent.trim();
    // Update active state
    dropdown.querySelectorAll('.dt-dropdown__item').forEach(i => i.classList.remove('active'));
    btn.classList.add('active');
    // Close and submit
    dropdown.querySelector('.dt-dropdown__menu').classList.remove('open');
    dropdown.closest('form').submit();
}

// Close dropdowns on outside click
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dt-dropdown')) {
        document.querySelectorAll('.dt-dropdown__menu.open').forEach(m => m.classList.remove('open'));
    }
});

/* ─── Data Table: Search Debounce ─── */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dt-search__input').forEach(input => {
        let timer;
        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => input.closest('form').submit(), 300);
        });
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') { clearTimeout(timer); input.closest('form').submit(); }
        });
    });
});

/* ─── Data Table: Bulk Select ─── */
function dtInitBulkSelect() {
    const checkAll = document.querySelector('.dt-check-all');
    const rowChecks = document.querySelectorAll('.dt-row-check');
    const bulkBar = document.getElementById('dt-bulk-bar');
    const countEl = document.getElementById('dt-bulk-count');

    if (!checkAll || !bulkBar) return;

    function updateBulkBar() {
        const checked = document.querySelectorAll('.dt-row-check:checked');
        countEl.textContent = checked.length;
        bulkBar.classList.toggle('visible', checked.length > 0);
        // Update row highlight
        rowChecks.forEach(cb => cb.closest('tr').classList.toggle('dt-selected', cb.checked));
        // Update check-all state
        checkAll.checked = checked.length === rowChecks.length && rowChecks.length > 0;
        checkAll.indeterminate = checked.length > 0 && checked.length < rowChecks.length;
    }

    checkAll.addEventListener('change', () => {
        rowChecks.forEach(cb => { cb.checked = checkAll.checked; });
        updateBulkBar();
    });

    rowChecks.forEach(cb => cb.addEventListener('change', updateBulkBar));
}

document.addEventListener('DOMContentLoaded', dtInitBulkSelect);

/* ─── Data Table: Bulk Action Submit ─── */
window.dtBulkAction = function(route, action, label) {
    const ids = Array.from(document.querySelectorAll('.dt-row-check:checked')).map(cb => cb.value);
    if (ids.length === 0) return;

    const msg = action === 'delete'
        ? `Are you sure you want to delete ${ids.length} item(s)? This cannot be undone.`
        : `${label} ${ids.length} item(s)?`;

    if (!confirm(msg)) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = route;
    let inputs = `<input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]')?.content || ''}">`;
    inputs += `<input type="hidden" name="action" value="${action}">`;
    ids.forEach(id => { inputs += `<input type="hidden" name="ids[]" value="${id}">`; });
    form.innerHTML = inputs;
    document.body.appendChild(form);
    form.submit();
}
