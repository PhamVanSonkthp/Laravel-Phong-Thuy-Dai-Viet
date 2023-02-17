<?php

namespace App\Traits;

use App\Models\Formatter;
use App\Models\Helper;
use App\Models\Image;
use App\Models\SingleImage;
use Illuminate\Support\Facades\View;

trait BaseControllerTrait{

    private $model;
    private $prefixView;
    private $prefixExport;
    private $title;
    private $table;
    private $relateImageTableId;
    private $imagePathSingple;
    private $imagePostUrl;
    private $imagesPath;
    private $imageMultiplePostUrl;
    private $imageMultipleDeleteUrl;
    private $imageMultipleSortUrl;
    private $forceDelete;
    private $isSingleImage;
    private $isMultipleImages;

    private $prefixViewApi;

    public function initBaseModel($model){
        $this->model = $model;
        $this->forceDelete = false;
        $this->isSingleImage = false;
        $this->isMultipleImages = true;
        $this->table = $this->model->getTableName();

        $this->prefixView = $this->table;
        $this->prefixViewApi = str_replace("_","-",$this->prefixView);
        $this->prefixExport = $this->prefixView . "_" . Formatter::getDateTime(now());
        $this->title = $this->prefixView;

        $this->relateImageTableId = Helper::getNextIdTable($this->table);

        $this->imagePathSingple = optional(SingleImage::where('relate_id', Helper::getNextIdTable($this->table))->where('table', $this->table)->first())->image_path;
        $this->imagePostUrl = route('ajax,administrator.upload_image.store');

        $this->imagesPath = Image::where('relate_id', Helper::getNextIdTable($this->table))->where('table', $this->table)->orderBy('index')->get();
        $this->imageMultiplePostUrl = route('ajax,administrator.upload_multiple_images.store');
        $this->imageMultipleDeleteUrl = route('ajax,administrator.upload_multiple_images.delete');
        $this->imageMultipleSortUrl = route('ajax,administrator.upload_multiple_images.sort');
    }

    public function shareBaseModel($model){

        View::share('title', $this->title);
        View::share('table', $this->table);
        View::share('prefixView', $this->prefixView);
        View::share('prefixViewApi', $this->prefixViewApi);
        View::share('relateImageTableId', $this->relateImageTableId);
        View::share('imagePathSingple', $this->imagePathSingple);
        View::share('imagePostUrl', $this->imagePostUrl);
        View::share('imagesPath', $this->imagesPath);
        View::share('imageMultiplePostUrl', $this->imageMultiplePostUrl);
        View::share('imageMultipleDeleteUrl', $this->imageMultipleDeleteUrl);
        View::share('imageMultipleSortUrl', $this->imageMultipleSortUrl);
        View::share('isSingleImage', $this->isSingleImage);
        View::share('isMultipleImages', $this->isMultipleImages);
    }

}
