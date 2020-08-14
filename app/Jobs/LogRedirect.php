<?php

namespace App\Jobs;

use App\RedirectLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogRedirect implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $device,$browser,$ip,$short_url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($device,$browser,$ip,$short_url)
    {
        $this->device = $device;
        $this->browser = $browser;
        $this->ip = $ip;
        $this->short_url = $short_url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $redirectLog = new RedirectLog();
        $redirectLog->device = $this->device;
        $redirectLog->browser = $this->browser;
        $redirectLog->ip = $this->ip;
        $redirectLog->short_url = $this->short_url;
        $redirectLog->save();
    }
}
