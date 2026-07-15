{{-- Shared admin table pagination --}}
{{-- Required: $items (LengthAwarePaginator) --}}
@if($items->hasPages())
<div class="dt-pagination">
  <span class="dt-pagination__info">
    Showing {{ $items->firstItem() ?? 0 }}&ndash;{{ $items->lastItem() ?? 0 }} of {{ $items->total() }} records
  </span>
  <div class="dt-pagination__links">
    {!! $items->links('admin.partials.pagination') !!}
  </div>
</div>
@elseif($items->total() > 0)
<div class="dt-pagination">
  <span class="dt-pagination__info">
    Showing {{ $items->total() }} {{ Str::plural('record', $items->total()) }}
  </span>
</div>
@endif
