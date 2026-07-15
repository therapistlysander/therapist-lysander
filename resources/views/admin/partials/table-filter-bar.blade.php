{{--
  Shared table filter bar partial.

  Expected variables:
    - $searchPlaceholder (string, optional) — placeholder text for search input
    - $searchColumns (array, optional) — not used here, for reference
    - $filters (array, optional) — array of filter configs:
        [
          'id' => 'status',
          'label' => 'Status',
          'options' => [
            ['value' => '', 'label' => 'All'],
            ['value' => 'active', 'label' => 'Active'],
            ...
          ],
          'colors' => ['active' => '#10b981', ...]  // optional dot colors
        ]
    - $resetUrl (string) — URL to reset all filters
    - $hasFilters (bool) — whether any filters are currently active
    - $total (int) — total record count
    - $perPageOptions (array, optional) — [10, 25, 50, 100]
    - $currentPerPage (int) — current per_page value
--}}
@php
  $searchPlaceholder = $searchPlaceholder ?? 'Search...';
  $filters = $filters ?? [];
  $resetUrl = $resetUrl ?? request()->url();
  $hasFilters = $hasFilters ?? (request('search') || request('status') || request('category') || request('type') || request('is_active') !== null);
  $total = $total ?? 0;
  $perPageOptions = $perPageOptions ?? [10, 25, 50, 100];
  $currentPerPage = (int) (request('per_page', 10));
@endphp

<div class="table-toolbar">
  <form method="GET" class="table-toolbar__filters" id="table-filter-form">
    {{-- Preserve existing query params --}}
    @foreach(request()->except('search','status','category','type','is_active','is_featured','sort','dir','per_page','page') as $key => $value)
      @if(is_array($value))
        @foreach($value as $v)
          <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
        @endforeach
      @else
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
      @endif
    @endforeach

    {{-- Search --}}
    <div class="table-search-input">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" placeholder="{{ $searchPlaceholder }}" value="{{ request('search') }}" class="table-search-input__field" autocomplete="off">
      @if(request('search'))
        <button type="button" class="table-search-input__clear" onclick="clearSearch()" title="Clear search">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      @endif
    </div>

    {{-- Filter dropdowns --}}
    @foreach($filters as $filter)
      @php
        $filterId = $filter['id'];
        $filterValue = request($filterId, '');
      @endphp
      <div class="table-dropdown" id="dropdown-{{ $filterId }}">
        <button type="button" class="table-dropdown__trigger" onclick="toggleTableDropdown('dropdown-{{ $filterId }}')">
          @if(!empty($filter['icon']))
            {!! $filter['icon'] !!}
          @else
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
          @endif
          <span id="dropdown-{{ $filterId }}-label">
            @php
              $currentLabel = $filter['label'];
              foreach($filter['options'] as $opt) {
                if ((string)$opt['value'] === (string)$filterValue) {
                  $currentLabel = $opt['label'];
                  break;
                }
              }
            @endphp
            {{ $currentLabel }}
          </span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div class="table-dropdown__menu">
          @foreach($filter['options'] as $opt)
            <button type="button"
              class="table-dropdown__item {{ (string)$opt['value'] === (string)$filterValue ? 'active' : '' }}"
              onclick="selectTableDropdown('dropdown-{{ $filterId }}', '{{ $opt['value'] }}', '{{ addslashes($opt['label']) }}')">
              @if(!empty($filter['colors']) && isset($filter['colors'][$opt['value']]))
                <span style="width:8px;height:8px;border-radius:50%;background:{{ $filter['colors'][$opt['value']] }};flex-shrink:0;"></span>
              @endif
              {{ $opt['label'] }}
            </button>
          @endforeach
        </div>
        <input type="hidden" name="{{ $filterId }}" value="{{ $filterValue }}">
      </div>
    @endforeach

    {{-- Per page --}}
    <select name="per_page" class="table-per-page" onchange="document.getElementById('table-filter-form').submit()">
      @foreach($perPageOptions as $opt)
        <option value="{{ $opt }}" {{ $currentPerPage === $opt ? 'selected' : '' }}>{{ $opt }} per page</option>
      @endforeach
    </select>

    {{-- Submit / Reset --}}
    <button type="submit" class="btn-admin btn-admin--primary btn-admin--sm">Filter</button>
    @if($hasFilters)
      <a href="{{ $resetUrl }}" class="btn-admin btn-admin--outline btn-admin--sm">Reset</a>
    @endif
  </form>

  <span class="table-toolbar__count">{{ $total }} record{{ $total !== 1 ? 's' : '' }}</span>
</div>
