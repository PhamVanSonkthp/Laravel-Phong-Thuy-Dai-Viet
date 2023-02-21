<?php

namespace App\Models;

use App\Traits\DeleteModelTrait;
use App\Traits\StorageImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Contracts\Auditable;

class Voucher extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use DeleteModelTrait;
    use StorageImageTrait;

    protected $guarded = [];

    // begin

    public function textTypeVoucher(){
        return !empty($this->discount_amount) ? 'Giảm giá trực tiếp' : 'Giảm theo phần trăm';
    }

    public function typeVoucher(){
        return !empty($this->discount_amount) ? 1 : 2;
    }

    public function amountDiscount($amount){
        if ($this->typeVoucher() == 1){
            return $this->discount_amount;
        }else{
            $discount = ($this->discount_percent / 100) * $amount;

            if ($discount > $this->max_discount_percent_amount) return $this->max_discount_percent_amount;

            return $discount;
        }
    }

    public function isLimited(): bool
    {
        if ($this->used >= $this->max_use_by_time) return false;
        if ($this->used >= $this->max_use_by_user) return false;

        return true;
    }
    public function isLimitedByUser(): bool
    {

        $total = VoucherUsed::where('user_id', auth()->id())->where('voucher_id', $this->id)->count();

        return $total >= $this->max_use_by_user;
    }

    public function isExpired(): bool
    {
        return strtotime(now()) > strtotime($this->end);
    }

    public function isUnavailable(): bool
    {
        return strtotime(now()) < strtotime($this->begin);
    }

    public function isAcceptAmount($amount): bool
    {
        return $this->min_amount >= $amount;
    }

    // end

    public function getTableName()
    {
        return Helper::getTableName($this);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['image_path_avatar'] = $this->avatar();
        $array['path_images'] = $this->images;
        return $array;
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

    public function searchByQuery($request, $queries = [], $randomRecord = null, $makeHiddens = null, $isCustom = false)
    {
        return Helper::searchByQuery($this, $request, $queries, $randomRecord, $makeHiddens, $isCustom);
    }

    public function storeByQuery($request)
    {
        $dataInsert = [
            'name' => $request->name,
            'code' => $request->code ?? strtoupper(Helper::randomString()),
            'begin' => $request->begin,
            'end' => $request->end,
            'min_amount' => $request->min_amount,
            'max_use_by_time' => $request->max_use_by_time,
            'max_use_by_user' => $request->max_use_by_user,
        ];

        if (isset($request->discount_amount) && !empty($request->discount_amount)){
            $dataInsert['discount_amount'] = $request->discount_amount;
            $dataInsert['discount_percent'] = 0;
            $dataInsert['max_discount_percent_amount'] = 0;
        }else{
            $dataInsert['discount_amount'] = 0;
            $dataInsert['discount_percent'] = $request->discount_percent;
            $dataInsert['max_discount_percent_amount'] = $request->max_discount_percent_amount;
        }

        $item = Helper::storeByQuery($this, $request, $dataInsert);

        return $this->findById($item->id);
    }

    public function updateByQuery($request, $id)
    {
        $dataUpdate = [
            'name' => $request->name,
            'code' => $request->code ?? strtoupper(Helper::randomString()),
            'begin' => $request->begin,
            'end' => $request->end,
            'min_amount' => $request->min_amount,
            'max_use_by_time' => $request->max_use_by_time,
            'max_use_by_user' => $request->max_use_by_user,
        ];

        if (isset($request->discount_amount) && !empty($request->discount_amount)){
            $dataUpdate['discount_amount'] = $request->discount_amount;
            $dataUpdate['discount_percent'] = 0;
            $dataUpdate['max_discount_percent_amount'] = 0;
        }else{
            $dataUpdate['discount_amount'] = 0;
            $dataUpdate['discount_percent'] = $request->discount_percent;
            $dataUpdate['max_discount_percent_amount'] = $request->max_discount_percent_amount;
        }

        $item = Helper::updateByQuery($this, $request, $id, $dataUpdate);
        return $this->findById($item->id);
    }

    public function deleteByQuery($request, $id, $forceDelete = false)
    {
        return Helper::deleteByQuery($this, $request, $id, $forceDelete);
    }

    public function deleteManyByIds($request, $forceDelete = false)
    {
        return Helper::deleteManyByIds($this, $request, $forceDelete);
    }

    public function findById($id){
        $item = $this->find($id);
        return $item;
    }

}
