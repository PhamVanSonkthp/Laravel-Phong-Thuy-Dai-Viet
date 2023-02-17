<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParticipantChat;
use App\Models\Role;
use App\Models\User;
use App\Traits\DeleteModelTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use function view;

class ChatController extends Controller
{

    use DeleteModelTrait;

    private $user;
    private $role;

    private $prefixView;
    private $prefixExport;
    private $title;

    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;

        $this->prefixView = "chat";
        $this->prefixExport = "Chat_" . date('Y-m-d H:i:s');
        $this->title = "Chat";

        View::share('title', $this->title);
    }

    public function index(Request $request)
    {
        $query = ParticipantChat::where('user_id' , auth()->id());

        $items = $query->latest()->paginate(10)->appends(request()->query());

        return view('administrator.'.$this->prefixView.'.index', compact('items'));
    }

    public function delete($id)
    {
        return $this->deleteModelTrait($id, $this->user);
    }

}
