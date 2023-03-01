<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ModelExport;
use App\Http\Controllers\Controller;
use App\Imports\CalendarImport;
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
use App\Traits\BaseControllerTrait;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Monolog\Handler\IFTTTHandler;
use function redirect;
use function view;

class CalendarsController extends Controller
{
    use BaseControllerTrait;

    public function __construct(Calendar $model)
    {
        $this->initBaseModel($model);
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

    public function import(Request $request)
    {
        set_time_limit(36000);

        $path = storage_path() . '/app/' . request()->file('import_file')->store('tmp');

        $reader = ReaderEntityFactory::createReaderFromFile($path);

        $reader->open($path);
        $calendarId = 0;
        $itemAdded = 0;



        $quotation_id_min = optional(Quotation::first())->id ?? 1;
        $quotation_id_max = $quotation_id_min + Quotation::count();

        foreach ($reader->getSheetIterator() as $sheet) foreach ($sheet->getRowIterator() as $index => $row) {
            // do stuff with the row

            $item = [];
            $sunCalendar = [];
            $lunaCalendar = [];
            $timeCalendars = [];
            $timeZodiacCalendars = [];
            $goodStarCalendars = [];
            $badStarCalendars = [];
            $gioLyThuanPhongCalendars = [];

            if ($index > 2) {
                $cells = $row->getCells();

                if (count($cells) < 100) break;

                $itemAdded++;

                $item['weekdays'] = Formatter::trimer($cells[1]->getValue());
                $item['weather'] = Formatter::trimer($cells[30]->getValue());
                $item['score'] = Formatter::trimer($cells[31]->getValue());
                $item['quotation_id'] = rand($quotation_id_min, $quotation_id_max);
                $item['note'] =  Formatter::trimer($cells[107]->getValue());
                $item['description'] =  Formatter::trimer($cells[107]->getValue());

                $item = Calendar::create($item);

                $calendarId = $item->id;

                $sunCalendar['date'] = $cells[2]->getValue();
                $sunCalendar['day_of_month'] = Formatter::trimer($cells[3]->getValue());
                $sunCalendar['month'] = Formatter::trimer($cells[4]->getValue());
                $sunCalendar['year'] = Formatter::trimer($cells[5]->getValue());
                $sunCalendar['calendar_id'] = $calendarId;

                $sunCalendarFind = SunCalendar::where('date', ($cells[2]->getValue()))->first();

                if (!empty($sunCalendarFind)){
                    optional(Calendar::find($sunCalendarFind->calendar_id))->delete();
                    $sunCalendarFind->delete();
                }

                SunCalendar::create($sunCalendar);

                $lunaCalendar['date'] = Formatter::convertDateVNToEng(Formatter::trimer($cells[6]->getValue()));
                $lunaCalendar['day_of_month'] = Formatter::trimer($cells[7]->getValue());
                $lunaCalendar['month'] = Formatter::trimer($cells[8]->getValue());
                $lunaCalendar['year'] = Formatter::trimer($cells[9]->getValue());
                $lunaCalendar['can_day'] = Formatter::trimer($cells[10]->getValue());
                $lunaCalendar['chi_day'] = Formatter::trimer($cells[11]->getValue());
                $lunaCalendar['luna_day'] = Formatter::trimer($cells[12]->getValue());
                $lunaCalendar['can_month'] = Formatter::trimer($cells[13]->getValue());
                $lunaCalendar['chi_month'] = Formatter::trimer($cells[14]->getValue());
                $lunaCalendar['can_year'] = Formatter::trimer($cells[15]->getValue());
                $lunaCalendar['chi_year'] = Formatter::trimer($cells[16]->getValue());
                $lunaCalendar['text_year'] = Formatter::trimer($cells[17]->getValue());
                $lunaCalendar['calendar_id'] = $calendarId;

                LunaCalendar::create($lunaCalendar);

                $timeCalendars[] = [
                    'min_hour' => 23,
                    'max_hour' => 1,
                    'name' => 'Tý',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[18]->getValue()),
                ];
                $timeCalendars[] = [
                    'min_hour' => 1,
                    'max_hour' => 3,
                    'name' => 'Sửu',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[19]->getValue()),
                ];
                $timeCalendars[] = [
                    'min_hour' => 3,
                    'max_hour' => 5,
                    'name' => 'Dần',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[20]->getValue()),
                ];
                $timeCalendars[] = [
                    'min_hour' => 5,
                    'max_hour' => 7,
                    'name' => 'Mão',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[21]->getValue()),
                ];
                $timeCalendars[] = [
                    'min_hour' => 7,
                    'max_hour' => 9,
                    'name' => 'Thìn',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[22]->getValue()),
                ];
                $timeCalendars[] = [
                    'min_hour' => 9,
                    'max_hour' => 11,
                    'name' => 'Tỵ',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[23]->getValue()),
                ];
                $timeCalendars[] = [
                    'min_hour' => 11,
                    'max_hour' => 13,
                    'name' => 'Ngọ',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[24]->getValue()),
                ];
                $timeCalendars[] = [
                    'min_hour' => 13,
                    'max_hour' => 15,
                    'name' => 'Mùi',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[25]->getValue()),
                ];
                $timeCalendars[] = [
                    'min_hour' => 15,
                    'max_hour' => 17,
                    'name' => 'Thân',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[26]->getValue()),
                ];
                $timeCalendars[] = [
                    'min_hour' => 17,
                    'max_hour' => 19,
                    'name' => 'Dậu',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[27]->getValue()),
                ];
                $timeCalendars[] = [
                    'min_hour' => 19,
                    'max_hour' => 21,
                    'name' => 'Tuất',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[28]->getValue()),
                ];
                $timeCalendars[] = [
                    'min_hour' => 21,
                    'max_hour' => 23,
                    'name' => 'Hợi',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[29]->getValue()),
                ];

                foreach ($timeCalendars as $timeCalendarItem) {
                    TimeCalendar::create($timeCalendarItem);
                }

                $timeZodiacCalendars[] = [
                    'min_hour' => 23,
                    'max_hour' => 1,
                    'name' => 'Giờ hoàng đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[32]->getValue()),
                ];
                $timeZodiacCalendars[] = [
                    'min_hour' => 1,
                    'max_hour' => 3,
                    'name' => 'Giờ hoàng đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[33]->getValue()),
                ];
                $timeZodiacCalendars[] = [
                    'min_hour' => 3,
                    'max_hour' => 5,
                    'name' => 'Giờ hoàng đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[34]->getValue()),
                ];
                $timeZodiacCalendars[] = [
                    'min_hour' => 5,
                    'max_hour' => 7,
                    'name' => 'Giờ hoàng đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[35]->getValue()),
                ];
                $timeZodiacCalendars[] = [
                    'min_hour' => 7,
                    'max_hour' => 9,
                    'name' => 'Giờ hoàng đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[36]->getValue()),
                ];
                $timeZodiacCalendars[] = [
                    'min_hour' => 9,
                    'max_hour' => 11,
                    'name' => 'Giờ hoàng đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[37]->getValue()),
                ];
                $timeZodiacCalendars[] = [
                    'min_hour' => 11,
                    'max_hour' => 13,
                    'name' => 'Giờ hắc đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[38]->getValue()),
                ];
                $timeZodiacCalendars[] = [
                    'min_hour' => 13,
                    'max_hour' => 15,
                    'name' => 'Giờ hắc đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[39]->getValue()),
                ];
                $timeZodiacCalendars[] = [
                    'min_hour' => 15,
                    'max_hour' => 17,
                    'name' => 'Giờ hắc đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[40]->getValue()),
                ];
                $timeZodiacCalendars[] = [
                    'min_hour' => 17,
                    'max_hour' => 19,
                    'name' => 'Giờ hắc đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[41]->getValue()),
                ];
                $timeZodiacCalendars[] = [
                    'min_hour' => 19,
                    'max_hour' => 21,
                    'name' => 'Giờ hắc đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[42]->getValue()),
                ];
                $timeZodiacCalendars[] = [
                    'min_hour' => 21,
                    'max_hour' => 23,
                    'name' => 'Giờ hắc đạo',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[43]->getValue()),
                ];
                foreach ($timeZodiacCalendars as $timeZodiacCalendarItem) {
                    TimeZodiacCalendar::create($timeZodiacCalendarItem);
                }

                $fiveElementCalendar = [
                    'cat_hung_day' => Formatter::trimer($cells[44]->getValue()),
                    'nap_am_day' => Formatter::trimer($cells[45]->getValue()),
                    'ngu_hanh_day' => Formatter::trimer($cells[46]->getValue()),
                    'hop_day' => Formatter::trimer($cells[47]->getValue()),
                    'khac_day' => Formatter::trimer($cells[48]->getValue()),
                    'calendar_id' => $calendarId,
                ];
                FiveElementCalendar::create($fiveElementCalendar);

                $dayZodiacCalendar = [
                    'name' => Formatter::trimer($cells[49]->getValue()),
                    'status' => Formatter::trimer($cells[50]->getValue()),
                    'description' => Formatter::trimer($cells[51]->getValue()),
                    'calendar_id' => $calendarId,
                ];
                DayZodiacCalendar::create($dayZodiacCalendar);

                $trucDayCalendar = [
                    'name' => Formatter::trimer($cells[52]->getValue()),
                    'should_do' => Formatter::trimer($cells[53]->getValue()),
                    'should_not_do' => Formatter::trimer($cells[54]->getValue()),
                    'calendar_id' => $calendarId,
                ];
                TrucDayCalendar::create($trucDayCalendar);

                $thapNhiBatTuDayCalendar = [
                    'star' => Formatter::trimer($cells[55]->getValue()),
                    'status' => Formatter::trimer($cells[56]->getValue()),
                    'should_do' => Formatter::trimer($cells[57]->getValue()),
                    'should_not_do' => Formatter::trimer($cells[58]->getValue()),
                    'description' => Formatter::trimer($cells[59]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                ThapNhiBatTuDayCalendar::create($thapNhiBatTuDayCalendar);

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[60]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[61]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[62]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[63]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[64]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[65]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[66]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[67]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[68]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[69]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[70]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[71]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[72]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[73]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[74]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $goodStarCalendars[] = [
                    'description' => Formatter::trimer($cells[75]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                foreach ($goodStarCalendars as $goodStarCalendarItem) {
                    if (!empty($goodStarCalendarItem['description'])) {
                        GoodStarCalendar::create($goodStarCalendarItem);
                    }
                }

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[76]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[77]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[78]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[79]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[80]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[81]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[82]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[83]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[84]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[85]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[86]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[87]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[88]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[89]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[90]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[91]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                $badStarCalendars[] = [
                    'description' => Formatter::trimer($cells[92]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                foreach ($badStarCalendars as $badStarCalendarItem) {
                    if (!empty($badStarCalendarItem['description'])) {
                        BadStarCalendar::create($badStarCalendarItem);
                    }
                }

                $tongHopBangkeCalendar = [
                    'good_star' => Formatter::trimer($cells[93]->getValue()),
                    'bad_star' => Formatter::trimer($cells[94]->getValue()),
                    'calendar_id' => $calendarId,
                ];

                TongHopBangKeCalendar::create($tongHopBangkeCalendar);

                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 23,
                    'max_hour' => 1,
                    'name' => 'Tý',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[95]->getValue()),
                ];
                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 1,
                    'max_hour' => 3,
                    'name' => 'Sửu',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[96]->getValue()),
                ];
                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 3,
                    'max_hour' => 5,
                    'name' => 'Dần',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[97]->getValue()),
                ];
                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 5,
                    'max_hour' => 7,
                    'name' => 'Mão',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[98]->getValue()),
                ];
                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 7,
                    'max_hour' => 9,
                    'name' => 'Thìn',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[99]->getValue()),
                ];
                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 9,
                    'max_hour' => 11,
                    'name' => 'Tỵ',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[100]->getValue()),
                ];
                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 11,
                    'max_hour' => 13,
                    'name' => 'Ngọ',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[101]->getValue()),
                ];
                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 13,
                    'max_hour' => 15,
                    'name' => 'Mùi',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[102]->getValue()),
                ];
                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 15,
                    'max_hour' => 17,
                    'name' => 'Thân',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[103]->getValue()),
                ];
                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 17,
                    'max_hour' => 19,
                    'name' => 'Dậu',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[104]->getValue()),
                ];
                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 19,
                    'max_hour' => 21,
                    'name' => 'Tuất',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[105]->getValue()),
                ];
                $gioLyThuanPhongCalendars[] = [
                    'min_hour' => 21,
                    'max_hour' => 23,
                    'name' => 'Hợi',
                    'calendar_id' => $calendarId,
                    'description' => Formatter::trimer($cells[106]->getValue()),
                ];

                foreach ($gioLyThuanPhongCalendars as $gioLyThuanPhongCalendarItem) {
                    if (!empty($gioLyThuanPhongCalendarItem['description'])) {
                        GioLyThuanPhongCalendar::create($gioLyThuanPhongCalendarItem);
                    }
                }

            }

        }

        $reader->close();

        return response()->json($itemAdded);
    }
}
