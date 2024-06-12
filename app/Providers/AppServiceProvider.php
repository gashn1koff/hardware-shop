<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response;

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

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(200)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response('Too many requests.', Response::HTTP_TOO_MANY_REQUESTS, $headers);
                });
        });
    }
}
