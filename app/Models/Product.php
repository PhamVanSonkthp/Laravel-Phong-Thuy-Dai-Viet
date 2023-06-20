<?php

namespace App\Models;

use App\Traits\DeleteModelTrait;
use App\Traits\StorageImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Rinvex\Attributes\Traits\Attributable;

class Product extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use DeleteModelTrait;
    use StorageImageTrait;
    use Attributable;

    protected $searchable = [
        'name'
    ];

    protected $guarded = [];

    protected $with = ['eav'];

    // begin

    public static function search($request)
    {

        $results = Product::where('product_visibility_id', 2);

        if (isset($request->category_id) && !empty($request->category_id)) {
            $results = $results->where('category_id', $request->category_id);
        }

        if (isset($request->min_price)) {
            $results = $results->where(function ($query) use ($request) {
                $query->where('price_client', '>=', $request->min_price)
                    ->orWhere('price_agent', '>=', $request->min_price);
            });
        }

        if (isset($request->max_price)) {
            $results = $results->where(function ($query) use ($request) {
                $query->where('price_client', '<=', $request->max_price)
                    ->orWhere('price_agent', '<=', $request->max_price);
            });
        }

        if (isset($request->empty_inventory) && $request->empty_inventory == 2) {
            $results = $results->where('inventory', '<=', 0);
        }

        if (isset($request->empty_inventory) && $request->empty_inventory == 1) {
            $results = $results->where('inventory', '>', 0);
        }

        if (!empty($request->search_query)) {

            $results = $results->where(function ($query) use ($request) {
                $words = explode(" ", $request->search_query);

                $query = $query->where(function ($results) use ($words) {
                    foreach ($words as $word) {
                        $results = $results->where('name', 'LIKE', "%{$word}%");
                    }
                });

                $query = $query->orWhere("name", "LIKE", "%{$request->search_query}%");

                $query = $query->orWhere(function ($results) use ($words) {
                    foreach ($words as $word) {
                        $results = $results->orWhere('name', 'LIKE', "%{$word}%");
                    }
                });

            });


            $words = explode(" ", $request->search_query);
            $sort1 = "";
            foreach ($words as $index => $word) {
                $sort1 .= "name LIKE '%{$word}%'";

                if ($index != count($words) - 1){
                    $sort1 .= " AND ";
                }
            }

            $sort = "CASE WHEN name LIKE '%{$request->search_query}%' THEN 1 WHEN {$sort1} THEN 2 ELSE 3 END, name";

            $results = $results->orderByRaw($sort);
        }

        if (isset($request->sort_by_price)) {
            if ($request->sort_by_price == "asc") {
                $results = $results->orderBy('price_client', 'asc');
            } else if ($request->sort_by_price == "desc") {
                $results = $results->orderBy('price_client', 'DESC');
            }
        }

        if (!empty($request->is_feature)) {
            $results = $results->where('is_feature', $request->is_feature);
        }

        if (isset($request->is_best) && $request->is_best == 1) {
            $results = $results->orderBy('sold', 'DESC');
        } else {
            $results = $results->latest();
        }

        if (isset($request->min_inventory) && strlen($request->min_inventory)) {
            $results = $results->where('inventory', '>=', $request->min_inventory);
        }

        if (isset($request->min_inventory) && strlen($request->min_inventory)) {
            $results = $results->where('inventory', '<=', $request->max_inventory);
        }

        $results = $results->paginate(Formatter::getLimitRequest($request->limit))->appends(request()->query());

        return $results;
    }

    public function star()
    {
        return 5;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['description'] = str_replace("src=\"/storage", "src=\"" . env('APP_URL') . "/storage", $array['description']);
        $array['description'] = str_replace("src=\"//bizweb", "src=\"" . "https://bizweb", $array['description']);

        $array['image_path_avatar'] = $this->avatar();
        $array['path_images'] = $this->images;
        if (count($array['path_images']) == 0) {
            $array['path_images'][] = [
                'id' => 0,
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
        $array['price_up_sale'] = $this->priceUpSale();
        $array['is_flash_sale'] = rand(0, 1) == 1;
        $array['price_by_user'] = $this->priceByUser();

        if ((auth('sanctum')->check() || auth()->check()) && (optional(auth('sanctum')->user())->is_admin != 0 || optional(auth()->user())->is_admin != 0)) {
            // is admin user
        } else {
            unset($array['price_import']);
            if (auth('sanctum')->check()) {
                $user_type_id = optional(auth('sanctum')->user())->user_type_id;
                if ($user_type_id == 2) {
                    unset($array['price_partner']);
                } else if ($user_type_id == 3) {
                    unset($array['price_agent']);
                } else {
                    unset($array['price_agent']);
                    unset($array['price_partner']);
                }
            } else {
                unset($array['price_agent']);
                unset($array['price_partner']);
            }
        }

        return $array;
    }

    public function productsAttributes()
    {
        return $this->hasMany(Product::class, 'group_product_id', 'group_product_id');
    }

    public function isEmptyInventory()
    {
        return $this->inventory <= 0;
    }

    public function priceByUser()
    {
        if (auth('sanctum')->check()) {
            $user_type_id = optional(auth('sanctum')->user())->user_type_id;
            if ($user_type_id == 3) {
                return $this->price_partner;
            }
            if ($user_type_id == 2) {
                return $this->price_agent;
            }
        }

        return $this->price_client;
    }

    public function priceSale()
    {

        $productsAttributes = $this->productsAttributes;

        $priceMinAgent = PHP_INT_MAX;
        $priceMinClient = PHP_INT_MAX;
        $priceMaxAgent = PHP_INT_MIN;
        $priceMaxClient = PHP_INT_MIN;

        $resultAgent = 0;
        $resultClient = 0;

        foreach ($productsAttributes as $item) {
            if ($item->price_client <= $priceMinClient) $priceMinClient = $item->price_client;
            if ($item->price_client >= $priceMaxClient) $priceMaxClient = $item->price_client;


            if (auth('sanctum')->check()) {

                if (auth('sanctum')->user()->user_type_id == 2) {
                    if ($item->price_agent <= $priceMinAgent) $priceMinAgent = $item->price_agent;
                    if ($item->price_agent >= $priceMaxAgent) $priceMaxAgent = $item->price_agent;
                } elseif (auth('sanctum')->user()->user_type_id == 3) {
                    if ($item->price_partner <= $priceMinAgent) $priceMinAgent = $item->price_partner;
                    if ($item->price_partner >= $priceMaxAgent) $priceMaxAgent = $item->price_partner;
                }
            } else {
                if ($item->price_agent <= $priceMinAgent) $priceMinAgent = $item->price_agent;
                if ($item->price_agent >= $priceMaxAgent) $priceMaxAgent = $item->price_agent;
            }

        }

        if (!empty($priceMinAgent) || !empty($priceMaxAgent)) {
            if ($priceMinAgent != $priceMaxAgent) {
                $resultAgent = Formatter::convertNumberToMoney($priceMinAgent) . " ~ " . Formatter::convertNumberToMoney($priceMaxAgent);
            } else {
                $resultAgent = Formatter::convertNumberToMoney($priceMinAgent);
            }
        }

        if (!empty($priceMinClient) || !empty($priceMaxClient)) {
            if ($priceMinClient != $priceMaxClient) {
                $resultClient = Formatter::convertNumberToMoney($priceMinClient) . " ~ " . Formatter::convertNumberToMoney($priceMaxClient);
            } else {
                $resultClient = Formatter::convertNumberToMoney($priceMinClient);
            }
        }

        if (!$this->isProductVariation()){

            if ( auth('sanctum')->check() && auth('sanctum')->user()->user_type_id == 3){
                $resultAgent = Formatter::convertNumberToMoney(round($this->price_agent));
            }else{
                $resultAgent = Formatter::convertNumberToMoney(round($this->price_partner));
            }

            $resultClient = Formatter::convertNumberToMoney(round($this->price_client));
        }

        if (auth('sanctum')->check() && auth('sanctum')->user()->user_type_id != 1) {
            return $resultAgent . "";
        } else {
            return $resultClient . "";
        }
    }

    public function priceUpSale()
    {

        $percent = 0.25;

        $productsAttributes = $this->productsAttributes;

        $priceMinAgent = PHP_INT_MAX;
        $priceMinClient = PHP_INT_MAX;
        $priceMaxAgent = PHP_INT_MIN;
        $priceMaxClient = PHP_INT_MIN;

        $resultAgent = 0;
        $resultClient = 0;

        foreach ($productsAttributes as $item) {
            if ($item->price_client <= $priceMinClient) $priceMinClient = $item->price_client;
            if ($item->price_client >= $priceMaxClient) $priceMaxClient = $item->price_client;
            if ($item->price_agent <= $priceMinAgent) $priceMinAgent = $item->price_agent;
            if ($item->price_agent >= $priceMaxAgent) $priceMaxAgent = $item->price_agent;
        }
        if ($priceMinClient == PHP_INT_MIN) $priceMinClient = 0;
        if ($priceMaxClient == PHP_INT_MAX) $priceMaxClient = 0;

        if (!empty($priceMinAgent) || !empty($priceMaxAgent)) {
            if ($priceMinAgent != $priceMaxAgent) {
                $resultAgent = Formatter::convertNumberToMoney(round($priceMinAgent + $priceMinAgent * $percent)) . " ~ " . Formatter::convertNumberToMoney(round($priceMaxAgent + $priceMaxAgent * $percent));
            } else {
                $resultAgent = Formatter::convertNumberToMoney(round($priceMinAgent + $priceMinAgent * $percent));
            }
        }

        if (!empty($priceMinClient) || !empty($priceMaxClient)) {
            if ($priceMinClient != $priceMaxClient) {
                $resultClient = Formatter::convertNumberToMoney(round($priceMinClient + $priceMinClient * $percent)) . " ~ " . Formatter::convertNumberToMoney(round($priceMaxClient + $priceMaxClient * $percent));
            } else {
                $resultClient = Formatter::convertNumberToMoney(round($priceMinClient + $priceMinClient * $percent));
            }
        }

        if (!$this->isProductVariation()){
            if ( auth('sanctum')->check() && auth('sanctum')->user()->user_type_id == 3){
                $resultAgent = Formatter::convertNumberToMoney(round($this->price_agent));
            }else{
                $resultAgent = Formatter::convertNumberToMoney(round($this->price_partner));
            }

            $resultClient = Formatter::convertNumberToMoney(round($this->price_client + $this->price_client * $percent));
        }

        if (auth('sanctum')->check() && auth('sanctum')->user()->user_type_id != 1) {
            return $resultAgent . "";
        } else {
            return $resultClient . "";
        }
    }

    public function attributes()
    {
        $products = $this->where('group_product_id', $this->group_product_id)->get();

        $results = [];

        foreach ($products as $item) {
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

    public function getInventoryByAttributes($input)
    {
        foreach ($this->attributes() as $attribute) {
            if ($attribute['size'] == $input) {
                return $attribute['inventory'];
            }
        }

        return 0;
    }

    public function isProductVariation()
    {
        return !empty($this->attributesJson()) && is_array($this->attributesJson()) && count($this->attributesJson()) > 0;
    }

    public function attributesJson()
    {
        $products = $this->where('group_product_id', $this->group_product_id)->get();

        $resultsSize = [];
        $resultsColor = [];

        $resultsSizeFiltered = [];
        $resultsColorFiltered = [];

        foreach ($products as $item) {
            $tempSize = $item->size;
            $tempColor = $item->color;

            if (is_null($tempSize)) continue;
            $resultsSize[] = $tempSize;

            if (is_null($tempColor)) continue;
            $resultsColor[] = $tempColor;
        }

        $attributes = $this->attributes();

        foreach ($resultsSize as $resultsSizeItem) {

            $isBelong = false;

            foreach ($attributes as $attributeItem) {
                if ($resultsSizeItem == $attributeItem['size'] && !in_array($resultsSizeItem, $resultsSizeFiltered)) {
                    $isBelong = true;
                    break;
                }
            }
            if ($isBelong) {
                $resultsSizeFiltered[] = $resultsSizeItem;
            }
        }

        foreach ($resultsColor as $resultsColorItem) {

            $isBelong = false;

            foreach ($attributes as $attributeItem) {
                if ($resultsColorItem == $attributeItem['color'] && !in_array($resultsColorItem, $resultsColorFiltered)) {
                    $isBelong = true;
                    break;
                }
            }
            if ($isBelong) {
                $resultsColorFiltered[] = $resultsColorItem;
            }
        }

        $result = [];

        if (!empty($resultsSizeFiltered) && count($resultsSizeFiltered) > 0) {
            $result['sizes'] = $resultsSizeFiltered;
        }

        if (!empty($resultsColorFiltered) && count($resultsColorFiltered) > 0) {
            $result['colors'] = $resultsColorFiltered;
        }

        return $result;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    function firstOrCreateCategory($category_id)
    {
        if (!empty($category_id) && !is_numeric($category_id)) {
            $category = Category::firstOrCreate([
                'name' => $category_id,
            ], [
                'name' => $category_id,
                'slug' => Helper::addSlug($this, 'slug', $category_id),
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

    public function avatar($size = "1000x1000")
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

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by_id');
    }

    public function searchByQuery($request, $queries = [], $randomRecord = null, $makeHiddens = null, $isCustom = false)
    {
        return Helper::searchByQuery($this, $request, $queries, $randomRecord, $makeHiddens, $isCustom);
    }

    public function storeByQuery($request)
    {

        $group_product_id = 2;

        $productLatest = Product::latest()->first();

        if (!empty($productLatest)) {
            $group_product_id = $productLatest->id + 1;
        }

        $product_visibility_id = 2;

        if (isset($request->_headers) && isset($request->_attributes) && !empty($request->_headers) && !empty($request->_attributes)) {
            $_headers = json_decode($request->_headers);
            $_attributes = json_decode($request->_attributes);

            if (count($_headers) == 1) {

                for ($i = 0; $i < count($_attributes[0]); $i++) {

                    $dataInsert = [
                        'name' => $request->name,
                        'short_description' => $request->short_description,
                        'description' => $request->description,
                        'slug' => Helper::addSlug($this, 'slug', $request->name),
                        'price_import' => Formatter::formatMoneyToDatabase($request->import_prices[$i]),
                        'price_client' => Formatter::formatMoneyToDatabase($request->client_prices[$i]),
                        'price_agent' => Formatter::formatMoneyToDatabase($request->agent_prices[$i]),
                        'price_partner' => Formatter::formatMoneyToDatabase($request->partner_prices[$i]),
                        'category_id' => $this->firstOrCreateCategory($request->category_id),
                        'inventory' => Formatter::formatNumberToDatabase($request->inventories[$i]),
                        'sku' => Formatter::formatNumberToDatabase($request->skus[$i]),
                        'group_product_id' => $group_product_id,
                        'product_visibility_id' => $product_visibility_id,
                    ];

                    $product_visibility_id = 1;

                    $item = Helper::storeByQuery($this, $request, $dataInsert);

                    $attr = [];
                    $attr['size'] = $_attributes[0][$i];

                    $item->fill($attr)->save();
                }
            } else {

                $pointer = 0;

                for ($i = 0; $i < count($_attributes[0]); $i++) {

                    for ($j = 0; $j < count($_attributes[1]); $j++) {
                        $dataInsert = [
                            'name' => $request->name,
                            'short_description' => $request->short_description,
                            'description' => $request->description,
                            'slug' => Helper::addSlug($this, 'slug', $request->name),
                            'price_import' => Formatter::formatMoneyToDatabase($request->import_prices[$pointer]),
                            'price_client' => Formatter::formatMoneyToDatabase($request->client_prices[$pointer]),
                            'price_agent' => Formatter::formatMoneyToDatabase($request->agent_prices[$pointer]),
                            'price_partner' => Formatter::formatMoneyToDatabase($request->partner_prices[$pointer]),
                            'category_id' => $this->firstOrCreateCategory($request->category_id),
                            'inventory' => Formatter::formatNumberToDatabase($request->inventories[$pointer]),
                            'sku' => Formatter::formatNumberToDatabase($request->skus[$pointer]),
                            'group_product_id' => $group_product_id,
                            'product_visibility_id' => $product_visibility_id,
                        ];

                        $product_visibility_id = 1;

                        $item = Helper::storeByQuery($this, $request, $dataInsert);

                        $attr = [];
                        $attr['size'] = $_attributes[0][$i];
                        $attr['color'] = $_attributes[1][$j];

                        $item->fill($attr)->save();

                        $pointer++;
                    }

                }

            }

        } else {
            $dataInsert = [
                'name' => $request->name,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'slug' => Helper::addSlug($this, 'slug', $request->name),
                'price_import' => Formatter::formatMoneyToDatabase($request->price_import),
                'price_client' => Formatter::formatMoneyToDatabase($request->price_client),
                'price_agent' => Formatter::formatMoneyToDatabase($request->price_agent),
                'price_partner' => Formatter::formatMoneyToDatabase($request->price_partner),
                'category_id' => $this->firstOrCreateCategory($request->category_id),
                'inventory' => Formatter::formatNumberToDatabase($request->inventory),
                'product_visibility_id' => $product_visibility_id,
                'group_product_id' => $group_product_id,
                'sku' => $request->sku,
            ];

            $item = Helper::storeByQuery($this, $request, $dataInsert);

            return $this->findById($item->id);
        }


    }

    public function updateByQuery($request, $id)
    {
        $dataUpdate = [
            'name' => $request->name,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'slug' => Helper::addSlug($this, 'slug', $request->title),
            'price_import' => Formatter::formatMoneyToDatabase($request->price_import),
            'price_client' => Formatter::formatMoneyToDatabase($request->price_client),
            'price_agent' => Formatter::formatMoneyToDatabase($request->price_agent),
            'price_partner' => Formatter::formatMoneyToDatabase($request->price_partner),
            'category_id' => $this->firstOrCreateCategory($request->category_id),
            'inventory' => Formatter::formatNumberToDatabase($request->inventory),
            'sku' => $request->sku,
            'is_feature' => $request->is_feature ?? 0,
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

    public function findById($id)
    {
        $item = $this->find($id);
        return $item;
    }
}
