<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Chat;
use App\Models\ChatGroup;
use App\Models\Formatter;
use App\Models\News;
use App\Models\ParticipantChat;
use App\Models\Product;
use App\Models\RestfulAPI;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NewsController extends Controller
{
    private $modelNew;

    public function __construct(News $new)
    {
        $this->modelNew = $new;
    }

    public function list(Request $request)
    {

        $results = RestfulAPI::response($this->modelNew, $request);

        foreach ($results as $item){
            $item->category;
        }

        return response()->json($results);
    }

    public function get(Request $request, $id)
    {
        $item = $this->modelNew->findOrFail($id);
        return response()->json($item);
    }
}
