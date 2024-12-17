<?php
// phpcs:ignoreFile
namespace Pensoft\AwtLaravelLog;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Pensoft\AwtLaravelLog\LogElasticsearchService;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge the configuration into the app
        $this->mergeConfigFrom(
            __DIR__ . '/../config/logging.php', 'logging.channels'
        );

        $this->app->singleton(LogElasticsearchService::class, function ($app) {
            return new LogElasticsearchService();
        });
    }
}
