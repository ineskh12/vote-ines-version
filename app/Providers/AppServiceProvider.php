<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Percentage;
use App\Models\NoteSetting;
use App\Models\Project;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $data_sidebar = array();
        if (Schema::hasTable('percentages')) {
            $somme = Percentage::where('status', 1)->sum('percentage') != null ? Percentage::where('status', 1)->sum('percentage') : 0;
            $percentages = Percentage::where(['status'=>1,'type'=>0])->get();
            $data_sidebar['somme'] = $somme;
            $data_sidebar['percentages'] = $percentages;
        }

        if (Schema::hasTable('notes_setting')) {
            $settings = NoteSetting::first();
            if(!$settings || $settings->somme == 0 ){
                $data_sidebar['setting_note'] = false;
            }
        }

        $data_sidebar['somme'] = 100;
        $data_sidebar['percentages'] = [];
        view()->share('data_sidebar', $data_sidebar);
    }
}
