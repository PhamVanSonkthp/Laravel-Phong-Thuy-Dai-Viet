<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ModelExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleAddRequest;
use App\Http\Requests\RoleEditRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\BaseControllerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use function redirect;
use function view;

class RoleController extends Controller
{
    use BaseControllerTrait;

    private $model;
    private $premission;
    private $roles;

    public function __construct(Role $model, Permission $premission)
    {
        $this->model = $model;
        $this->models = Role::all();
        $this->premission = $premission;

        $this->initBaseModel($model);
        $this->isSingleImage = false;
        $this->isMultipleImages = false;
        $this->shareBaseModel($model);

        View::share('title', $this->title);
        View::share('roles', $this->models);
    }

    public function index(Request $request){
        $items = $this->model->searchByQuery($request);
        $premissionsParent = $this->premission->where('parent_id' , 0)->orderBy('display_name')->get();

        return view('administrator.'.$this->prefixView.'.index', compact('items','premissionsParent'));
    }

    public function get(Request $request, $id)
    {
        return $this->model->findById($id);
    }

    public function create(){
        $premissionsParent = $this->premission->where('parent_id' , 0)->orderBy('display_name')->get();
        return view('administrator.'.$this->prefixView.'.add' , compact('premissionsParent'));
    }

    public function store(RoleAddRequest $request){
        $role = $this->model->create([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);
        $role->permissions()->attach($request->permission_id);
        return redirect()->route('administrator.roles.index');
    }

    public function edit($id){
        $premissionsParent = $this->premission->where('parent_id' , 0)->orderBy('display_name')->get();
        $role = $this->model->find($id);
        $permissionsChecked = $role->permissions;
        return view('administrator.'.$this->prefixView.'.edit' , compact('premissionsParent'  , 'role' , 'permissionsChecked'));
    }

    public function update($id , RoleEditRequest $request){
        $this->model->find($id)->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        $role = $this->model->find($id);

        $role->permissions()->sync($request->permission_id);
        return back();
    }

    public function delete($id){
        return $this->deleteModelTrait($id, $this->model);
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
