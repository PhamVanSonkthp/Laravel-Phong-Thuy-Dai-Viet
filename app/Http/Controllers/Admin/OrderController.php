<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ModelExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\BaseControllerTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use function redirect;
use function view;

class OrderController extends Controller
{
    use BaseControllerTrait;

    public function __construct(Order $model)
    {
        $this->initBaseModel($model);
        $this->isSingleImage = false;
        $this->isMultipleImages = false;
        $this->shareBaseModel($model);
    }

    public function index(Request $request)
    {
        $items = $this->model->searchByQuery($request);
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
        $item = $this->model->storeByQuery($request);
        return redirect()->route('administrator.' . $this->prefixView . '.edit', ["id" => $item->id]);
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

    public function updateToShipping(Request $request, $id)
    {
        $item = $this->model->find($id);
        $item->updateToShipping();
        $item->refresh();
        return response()->json($item);
    }
}
