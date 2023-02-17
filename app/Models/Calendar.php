<?php

namespace App\Models;

use App\Traits\DeleteModelTrait;
use App\Traits\StorageImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Contracts\Auditable;

class Calendar extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use DeleteModelTrait;
    use StorageImageTrait;

    protected $guarded = [];

    // begin

    public function sunCalendar(){
        return $this->hasOne(SunCalendar::class);
    }

    public function lunaCalendar(){
        return $this->hasOne(LunaCalendar::class);
    }

    public function timeCalendar(){
        return $this->hasMany(TimeCalendar::class);
    }

    public function timeZodiacCalendar(){
        return $this->hasMany(TimeZodiacCalendar::class);
    }

    public function fiveElementCalendar(){
        return $this->hasOne(FiveElementCalendar::class);
    }

    public function dayZodiacCalendar(){
        return $this->hasOne(DayZodiacCalendar::class);
    }

    public function trucDayCalendar(){
        return $this->hasOne(TrucDayCalendar::class);
    }

    public function thapNhiBatTuDayCalendar(){
        return $this->hasOne(ThapNhiBatTuDayCalendar::class);
    }

    public function goodStarCalendar(){
        return $this->hasMany(GoodStarCalendar::class);
    }

    public function badStarCalendar(){
        return $this->hasMany(BadStarCalendar::class);
    }

    public function tongHopBangKeCalendar(){
        return $this->hasOne(TongHopBangKeCalendar::class);
    }

    public function gioLyThuanPhongCalendar(){
        return $this->hasMany(GioLyThuanPhongCalendar::class);
    }

    public function quotation(){
        return $this->belongsTo(Quotation::class);
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
        $array['sun_calendar'] = $this->sunCalendar;
        $array['luna_calendar'] = $this->lunaCalendar;
        $array['time_calendar'] = $this->timeCalendar;
        $array['time_zodiac_calendar'] = $this->timeZodiacCalendar;
        $array['five_element_calendar'] = $this->fiveElementCalendar;
        $array['day_zodiac_calendar'] = $this->dayZodiacCalendar;
        $array['truc_day_calendar'] = $this->trucDayCalendar;
        $array['thap_nhi_bat_tuDay_calendar'] = $this->thapNhiBatTuDayCalendar;
        $array['good_star_calendar'] = $this->goodStarCalendar;
        $array['bad_star_calendar'] = $this->badStarCalendar;
        $array['tong_hop_bang_ke_calendar'] = $this->tongHopBangKeCalendar;
        $array['gio_ly_thuan_phong_calendar'] = $this->gioLyThuanPhongCalendar;
        $array['quotation'] = $this->quotation;
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
            'title' => $request->title,
            'content' => $request->contents,
            'slug' => Helper::addSlug($this,'slug', $request->title),
        ];

        $item = Helper::storeByQuery($this, $request, $dataInsert);

        return $this->findById($item->id);
    }

    public function updateByQuery($request, $id)
    {
        $dataUpdate = [
            'title' => $request->title,
            'content' => $request->contents,
            'slug' => Helper::addSlug($this,'slug', $request->title),
        ];
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
