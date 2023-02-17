<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait DeleteModelTrait{
    public function deleteModelTrait($id, $model, $forceDelete = false){

        try {
            if ($forceDelete){
                $model->withTrashed()->find($id)->forceDelete();
            }else{
                $model->withTrashed()->find($id)->delete();
            }

            return response()->json([
                'code'=>200,
                'message'=>'success',
            ],200);
        }catch (\Exception $exception){

            try {
                if ($forceDelete){
                    $model->find($id)->forceDelete();
                }else{
                    $model->find($id)->delete();
                }

                return response()->json([
                    'code'=>200,
                    'message'=>'success',
                ],200);
            }catch (\Exception $exception){
                Log::error('Message: ' . $exception->getMessage() . 'Line' . $exception->getLine());
                return response()->json([
                    'code'=>500,
                    'message'=>'fail',
                ],500);
            }
        }
    }
}
