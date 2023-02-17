<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ModelExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Formatter;
use App\Models\Product;
use App\Traits\BaseControllerTrait;
use App\Traits\DeleteModelTrait;
use App\Traits\StorageImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use function redirect;
use function view;

class ProductController extends Controller
{
    use BaseControllerTrait;

    public function __construct(Product $model)
    {
        $this->initBaseModel($model);
        $categories = Category::all();
        $this->shareBaseModel($model);
        View::share('categories', $categories);
    }

    public function index(Request $request)
    {
        $query = $this->model->searchByQuery($request, ['product_visibility_id' => 2], null, null, true);

        if (isset($request->min_inventory) && strlen($request->min_inventory)){
            $query = $query->where('inventory' ,'>=', $request->min_inventory);
        }

        if (isset($request->min_inventory) && strlen($request->min_inventory)){
            $query = $query->where('inventory', '<=', $request->max_inventory);
        }

        $items = $query->latest()->paginate(Formatter::getLimitRequest($request->limit))->appends(request()->query());

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
        $this->model->storeByQuery($request);
        return redirect()->route('administrator.' . $this->prefixView . '.index');
    }

    public function edit($id)
    {
        $item = $this->model->find($id);
        return view('administrator.' . $this->prefixView . '.edit', compact('item'));
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
