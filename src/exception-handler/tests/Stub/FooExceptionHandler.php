<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace HyperfTest\ExceptionHandler\Stub;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class FooExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        Context::set('test.exception-handler.latest-handler', static::class);
        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
