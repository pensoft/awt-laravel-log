<?php

namespace Pensoft\AwtLaravelLog;

use Illuminate\Support\Facades\Log;
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
    /**
     * Construct.
     * 
     * @param public $channel Channel.
     */
    public function __construct(public $channel)
    {
        
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
        $context = [
            'code' => $exception->getCode(),
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
            'trace' => $exception->getTrace(),
            'traceAsString' => $exception->getTraceAsString()
        ];
        $this->error($message, $context);
    }
}
