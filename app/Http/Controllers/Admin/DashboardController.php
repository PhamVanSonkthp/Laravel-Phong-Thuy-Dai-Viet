<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use function auth;
use function view;

class DashboardController extends Controller
{
    public function index(){
        if(auth()->check()){
            return view('administrator.dashboard.index');
        }
        return redirect()->to('/admin');
    }
}
