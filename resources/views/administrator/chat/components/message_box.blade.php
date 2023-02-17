@if($itemChat->sender)
<div class="d-flex flex-row justify-content-end message">
    <div class="w-100 text-end">
        <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary text-chat" style="{{!empty($itemChat->content) ? 'display: inline-block;' : 'none'}};">{{$itemChat->content}}</p>
        <div class="row justify-content-end small me-3 rounded-3">
            @foreach($itemChat->images as $item)
                <div class="col-4 p-3">
                    <img data-src="${images[i].image_path}" onclick="showImage(this)" class="image-chat w-100" src="{{$item->image_path}}">
                </div>
            @endforeach
        </div>
        <p class="small me-3 mb-3 rounded-3 text-muted d-flex justify-content-end">{{\App\Models\Formatter::formatTimeToNow($itemChat->time)}}</p>
    </div>
    <img src="{{auth()->user()->feature_image_path}}" class="avatar-chat">
</div>

@else
<div class="d-flex flex-row justify-content-start mb-4 message">
    <img src="${img_link}"  class="avatar-chat">
    <div class="w-100 text-start">
        <p class="small ms-3 mb-1 text-chat">${name_getter}</p>
        <p class="small p-2 ms-3 mb-1 rounded-3 text-chat" style="background-color: #f5f6f7;{{!empty($itemChat->content) ? 'display: inline-block;' : 'none'}};">{{$itemChat->content}}</p>
        <div class="row justify-content-end small me-3 rounded-3">
            @foreach($itemChat->images as $item)
                <div class="col-4 p-3">
                    <img data-src="${images[i].image_path}" onclick="showImage(this)" class="image-chat w-100" src="{{$item->image_path}}">
                </div>
            @endforeach
        </div>
        <p class="small ms-3 mb-3 rounded-3 text-muted">{{\App\Models\Formatter::formatTimeToNow($itemChat->time)}}</p>
    </div>
</div>
@endif
