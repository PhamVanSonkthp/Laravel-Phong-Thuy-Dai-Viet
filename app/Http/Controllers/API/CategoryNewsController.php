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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CategoryNewsController extends Controller
{

    private $modelCategoryNew;

    public function __construct(CategoryNew $categoryNew)
    {
        $this->modelCategoryNew = $categoryNew;
    }

    public function list(Request $request)
    {
        $results = RestfulAPI::response($this->modelCategoryNew, $request, null, null, null, true);

        $results = $results->orderBy('index', 'DESC')->paginate(Formatter::getLimitRequest(optional($request)->limit))->appends(request()->query());

        return response()->json($results);
    }
}
