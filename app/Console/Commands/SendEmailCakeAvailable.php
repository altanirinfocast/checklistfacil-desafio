<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendEmailCakeAvailableJob;

class SendEmailCakeAvailable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-notification:cake-available';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notify cake is available';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SendEmailCakeAvailableJob::dispatch();
    }
}
