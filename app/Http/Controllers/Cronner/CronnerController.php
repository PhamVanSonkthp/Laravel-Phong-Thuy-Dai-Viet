<?php

namespace App\Http\Controllers\Cronner;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Formatter;
use App\Models\Helper;
use App\Models\SunCalendar;
use App\Models\User;
use App\Notifications\Notifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CronnerController extends Controller
{
    public function __construct()
    {
//        $this->middleware('XSS');
    }

    public function callback()
    {
        $jobEmails = \App\Models\JobEmail::whereDate('time_send' , '<=', now())->limit(env('MAXIMUM_SEND_EMAIL_ONE_MINUTE', 10))->get();

        foreach ($jobEmails as $jobEmail) {
            if (!empty($jobEmail->user) && filter_var($jobEmail->user->email, FILTER_VALIDATE_EMAIL)){
                $jobEmail->user->notify(new Notifications($jobEmail->title, $jobEmail->content));
            }
            $jobEmail->delete();
        }

        ///////----///////

        $sendAll = [];

        $currentHour = (int)(date('H')) + 0;
        $dayOfWeek = (int)date('w');

        if ($currentHour >= 24) {
            $currentHour -= 24;
            $dayOfWeek++;
        }

        $nowTime = $currentHour . ':' . date('i') . ":00";

        $nowTime = Carbon::parse($nowTime);
//        $nowTime = $nowTime->addHours(5);
//        $nowTime = $nowTime->addMinutes(30);

        if (false && $nowTime->toTimeString() == "09:00:00"){
            $item = SunCalendar::whereDate('date', date('Y-m-d',strtotime($nowTime)))->first();
            if (!empty($item)) {
                $item = Calendar::find($item->calendar_id);
                if (!empty($item)){
                    Helper::sendNotificationToTopic(env('FIREBASE_TOPIC_ALL_N1','app'), "Thời tiết: ". $item->weather, trim(optional($item->quotation)->description));
                }
            }
        }

        $resultsCron = \App\Models\JobNotification::where('time', $nowTime)->where('notiable', 1)->get();

        $resultCron = [];

        foreach ($resultsCron as $item) {

            foreach ($item->scheduleCronRepeats as $scheduleRepeatItem) {
                if ($scheduleRepeatItem->day_of_week == $dayOfWeek) {
                    if (!$scheduleRepeatItem->sent || $item->repeat) {
                        // send $item->user_id

                        if ($item->userScheduleCron->count() == 0) {
                            $sendAll[] = [
                                'title' => $item->title,
                                'description' => $item->description,
                                'app_id' => $item->app_id,
                            ];

                            $scheduleRepeatItem->update([
                                'sent' => true
                            ]);

                        } else {
                            foreach ($item->userScheduleCron as $itemUserScheduleCron) {
                                if (!empty(optional($itemUserScheduleCron->user)->id)) {
                                    $resultCron[] = [
                                        'topic' => optional($item->user)->id,
                                        'title' => $item->title,
                                        'description' => $item->description,
                                        'user_id' => optional($item->user)->id,
                                    ];
                                    //
                                    $scheduleRepeatItem->update([
                                        'sent' => true
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($resultCron as $item) {
            Helper::sendNotificationToTopic($item['topic'], $item['title'], $item['description'], true, $item->user_id);
        }

        foreach ($sendAll as $item) {
            if ($item['app_id'] == 0){
                Helper::sendNotificationToTopic(env('FIREBASE_TOPIC_ALL_N1','app'), $item['title'], $item['description']);
            }else if ($item['app_id'] == 1){
                Helper::sendNotificationToTopic(env('FIREBASE_TOPIC_ALL_N2','app'), $item['title'], $item['description']);
            }else if ($item['app_id'] == 2){
                Helper::sendNotificationToTopic(env('FIREBASE_TOPIC_ALL_N3','app'), $item['title'], $item['description']);
            }

        }

        return response()->json([
            'emails' => $jobEmails,
            'users' => $resultCron,
            'all' => $sendAll,
            'nowTime' => Formatter::getDateTime($nowTime),
        ]);
    }
}
