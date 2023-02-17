<?php

namespace App\Console\Commands;

use App\Models\Helper;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class JobNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:job_notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $sendAll = [];

        $currentHour = (int)(date('H')) + 0;
        $dayOfWeek = (int)date('w');

        if ($currentHour >= 24) {
            $currentHour -= 24;
            $dayOfWeek++;
        }
        $nowTime = $currentHour . ':' . date('i') . ":00";

        $nowTime = Carbon::parse($nowTime);
//        $nowTime = $nowTime->addMinutes(6);

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
            Helper::sendNotificationToTopic(env('FIREBASE_TOPIC_ALL_N1','app'), $item['title'], $item['description']);
        }

        Helper::sendNotificationToTopic(env('FIREBASE_TOPIC_ALL_N1','app'), "hello", "abcd");

    }
}
