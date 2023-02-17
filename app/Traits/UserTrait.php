<?php

namespace App\Traits;

use App\Models\Invoice;
use App\Models\Level;
use App\Models\Notification;
use App\Models\ProductOfUser;
use App\Models\Trading;
use App\Models\TradingOfUser;
use App\Models\User;
use App\Models\UserGift;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UserTrait{

    public function getUserLevelTrait($id){
        $user = User::find($id);
        if(empty($user)) return 0;

        $levels = Level::orderBy('point_require', 'desc')->get();

        foreach ($levels as $level){
            if($user->point >= $level->point_require) return $level->level;
        }

        return 0;
    }

    public function getUserNumberProductTrait($id){
        return ProductOfUser::where('user_id' , $id)->get()->count();
    }

    public function getUserNumberTradingTrait($id){
        return TradingOfUser::where('user_id' , $id)->get()->count();
    }

    public function getUserHasGiftTrait($user_id, $level_id){
        return UserGift::where('user_id' , $user_id)->where('level_id' , $level_id)->first();
    }

    public function getUserNotificationTrait($id){
        return Notification::where('notifiable_id' , $id)->whereNull('read_at')->get();
    }
}
