<?php declare(strict_types=1);
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
        return $record;
    }
}
