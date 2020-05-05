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
use Hyperf\Redis\Pool\PoolFactory;
use Hyperf\Redis\Pool\RedisPool;
use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class RedisProxyTest extends TestCase
{
    protected function tearDown()
    {
        $redis = $this->getRedis();
        $redis->flushDB();

        Mockery::close();
    }

    public function testRedisOptionPrefix()
    {
        $redis = $this->getRedis([
            \Redis::OPT_PREFIX => 'test:',
        ]);

        $redis->set('test', 'yyy');
        $this->assertSame('yyy', $redis->get('test'));

        $this->assertSame('yyy', $this->getRedis()->get('test:test'));
    }

    public function testRedisOptionSerializer()
    {
        $redis = $this->getRedis([
            \Redis::OPT_SERIALIZER => (string) \Redis::SERIALIZER_PHP,
        ]);

        $redis->set('test', 'yyy');
        $this->assertSame('yyy', $redis->get('test'));

        $this->assertSame('s:3:"yyy";', $this->getRedis()->get('test'));
    }

    public function testRedisScan()
    {
        $redis = $this->getRedis();
        $origin = ['scan:1', 'scan:2', 'scan:3', 'scan:4'];
        foreach ($origin as $value) {
            $redis->set($value, '1');
        }

        $it = null;
        $result = [];
        while (false !== $res = $redis->scan($it, 'scan:*', 2)) {
            $result = array_merge($result, $res);
        }

        sort($result);

        $this->assertEquals($origin, $result);
        $this->assertSame(0, $it);
    }

    public function testRedisHScan()
    {
        $redis = $this->getRedis();
        $origin = ['scan:1', 'scan:2', 'scan:3', 'scan:4'];
        foreach ($origin as $value) {
            $redis->hSet('scaner', $value, '1');
        }

        $it = null;
        $result = [];
        while (false !== $res = $redis->hScan('scaner', $it, 'scan:*', 2)) {
            $result = array_merge($result, array_keys($res));
        }

        sort($result);

        $this->assertEquals($origin, $result);
        $this->assertSame(0, $it);
    }

    /**
     * @param mixed $optinos
     * @return \Redis
     */
    private function getRedis($optinos = [])
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('get')->once()->with(ConfigInterface::class)->andReturn(new Config([
            'redis' => [
                'default' => [
                    'host' => 'localhost',
                    'auth' => null,
                    'port' => 6379,
                    'db' => 0,
                    'options' => $optinos,
                    'pool' => [
                        'min_connections' => 1,
                        'max_connections' => 30,
                        'connect_timeout' => 10.0,
                        'wait_timeout' => 3.0,
                        'heartbeat' => -1,
                        'max_idle_time' => 60,
                    ],
                ],
            ],
        ]));
        $pool = new RedisPool($container, 'default');
        $frequency = Mockery::mock(LowFrequencyInterface::class);
        $frequency->shouldReceive('isLowFrequency')->andReturn(false);
        $container->shouldReceive('make')->with(Frequency::class, Mockery::any())->andReturn($frequency);
        $container->shouldReceive('make')->with(RedisPool::class, ['name' => 'default'])->andReturn($pool);
        $container->shouldReceive('make')->with(Channel::class, ['size' => 30])->andReturn(new Channel(30));
        $container->shouldReceive('make')->with(PoolOption::class, Mockery::any())->andReturnUsing(function ($class, $args) {
            return new PoolOption(...array_values($args));
        });
        ApplicationContext::setContainer($container);

        $factory = new PoolFactory($container);

        return new Redis($factory);
    }
}
