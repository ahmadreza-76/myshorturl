<?php

namespace App\Console\Commands;

use App\Analytic;
use App\RedirectLog;
use App\Services\AnalyticService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class HourlyAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytic:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule to run every hour and update today analytics';

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
     * @return mixed
     */
    public function handle()
    {
        //find urls that have a request in last hour
        $changed_urls = RedirectLog::select('short_url')->whereDate('created_at',Carbon::today())
            ->where('state',0)->distinct()->pluck('short_url');
        //change those url states to 0
        RedirectLog::where('state',0)->update(['state' => 1]);

        //loop over changed urls
        foreach ($changed_urls as $short_url){

            $today = Analytic::where('short_url',$short_url)->where('type','today')->first();

            //get redirect logs of changed urls and update analytics
            $logs = RedirectLog::where('created_at','>=',Carbon::today()->toDateTimeString())
                ->where('short_url',$short_url)->get();

            //update analytic based on logs
            $today = AnalyticService::update($logs,$today);

            $today->save();

        }


    }
}
