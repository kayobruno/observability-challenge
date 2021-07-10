<?php

namespace App\Providers;

use App\Models\Metric;
use App\Observers\MetricObserver;
use App\Services\AlertDatabaseService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AlertDatabaseService::class, function ($app) {
            return new AlertDatabaseService(
                env('DB_HOST', 'localhost'),
                env('DB_DATABASE', 'laravel'),
                env('DB_USERNAME', 'root'),
                env('DB_PASSWORD', 'root')
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Metric::observe(MetricObserver::class);
    }
}
