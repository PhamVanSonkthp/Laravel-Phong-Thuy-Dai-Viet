<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryNew;
use App\Models\Chat;
use App\Models\ChatGroup;
use App\Models\Formatter;
use App\Models\Helper;
use App\Models\ParticipantChat;
use App\Models\Product;
use App\Models\RestfulAPI;
use App\Models\User;
use App\Models\UserCart;
use App\Models\UserProductRecent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CartController extends Controller
{

    private $model;

    public function __construct(UserCart $model)
    {
        $this->model = $model;
    }

    public function list(Request $request)
    {
        $queries = ['user_id' => auth()->id()];
        $results = RestfulAPI::response($this->model, $request, $queries);
        return response()->json($results);
    }

    public function listNotAuth(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            "product_ids.*" => "required|numeric|min:1",
        ]);

        $results = [];

        foreach ($request->product_ids as $product_id){
            $product = Product::find($product_id);
            if (empty($product)) continue;
            $results[] = $product;
        }

        return response()->json($results);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric|exists:products,id',
            'quantity' => 'numeric|min:1',
        ]);

        if (empty($request->quantity)){
            $request->quantity = 1;
        }

        $item = $this->model->where([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ])->first();

        DB::beginTransaction();

        if (!empty($item)){
            $item->increment('quantity', $request->quantity);
        }else{
            $item = $this->model->create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        $item->refresh();

        $product = Product::find($request->product_id);

        if ($item->quantity > $product->inventory){
            DB::rollback();
            return response()->json(Helper::errorAPI(99, [
                'max_inventory' => $product->inventory
            ],"Số lượng sản phẩm không hợp lệ"), 400);
        }else{
            DB::commit();
        }

        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1',
        ]);

        $item = $this->model->findOrFail($id);

        if ($item->user_id != auth()->id()){
            return response()->json(Helper::errorAPI(99, [],"Không tìm thấy giỏ hàng"), 400);
        }

        DB::beginTransaction();

        $item->update([
            'quantity' => $request->quantity
        ]);

        $item->refresh();

        $product = Product::find($item->product_id);

        if ($item->quantity > $product->inventory){
            DB::rollback();
            return response()->json(Helper::errorAPI(99, [
                'max_inventory' => $product->inventory
            ],"Số lượng sản phẩm không hợp lệ"), 400);
        }else{
            DB::commit();
        }

        return response()->json($item);
    }

    public function delete(Request $request, $id)
    {

        $item = $this->model->findOrFail($id);

        if ($item->user_id != auth()->id()){
            return response()->json(Helper::errorAPI(99, [],"Không tìm thấy giỏ hàng"), 400);
        }

        $item = $this->model->deleteByQuery($request, $id);

        return $item;
    }

}
