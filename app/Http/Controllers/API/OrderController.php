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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{

    private $model;

    public function __construct(Order $model)
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
            'cart_ids' => 'required|array|min:1',
            "cart_ids.*" => "required|numeric|min:1",
        ]);

        DB::beginTransaction();

        $item = $this->model->create([
            'user_id' => auth()->id(),
        ]);

        foreach ($request->cart_ids as $cart_id) {
            $cartItem = UserCart::find($cart_id);

            if (empty($cartItem) || $cartItem->user_id != auth()->id()) {
                DB::rollback();
                return response()->json(Helper::errorAPI(99, [
                    'cart_id' => $cart_id
                ], "Mã giỏ hàng không hợp lệ"), 400);
            }

            $orderProduct = OrderProduct::create([
                'order_id' => $item->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->priceByUser(),
                'name' => $cartItem->product->name,
                'product_image' => $cartItem->product->avatar(),
            ]);

            $orderProduct->fill(['order_size' => $cartItem->product->size, 'order_color' => $cartItem->product->color])->save();

            $cartItem->delete();
        }

        DB::commit();

        $html = "<p>Thông tin khách hàng</p>";
        $html .= "<div>Họ và tên: " . auth()->user()->name . "</div>";
        $html .= "<div>Số điện thoại: " . auth()->user()->phone . "</div>";
        $html .= "<div>Địa chỉ: " . auth()->user()->address . "</div>";

        $html .= "<p>Danh sách đơn hàng</p>";

        $table = "<table style='width: 100%;border: solid;'>";
        $table .= "<thead><tr><th style='border: 1px solid;'>Sản phẩm</th><th style='border: 1px solid;'>Số lượng</th></tr></thead>";
        $table .= "<tbody>";
        foreach ($item->products as $productItem) {

            $productAttributeHtml = "";

            if (!empty($productItem->order_size) || !empty($productItem->order_color)) {
                $productAttributeHtml = '<div>Phân loại:<strong>' . Formatter::getShortDescriptionAttribute($productItem->order_size) . '</strong>,<strong>' . Formatter::getShortDescriptionAttribute($productItem->order_color) . '</strong></div>';
            }

            if (!(strpos($productItem->product_image, 'http') !== false)) {
                $productItem->product_image = env('APP_URL') . $productItem->product_image;
            }

            $productsHtml = '<div style="margin-top: 5px;display: flex;gap: 10px;"><div style="flex: 1;"><img style="height: 40px;" src="' . $productItem->product_image . '"></div><div style="flex: 5;"><div>' . $productItem->name . '</div>' . $productAttributeHtml . '</div></div>';

            $table .= "<tr><td>" . $productsHtml . "</td><td style='text-align: center;'>{$productItem->quantity}</td></tr>";
        }

        $table .= "</tbody>";
        $table .= "</table>";

        $html .= $table;
        $html .= "<div style='margin-top: 10px;'>Hãy truy cập <a href='" . route('administrator.orders.index') . "'>" . route('administrator.orders.index') . "</a> để kiểm tra đơn hàng!</div>";

        Helper::sendEmailToShop('Đơn hàng mới!', $html);

        $item->refresh();

        return response()->json($item);
    }

    public function storeNotAuth(Request $request)
    {
        $request->validate([
            'quantities' => 'required|array|min:1',
            "quantities.*" => "required|numeric|min:1",
            'product_ids' => 'required|array|min:1',
            "product_ids.*" => "required|numeric|min:1",
            "name" => "required",
            "phone" => "required",
            "address" => "required",
        ]);

        if (count($request->quantities) != count($request->product_ids)) {
            return Helper::errorAPI(99,[],"2 mảng phải bằng nhau");
        }

        DB::beginTransaction();

        $item = $this->model->create([
            'user_id' => 0,
        ]);

        foreach ($request->product_ids as $index => $product_id) {

            $product = Product::find($product_id);

            if (empty($product)) continue;

            $orderProduct = OrderProduct::create([
                'order_id' => $item->id,
                'product_id' => $product->id,
                'quantity' => $request->quantities[$index],
                'price' => $product->priceByUser(),
                'name' => $product->name,
                'product_image' => $product->avatar(),
            ]);

            $orderProduct->fill(['order_size' => $product->size, 'order_color' => $product->color])->save();
        }

        DB::commit();

        $html = "<p>Thông tin khách hàng</p>";
        $html .= "<div>Họ và tên: " . $request->name . "</div>";
        $html .= "<div>Số điện thoại: " . $request->phone . "</div>";
        $html .= "<div>Địa chỉ: " . $request->address . "</div>";

        $html .= "<p>Danh sách đơn hàng</p>";

        $table = "<table style='width: 100%;border: solid;'>";
        $table .= "<thead><tr><th style='border: 1px solid;'>Sản phẩm</th><th style='border: 1px solid;'>Số lượng</th></tr></thead>";
        $table .= "<tbody>";
        foreach ($item->products as $productItem) {

            $productAttributeHtml = "";

            if (!empty($productItem->order_size) || !empty($productItem->order_color)) {
                $productAttributeHtml = '<div>Phân loại:<strong>' . Formatter::getShortDescriptionAttribute($productItem->order_size) . '</strong>,<strong>' . Formatter::getShortDescriptionAttribute($productItem->order_color) . '</strong></div>';
            }

            if (!(strpos($productItem->product_image, 'http') !== false)) {
                $productItem->product_image = env('APP_URL') . $productItem->product_image;
            }

            $productsHtml = '<div style="margin-top: 5px;display: flex;gap: 10px;"><div style="flex: 1;"><img style="height: 40px;" src="' . $productItem->product_image . '"></div><div style="flex: 5;"><div>' . $productItem->name . '</div>' . $productAttributeHtml . '</div></div>';

            $table .= "<tr><td>" . $productsHtml . "</td><td style='text-align: center;'>{$productItem->quantity}</td></tr>";
        }

        $table .= "</tbody>";
        $table .= "</table>";

        $html .= $table;
        $html .= "<div style='margin-top: 10px;'>Hãy truy cập <a href='" . route('administrator.orders.index') . "'>" . route('administrator.orders.index') . "</a> để kiểm tra đơn hàng!</div>";

        Helper::sendEmailToShop('Đơn hàng mới!', $html);

        $item->refresh();

        return response()->json($item);
    }

}
