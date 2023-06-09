<?php

namespace App\Exports;

use App\Models\Formatter;
use App\Models\Helper;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{

    private $request;
    private $model;

    public function __construct($model, $request)
    {
        $this->request = $request;
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $items = $this->model->search($this->request);

        $itemsExport = [];

        foreach ($items as $index => $item) {

            $imagesAdded = [];

            $images = $item->images;

            $image_path = $item->avatar('original');

            if (!empty($image_path)){
                if (!str_contains($image_path, "http")){
                    $image_path = env("APP_URL") . $image_path;
                }
            }else{
                $image_path = str_contains($item->feature_image_path , "http") ? $item->feature_image_path : env("APP_URL") . $item->feature_image_path;
            }

            $imagesAdded[] = $image_path;

            $itemTemp = [
                Str::slug($item->name),
                $item->name,
                $item->description,
                "",
                optional($item->category)->name,
                "",
                $item->product_visibility_id == 2 ? "TRUE" : "",
                $item->product_visibility_id == 2 ? "Title" : "",
                "",
                "",
                "",
                "",
                "",
                $item->sku,
                "",
                $item->inventory,
                $item->product_buy_empty_id == 2 ? "" : 'deny',
                "",
                $item->price_client,
                $item->price_agent,
                $item->price_partner,
                "",
                $item->request_devilvery_id == 1 ? 'FALSE' : "TRUE",
                $item->vat_id ? "FALSE" : "TRUE",
                $item->bar_code,
                $image_path,
                "",
                "",
                "",
                $item->weight,
                $item->type_weight,
                $image_path,
                $item->description,
                $item->primary_id,
                $item->second_id,
            ];

            $itemsExport[] = $itemTemp;

            if($item->isProductVariation()){
                $jumped = 0;

                foreach($item->attributes() as $key => $itemAttribute){
                    $jumped = $key + 1;

                    $productAtt = Product::find($itemAttribute['id']);

                    $image_path = str_contains($productAtt->feature_image_path , "http") ? $productAtt->feature_image_path : env("APP_URL") . optional($productAtt)->feature_image_path;

                    if ($key + 1 < count($images)){
                        $image_path = $images[$key + 1]->image_path;

                        if (!str_contains($image_path , "http")){
                            $image_path = env("APP_URL") . $image_path;
                        }
                    }

                    if (in_array($image_path, $imagesAdded)){
                        $image_path = "";
                    }else{
                        $imagesAdded[] = $image_path;
                    }

                    $itemTemp = [
                        Str::slug($item->name),
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "Title",
                        $itemAttribute['size'] ? $itemAttribute['size'] : "",
                        $itemAttribute['color'] ? "Title" : '',
                        $itemAttribute['color'],
                        "",
                        optional($productAtt)->sku,
                        "",
                        Formatter::formatNumber($itemAttribute['inventory']),
                        optional($productAtt)->product_buy_empty_id == 2 ? "" : 'deny',
                        "",
                        optional($productAtt)->price_client,
                        optional($productAtt)->price_agent,
                        optional($productAtt)->price_partner,
                        "",
                        optional($productAtt)->request_devilvery_id == 1 ? 'FALSE' : "TRUE",
                        optional($productAtt) ? "FALSE" : "TRUE",
                        optional($productAtt)->bar_code,
                        $image_path,
                        "",
                        "",
                        "",
                        optional($productAtt)->weight,
                        optional($productAtt)->type_weight,
                        $image_path,
                        optional($productAtt)->description,
                        optional($productAtt)->primary_id,
                        optional($productAtt)->second_id,
                    ];
                    $itemsExport[] = $itemTemp;

                }

                foreach ($images as $key => $image){

                    if ($jumped >= $key) continue;

                    $image_path = str_contains($image->image_path , "http") ? $image->image_path : env("APP_URL") . $image->image_path;

                    if (in_array($image_path, $imagesAdded)){
                        $itemTemp = [
                            Str::slug($item->name),
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            $image_path,
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            "",
                            $item->primary_id,
                            "",
                        ];

                        $itemsExport[] = $itemTemp;
                    }
                }

            }else{
                foreach ($images as $key => $image){

                    if ($key == 0) continue;

                    $image_path = str_contains($image->image_path , "http") ? $image->image_path : env("APP_URL") . $image->image_path;

                    if (in_array($image_path, $imagesAdded)){
                        $image_path = "";
                    }else{
                        $imagesAdded[] = $image_path;
                    }

                    $itemTemp = [
                        Str::slug($item->name),
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        $image_path,
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        $item->primary_id,
                        "",
                    ];

                    $itemsExport[] = $itemTemp;
                }
            }


        }

        return collect($itemsExport);
    }

    public function headings(): array
    {
        $headings = [
            ['Dường dẫn / Alias','Tên sản phẩm','Nội dung'
                ,'Nhà cung cấp','Loại','Tags','Hiển thị','Thuộc tính 1(Option1 Name)'
                ,'Giá trị thuộc tính 1(Option1 Value)'
                ,'Thuộc tính 2(Option2 Name)','Giá trị thuộc tính 2(Option2 Value)'
                ,'Thuộc tính 3(Option3 Name)','Giá trị thuộc tính 1(Option3 Value)'
                ,'Mã (SKU)','Quản lý kho','Số lượng','Cho phép tiếp tục mua khi hết hàng(continue/deny)'
                ,'Variant Fulfillment Service','Giá','Giá bán buôn'
                ,'Giá CTV','Giá so sánh','Yêu cầu vận chuyển'
                ,'VAT','Mã vạch(Barcode)','Ảnh đại diện'
                ,'Chú thích ảnh','Thẻ tiêu đề(SEO Title)','Thẻ mô tả(SEO Description)'
                ,'Cân nặng','Đơn vị cân nặng','Ảnh phiên bản','Mô tả ngắn','Id sản phẩm','Id tùy chọn']
        ];

        return $headings;
    }

}
