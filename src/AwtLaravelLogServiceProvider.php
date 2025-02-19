<?php
// phpcs:ignoreFile
namespace Pensoft\AwtLaravelLog;

use Pensoft\AwtLaravelLog\LogElasticsearchService;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Formatter\ElasticsearchFormatter;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;


class AwtLaravelLogServiceProvider extends ServiceProvider
{
    protected $channel = 'elasticsearch';

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/elastic.php' => config_path('elastic.php'),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function ($app) {
            $config = config('elastic');
            return ClientBuilder::create()
                ->setHosts($config['host'])
                ->setSSLVerification($config['ssl_verification'])
                ->setBasicAuthentication(
                    $config['username'],
                    $config['password'],
                )
                ->build();
        });

        $this->app->bind(ElasticsearchFormatter::class, function ($app) {
            $config = config('elastic');
            return new ElasticsearchFormatter($config['index'], $config['type']);
        });

        $this->app->bind(ElasticsearchHandler::class, function ($app) {
            $config = config('elastic');
            return new ElasticsearchHandler($app->make(Client::class), [
                'index'        => $config['index'],
                'type'         => $config['type'],
                'ignore_error' => false,
            ]);
        });

        $this->app->singleton(LogElasticsearchService::class, function ($app) {
            $channel = env('LOGGING_CHANNEL_ELASTIC', 'elastic');
            $client = $app->make(Client::class);
            return new LogElasticsearchService($channel, $client);
        });
    }
}
