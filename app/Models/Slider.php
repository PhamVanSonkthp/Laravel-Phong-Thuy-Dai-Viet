<?php

namespace App\Models;

use App\Traits\DeleteModelTrait;
use App\Traits\StorageImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

class Slider extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use StorageImageTrait;
    use DeleteModelTrait;

    protected $guarded = [];

    public function toArray()
    {
        $array = parent::toArray();
        $array['image_path_avatar'] = $this->avatar();
        $array['path_images'] = $this->images;
        return $array;
    }

    public function getTableName()
    {
        return Helper::getTableName($this);
    }

    public function avatar($size = "100x100")
    {
        return Helper::getDefaultIcon($this, $size);
    }

    public function image()
    {
        return Helper::image($this);
    }

    public function images()
    {
        return Helper::images($this);
    }

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by_id');
    }

    public function searchByQuery($request, $queries = [])
    {
        return Helper::searchByQuery($this, $request, $queries);
    }

    public function storeByQuery($request)
    {
        $dataInsert = [
            'link' => $request->link,
        ];

        $item = Helper::storeByQuery($this, $request, $dataInsert);

        return $this->findById($item->id);
    }

    public function updateByQuery($request, $id)
    {
        $dataUpdate = [
            'link' => $request->link,
        ];

        $item = Helper::updateByQuery($this, $request, $id, $dataUpdate);
        return $this->findById($item->id);
    }

    public function deleteByQuery($request, $id, $forceDelete = false)
    {
        return Helper::deleteByQuery($this, $request, $id, $forceDelete);
    }

    public function findById($id)
    {
        $item = $this->find($id);
        return $item;
    }
}
