<?php

use App\Models\BadStarCalendar;
use App\Models\Calendar;
use App\Models\Category;
use App\Models\DayZodiacCalendar;
use App\Models\FiveElementCalendar;
use App\Models\Formatter;
use App\Models\GioLyThuanPhongCalendar;
use App\Models\GoodStarCalendar;
use App\Models\Helper;
use App\Models\Image;
use App\Models\LunaCalendar;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\SunCalendar;
use App\Models\ThapNhiBatTuDayCalendar;
use App\Models\TimeCalendar;
use App\Models\TimeZodiacCalendar;
use App\Models\TongHopBangKeCalendar;
use App\Models\TrucDayCalendar;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpWord\IOFactory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/assets/{type}/{user_id}/{id}/{size}/{slug}', [
    'uses' => 'App\Http\Controllers\ImagesController@show',
]);

Route::prefix('/')->group(function () {
//    Route::get('/', [
//        'as' => 'welcome.index',
//        'uses' => 'App\Http\Controllers\User\UserController@index',
//    ]);

//    Route::get('/ttt', function () {
//        $product = \App\Models\Product::find(124);
//        $product->fill(['size' => "67CM", 'color' => 'Vàng'])->save();
//    });


//    Route::get('/quot', function () {
//
//        $path = "C:\Game\quot.xlsx";
//
//        $reader = ReaderEntityFactory::createReaderFromFile($path);
//
//        $reader->open($path);
//
//        foreach ($reader->getSheetIterator() as $sheet) {
//            foreach ($sheet->getRowIterator() as $index => $row) {
//                // do stuff with the row
//
//                if ($index > 0) {
//                    $cells = $row->getCells();
//
//                    Quotation::create([
//                        'description' => Formatter::trimer($cells[1]->getValue()),
//                        'author' => Formatter::trimer($cells[2]->getValue()),
//                    ]);
//
//                }
//            }
//        }
//        $reader->close();
//
//        dd('ok');
//    });

//    Route::get('/abc', function () {
//
//        $max = Quotation::count();
//
//        foreach (Calendar::all() as $item){
//            $item->update([
//                'quotation_id' => rand(6074, 6074 + $max)
//            ]);
//        }
//        dd('123');
//    });
//
//    Route::get('/product', function () {
//
////        app('rinvex.attributes.attribute')->create([
////            'slug' => 'size',
////            'type' => 'varchar',
////            'name' => ['vi' => 'Kích thước', 'en' => 'size'],
////            'entities' => ['App\Models\Product'],
////        ]);
////
////        app('rinvex.attributes.attribute')->create([
////            'slug' => 'color',
////            'type' => 'varchar',
////            'name' => ['vi' => 'Màu sắc', 'en' => 'color'],
////            'entities' => ['App\Models\Product'],
////        ]);
//
//        // Mass assignment
////        $product = \App\Models\Product::find(6);
////        $product->fill(['size' => 123, 'color' => 'Đỏ'])->save();
//
//        set_time_limit(36000);
//
//        $path = "C:\Game\product.xlsx";
//
//        $reader = ReaderEntityFactory::createReaderFromFile($path);
//
//        $reader->open($path);
//
//        $slug = "";
//        $name = "";
//        $category = "";
//        $description = "";
//        $group_product_id = 2;
//        $images = [];
//        $products = [];
//
//        Image::where('table', 'products')->delete();
//
//        foreach ($reader->getSheetIterator() as $sheet) {
//            foreach ($sheet->getRowIterator() as $index => $row) {
//                // do stuff with the row
//
//                if ($index > 1) {
//                    $cells = $row->getCells();
//
//                    $images[] = [
//                        'path' => Formatter::trimer($cells[23]->getValue()),
//                        'name' => Formatter::trimer($cells[24]->getValue()),
//                    ];
//
//                    if ($cells[18]->getValue() == "") continue;
//
//                    $item = [];
//                    $item['slug'] = Formatter::trimer($cells[0]->getValue());
//
//                    $item['name'] = Formatter::trimer($cells[1]->getValue());
//                    if (empty($item['name'])) {
//                        $item['name'] = $name;
//                    } else {
//                        $name = $item['name'];
//                    }
//
//                    $item['category_id'] = Formatter::trimer($cells[4]->getValue());
//                    if (empty($item['category_id'])) {
//                        $item['category_id'] = $category;
//                    } else {
//                        $category = $item['category_id'];
//                    }
//
//                    if (!empty($category)) {
//                        $itemCategory = Category::firstOrCreate([
//                            'name' => $category
//                        ], [
//                            'name' => $category,
//                            'slug' => Helper::addSlug(new Category, 'slug', $category),
//                        ]);
//                        $item['category_id'] = $itemCategory->id;
//                    } else {
//                        $item['category_id'] = 0;
//                    }
//
//                    $item['description'] = Formatter::trimer($cells[2]->getValue());
//                    if (empty($item['description'])) {
//                        $item['description'] = $description;
//                    } else {
//                        $description = $item['description'];
//                    }
//
//                    $shortDescription = Formatter::getShortDescriptionAttribute($description, 30);
//
//                    $item['short_description'] = $shortDescription;
//                    $item['product_visibility_id'] = Formatter::trimer($cells[6]->getValue()) == 'TRUE' ? 2 : 1;
//                    $item['sku'] = Formatter::trimer($cells[13]->getValue());
//                    $item['inventory'] = Formatter::formatNumberToDatabase(Formatter::trimer($cells[15]->getValue()));
//                    $item['product_buy_empty_id'] = Formatter::trimer($cells[16]->getValue()) == 'continue' ? 2 : 1;
//                    $item['price_import'] = Formatter::formatNumberToDatabase(Formatter::trimer($cells[18]->getValue()));
//                    $item['price_client'] = Formatter::formatNumberToDatabase(Formatter::trimer($cells[18]->getValue()));
//                    $item['price_agent'] = Formatter::formatNumberToDatabase(Formatter::trimer($cells[18]->getValue()));
//                    $item['price_partner'] = Formatter::formatNumberToDatabase(Formatter::trimer($cells[18]->getValue()));
//                    $item['request_devilvery_id'] = Formatter::trimer($cells[20]->getValue()) == 'TRUE' ? 2 : 1;
//                    $item['bar_code'] = Formatter::trimer($cells[22]->getValue());
//                    $item['feature_image_path'] = Formatter::trimer($cells[23]->getValue());
//                    $item['seo_title'] = Formatter::trimer($cells[25]->getValue());
//                    $item['seo_description'] = Formatter::trimer($cells[26]->getValue());
//                    $item['weight'] = Formatter::trimer($cells[27]->getValue());
//                    $item['type_weight'] = Formatter::trimer($cells[28]->getValue());
//
//                    if ($item['slug'] != $slug) {
//                        $slug = Formatter::trimer($cells[0]->getValue());
//
//                        foreach ($products as $productItem){
//                            foreach ($images as $itemImage){
//                                Image::create([
//                                    'uuid' => Helper::randomString(),
//                                    'table' => 'products',
//                                    'image_path' => $itemImage['path'],
//                                    'image_name' => $itemImage['name'],
//                                    'relate_id' => $productItem->id,
//                                ]);
//                            }
//                        }
//                        $products = [];
//                        $images = [];
//                        $group_product_id++;
//                    }
//
//                    $item['group_product_id'] = $group_product_id;
//
//                    $product = Product::create($item);
//
//                    $products[] = $product;
//
//                    $attr1 = Formatter::trimer($cells[8]->getValue());
//                    $attr2 = Formatter::trimer($cells[10]->getValue());
//
//                    $attr = [];
//
//                    if (!empty($attr1)) {
//                        $attr['size'] = $attr1;
//                    }
//
//                    if (!empty($attr2)) {
//                        $attr['color'] = $attr2;
//                    }
//                    $product->fill($attr)->save();
//                }
//            }
//        }
//
//        $reader->close();
//
//    });
//
//    Route::get('/calendar', function () {
//
//        set_time_limit(36000);
//        $path = "C:/Game/new_8_2calendar.xlsx";
//
//        $reader = ReaderEntityFactory::createReaderFromFile($path);
//
//        $reader->open($path);
//        $calendarId = 0;
//        foreach ($reader->getSheetIterator() as $sheet) {
//            foreach ($sheet->getRowIterator() as $index => $row) {
//                // do stuff with the row
//
//                $item = [];
//                $sunCalendar = [];
//                $lunaCalendar = [];
//                $timeCalendars = [];
//                $timeZodiacCalendars = [];
//                $goodStarCalendars = [];
//                $badStarCalendars = [];
//                $gioLyThuanPhongCalendars = [];
//
//                if ($index > 2) {
//                    $cells = $row->getCells();
//
//
//                    $calendarId++;
//
////                    $item['weekdays'] = Formatter::trimer($cells[1]->getValue());
////                    $item['weather'] = Formatter::trimer($cells[30]->getValue());
////                    $item['score'] = Formatter::trimer($cells[31]->getValue());
////                    $item['quotation_id'] = 1;
////                    $item['note'] =  Formatter::trimer($cells[107]->getValue());
////                    $item['description'] =  Formatter::trimer($cells[107]->getValue());
////
////                    $item = Calendar::create($item);
////
////                    $calendarId = $item->id;
////
////                    $sunCalendar['date'] = Formatter::trimer($cells[2]->getValue());
////                    $sunCalendar['day_of_month'] = Formatter::trimer($cells[3]->getValue());
////                    $sunCalendar['month'] = Formatter::trimer($cells[4]->getValue());
////                    $sunCalendar['year'] = Formatter::trimer($cells[5]->getValue());
////                    $sunCalendar['calendar_id'] = $calendarId;
////
////                    SunCalendar::create($sunCalendar);
////
////                    $lunaCalendar['date'] = Formatter::convertDateVNToEng(Formatter::trimer($cells[6]->getValue()));
////                    $lunaCalendar['day_of_month'] = Formatter::trimer($cells[7]->getValue());
////                    $lunaCalendar['month'] = Formatter::trimer($cells[8]->getValue());
////                    $lunaCalendar['year'] = Formatter::trimer($cells[9]->getValue());
////                    $lunaCalendar['can_day'] = Formatter::trimer($cells[10]->getValue());
////                    $lunaCalendar['chi_day'] = Formatter::trimer($cells[11]->getValue());
////                    $lunaCalendar['luna_day'] = Formatter::trimer($cells[12]->getValue());
////                    $lunaCalendar['can_month'] = Formatter::trimer($cells[13]->getValue());
////                    $lunaCalendar['chi_month'] = Formatter::trimer($cells[14]->getValue());
////                    $lunaCalendar['can_year'] = Formatter::trimer($cells[15]->getValue());
////                    $lunaCalendar['chi_year'] = Formatter::trimer($cells[16]->getValue());
////                    $lunaCalendar['text_year'] = Formatter::trimer($cells[17]->getValue());
////                    $lunaCalendar['calendar_id'] = $calendarId;
////
////                    LunaCalendar::create($lunaCalendar);
////
////                    $timeCalendars[] = [
////                        'min_hour' => 23,
////                        'max_hour' => 1,
////                        'name' => 'Tý',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[18]->getValue()),
////                    ];
////                    $timeCalendars[] = [
////                        'min_hour' => 1,
////                        'max_hour' => 3,
////                        'name' => 'Sửu',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[19]->getValue()),
////                    ];
////                    $timeCalendars[] = [
////                        'min_hour' => 3,
////                        'max_hour' => 5,
////                        'name' => 'Dần',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[20]->getValue()),
////                    ];
////                    $timeCalendars[] = [
////                        'min_hour' => 5,
////                        'max_hour' => 7,
////                        'name' => 'Mão',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[21]->getValue()),
////                    ];
////                    $timeCalendars[] = [
////                        'min_hour' => 7,
////                        'max_hour' => 9,
////                        'name' => 'Thìn',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[22]->getValue()),
////                    ];
////                    $timeCalendars[] = [
////                        'min_hour' => 9,
////                        'max_hour' => 11,
////                        'name' => 'Tỵ',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[23]->getValue()),
////                    ];
////                    $timeCalendars[] = [
////                        'min_hour' => 11,
////                        'max_hour' => 13,
////                        'name' => 'Ngọ',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[24]->getValue()),
////                    ];
////                    $timeCalendars[] = [
////                        'min_hour' => 13,
////                        'max_hour' => 15,
////                        'name' => 'Mùi',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[25]->getValue()),
////                    ];
////                    $timeCalendars[] = [
////                        'min_hour' => 15,
////                        'max_hour' => 17,
////                        'name' => 'Thân',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[26]->getValue()),
////                    ];
////                    $timeCalendars[] = [
////                        'min_hour' => 17,
////                        'max_hour' => 19,
////                        'name' => 'Dậu',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[27]->getValue()),
////                    ];
////                    $timeCalendars[] = [
////                        'min_hour' => 19,
////                        'max_hour' => 21,
////                        'name' => 'Tuất',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[28]->getValue()),
////                    ];
////                    $timeCalendars[] = [
////                        'min_hour' => 21,
////                        'max_hour' => 23,
////                        'name' => 'Hợi',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[29]->getValue()),
////                    ];
////
////                    foreach ($timeCalendars as $timeCalendarItem) {
////                        TimeCalendar::create($timeCalendarItem);
////                    }
////
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 23,
////                        'max_hour' => 1,
////                        'name' => 'Giờ hoàng đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[32]->getValue()),
////                    ];
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 1,
////                        'max_hour' => 3,
////                        'name' => 'Giờ hoàng đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[33]->getValue()),
////                    ];
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 3,
////                        'max_hour' => 5,
////                        'name' => 'Giờ hoàng đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[34]->getValue()),
////                    ];
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 5,
////                        'max_hour' => 7,
////                        'name' => 'Giờ hoàng đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[35]->getValue()),
////                    ];
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 7,
////                        'max_hour' => 9,
////                        'name' => 'Giờ hoàng đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[36]->getValue()),
////                    ];
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 9,
////                        'max_hour' => 11,
////                        'name' => 'Giờ hoàng đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[37]->getValue()),
////                    ];
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 11,
////                        'max_hour' => 13,
////                        'name' => 'Giờ hắc đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[38]->getValue()),
////                    ];
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 13,
////                        'max_hour' => 15,
////                        'name' => 'Giờ hắc đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[39]->getValue()),
////                    ];
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 15,
////                        'max_hour' => 17,
////                        'name' => 'Giờ hắc đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[40]->getValue()),
////                    ];
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 17,
////                        'max_hour' => 19,
////                        'name' => 'Giờ hắc đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[41]->getValue()),
////                    ];
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 19,
////                        'max_hour' => 21,
////                        'name' => 'Giờ hắc đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[42]->getValue()),
////                    ];
////                    $timeZodiacCalendars[] = [
////                        'min_hour' => 21,
////                        'max_hour' => 23,
////                        'name' => 'Giờ hắc đạo',
////                        'calendar_id' => $calendarId,
////                        'description' => Formatter::trimer($cells[43]->getValue()),
////                    ];
////                    foreach ($timeZodiacCalendars as $timeZodiacCalendarItem) {
////                        TimeZodiacCalendar::create($timeZodiacCalendarItem);
////                    }
////
////                    $fiveElementCalendar = [
////                        'cat_hung_day' => Formatter::trimer($cells[44]->getValue()),
////                        'nap_am_day' => Formatter::trimer($cells[45]->getValue()),
////                        'ngu_hanh_day' => Formatter::trimer($cells[46]->getValue()),
////                        'hop_day' => Formatter::trimer($cells[47]->getValue()),
////                        'khac_day' => Formatter::trimer($cells[48]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////                    FiveElementCalendar::create($fiveElementCalendar);
////
////                    $dayZodiacCalendar = [
////                        'name' => Formatter::trimer($cells[49]->getValue()),
////                        'status' => Formatter::trimer($cells[50]->getValue()),
////                        'description' => Formatter::trimer($cells[51]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////                    DayZodiacCalendar::create($dayZodiacCalendar);
////
////                    $trucDayCalendar = [
////                        'name' => Formatter::trimer($cells[52]->getValue()),
////                        'should_do' => Formatter::trimer($cells[53]->getValue()),
////                        'should_not_do' => Formatter::trimer($cells[54]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////                    TrucDayCalendar::create($trucDayCalendar);
////
////                    $thapNhiBatTuDayCalendar = [
////                        'star' => Formatter::trimer($cells[55]->getValue()),
////                        'status' => Formatter::trimer($cells[56]->getValue()),
////                        'should_do' => Formatter::trimer($cells[57]->getValue()),
////                        'should_not_do' => Formatter::trimer($cells[58]->getValue()),
////                        'description' => Formatter::trimer($cells[59]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    ThapNhiBatTuDayCalendar::create($thapNhiBatTuDayCalendar);
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[60]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[61]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[62]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[63]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[64]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[65]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[66]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[67]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[68]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[69]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[70]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[71]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[72]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[73]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[74]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $goodStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[75]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    foreach ($goodStarCalendars as $goodStarCalendarItem) {
////                        if (!empty($goodStarCalendarItem['description'])) {
////                            GoodStarCalendar::create($goodStarCalendarItem);
////                        }
////                    }
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[76]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[77]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[78]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[79]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[80]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[81]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[82]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[83]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[84]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[85]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[86]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[87]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[88]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[89]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[90]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[91]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    $badStarCalendars[] = [
////                        'description' => Formatter::trimer($cells[92]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    foreach ($badStarCalendars as $badStarCalendarItem) {
////                        if (!empty($badStarCalendarItem['description'])) {
////                            BadStarCalendar::create($badStarCalendarItem);
////                        }
////                    }
////
////                    $tongHopBangkeCalendar = [
////                        'good_star' => Formatter::trimer($cells[93]->getValue()),
////                        'bad_star' => Formatter::trimer($cells[94]->getValue()),
////                        'calendar_id' => $calendarId,
////                    ];
////
////                    TongHopBangKeCalendar::create($tongHopBangkeCalendar);
//
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 23,
//                        'max_hour' => 1,
//                        'name' => 'Tý',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[95]->getValue()),
//                    ];
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 1,
//                        'max_hour' => 3,
//                        'name' => 'Sửu',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[96]->getValue()),
//                    ];
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 3,
//                        'max_hour' => 5,
//                        'name' => 'Dần',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[97]->getValue()),
//                    ];
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 5,
//                        'max_hour' => 7,
//                        'name' => 'Mão',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[98]->getValue()),
//                    ];
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 7,
//                        'max_hour' => 9,
//                        'name' => 'Thìn',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[99]->getValue()),
//                    ];
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 9,
//                        'max_hour' => 11,
//                        'name' => 'Tỵ',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[100]->getValue()),
//                    ];
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 11,
//                        'max_hour' => 13,
//                        'name' => 'Ngọ',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[101]->getValue()),
//                    ];
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 13,
//                        'max_hour' => 15,
//                        'name' => 'Mùi',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[102]->getValue()),
//                    ];
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 15,
//                        'max_hour' => 17,
//                        'name' => 'Thân',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[103]->getValue()),
//                    ];
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 17,
//                        'max_hour' => 19,
//                        'name' => 'Dậu',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[104]->getValue()),
//                    ];
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 19,
//                        'max_hour' => 21,
//                        'name' => 'Tuất',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[105]->getValue()),
//                    ];
//                    $gioLyThuanPhongCalendars[] = [
//                        'min_hour' => 21,
//                        'max_hour' => 23,
//                        'name' => 'Hợi',
//                        'calendar_id' => $calendarId,
//                        'description' => Formatter::trimer($cells[106]->getValue()),
//                    ];
//
//                    foreach ($gioLyThuanPhongCalendars as $gioLyThuanPhongCalendarItem) {
//                        if (!empty($gioLyThuanPhongCalendarItem['description'])) {
//                            GioLyThuanPhongCalendar::create($gioLyThuanPhongCalendarItem);
//                        }
//                    }
//
//                }
//
//            }
//
//        }
//
//        $reader->close();
//
//    });

});
