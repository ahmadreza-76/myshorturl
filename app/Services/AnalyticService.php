<?php


namespace App\Services;


class AnalyticService
{

    public static function update($logs,$analytic) {

        $analytic->view_total = $logs->count();
        $analytic->unique_total = $logs->unique('ip')->count();
        $analytic->view_phone = $logs->where('device','mobile')->count();
        $analytic->unique_phone = $logs->unique('ip')->where('device','mobile')->count();
        $analytic->view_desktop = $logs->where('device','desktop')->count();
        $analytic->unique_desktop = $logs->unique('ip')->where('device','desktop')->count();
        $analytic->view_others = $logs->where('device','others')->count();
        $analytic->unique_others = $logs->unique('ip')->where('device','others')->count();
        $analytic->view_browser_chrome = $logs->where('browser','Chrome')->count();
        $analytic->unique_browser_chrome = $logs->unique('ip')->where('browser','Chrome')->count();
        $analytic->view_browser_firefox = $logs->where('browser','Firefox')->count();
        $analytic->unique_browser_firefox = $logs->unique('ip')->where('browser','Firefox')->count();
        $analytic->view_browser_edge = $logs->where('browser','Edge')->count();
        $analytic->unique_browser_edge = $logs->unique('ip')->where('browser','Edge')->count();
        $analytic->view_browser_ie = $logs->where('browser','IE')->count();
        $analytic->unique_browser_ie = $logs->unique('ip')->where('browser','IE')->count();
        $analytic->view_browser_safari = $logs->where('browser','Safari')->count();
        $analytic->unique_browser_safari = $logs->unique('ip')->where('browser','Safari')->count();
        $analytic->view_browser_opera = $logs->where('browser','Opera')->count();
        $analytic->unique_browser_opera = $logs->unique('ip')->where('browser','Opera')->count();
        $analytic->view_browser_others = $logs->whereNotIn('browser',['Opera','Safari','IE','Edge','Firefox','Chrome'])->count();
        $analytic->unique_browser_others = $logs->unique('ip')->whereNotIn('browser',['Opera','Safari','IE','Edge','Firefox','Chrome'])->count();
        return $analytic;
    }

    public static function reset($analytic) {
        $analytic->view_total = 0;
        $analytic->unique_total = 0;
        $analytic->view_phone = 0;
        $analytic->unique_phone = 0;
        $analytic->view_desktop = 0;
        $analytic->unique_desktop = 0;
        $analytic->view_others = 0;
        $analytic->unique_others = 0;
        $analytic->view_browser_chrome = 0;
        $analytic->unique_browser_chrome = 0;
        $analytic->view_browser_firefox = 0;
        $analytic->unique_browser_firefox = 0;
        $analytic->view_browser_edge = 0;
        $analytic->unique_browser_edge = 0;
        $analytic->view_browser_ie = 0;
        $analytic->unique_browser_ie = 0;
        $analytic->view_browser_safari = 0;
        $analytic->unique_browser_safari = 0;
        $analytic->view_browser_opera = 0;
        $analytic->unique_browser_opera = 0;
        $analytic->view_browser_others = 0;
        $analytic->unique_browser_others = 0;
        return $analytic;
    }

}
