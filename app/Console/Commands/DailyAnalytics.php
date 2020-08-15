<?php

namespace App\Console\Commands;

use App\Analytic;
use App\RedirectLog;
use App\Services\AnalyticService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytic:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule to run every day and update all analytics';

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
        //get urls that changed in last day
        $changed_urls_yesterday = RedirectLog::select('short_url')->whereDate('created_at',Carbon::yesterday())
            ->distinct()->pluck('short_url');

        //reset today analytics of all urls that viewed yesterday
        foreach ($changed_urls_yesterday as $short_url){
            $today = Analytic::where('short_url',$short_url)->where('type','today')->first();
            $today = AnalyticService::reset($today);
            $today->save();
        }

        //get urls that changed day before yesterday
        $changed_urls_two_days_ago = RedirectLog::select('short_url')->whereDate('created_at',Carbon::yesterday()->subDay())
            ->distinct()->pluck('short_url');

        //get urls that should check to update yesterday analytics
        $yesterday_updated_urls = $changed_urls_yesterday->merge($changed_urls_two_days_ago)->unique();
        foreach ($yesterday_updated_urls as $short_url){
            $yesterday = Analytic::where('short_url',$short_url)->where('type','yesterday')->first();
            //get redirect logs of changed urls and update analytics
            $logs = RedirectLog::whereDate('created_at',Carbon::yesterday())
                ->where('short_url',$short_url)->get();
            $yesterday = AnalyticService::update($logs,$yesterday);
            $yesterday->save();
        }

        //get urls that changed day before last week
        $changed_urls_day_before_last_week = RedirectLog::select('short_url')->whereDate('created_at',Carbon::yesterday()->subWeek())
            ->distinct()->pluck('short_url');

        //get urls that should check to update week analytics
        $last_week_updated_urls = $changed_urls_yesterday->merge($changed_urls_day_before_last_week)->unique();
        foreach ($last_week_updated_urls as $short_url){
            $week = Analytic::where('short_url',$short_url)->where('type','week')->first();
            //get redirect logs of changed urls and update analytics
            $logs = RedirectLog::where('created_at','>=',Carbon::today()->subWeek()->toDateTimeString())
                ->where('created_at','<',Carbon::today()->toDateTimeString())
                ->where('short_url',$short_url)->get();
            $week = AnalyticService::update($logs,$week);
            $week->save();
        }


        //get urls that changed day before last month
        $changed_urls_day_before_last_month = RedirectLog::select('short_url')->whereDate('created_at',Carbon::yesterday()->subMonth())
            ->distinct()->pluck('short_url');

        //get urls that should check to update month analytics
        $last_month_updated_urls = $changed_urls_yesterday->merge($changed_urls_day_before_last_month)->unique();
        foreach ($last_month_updated_urls as $short_url){
            $month = Analytic::where('short_url',$short_url)->where('type','month')->first();
            //get redirect logs of changed urls and update analytics
            $logs = RedirectLog::where('created_at','>=',Carbon::today()->subMonth()->toDateTimeString())
                ->where('created_at','<',Carbon::today()->toDateTimeString())
                ->where('short_url',$short_url)->get();
            $month = AnalyticService::update($logs,$month);
            $month->save();
        }

    }
}
