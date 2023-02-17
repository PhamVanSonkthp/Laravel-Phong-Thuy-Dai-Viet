<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ParticipantChat extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function adminCare(){
        return $this->hasOne(User::class,'id','admin_care_id');
    }

    public function chatGroup(){
        return $this->belongsTo(ChatGroup::class);
    }

    public function user(){
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function users(){
        $users = [];
        $participantChats = ParticipantChat::where('chat_group_id' , $this->chat_group_id)->where('user_id' , '!=',auth()->id())->get();
        foreach ($participantChats as $item){
            $item->user;
            optional($item->user)->role;
            $users[] = $item->user;
        }

        return $users;
    }

    public function chatGroupIdWithUser($user_id){

        if (empty($user_id)){
            return 0;
        }
        $participantChats = ParticipantChat::where('user_id', $user_id)->get();

        foreach ($participantChats as $item){
            $participantChatAuther = ParticipantChat::where('user_id', auth()->id())->get();
            foreach ($participantChatAuther as $chatAuther){
                if (!empty($chatAuther) && $chatAuther->chat_group_id == $item->chat_group_id){
                    return $chatAuther->chat_group_id;
                }
            }

        }

        return 0;
    }

    public function status(){
        return $this->hasOne(ParticipantChatStatus::class, 'id','status');
    }
}
