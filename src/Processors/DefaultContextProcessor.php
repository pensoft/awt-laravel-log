<?php declare(strict_types=1);
// phpcs:ignoreFile
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pensoft\AwtLaravelLog\Processors;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

/**
 * Generates a context from a Closure if the Closure is the only value
 * in the context
 *
 * It helps reduce the performance impact of debug logs if they do
 * need to create lots of context information. If this processor is added
 * on the correct handler the context data will only be generated
 * when the logs are actually logged to that handler, which is useful when
 * using FingersCrossedHandler or other filtering handlers to conditionally
 * log records.
 */
class DefaultContextProcessor implements ProcessorInterface
{
    public function __invoke(LogRecord $record): LogRecord
    {
        $context = $record->context;
            
        if (!\is_array($context)) {
            $context = [$context];
        }

        $request = request();
        // Check if the exception is part of the context
        if (isset($context['exception']) && $context['exception'] instanceof \Throwable) {
            $context['error_code'] = $context['exception']->getCode();
            $context['stack_trace'] = $context['exception']->getTraceAsString();
        }
        unset($context['exception']);
        
        // Check if request exists
        if ( !app()->runningInConsole() ) {
            $context = array_merge([
                'request_uri' => $request->path(),
                'request_method' => $request->method(),
                'user_agent' => $request->header('User-Agent'),
                'ip_address' => $request->ip(),
                'payload' => $request->all(),
            ], $context);
        }
        // Check is auth exists
        if (auth()->id()) {
            $context = array_merge([
                'user_id' => auth()->id(),
            ], $context);
        }

        //Default context
        $context = array_merge([
            'service_name' => env('SERVICE_NAME') ?? env('APP_NAME'),
            'server_info' => php_uname('n'),
        ], $context);
        
        $record = $record->with(context: $context);
        return $record;
    }
}
