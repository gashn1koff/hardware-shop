<?php

namespace App\Providers;

use App\Contracts\TelegramBotApiContract;
use App\Services\Telegram\TelegramBotApi;
use Carbon\CarbonInterval;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class AppServiceProvider extends ServiceProvider
{

    public array $bindings = [
        TelegramBotApiContract::class => TelegramBotApi::class,
    ];

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

        // when updating field that is not in "fillable"
        // more info in method
        Model::shouldBeStrict(!app()->isProduction());

        if (app()->isProduction()) {
            DB::listen(function (QueryExecuted $query) {
                if ($query->time > 1000) {
                    logger()
                        ->channel('telegram')
                        ->debug('Query is more than 1s, ' . $query->sql, $query->bindings);
                }
            });

            app(Kernel::class)->whenRequestLifecycleIsLongerThan(
                CarbonInterval::seconds(5),
                function (Request $request) {
                    logger()->channel('telegram')->debug('Too long request: ' . $request->url());
                }
            );
        }

        // notify when total amount of queries is long
        DB::whenQueryingForLongerThan(CarbonInterval::seconds(4), function (Connection $connection) {
            logger()->channel('telegram')->debug('Too long query: ' . $connection->query()->toSql());
        });

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(200)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response('Too many requests.', Response::HTTP_TOO_MANY_REQUESTS, $headers);
                });
        });

    }
}
