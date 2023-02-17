<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ModelExport;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use App\Traits\BaseControllerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use function view;

class UserController extends Controller
{
    use BaseControllerTrait;

    public function __construct(User $model, Role $role)
    {
        $this->initBaseModel($model);
        $this->isSingleImage = true;
        $this->isMultipleImages = false;
        $this->shareBaseModel($model);
        $this->role = $role;
        $userTypes = UserType::all();
        View::share('userTypes', $userTypes);
    }

    public function index(Request $request)
    {
        $items = $this->model->searchByQuery($request, ['is_admin' => 0]);
        return view('administrator.' . $this->prefixView . '.index', compact('items'));
    }

    public function get(Request $request, $id)
    {
        return $this->model->findById($id);
    }

    public function create()
    {
        $roles = $this->role->all();
        return view('administrator.' . $this->prefixView . '.add', compact('roles'));
    }

    public function store(Request $request)
    {
        $item = $this->model->storeByQuery($request);
        return redirect()->route('administrator.users.index');
    }

    public function edit($id)
    {
        $item = $this->model->findById($id);
        return view('administrator.' . $this->prefixView . '.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = $this->model->updateByQuery($request, $id);
        return redirect()->route('administrator.users.index');
    }

    public function delete(Request $request, $id)
    {
        return $this->model->deleteByQuery($request, $id, $this->forceDelete);
    }

    public function deleteManyByIds(Request $request)
    {
        return $this->model->deleteManyByIds($request, $this->forceDelete);
    }

    public function export(Request $request)
    {
        return Excel::download(new ModelExport($this->model, $request), $this->prefixView . '.xlsx');
    }

}
