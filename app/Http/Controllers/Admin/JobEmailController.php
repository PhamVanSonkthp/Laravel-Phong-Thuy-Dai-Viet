<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ModelExport;
use App\Http\Controllers\Controller;
use App\Models\JobEmail;
use App\Traits\BaseControllerTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use function view;

class JobEmailController extends Controller
{
    use BaseControllerTrait;

    public function __construct(JobEmail $model)
    {
        $this->initBaseModel($model);
        $this->shareBaseModel($model);
    }

    public function index(Request $request)
    {
        $queries = ['with_trashed' => true];
        $items = $this->model->searchByQuery($request, $queries);

        return view('administrator.' . $this->prefixView . '.index', compact('items'));
    }

    public function get(Request $request, $id)
    {
        return $this->model->findById($id);
    }

    public function create()
    {
        return view('administrator.' . $this->prefixView . '.add');
    }

    public function store(Request $request)
    {
        foreach ($request->user_ids as $id) {
            $request->id = $id;
            $this->model->storeByQuery($request);
        }

        return back();
    }

    public function edit($id)
    {
        $item = $this->model->find($id);
        return view('administrator.' . $this->prefixView . '.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = $this->model->updateByQuery($request, $id);
        return redirect()->route('administrator.' . $this->prefixView . '.edit', ['id' => $id]);
    }

    public function delete(Request $request, $id)
    {
        $this->forceDelete = true;
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
