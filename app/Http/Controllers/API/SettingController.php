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
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    private $modelNew;

    public function __construct(Setting $new)
    {
        $this->modelNew = $new;
    }

    public function get(Request $request)
    {
        $item = $this->modelNew->first();

        return response()->json($item);
    }
}
