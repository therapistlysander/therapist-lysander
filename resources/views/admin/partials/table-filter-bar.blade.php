{{-- Shared admin table filter bar --}}
{{-- Required: $resetUrl (string) --}}
{{-- Optional: $searchPlaceholder, $statusOptions, $extraFilters, $hasFilters --}}
@php
  $searchPlaceholder = $searchPlaceholder ?? 'Search...';
  $statusOptions = $statusOptions ?? [];
  $extraFilters = $extraFilters ?? [];
  // Sanitize: ensure all request values used in the form are strings (not arrays)
  $reqSearch = is_string(request('search')) ? request('search') : '';
  $reqStatus = is_string(request('status')) ? request('status') : '';
  $hasActiveFilters = request()->hasAny(array_merge(['search', 'status'], array_keys($extraFilters))) && request()->except('page', 'per_page', 'sort', 'direction') !== [];
@endphp

<div class="admin-table-header">
  <form method="GET" action="{{ request()->url() }}" class="dt-filter-bar">

    {{-- Search --}}
    <div class="dt-search">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" placeholder="{{ $searchPlaceholder }}" value="{{ $reqSearch }}" class="dt-search__input" autocomplete="off">
      @if($reqSearch)
        <button type="button" class="dt-search__clear" onclick="this.previousElementSibling.value='';this.closest('form').submit();" title="Clear search">&times;</button>
      @endif
    </div>

    {{-- Status filter --}}
    @if(!empty($statusOptions))
    <div class="dt-dropdown" data-dropdown="status">
      <button type="button" class="dt-dropdown__trigger" onclick="dtToggleDropdown(this)">
        <span>{{ $reqStatus ? ($statusOptions[$reqStatus] ?? ucfirst($reqStatus)) : 'All Statuses' }}</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
      </button>
      <div class="dt-dropdown__menu">
        @foreach($statusOptions as $val => $label)
          <button type="button" class="dt-dropdown__item {{ $reqStatus === $val ? 'active' : '' }}"
                  onclick="dtSelectFilter('status', '{{ $val }}', this)">{{ $label }}</button>
        @endforeach
      </div>
      <input type="hidden" name="status" value="{{ $reqStatus }}">
    </div>
    @endif

    {{-- Extra filters (category, type, etc.) --}}
    @foreach($extraFilters as $key => $options)
    <div class="dt-dropdown" data-dropdown="{{ $key }}">
      @php $reqVal = is_string(request($key)) ? request($key) : ''; @endphp
      <button type="button" class="dt-dropdown__trigger" onclick="dtToggleDropdown(this)">
        <span>{{ $reqVal ? ($options[$reqVal] ?? ucfirst($reqVal)) : ($options[''] ?? 'All') }}</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
      </button>
      <div class="dt-dropdown__menu">
        @foreach($options as $val => $label)
          <button type="button" class="dt-dropdown__item {{ $reqVal === $val ? 'active' : '' }}"
                  onclick="dtSelectFilter('{{ $key }}', '{{ $val }}', this)">{{ $label }}</button>
        @endforeach
      </div>
      <input type="hidden" name="{{ $key }}" value="{{ $reqVal }}">
    </div>
    @endforeach

    {{-- Per-page --}}
    <select name="per_page" class="dt-per-page" onchange="this.form.submit()">
      @foreach([10, 25, 50, 100] as $pp)
        <option value="{{ $pp }}" {{ request('per_page', 10) == $pp ? 'selected' : '' }}>{{ $pp }} / page</option>
      @endforeach
    </select>

    {{-- Reset --}}
    @if($hasActiveFilters)
      <a href="{{ $resetUrl }}" class="btn-admin btn-admin--outline dt-reset-btn">Reset</a>
    @endif
  </form>
</div>
