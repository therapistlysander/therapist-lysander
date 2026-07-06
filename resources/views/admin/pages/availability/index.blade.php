@extends('admin.layouts.admin')

@section('title', 'Booking Availability')
@section('page_title', 'Booking Availability')

@section('page_styles')
<style>
  .avail-layout { display: flex; flex-direction: column; gap: 24px; }
  .avail-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
  @media (max-width: 1080px) { .avail-row { grid-template-columns: 1fr; } }

  .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; }
  .card__header { padding: 20px 24px; border-bottom: 1px solid #f3f4f6; }
  .card__header h2 { font-size: 15px; font-weight: 600; margin: 0; color: #1a2332; }
  .card__header p { font-size: 12px; color: #9ca3af; margin: 3px 0 0; }
  .card__body { padding: 24px; }

  .config-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; }
  .config-field label { display: block; font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em; }
  .config-field select,
  .config-field input[type="time"] { width: 100%; padding: 9px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; color: #1a2332; background: white; }
  .config-field select:focus,
  .config-field input[type="time"]:focus { border-color: #5a9e97; outline: none; box-shadow: 0 0 0 3px rgba(90,158,151,0.1); }

  /* Form Dropdown (modern styled select for forms) */
  .form-dropdown { position: relative; }
  .form-dropdown__trigger {
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
    width: 100%; padding: 9px 12px;
    background: white; border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 14px; color: #1a2332; cursor: pointer;
    transition: border-color 0.15s, box-shadow 0.15s;
  }
  .form-dropdown__trigger:hover { border-color: #5a9e97; }
  .form-dropdown__trigger:focus { outline: none; border-color: #5a9e97; box-shadow: 0 0 0 3px rgba(90,158,151,0.1); }
  .form-dropdown__trigger svg { width: 14px; height: 14px; color: #9ca3af; flex-shrink: 0; }
  .form-dropdown__menu {
    display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: white; border: 1px solid #e5e7eb;
    border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    z-index: 100; overflow: hidden; padding: 4px; max-height: 240px; overflow-y: auto;
  }
  .form-dropdown__menu.open { display: block; }
  .form-dropdown__item {
    display: block; width: 100%;
    padding: 8px 12px; font-size: 14px; color: #1a2332;
    border: none; background: none; cursor: pointer; border-radius: 6px;
    transition: background 0.1s; text-align: left;
  }
  .form-dropdown__item:hover { background: #f3f4f6; }
  .form-dropdown__item.active { background: #f0fdf9; color: #5a9e97; font-weight: 600; }

  .slot-preview { margin-top: 20px; padding-top: 20px; border-top: 1px solid #f3f4f6; }
  .slot-preview__label { font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.04em; }
  .slot-chips { display: flex; flex-wrap: wrap; gap: 6px; }
  .slot-chip { padding: 5px 10px; background: #f0fdf9; border: 1px solid #d1fae5; border-radius: 6px; font-size: 12px; font-weight: 500; color: #065f46; font-family: 'SF Mono', 'Fira Code', monospace; }
  .slot-count { font-size: 12px; color: #9ca3af; margin-top: 10px; }

  .day-list { display: flex; flex-direction: column; }
  .day-item { display: flex; align-items: center; gap: 14px; padding: 14px 0; border-bottom: 1px solid #f3f4f6; }
  .day-item:last-child { border-bottom: none; }
  .day-toggle { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
  .day-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
  .day-toggle__track { position: absolute; inset: 0; background: #e5e7eb; border-radius: 11px; transition: background 0.2s; cursor: pointer; }
  .day-toggle input:checked + .day-toggle__track { background: #5a9e97; }
  .day-toggle__thumb { position: absolute; top: 2px; left: 2px; width: 18px; height: 18px; background: white; border-radius: 50%; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
  .day-toggle input:checked ~ .day-toggle__thumb { transform: translateX(18px); }
  .day-info { flex: 1; min-width: 0; }
  .day-name { font-size: 14px; font-weight: 500; color: #1a2332; }
  .day-name.inactive { color: #9ca3af; }
  .day-override { display: flex; align-items: center; gap: 6px; margin-top: 4px; flex-wrap: wrap; }
  .day-override input[type="time"] { padding: 4px 8px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 12px; color: #374151; width: 100px; }
  .day-override input[type="time"]:focus { border-color: #5a9e97; outline: none; }
  .day-override span { font-size: 11px; color: #9ca3af; }
  .day-status { font-size: 11px; padding: 3px 8px; border-radius: 999px; font-weight: 500; flex-shrink: 0; }
  .day-status--on { background: #d1fae5; color: #065f46; }
  .day-status--off { background: #f3f4f6; color: #9ca3af; }

  .blocked-list { display: flex; flex-direction: column; }
  .blocked-item { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
  .blocked-item:last-child { border-bottom: none; }
  .blocked-date { font-size: 13px; font-weight: 600; color: #1a2332; min-width: 130px; }
  .blocked-type { font-size: 11px; padding: 2px 8px; border-radius: 4px; font-weight: 500; }
  .blocked-type--full { background: #fee2e2; color: #dc2626; }
  .blocked-type--partial { background: #fef3c7; color: #92400e; }
  .blocked-reason { font-size: 12px; color: #6b7280; flex: 1; }
  .blocked-slots { font-size: 11px; color: #374151; font-family: monospace; }

  .add-block { padding-top: 16px; border-top: 1px solid #e5e7eb; margin-top: 16px; }
  .add-block__row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; }
  @media (max-width: 600px) { .add-block__row { grid-template-columns: 1fr; } }
  .add-block__field label { display: block; font-size: 11px; font-weight: 500; color: #6b7280; margin-bottom: 4px; }
  .add-block__field input,
  .add-block__field select { width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px; }
  .add-block__field input:focus,
  .add-block__field select:focus { border-color: #5a9e97; outline: none; }
  .slots-field { display: none; }
  .slots-field.visible { display: block; }

  .btn-save { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: #5a9e97; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.15s; }
  .btn-save:hover { background: #4a8880; }
  .btn-add { display: inline-flex; align-items: center; gap: 5px; padding: 8px 14px; background: #1a2332; color: white; border: none; border-radius: 8px; font-size: 12px; font-weight: 500; cursor: pointer; }
  .btn-add:hover { background: #2d3a4d; }
  .btn-remove { background: none; border: none; color: #dc2626; cursor: pointer; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; }
  .btn-remove:hover { background: #fee2e2; }
  .empty-state { text-align: center; padding: 32px 16px; color: #9ca3af; font-size: 13px; }

  /* Responsive */
  @media (max-width: 768px) {
    .avail-row { grid-template-columns: 1fr; }
    .config-grid { grid-template-columns: 1fr 1fr; }
    .day-item { flex-wrap: wrap; gap: 8px; }
    .day-info { width: calc(100% - 60px); }
    .day-status { margin-left: auto; }
    .day-override { width: 100%; }
    .day-override input[type="time"] { flex: 1; min-width: 80px; }
    .btn-save { width: 100%; justify-content: center; }
    .add-block__row { grid-template-columns: 1fr; }
    .card__body { padding: 16px; }
  }
  @media (max-width: 480px) {
    .config-grid { grid-template-columns: 1fr; }
  }
</style>
@endsection

@section('content')
<div class="admin-page-header">
  <h1>Booking Availability</h1>
</div>

<div class="avail-layout">

  <!-- Schedule Settings -->
  <div class="card">
    <div class="card__header">
      <h2>Schedule Settings</h2>
      <p>Set your session duration and working hours. Time slots are generated automatically.</p>
    </div>
    <div class="card__body">
      <form method="POST" action="{{ route('admin.availability.config') }}">
        @csrf
        @method('PATCH')

        <div class="config-grid">
          <div class="config-field">
            <label>Session Duration</label>
            <div class="form-dropdown" id="duration-dropdown">
              <button type="button" class="form-dropdown__trigger" onclick="toggleFormDropdown('duration-dropdown')">
                <span id="duration-label">{{ collect([15 => '15 minutes', 20 => '20 minutes', 30 => '30 minutes', 45 => '45 minutes', 50 => '50 minutes', 60 => '1 hour', 90 => '1.5 hours', 120 => '2 hours'])->first(fn($v, $k) => $k == $config->slot_duration) ?? '30 minutes' }}</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="form-dropdown__menu">
                @foreach([15 => '15 minutes', 20 => '20 minutes', 30 => '30 minutes', 45 => '45 minutes', 50 => '50 minutes', 60 => '1 hour', 90 => '1.5 hours', 120 => '2 hours'] as $val => $label)
                <button type="button" class="form-dropdown__item {{ $config->slot_duration == $val ? 'active' : '' }}" onclick="selectFormDropdown('duration-dropdown', '{{ $val }}', '{{ $label }}')">
                  {{ $label }}
                </button>
                @endforeach
              </div>
              <input type="hidden" name="slot_duration" value="{{ $config->slot_duration }}">
            </div>
          </div>
          <div class="config-field">
            <label>Start Time</label>
            <input type="time" name="default_start_time" value="{{ $config->default_start_time }}">
          </div>
          <div class="config-field">
            <label>End Time</label>
            <input type="time" name="default_end_time" value="{{ $config->default_end_time }}">
          </div>
          <div class="config-field">
            <label>Break Start</label>
            <input type="time" name="break_start" id="break_start" value="{{ $config->break_start }}" {{ !$config->break_start ? 'disabled' : '' }}>
          </div>
          <div class="config-field">
            <label>Break End</label>
            <input type="time" name="break_end" id="break_end" value="{{ $config->break_end }}" {{ !$config->break_end ? 'disabled' : '' }}>
          </div>
          <div class="config-field" style="display: flex; align-items: flex-end; padding-bottom: 4px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; text-transform: none; font-size: 13px; color: #374151; letter-spacing: 0;">
              <input type="checkbox" id="enable-break" {{ $config->break_start ? 'checked' : '' }} onchange="toggleBreak(this)" style="width: 16px; height: 16px; accent-color: #5a9e97;">
              Enable break
            </label>
          </div>
        </div>

        <div class="slot-preview">
          <div class="slot-preview__label">Generated time slots (preview)</div>
          <div class="slot-chips">
            @foreach($previewSlots as $slot)
              <span class="slot-chip">{{ $slot }}</span>
            @endforeach
          </div>
          <p class="slot-count">{{ count($previewSlots) }} slots per working day</p>
        </div>

        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
          <button type="submit" class="btn-save">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            Save Settings
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Working Days + Blocked Dates -->
  <div class="avail-row">

    <div class="card">
      <div class="card__header">
        <h2>Working Days</h2>
        <p>Toggle days on/off. Times are auto-filled from your schedule settings above.</p>
      </div>
      <div class="card__body">
        <form method="POST" action="{{ route('admin.availability.schedule') }}">
          @csrf
          @method('PATCH')

          <div class="day-list">
            @php $dayNames = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']; @endphp
            @foreach($dayNames as $i => $dayName)
              @php
                $day = $schedule[$i] ?? null;
                $active = $day ? $day->is_active : ($i <= 4);
                $startVal = $day->start_time ?? $config->default_start_time;
                $endVal = $day->end_time ?? $config->default_end_time;
              @endphp
              <div class="day-item">
                <label class="day-toggle">
                  <input type="checkbox" name="days[{{ $i }}][is_active]" value="1" {{ $active ? 'checked' : '' }}
                         onchange="toggleDayUI(this, {{ $i }})">
                  <span class="day-toggle__track"></span>
                  <span class="day-toggle__thumb"></span>
                </label>
                <div class="day-info">
                  <div class="day-name {{ $active ? '' : 'inactive' }}" id="dayname-{{ $i }}">{{ $dayName }}</div>
                  <div class="day-override" id="dayoverride-{{ $i }}" style="{{ $active ? '' : 'opacity:0.4;pointer-events:none;' }}">
                    <input type="time" name="days[{{ $i }}][start_time]" value="{{ $startVal }}">
                    <span>to</span>
                    <input type="time" name="days[{{ $i }}][end_time]" value="{{ $endVal }}">
                  </div>
                </div>
                <span class="day-status {{ $active ? 'day-status--on' : 'day-status--off' }}" id="daystatus-{{ $i }}">
                  {{ $active ? 'Active' : 'Off' }}
                </span>
              </div>
            @endforeach
          </div>

          <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn-save">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
              Save Days
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card__header">
        <h2>Blocked Dates</h2>
        <p>Block entire days or specific slots (holidays, leave, etc.)</p>
      </div>
      <div class="card__body">
        @if($blockedDates->count())
          <div class="blocked-list">
            @foreach($blockedDates as $blocked)
              <div class="blocked-item">
                <span class="blocked-date">{{ $blocked->blocked_date->format('D, d M Y') }}</span>
                @if($blocked->blocked_slots)
                  <span class="blocked-type blocked-type--partial">Partial</span>
                  <span class="blocked-slots">{{ implode(', ', $blocked->blocked_slots) }}</span>
                @else
                  <span class="blocked-type blocked-type--full">Full day</span>
                @endif
                <span class="blocked-reason">{{ $blocked->reason ?? '' }}</span>
                <form method="POST" action="{{ route('admin.availability.blocked.destroy', $blocked) }}" style="margin:0;" onsubmit="return confirm('Remove this blocked date?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-remove">Remove</button>
                </form>
              </div>
            @endforeach
          </div>
        @else
          <div class="empty-state">No blocked dates. Your full schedule is available.</div>
        @endif

        <div class="add-block">
          <form method="POST" action="{{ route('admin.availability.blocked.store') }}">
            @csrf
            <div class="add-block__row">
              <div class="add-block__field">
                <label>Date to block</label>
                <input type="date" name="blocked_date" required min="{{ date('Y-m-d') }}">
              </div>
              <div class="add-block__field">
                <label>Block type</label>
                <div class="form-dropdown" id="block-type-dropdown">
                  <button type="button" class="form-dropdown__trigger" onclick="toggleFormDropdown('block-type-dropdown')">
                    <span id="block-type-label">Entire day</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                  </button>
                  <div class="form-dropdown__menu">
                    <button type="button" class="form-dropdown__item active" onclick="selectFormDropdownWithCallback('block-type-dropdown', 'full_day', 'Entire day', function() { document.getElementById('slots-field').classList.remove('visible'); })">
                      Entire day
                    </button>
                    <button type="button" class="form-dropdown__item" onclick="selectFormDropdownWithCallback('block-type-dropdown', 'specific_slots', 'Specific time slots only', function() { document.getElementById('slots-field').classList.add('visible'); })">
                      Specific time slots only
                    </button>
                  </div>
                  <input type="hidden" name="block_type" value="full_day">
                </div>
              </div>
            </div>
            <div class="add-block__row">
              <div class="add-block__field slots-field" id="slots-field">
                <label>Slots to block (comma-separated)</label>
                <input type="text" name="blocked_slots" placeholder="e.g. 09:00, 09:30, 10:00">
              </div>
              <div class="add-block__field">
                <label>Reason (optional)</label>
                <input type="text" name="reason" placeholder="e.g. Public holiday, Annual leave">
              </div>
            </div>
            <button type="submit" class="btn-add">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
              Add Blocked Date
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('page_scripts')
<script>
function toggleBreak(checkbox) {
  const startInput = document.getElementById('break_start');
  const endInput = document.getElementById('break_end');
  if (checkbox.checked) {
    startInput.disabled = false;
    endInput.disabled = false;
    if (!startInput.value) startInput.value = '12:00';
    if (!endInput.value) endInput.value = '13:30';
  } else {
    startInput.disabled = true;
    endInput.disabled = true;
    startInput.value = '';
    endInput.value = '';
  }
}

function toggleDayUI(checkbox, idx) {
  const name = document.getElementById('dayname-' + idx);
  const override = document.getElementById('dayoverride-' + idx);
  const status = document.getElementById('daystatus-' + idx);
  if (checkbox.checked) {
    name.classList.remove('inactive');
    override.style.opacity = '1';
    override.style.pointerEvents = 'auto';
    status.className = 'day-status day-status--on';
    status.textContent = 'Active';
  } else {
    name.classList.add('inactive');
    override.style.opacity = '0.4';
    override.style.pointerEvents = 'none';
    status.className = 'day-status day-status--off';
    status.textContent = 'Off';
  }
}

function toggleSlotsField(select) {
  const field = document.getElementById('slots-field');
  if (select.value === 'specific_slots') {
    field.classList.add('visible');
  } else {
    field.classList.remove('visible');
  }
}

// Form Dropdown Functions (for form fields - no auto-submit)
function toggleFormDropdown(id) {
  const dropdown = document.getElementById(id);
  const menu = dropdown.querySelector('.form-dropdown__menu');
  const isOpen = menu.classList.contains('open');

  // Close all dropdowns
  document.querySelectorAll('.form-dropdown__menu').forEach(m => m.classList.remove('open'));

  // Toggle current
  if (!isOpen) {
    menu.classList.add('open');
  }
}

function selectFormDropdown(id, value, label) {
  const dropdown = document.getElementById(id);
  const input = dropdown.querySelector('input[type="hidden"]');
  const labelEl = dropdown.querySelector('[id$="-label"]');

  input.value = value;
  labelEl.textContent = label;

  // Update active state
  dropdown.querySelectorAll('.form-dropdown__item').forEach(item => {
    item.classList.remove('active');
  });
  event.target.closest('.form-dropdown__item').classList.add('active');

  // Close dropdown
  dropdown.querySelector('.form-dropdown__menu').classList.remove('open');
}

function selectFormDropdownWithCallback(id, value, label, callback) {
  selectFormDropdown(id, value, label);
  if (callback) callback();
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
  if (!e.target.closest('.form-dropdown')) {
    document.querySelectorAll('.form-dropdown__menu').forEach(m => m.classList.remove('open'));
  }
});
</script>
@endsection
