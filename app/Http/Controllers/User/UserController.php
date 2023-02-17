<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function view;

class UserController extends Controller
{
    public function index(Request $request){
        return view('user.home.index');
    }

}
