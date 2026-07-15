{{--
  Shared sortable table header cell partial.

  Expected variables:
    - $column (string) — the database column name
    - $label (string) — display label
    - $currentSort (string|null) — currently sorted column
    - $currentDir (string) — current direction ('asc' or 'desc')
--}}
@php
  $isActive = ($currentSort ?? '') === $column;
  $dir = $isActive ? $currentDir : 'asc';
  $nextDir = $isActive && $dir === 'asc' ? 'desc' : 'asc';
@endphp
<th class="table-sortable {{ $isActive ? 'table-sortable--active' : '' }}">
  <a href="{{ request()->fullUrlWithQuery(['sort' => $column, 'dir' => $nextDir, 'page' => null]) }}"
     class="table-sortable__link" style="text-decoration:none;color:inherit;display:inline-flex;align-items:center;gap:4px;">
    {{ $label }}
    @if($isActive)
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"
           style="transform:{{ $dir === 'desc' ? 'rotate(180deg)' : 'none' }};transition:transform 0.15s;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/>
      </svg>
    @else
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12" style="opacity:0.3;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/>
      </svg>
    @endif
  </a>
</th>
