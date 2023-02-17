<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryNew;
use App\Models\Chat;
use App\Models\ChatGroup;
use App\Models\Formatter;
use App\Models\Helper;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ParticipantChat;
use App\Models\Product;
use App\Models\RestfulAPI;
use App\Models\User;
use App\Models\UserCart;
use App\Models\UserProductRecent;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VoucherController extends Controller
{

    private $model;

    public function __construct(UserVoucher $model)
    {
        $this->model = $model;
    }

    public function list(Request $request)
    {
        $queries = ['user_id' => auth()->id()];
        $results = RestfulAPI::response($this->model, $request, $queries);
        return response()->json($results);
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required',
        ]);

        $voucher = Voucher::find($request->voucher_id);

        if (empty($voucher)){
            $voucher = Voucher::where('code', $request->voucher_id)->first();
        }

        if (empty($voucher)){
            return response()->json(Helper::errorAPI(99,[],"voucher_id invalid") , 400);
        }

        $item = $this->model->firstOrCreate([
            'user_id' => auth()->id(),
            'voucher_id' => $request->voucher_id,
        ]);

        $item->refresh();

        return response()->json($item);
    }

    public function checkWithCarts(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required',
            'cart_ids' => 'required|array|min:1',
            "cart_ids.*" => "required|numeric|min:1",
        ]);

        $voucher = Voucher::find($request->voucher_id);

        if (empty($voucher)){
            $voucher = Voucher::where('code', $request->voucher_id)->first();
        }

        if (empty($voucher)){
            return response()->json(Helper::errorAPI(99,[],"voucher_id invalid") , 400);
        }




        $item->refresh();

        return response()->json($item);
    }

}
