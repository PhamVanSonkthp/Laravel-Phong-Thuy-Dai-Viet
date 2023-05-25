<?php

namespace App\Exports;

use App\Models\Helper;
use App\Models\User;
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
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $items = $this->model->search($this->request);

        $itemsExport = [];

        foreach ($items as $index => $item) {

            $images = $item->images;

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
                $item->feature_image_path,
                "",
                "",
                "",
                $item->weight,
                $item->type_weight,
                $item->feature_image_path,
                $item->description,
                $item->primary_id,
                $item->second_id,
            ];

            $itemsExport[] = $itemTemp;

            if($item->isProductVariation()){
                foreach($item->attributes() as $key => $itemAttribute){

                    $productAtt = \App\Models\Product::find($itemAttribute['id']);
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
                        \App\Models\Formatter::formatNumber($itemAttribute['inventory']),
                        optional($productAtt)->product_buy_empty_id == 2 ? "" : 'deny',
                        "",
                        optional($productAtt)->price_client,
                        optional($productAtt)->price_agent,
                        optional($productAtt)->price_partner,
                        "",
                        optional($productAtt)->request_devilvery_id == 1 ? 'FALSE' : "TRUE",
                        optional($productAtt) ? "FALSE" : "TRUE",
                        optional($productAtt)->bar_code,
                        optional($productAtt)->feature_image_path,
                        "",
                        "",
                        "",
                        optional($productAtt)->weight,
                        optional($productAtt)->type_weight,
                        optional($productAtt)->feature_image_path,
                        optional($productAtt)->description,
                        optional($productAtt)->primary_id,
                        optional($productAtt)->second_id,
                    ];
                    $itemsExport[] = $itemTemp;

                }
            }else{
                foreach ($images as $image){
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
                        $image->image_path,
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
