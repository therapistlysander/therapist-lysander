{{-- Shared admin table bulk action bar --}}
{{-- Required: $bulkActionRoute (string), $bulkActions (array of action => label) --}}
@php
  $bulkActions = $bulkActions ?? [
    'activate'   => 'Activate',
    'deactivate' => 'Deactivate',
    'delete'     => 'Delete',
  ];
@endphp
<div class="dt-bulk-bar" id="dt-bulk-bar">
  <span class="dt-bulk-bar__count"><strong id="dt-bulk-count">0</strong> selected</span>
  <div class="dt-bulk-bar__actions">
    @foreach($bulkActions as $action => $label)
      <button type="button" class="btn-admin {{ $action === 'delete' ? 'btn-admin--danger' : 'btn-admin--outline' }}"
              onclick="dtBulkAction('{{ $bulkActionRoute }}', '{{ $action }}', '{{ $label }}')" style="font-size:12px;padding:5px 12px;">
        {{ $label }}
      </button>
    @endforeach
  </div>
</div>
