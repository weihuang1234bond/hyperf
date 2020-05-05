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

namespace HyperfTest\AsyncQueue\Stub;

use Hyperf\AsyncQueue\Driver\RedisDriver;
use Hyperf\Utils\Coroutine\Concurrent;

class RedisDriverStub extends RedisDriver
{
    /**
     * @return null|Concurrent
     */
    public function getConcurrent(): ?Concurrent
    {
        return $this->concurrent;
    }
}
