<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ModelExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Formatter;
use App\Models\Helper;
use App\Models\Image;
use App\Models\Product;
use App\Traits\BaseControllerTrait;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
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

    public function import(Request $request)
    {
        set_time_limit(36000);

        $path = storage_path() . '/app/' . request()->file('import_file')->store('tmp');


        $reader = ReaderEntityFactory::createReaderFromFile($path);

        $reader->open($path);

        $slug = "";
        $name = "";
        $category = "";
        $description = "";
        $group_product_id = 2;
        $images = [];
        $products = [];

        $productAdded = 0;

        $tmpProduct = Product::latest()->first();

        if (!empty($tmpProduct)){
            $group_product_id = $tmpProduct->group_product_id;
            $group_product_id++;
        }

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $index => $row) {
                if ($index > 1) {
                    $cells = $row->getCells();

                    if (count($cells) == 0 || $cells[0]->getValue() == ""){
                        return response()->json($productAdded);
                    }

                    $images[] = [
                        'path' => Formatter::trimer($cells[23]->getValue()),
                        'name' => Formatter::trimer($cells[24]->getValue()),
                    ];

                    if ($cells[18]->getValue() == "") continue;

                    $item = [];
                    $item['slug'] = Formatter::trimer($cells[0]->getValue());

                    $item['name'] = Formatter::trimer($cells[1]->getValue());
                    if (empty($item['name'])) {
                        $item['name'] = $name;
                    } else {
                        $name = $item['name'];
                    }

                    $item['category_id'] = Formatter::trimer($cells[4]->getValue());
                    if (empty($item['category_id'])) {
                        $item['category_id'] = $category;
                    } else {
                        $category = $item['category_id'];
                    }

                    if (!empty($category)) {
                        $itemCategory = Category::firstOrCreate([
                            'name' => $category
                        ], [
                            'name' => $category,
                            'slug' => Helper::addSlug(new Category, 'slug', $category),
                        ]);
                        $item['category_id'] = $itemCategory->id;
                    } else {
                        $item['category_id'] = 0;
                    }

                    $item['description'] = Formatter::trimer($cells[2]->getValue());
                    if (empty($item['description'])) {
                        $item['description'] = $description;
                    } else {
                        $description = $item['description'];
                    }

                    $shortDescription = Formatter::getShortDescriptionAttribute($description, 30);

                    $item['short_description'] = $shortDescription;
                    $item['product_visibility_id'] = Formatter::trimer($cells[6]->getValue()) == 'TRUE' ? 2 : 1;
                    $item['sku'] = Formatter::trimer($cells[13]->getValue());
                    $item['inventory'] = Formatter::formatNumberToDatabase(Formatter::trimer($cells[15]->getValue()));
                    $item['product_buy_empty_id'] = Formatter::trimer($cells[16]->getValue()) == 'continue' ? 2 : 1;
                    $item['price_import'] = Formatter::formatNumberToDatabase(Formatter::trimer($cells[18]->getValue()));
                    $item['price_client'] = Formatter::formatNumberToDatabase(Formatter::trimer($cells[18]->getValue()));
                    $item['price_agent'] = Formatter::formatNumberToDatabase(Formatter::trimer($cells[18]->getValue()));
                    $item['price_partner'] = Formatter::formatNumberToDatabase(Formatter::trimer($cells[18]->getValue()));
                    $item['request_devilvery_id'] = Formatter::trimer($cells[20]->getValue()) == 'TRUE' ? 2 : 1;
                    $item['bar_code'] = Formatter::trimer($cells[22]->getValue());
                    $item['feature_image_path'] = Formatter::trimer($cells[23]->getValue());
                    $item['seo_title'] = Formatter::trimer($cells[25]->getValue());
                    $item['seo_description'] = Formatter::trimer($cells[26]->getValue());
                    $item['weight'] = Formatter::trimer($cells[27]->getValue());
                    $item['type_weight'] = Formatter::trimer($cells[28]->getValue());

                    if ($item['slug'] != $slug) {
                        $slug = Formatter::trimer($cells[0]->getValue());

                        foreach ($products as $productItem){
                            foreach ($images as $itemImage){
                                Image::create([
                                    'uuid' => Helper::randomString(),
                                    'table' => 'products',
                                    'image_path' => $itemImage['path'],
                                    'image_name' => $itemImage['name'],
                                    'relate_id' => $productItem->id,
                                ]);
                            }
                        }
                        $products = [];
                        $images = [];
                        $group_product_id++;
                    }

                    $item['group_product_id'] = $group_product_id;

                    $product = Product::create($item);

                    $products[] = $product;

                    $attr1 = Formatter::trimer($cells[8]->getValue());
                    $attr2 = Formatter::trimer($cells[10]->getValue());

                    $attr = [];

                    if (!empty($attr1)) {
                        $attr['size'] = $attr1;
                    }

                    if (!empty($attr2)) {
                        $attr['color'] = $attr2;
                    }
                    $product->fill($attr)->save();

                    $productAdded++;
                }
            }
        }

        return response()->json($productAdded);
    }
}
