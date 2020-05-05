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

namespace HyperfTest\ModelCache\Stub;

use Hyperf\Config\Config;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Database\ConnectionResolverInterface;
use Hyperf\Database\Connectors\ConnectionFactory;
use Hyperf\Database\Connectors\MySqlConnector;
use Hyperf\DbConnection\ConnectionResolver;
use Hyperf\DbConnection\Frequency;
use Hyperf\DbConnection\Pool\DbPool;
use Hyperf\DbConnection\Pool\PoolFactory;
use Hyperf\Di\Container;
use Hyperf\Event\EventDispatcher;
use Hyperf\Event\ListenerProvider;
use Hyperf\Framework\Logger\StdoutLogger;
use Hyperf\ModelCache\Handler\RedisHandler;
use Hyperf\ModelCache\Manager;
use Hyperf\ModelCache\Redis\LuaManager;
use Hyperf\Pool\Channel;
use Hyperf\Pool\PoolOption;
use Hyperf\Redis\Pool\RedisPool;
use Hyperf\Redis\RedisProxy;
use Hyperf\Utils\ApplicationContext;
use Mockery;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LogLevel;

class ContainerStub
{
    public static function mockContainer()
    {
        $container = Mockery::mock(Container::class);

        $factory = new PoolFactory($container);
        $container->shouldReceive('get')->with(PoolFactory::class)->andReturn($factory);

        $resolver = new ConnectionResolver($container);
        $container->shouldReceive('get')->with(ConnectionResolverInterface::class)->andReturn($resolver);

        $config = new Config([
            StdoutLoggerInterface::class => [
                'log_level' => [
                    LogLevel::ALERT,
                    LogLevel::CRITICAL,
                    LogLevel::DEBUG,
                    LogLevel::EMERGENCY,
                    LogLevel::ERROR,
                    LogLevel::INFO,
                    LogLevel::NOTICE,
                    LogLevel::WARNING,
                ],
            ],
            'databases' => [
                'default' => [
                    'driver' => 'mysql',
                    'host' => 'localhost',
                    'database' => 'hyperf',
                    'username' => 'root',
                    'password' => '',
                    'charset' => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix' => '',
                    'cache' => [
                        'handler' => RedisHandler::class,
                        'cache_key' => '{mc:%s:m:%s}:%s:%s',
                        'prefix' => 'default',
                        'pool' => 'default',
                        'ttl' => 3600 * 24,
                        'empty_model_ttl' => 3600,
                        'load_script' => true,
                    ],
                    'pool' => [
                        'min_connections' => 1,
                        'max_connections' => 10,
                        'connect_timeout' => 10.0,
                        'wait_timeout' => 3.0,
                        'heartbeat' => -1,
                        'max_idle_time' => 60.0,
                    ],
                ],
            ],
            'redis' => [
                'default' => [
                    'host' => 'localhost',
                    'auth' => null,
                    'port' => 6379,
                    'db' => 0,
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
        ]);
        $container->shouldReceive('get')->with(ConfigInterface::class)->andReturn($config);

        $logger = new StdoutLogger($config);
        $container->shouldReceive('get')->with(StdoutLoggerInterface::class)->andReturn($logger);

        $connectionFactory = new ConnectionFactory($container);
        $container->shouldReceive('get')->with(ConnectionFactory::class)->andReturn($connectionFactory);

        $eventDispatcher = new EventDispatcher(new ListenerProvider(), $logger);
        $container->shouldReceive('get')->with(EventDispatcherInterface::class)->andReturn($eventDispatcher);

        $container->shouldReceive('get')->with('db.connector.mysql')->andReturn(new MySqlConnector());
        $container->shouldReceive('has')->andReturn(true);
        $container->shouldReceive('make')->with(Frequency::class, Mockery::any())->andReturn(new Frequency());
        $container->shouldReceive('make')->with(DbPool::class, Mockery::any())->andReturnUsing(function ($_, $args) use ($container) {
            return new DbPool($container, $args['name']);
        });

        ApplicationContext::setContainer($container);
        $container->shouldReceive('make')->with(LuaManager::class, Mockery::any())->andReturnUsing(function ($_, $args) {
            return new LuaManager(...$args);
        });
        $container->shouldReceive('make')->with(Channel::class, Mockery::any())->andReturnUsing(function ($_, $args) {
            return new Channel($args['size']);
        });
        $container->shouldReceive('make')->with(PoolOption::class, Mockery::any())->andReturnUsing(function ($_, $args) {
            return new PoolOption(...array_values($args));
        });
        $container->shouldReceive('make')->with(\Hyperf\Redis\Frequency::class, Mockery::any())->andReturn(new \Hyperf\Redis\Frequency());
        $container->shouldReceive('make')->with(RedisPool::class, Mockery::any())->andReturnUsing(function ($_, $args) use ($container) {
            return new RedisPool($container, $args['name']);
        });
        $poolFactory = new \Hyperf\Redis\Pool\PoolFactory($container);
        $container->shouldReceive('make')->with(RedisProxy::class, Mockery::any())->andReturnUsing(function ($_, $args) use ($poolFactory) {
            return new RedisProxy($poolFactory, $args['pool']);
        });
        $container->shouldReceive('make')->with(RedisHandler::class, Mockery::any())->andReturnUsing(function ($_, $args) use ($container) {
            return new RedisHandler($container, $args['config']);
        });
        $container->shouldReceive('get')->with(Manager::class)->andReturn(new Manager($container));

        return $container;
    }
}
