<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        if(config('app.env') == 'production'){
            /* View::composer('app', function ($view) {
                $result = \DB::table('wp_options')->select('option_name', 'option_value')->where('option_name', 'jupiterx')->first();
                $data = unserialize($result->option_value);

                $script_head = stripslashes($data['tracking_codes_before_head']) ?? '';
                $script_body = stripslashes($data['tracking_codes_after_body']) ?? '';

                $view->with([
                    'google_script_head' => $script_head,
                    'google_script_body' => $script_body
                ]);
            }); */
        }
    }
}
