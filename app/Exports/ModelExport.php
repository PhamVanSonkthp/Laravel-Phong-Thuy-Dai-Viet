<?php

namespace App\Exports;

use App\Models\Helper;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModelExport implements FromCollection, ShouldAutoSize
{

    private $request;
    private $model;
    private $queries;
    private $heading;
    private $approvedColums;

    public function __construct($model, $request, $queries = [], $heading = null, $approvedColums = null)
    {
        $this->request = $request;
        $this->model = $model;
        $this->queries = $queries;

        if (empty($heading)){
            $heading = Helper::getAllColumsOfTable($this->model);
        }

        $this->heading = $heading;
        $this->approvedColums = $approvedColums;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $items = Helper::searchAllByQuery($this->model, $this->request, $this->queries);


//        if (!empty($this->approvedColums)){
//            $itemsApproved = [];
//
//            foreach ($items as $key => $item){
//
//            }
//        }

        return collect($items);
    }

//    public function headings(): array
//    {
//        return $this->heading;
//    }

}
