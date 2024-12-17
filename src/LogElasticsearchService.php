<?php

namespace Pensoft\AwtLaravelLog;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
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
     * @param Throwable $e Throwable.
     * @return void
     */
    public function __invoke(Throwable $e): void
    {
        if ((app()->runningUnitTests()
            && !app()->hasDebugModeEnabled())
            || app()->runningInConsole()) {
            return;
        }

        $code = $e->getCode() ?: 500;
        $response = new Response('', $code);

        if ($response->isInvalid() or $response->isServerError()) {
            Log::channel('elasticsearch')->error($e->getMessage(), [
                'exception' => $e
            ]);
        }
    }
}
