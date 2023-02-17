<?php

namespace App\Models;

use App\Traits\DeleteModelTrait;
use App\Traits\StorageImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Rinvex\Attributes\Traits\Attributable;
use function PHPUnit\Framework\isNull;

class Product extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use DeleteModelTrait;
    use StorageImageTrait;
    use Attributable;

    protected $guarded = [];

    protected $with = ['eav'];

    // begin

    public function star(){
        return 4.5;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['image_path_avatar'] = $this->avatar();

        $array['path_images'] = $this->images;
        if (count($array['path_images']) == 0){
            $array['path_images'][] = [
                'id' => "0",
                'uuid' => "none",
                'image_path' => $this->feature_image_path,
                'image_name' => "feature_image_path",
                'table' => "feature_image_path",
                'relate_id' => 0,
                'index' => 0,
                'status_image_id' => 0,
                'created_at' => "none",
                'updated_at' => "none",
            ];
        }
        $array['star'] = $this->star();
        $array['category'] = $this->category;
        $array['price'] = $this->priceSale();
        $array['price_by_user'] = $this->priceByUser();

        if ((auth('sanctum')->check() || auth()->check()) && (optional(auth('sanctum')->user())->is_admin != 0 || optional(auth()->user())->is_admin != 0)){
            // is admin user
        }else{
            unset($array['price_import']);
            if (auth('sanctum')->check()){
                $user_type_id = optional(auth('sanctum')->user())->user_type_id;
                if ($user_type_id == 2){
                    unset($array['price_partner']);
                }else if ($user_type_id == 3){
                    unset($array['price_agent']);
                }else{
                    unset($array['price_agent']);
                    unset($array['price_partner']);
                }
            }else{
                unset($array['price_agent']);
                unset($array['price_partner']);
            }
        }

        return $array;
    }

    public function productsAttributes(){
        return $this->hasMany(Product::class,'group_product_id','group_product_id');
    }

    public function isEmptyInventory(){
        return $this->inventory <= 0;
    }

    public function priceByUser(){
        if (auth('sanctum')->check()){
            $user_type_id = optional(auth('sanctum')->user())->user_type_id;
            if ($user_type_id == 3){
                return $this->price_partner;
            }if ($user_type_id == 2){
                return $this->price_agent;
            }
        }

        return $this->price_client;
    }

    public function priceSale(){

        $productsAttributes = $this->productsAttributes;

        $priceMinAgent = PHP_INT_MAX;
        $priceMinClient = PHP_INT_MAX;
        $priceMaxAgent = PHP_INT_MIN;
        $priceMaxClient = PHP_INT_MIN;

        $resultAgent = "Liên hệ";
        $resultClient = "Liên hệ";

        foreach ($productsAttributes as $item){
            if (!$item->isEmptyInventory()){
                if ($item->price_client <= $priceMinClient) $priceMinClient = $item->price_client;
                if ($item->price_client >= $priceMaxClient) $priceMaxClient = $item->price_client;
                if ($item->price_agent <= $priceMinAgent) $priceMinAgent = $item->price_agent;
                if ($item->price_agent >= $priceMaxAgent) $priceMaxAgent = $item->price_agent;
            }
        }

        if (!empty($priceMinAgent)){
            if ($priceMinAgent != $priceMaxAgent){
                $resultAgent = $priceMinAgent . " ~ " . $priceMaxAgent;
            }else{
                $resultAgent = $priceMinAgent;
            }
        }

        if (!empty($priceMinClient)){
            if ($priceMinClient != $priceMaxClient){
                $resultClient = $priceMinClient . " ~ " . $priceMaxClient;
            }else{
                $resultClient = $priceMinClient;
            }
        }

        if (auth('sanctum')->check() && auth('sanctum')->user()->user_type_id == 2){
            return $resultAgent . "";
        } else {
            return $resultClient . "";
        }
    }

    public function attributes()
    {
        $products = $this->where('group_product_id', $this->group_product_id)->get();

        $results = [];

        foreach ($products as $item){
            $temp = [];
            $temp['id'] = $item->id;
            $temp['size'] = $item->size;
            $temp['color'] = $item->color;
            $temp['inventory'] = $item->inventory;

            if (is_null($temp['size']) && is_null($temp['color'])) continue;

            $results[] = $temp;
        }

        return $results;
    }

    public function getInventoryByAttributes($input){
        foreach ($this->attributes() as $attribute){
            if ($attribute['size'] == $input){
                return $attribute['inventory'];
            }
        }

        return 0;
    }

    public function isProductVariation(){
        return !empty($this->attributesJson()) && is_array($this->attributesJson()) && count($this->attributesJson()) > 0;
    }

    public function attributesJson()
    {
        $products = $this->where('group_product_id', $this->group_product_id)->get();

        $resultsSize = [];
        $resultsColor = [];

        $resultsSizeFiltered = [];
        $resultsColorFiltered = [];

        foreach ($products as $item){
            $tempSize = $item->size;
            $tempColor = $item->color;

            if (is_null($tempSize)) continue;
            $resultsSize[] = $tempSize;

            if (is_null($tempColor)) continue;
            $resultsColor[] = $tempColor;
        }

        $attributes = $this->attributes();

        foreach ($resultsSize as $resultsSizeItem){

            $isBelong = false;

            foreach ($attributes as $attributeItem){
                if ($resultsSizeItem == $attributeItem['size'] && !in_array($resultsSizeItem, $resultsSizeFiltered)){
                    $isBelong = true;
                    break;
                }
            }
            if ($isBelong){
                $resultsSizeFiltered[] = $resultsSizeItem;
            }
        }

        foreach ($resultsColor as $resultsColorItem){

            $isBelong = false;

            foreach ($attributes as $attributeItem){
                if ($resultsColorItem == $attributeItem['color'] && !in_array($resultsColorItem, $resultsColorFiltered)){
                    $isBelong = true;
                    break;
                }
            }
            if ($isBelong){
                $resultsColorFiltered[] = $resultsColorItem;
            }
        }

        $result = [];

        if (!empty($resultsSizeFiltered) && count($resultsSizeFiltered) > 0){
            $result['sizes'] = $resultsSizeFiltered;
        }

        if (!empty($resultsColorFiltered) && count($resultsColorFiltered) > 0){
            $result['colors'] = $resultsColorFiltered;
        }

        return $result;
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    function firstOrCreateCategory($category_id){
        if (!empty($category_id) && !is_numeric($category_id)){
            $category = Category::firstOrCreate([
                'name' => $category_id,
            ],[
                'name' => $category_id,
                'slug' => Helper::addSlug($this,'slug', $category_id),
            ]);

            return $category->id;
        }

        return $category_id ?? 0;
    }

    // end

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

    public function searchByQuery($request, $queries = [], $randomRecord = null, $makeHiddens = null, $isCustom = false)
    {
        return Helper::searchByQuery($this, $request, $queries, $randomRecord, $makeHiddens, $isCustom);
    }

    public function storeByQuery($request)
    {

//        if (isset($request->headers) && isset($request->attributes) && !empty($request->headers) && !empty($request->attributes)){
//            $headers = json_decode($request->headers);
//            $attributes = json_decode($request->attributes);
//
//            if (count($headers) == 1){
//                app('rinvex.attributes.attribute')->create([
//                    'slug' => Formatter::slug($headers[0]),
//                    'type' => 'varchar',
//                    'name' => ['vi' => $headers[0], 'en' => $headers[0]],
//                    'entities' => ['App\Models\Product'],
//                ]);
//
//                for ($i = 0 ; $i < count($attributes[0]);$i++){
//
//                    $dataInsert = [
//                        'name' => $request->name,
//                        'short_description' => $request->short_description,
//                        'description' => $request->description,
//                        'slug' => Helper::addSlug($this,'slug', $request->title),
//                        'price_import' => Formatter::formatMoneyToDatabase($request->price_import),
//                        'price_client' => Formatter::formatMoneyToDatabase($request->price_client),
//                        'price_agent' => Formatter::formatMoneyToDatabase($request->price_agent),
//                        'price_partner' => Formatter::formatMoneyToDatabase($request->price_partner),
//                        'category_id' => $this->firstOrCreateCategory($request->category_id),
//                        'inventory' => Formatter::formatNumberToDatabase($request->inventory),
//                    ];
//
//                    $item = Helper::storeByQuery($this, $request, $dataInsert);
//
//                    $item->fill([Formatter::slug($headers[0]) => $attributes[0][$i]])->save();
//                }
//            }else{
//
//            }
//
//        }else{
//
//        }

        $dataInsert = [
            'name' => $request->name,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'slug' => Helper::addSlug($this,'slug', $request->title),
            'price_import' => Formatter::formatMoneyToDatabase($request->price_import),
            'price_client' => Formatter::formatMoneyToDatabase($request->price_client),
            'price_agent' => Formatter::formatMoneyToDatabase($request->price_agent),
            'price_partner' => Formatter::formatMoneyToDatabase($request->price_partner),
            'category_id' => $this->firstOrCreateCategory($request->category_id),
            'inventory' => Formatter::formatNumberToDatabase($request->inventory),
        ];

        $item = Helper::storeByQuery($this, $request, $dataInsert);

        return $this->findById($item->id);
    }

    public function updateByQuery($request, $id)
    {
        $dataUpdate = [
            'name' => $request->name,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'slug' => Helper::addSlug($this,'slug', $request->title),
            'price_import' => Formatter::formatMoneyToDatabase($request->price_import),
            'price_client' => Formatter::formatMoneyToDatabase($request->price_client),
            'price_agent' => Formatter::formatMoneyToDatabase($request->price_agent),
            'price_partner' => Formatter::formatMoneyToDatabase($request->price_partner),
            'category_id' => $this->firstOrCreateCategory($request->category_id),
            'inventory' => Formatter::formatNumberToDatabase($request->inventory),
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
