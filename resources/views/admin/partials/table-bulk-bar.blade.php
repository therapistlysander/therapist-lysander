{{--
  Shared table bulk action bar partial.

  Expected variables:
    - $bulkActionUrl (string) — URL for the bulk action form
    - $bulkActions (array, optional) — array of action configs:
        [
          ['label' => 'Delete Selected', 'action' => 'delete', 'class' => 'btn-admin--danger', 'icon' => '...'],
          ...
        ]
      Defaults to Delete if not provided.
--}}
@php
  $bulkActions = $bulkActions ?? [
    ['label' => 'Delete Selected', 'action' => 'delete', 'class' => 'btn-admin--danger'],
  ];
@endphp

<div class="table-bulk-bar" id="bulk-bar">
  <input type="checkbox" class="table-check-all" onchange="toggleAllTableChecks(this)" title="Select all on this page">
  <span class="table-bulk-bar__count" id="bulk-count">0 selected</span>
  <div class="table-bulk-bar__actions">
    @foreach($bulkActions as $action)
      <button type="button"
        class="btn-admin {{ $action['class'] ?? 'btn-admin--danger' }} btn-admin--sm"
        onclick="confirmBulkAction('{{ $action['label'] }}', '{{ $bulkActionUrl }}', '{{ $action['action'] ?? 'delete' }}')">
        @if(!empty($action['icon']))
          {!! $action['icon'] !!}
        @elseif(($action['action'] ?? '') === 'delete')
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
        @endif
        {{ $action['label'] }}
      </button>
    @endforeach
    <button type="button" class="btn-admin btn-admin--outline btn-admin--sm" onclick="clearTableSelection()">Cancel</button>
  </div>
</div>

{{-- Hidden bulk form --}}
<form id="bulk-form" method="POST" action="{{ $bulkActionUrl }}" style="display:none;">
  @csrf
</form>
