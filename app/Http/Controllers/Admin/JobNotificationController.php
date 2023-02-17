<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ModelExport;
use App\Http\Controllers\Controller;
use App\Models\JobNotification;
use App\Models\JobNotificationRepeat;
use App\Models\UserJobNotification;
use App\Traits\BaseControllerTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use function view;

class JobNotificationController extends Controller
{
    use BaseControllerTrait;

    public function __construct(JobNotification $model)
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
        $item = $this->model->findById($id);
        $item['schedule_repeats'] = $item->scheduleCronRepeats;

        $users = [];

        foreach ($item->userScheduleCron as $itemUserScheduleCron){
            $users[] = $itemUserScheduleCron->user;
        }

        $item['users'] = $users;
        return $item;
    }

    public function create()
    {
        return view('administrator.' . $this->prefixView . '.add');
    }

    public function store(Request $request)
    {

        $request->validate([
            'time' => 'required|date_format:H:i',
            'notiable' => 'bool',
        ]);

//        $item = $this->model->storeByQuery($request);


        $item = JobNotification::create([
            'title' => $request->title,
            'description' => $request->description,
            'time' => $request->time,
            'repeat' => $request->repeat,
            'notiable' => $request->notiable,
        ]);

        if (is_array($request->days_of_week)){
            foreach ($request->days_of_week as $day_of_week){
                if ($day_of_week >= 0 && $day_of_week <= 6){
                    JobNotificationRepeat::create([
                        'job_notification_id' => $item->id,
                        'day_of_week' => $day_of_week,
                    ]);
                }
            }
        }

        if (is_array($request->user_id)){
            foreach ($request->user_id as $user_id){
                UserJobNotification::create([
                    'user_id' => $user_id,
                    'job_notification_id' => $item->id,
                ]);
            }
        }

        $item->refresh();
        $item['schedule_repeats'] = $item->scheduleCronRepeats;

        $users = [];

        foreach ($item->userScheduleCron as $itemUserScheduleCron){
            $users[] = $itemUserScheduleCron->user;
        }

        $item['users'] = $users;

        return response()->json($item);
    }

    public function edit($id)
    {
        $item = $this->model->find($id);
        return view('administrator.' . $this->prefixView . '.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = JobNotification::findOrFail($id);

        if ($request->has('time')){
            $dataUpdate['time'] = $request->time;
        }
        if ($request->has('title')){
            $dataUpdate['title'] = $request->title;
        }
        if ($request->has('notiable')){
            $dataUpdate['notiable'] = $request->notiable;
        }
        if ($request->has('repeat')){
            $dataUpdate['repeat'] = $request->repeat;
        }
        if ($request->has('description')){
            $dataUpdate['description'] = $request->description;
        }

        $item->update($dataUpdate);
        $item->refresh();

        $item->scheduleCronRepeats()->delete();
        $item->userScheduleCron()->delete();

        if (is_array($request->days_of_week)) {
            foreach ($request->days_of_week as $day_of_week) {
                if ($day_of_week >= 0 && $day_of_week <= 6) {
                    JobNotificationRepeat::create([
                        'job_notification_id' => $item->id,
                        'day_of_week' => $day_of_week,
                    ]);
                }
            }
        }

        if (is_array($request->user_id)){
            foreach ($request->user_id as $user_id){
                UserJobNotification::create([
                    'user_id' => $user_id,
                    'job_notification_id' => $item->id,
                ]);
            }
        }

        $item->refresh();
        $item['schedule_repeats'] = $item->scheduleCronRepeats;

        $users = [];

        foreach ($item->userScheduleCron as $itemUserScheduleCron){
            $users[] = $itemUserScheduleCron->user;
        }

        $item['users'] = $users;

        return response()->json($item);
    }

    public function delete(Request $request, $id)
    {
        return $this->model->deleteByQuery($request, $id);
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
