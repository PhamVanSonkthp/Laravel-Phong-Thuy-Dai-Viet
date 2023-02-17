<?php

namespace App\Console\Commands;

use App\Notifications\Notifications;
use Illuminate\Console\Command;

class JobEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:job_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to user';

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
        $jobEmails = \App\Models\JobEmail::whereDate('time_send' , '<=', now())->limit(env('MAXIMUM_SEND_EMAIL_ONE_MINUTE', 10))->get();

        foreach ($jobEmails as $jobEmail) {
            if (!empty($jobEmail->user)){
                $jobEmail->user->notify(new Notifications($jobEmail->title, $jobEmail->content, $jobEmail->user));
            }
            $jobEmail->delete();
        }
    }
}
