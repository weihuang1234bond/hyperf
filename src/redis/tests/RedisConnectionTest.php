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

namespace HyperfTest\Redis;

use Hyperf\Config\Config;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Container;
use Hyperf\Pool\Channel;
use Hyperf\Pool\LowFrequencyInterface;
use Hyperf\Pool\PoolOption;
use Hyperf\Redis\Frequency;
use Hyperf\Utils\ApplicationContext;
use HyperfTest\Redis\Stub\RedisPoolStub;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class RedisConnectionTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testRedisConnectionConfig()
    {
        $pool = $this->getRedisPool();

        $config = $pool->get()->getConfig();

        $this->assertSame([
            'host' => 'redis',
            'port' => 16379,
            'auth' => 'redis',
            'db' => 0,
            'timeout' => 0.0,
            'cluster' => [
                'enable' => false,
                'name' => null,
                'seeds' => [],
            ],
            'options' => [],
            'pool' => [
                'min_connections' => 1,
                'max_connections' => 30,
                'connect_timeout' => 10.0,
                'wait_timeout' => 3.0,
                'heartbeat' => -1,
                'max_idle_time' => 1,
            ],
        ], $config);
    }

    public function testRedisConnectionReconnect()
    {
        $pool = $this->getRedisPool();

        $connection = $pool->get()->getConnection();
        $this->assertSame(null, $connection->getDatabase());

        $connection->setDatabase(2);
        $connection->reconnect();
        $this->assertSame(2, $connection->getDatabase());

        $connection->release();
        $connection = $pool->get()->getConnection();
        $this->assertSame(null, $connection->getDatabase());
    }

    public function testRedisCloseInLowFrequency()
    {
        $pool = $this->getRedisPool();

        $connection1 = $pool->get()->getConnection();
        $connection2 = $pool->get()->getConnection();
        $connection3 = $pool->get()->getConnection();

        $this->assertSame(3, $pool->getCurrentConnections());

        $connection1->release();
        $connection2->release();
        $connection3->release();

        $this->assertSame(3, $pool->getCurrentConnections());

        $connection = $pool->get()->getConnection();

        $this->assertSame(1, $pool->getCurrentConnections());

        $connection->release();
    }

    private function getRedisPool()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('get')->with(ConfigInterface::class)->andReturn(new Config([
            'redis' => [
                'default' => [
                    'host' => 'redis',
                    'auth' => 'redis',
                    'port' => 16379,
                    'pool' => [
                        'min_connections' => 1,
                        'max_connections' => 30,
                        'connect_timeout' => 10.0,
                        'wait_timeout' => 3.0,
                        'heartbeat' => -1,
                        'max_idle_time' => 1,
                    ],
                ],
            ],
        ]));

        $frequency = Mockery::mock(LowFrequencyInterface::class);
        $frequency->shouldReceive('isLowFrequency')->andReturn(true);
        $container->shouldReceive('make')->with(Frequency::class, Mockery::any())->andReturn($frequency);
        $container->shouldReceive('make')->with(PoolOption::class, Mockery::any())->andReturnUsing(function ($class, $args) {
            return new PoolOption(...array_values($args));
        });
        $container->shouldReceive('make')->with(Channel::class, Mockery::any())->andReturnUsing(function ($class, $args) {
            return new Channel($args['size']);
        });

        ApplicationContext::setContainer($container);

        return new RedisPoolStub($container, 'default');
    }
}
