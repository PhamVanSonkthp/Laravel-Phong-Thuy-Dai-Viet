<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Category;
use App\Models\CategoryNew;
use App\Models\Chat;
use App\Models\ChatGroup;
use App\Models\Formatter;
use App\Models\ParticipantChat;
use App\Models\Product;
use App\Models\RestfulAPI;
use App\Models\SunCalendar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CalendarController extends Controller
{

    private $model;

    public function __construct(Calendar $model)
    {
        $this->model = $model;
    }

    public function get(Request $request, $id)
    {
//        $item = $this->model->find($id);

        $item = SunCalendar::whereDate('date', $id)->first();

        if (empty($item)) return abort(404);

        $item = $this->model->find($item->calendar_id);

        return response()->json($item);
    }
}
