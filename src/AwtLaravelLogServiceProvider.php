<?php
// phpcs:ignoreFile
namespace Pensoft\AwtLaravelLog;

use Pensoft\AwtLaravelLog\LogElasticsearchService;
use Pensoft\AwtLaravelLog\Processors\DefaultContextProcessor;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Monolog\Logger;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Formatter\ElasticsearchFormatter;
use Elastic\Elasticsearch\ClientBuilder;

class AwtLaravelLogServiceProvider extends ServiceProvider
{
    protected $channel = 'elasticsearch';
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

        $config = config('logging.channels.elasticsearch.elastic');
        $client = ClientBuilder::create()
            ->setHosts($config['host'])
            ->setSSLVerification($config['ssl_verification'])
            ->setBasicAuthentication(
                $config['username'],
                $config['password'],
            )
            ->build();
        
        $this->app->extend('log', function(LogManager $logManager, $app) use ($client){
            $channel = $this->channel;
            $logManager->extend('monolog', function (Application $app, array $config) use ($channel, $client){
                $config_elastic = $config['elastic'];
                $index = $config_elastic['index'];
                $options = [
                    'index' => $index,
                    'ignore_error' => false,
                ];
                $level = Logger::toMonologLevel($config['level']);
                $handler = new ElasticsearchHandler($client, $options, $level);
                
                $formatter = new ElasticsearchFormatter($index, 'doc');
                $handler->setFormatter($formatter);
                $logger = new Logger($channel);
                $logger->pushHandler($handler);
                $logger->pushProcessor(new DefaultContextProcessor);
                return $logger;
            });
            return $logManager;

        });
        $channel = $this->channel;
        $this->app->singleton(LogElasticsearchService::class, function () use ($channel, $client) {
            return new LogElasticsearchService($channel, $client);
        });
    }
}
