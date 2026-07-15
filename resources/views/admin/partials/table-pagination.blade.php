{{--
  Shared table pagination partial.

  Expected variable:
    - $items (LengthAwarePaginator) — the paginated collection
--}}
@if(isset($items) && $items->hasPages())
<div class="table-pagination">
  <div class="table-pagination__info">
    Showing {{ $items->firstItem() }}–{{ $items->lastItem() }} of {{ $items->total() }} records
  </div>
  <div class="table-pagination__links">
    {{ $items->links() }}
  </div>
</div>
@elseif(isset($items))
<div class="table-pagination">
  <div class="table-pagination__info">
    Showing {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} of {{ $items->total() }} records
  </div>
</div>
@endif
