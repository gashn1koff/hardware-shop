<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(!app()->isProduction());

        // when updating field that is not in "fillable"
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        // notify when query is long
        DB::whenQueryingForLongerThan(500, function () {
            // log
        });

        // TODO: request
    }
}
