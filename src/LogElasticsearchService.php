<?php

namespace Pensoft\AwtLaravelLog;

use Illuminate\Support\Facades\Log;
use Pensoft\AwtErrorFormatter\ErrorPayloadFormatter;
use Throwable;

/**
 * Class LogElasticsearchService
 *
 * A service class responsible for logging application events and data to an
 * Elasticsearch index. This class abstracts the process of interacting with
 * Elasticsearch, providing a simplified interface for storing and retrieving
 * log data. It handles the communication with the Elasticsearch server,
 * ensures proper data formatting, and provides functionality to push logs to
 * specific indices.
 */
class LogElasticsearchService
{
    protected $channel;
    protected $client;

    /**
     * Construct.
     * 
     * @param public $channel Channel.
     */
    public function __construct($channel, $client)
    {
        $this->channel = $channel;
        $this->client = $client;
    }

    /**
     * Ping connections in Elasticsearch.
     * 
     * @return mixed
     */
    public function isConnected()
    {
        try {
            return $this->client->ping()->asBool();
        } catch( \Exception $e ) {
            return false;
        }
    }

    /**
     * Log with Throwable exception type error.
     * 
     * @param string $message Message of exception.
     * @param array  $context Context array.
     * 
     * @return void
     */
    public function error(string $message, array $context = []): void
    {
        Log::channel($this->channel)->error($message, $context);
    }

    /**
     * Log with Throwable exception type error.
     * 
     * @param Throwable $exception Throwable.
     * 
     * @return void
     */
    public function throw(Throwable $exception): void
    {
        $message = $exception->getMessage();
        $request = request() ?? [];
        $appName = env('APP_NAME', 'UNKNOWN_APP_NAME');
        $hostname = env('HOSTNAME', 'UNKNOWN_HOSTNAME_NAME');
        // Format the error payload according to this repo
        // https://github.com/pensoft/awt-error-formatter
        $context = ErrorPayloadFormatter::format(
            $request,
            $exception,
            $appName,
            $hostname
        );
        $this->error($message, $context);
    }
}
