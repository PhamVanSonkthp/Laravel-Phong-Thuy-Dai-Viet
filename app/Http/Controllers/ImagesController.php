<?php

namespace App\Http\Controllers;

use App\Models\SingleImage;
use Intervention\Image\Facades\Image;

class ImagesController extends Controller
{
    public function show($type, $user_id, $id, $size, $slug){

        if ($type == 'single'){
            $item = SingleImage::find($id);
        }else{
            $item = \App\Models\Image::find($id);
        }

        if (empty($item)){
            return abort(404);
        }

        if ($item->isPublic()){
            return $this->approved($type, $user_id, $id, $size, $slug);
        }

        if (!auth()->check()){
            return abort(404);
        }

        if (auth()->id() == $user_id || auth()->user()->isEmployee()){
            return $this->approved($type, $user_id, $id, $size, $slug);
        }

        foreach ($item->usersSingleImage as $user){
            if (auth()->id() == $user->user_id){
                return $this->approved($type, $user_id, $id, $size, $slug);
            }
        }

        return abort(404);
    }

    function approved($type, $user_id, $id, $size, $slug){
        $storagePath = storage_path('/assets/'.$type.'/' .$user_id. '/' . $id . '/' . $size . '/' . $slug);
        return Image::make($storagePath)->response();
    }
}

