@if(isset($items))
    @if(empty($items->count()))
        <div class="text-center" style="padding: 20px;">
            Không có dữ liệu
        </div>
    @else
        <div style="padding: 20px;">
            {{ $items->links('pagination::bootstrap-4') }}
        </div>

    @endif
@endif
