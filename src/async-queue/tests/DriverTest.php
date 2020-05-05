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

namespace HyperfTest\AsyncQueue;

use Hyperf\AsyncQueue\Driver\ChannelConfig;
use Hyperf\Di\Container;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Coroutine\Concurrent;
use Hyperf\Utils\Packer\PhpSerializerPacker;
use HyperfTest\AsyncQueue\Stub\Redis;
use HyperfTest\AsyncQueue\Stub\RedisDriverStub;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @internal
 * @coversNothing
 */
class DriverTest extends TestCase
{
    protected function tearDown()
    {
        Mockery::close();
    }

    public function testConcurrent()
    {
        $container = $this->getContainer();

        $driver = new RedisDriverStub($container, []);

        $this->assertNull($driver->getConcurrent());

        $driver = new RedisDriverStub($container, ['concurrent' => [
            'limit' => 100,
        ]]);

        $this->assertInstanceOf(Concurrent::class, $driver->getConcurrent());
        $this->assertSame(100, $driver->getConcurrent()->getLimit());
    }

    protected function getContainer()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('has')->andReturn(false);
        $container->shouldReceive('get')->with(\Redis::class)->andReturn(new Redis());
        $container->shouldReceive('get')->with(PhpSerializerPacker::class)->andReturn(new PhpSerializerPacker());
        $container->shouldReceive('get')->with(EventDispatcherInterface::class)->andReturn(null);
        $container->shouldReceive('make')->with(ChannelConfig::class, Mockery::any())->andReturnUsing(function ($class, $args) {
            return new ChannelConfig($args['channel']);
        });

        ApplicationContext::setContainer($container);

        return $container;
    }
}
