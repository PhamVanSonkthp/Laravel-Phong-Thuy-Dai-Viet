<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryNew;
use App\Models\Chat;
use App\Models\ChatGroup;
use App\Models\Formatter;
use App\Models\ParticipantChat;
use App\Models\Product;
use App\Models\RestfulAPI;
use App\Models\User;
use App\Models\UserProductRecent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{

    private $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function list(Request $request)
    {
        $request->validate([
            'min_price' => 'numeric',
            'max_price' => 'numeric',
            'empty_inventory' => 'numeric|min:0|max:2',
        ]);

        $queries = ['product_visibility_id' => 2];
        $results = RestfulAPI::response($this->model, $request, $queries, null, ['price_import'], true);

        if (isset($request->min_price)){
            $results = $results->where(function ($query) use ($request) {
                $query->where('price_client', '>=', $request->min_price)
                    ->orWhere('price_agent', '>=', $request->min_price);
            });
        }

        if (isset($request->max_price)){
            $results = $results->where(function ($query) use ($request) {
                $query->where('price_client', '<=', $request->max_price)
                    ->orWhere('price_agent', '<=', $request->max_price);
            });
        }

        if (isset($request->empty_inventory) && $request->empty_inventory == 2){
            $results = $results->where('inventory', '<=' , 0);
        }

        if (isset($request->empty_inventory) && $request->empty_inventory == 1){
            $results = $results->where('inventory' ,'>', 0);
        }

        $results = $results->latest()->paginate(Formatter::getLimitRequest($request->limit))->appends(request()->query());

        return response()->json($results);
    }

    public function get(Request $request, $id)
    {
        $item = $this->model->findById($id);

        if (empty($item)) return abort(404);

        $item['attributes'] = $item->attributes();
        $item['attributes_json'] = $item->attributesJson();

        if (auth('sanctum')->check()){
            $user = auth('sanctum')->user();

            $userProductRecent = UserProductRecent::firstOrCreate([
                'user_id' => $user->id,
                'product_id' => $item->id,
            ]);

            $userProductRecent->increment('count');
        }

        if (count($item['attributes_json']) == 0){
            $item['attributes_json'] = null;
        }

        return response()->json($item);
    }

    public function productSeenRecent(Request $request)
    {
        $queries = ['user_id' => auth()->id()];
        $results = RestfulAPI::response(new UserProductRecent, $request, $queries);

        return response()->json($results);
    }
}
