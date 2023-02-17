<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ModelExport;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Traits\BaseControllerTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use function redirect;
use function view;

class SliderController extends Controller
{
    use BaseControllerTrait;

    public function __construct(Slider $model)
    {
        $this->initBaseModel($model);
        $this->isSingleImage = true;
        $this->isMultipleImages = false;
        $this->shareBaseModel($model);
    }

    public function index(Request $request)
    {
        $items = $this->model->searchByQuery($request);
        return view('administrator.'.$this->prefixView.'.index', compact('items'));
    }

    public function get(Request $request, $id)
    {
        return $this->model->findById($id);
    }

    public function create()
    {
        return view('administrator.'.$this->prefixView.'.add');
    }

    public function store(Request $request)
    {
        $this->model->storeByQuery($request);
        return redirect()->route('administrator.'.$this->prefixView.'.index');
    }

    public function edit($id)
    {
        $item = $this->model->find($id);
        return view('administrator.'.$this->prefixView.'.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $this->model->updateByQuery($request, $id);
        return back();
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
