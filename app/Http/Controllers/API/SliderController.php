<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Category;
use App\Models\CategoryNew;
use App\Models\Chat;
use App\Models\ChatGroup;
use App\Models\ParticipantChat;
use App\Models\Product;
use App\Models\RestfulAPI;
use App\Models\Slider;
use App\Models\SystemBranch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SliderController extends Controller
{

    private $model;

    public function __construct(Slider $model)
    {
        $this->model = $model;
    }

    public function list(Request $request)
    {
        $results = RestfulAPI::response($this->model, $request);
        return response()->json($results);
    }
}
