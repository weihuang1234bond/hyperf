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

namespace HyperfTest\Task;

use Hyperf\Framework\Event\OnTask;
use Hyperf\Task\ChannelFactory;
use Hyperf\Task\Exception;
use Hyperf\Task\Finish;
use Hyperf\Task\Listener\OnTaskListener;
use Hyperf\Task\Task;
use Hyperf\Task\TaskExecutor;
use Hyperf\Utils\Serializer\ExceptionNormalizer;
use HyperfTest\Task\Stub\Foo;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Swoole\Server;

/**
 * @internal
 * @coversNothing
 */
class OnTaskListenerTest extends TestCase
{
    protected function tearDown()
    {
        Mockery::close();
    }

    public function testProcess()
    {
        $container = $this->getContainer();

        $listener = new OnTaskListener($container);
        $id = uniqid();
        $event = Mockery::mock(OnTask::class);
        $event->task = new Server\Task();
        $event->task->data = new Task([Foo::class, 'get'], [$id]);

        $event->shouldReceive('setResult')->with(Mockery::any())->andReturnUsing(function ($result) use ($id) {
            $this->assertInstanceOf(Finish::class, $result);
            $this->assertSame($id, $result->data);
        });

        $listener->process($event);
    }

    public function testProcessException()
    {
        $container = $this->getContainer();

        $listener = new OnTaskListener($container);
        $id = uniqid();
        $event = Mockery::mock(OnTask::class);
        $event->task = new Server\Task();
        $event->task->data = new Task([Foo::class, 'exception'], [$id]);

        $event->shouldReceive('setResult')->with(Mockery::any())->andReturnUsing(function ($result) use ($id) {
            $this->assertInstanceOf(Finish::class, $result);
            $this->assertInstanceOf(Exception::class, $result->data);
            $this->assertSame(\RuntimeException::class, $result->data->class);
            $this->assertSame('Foo::exception failed.', $result->data->attributes['message']);
            $this->assertSame(0, $result->data->attributes['code']);
        });

        $listener->process($event);
    }

    protected function getContainer()
    {
        $container = Mockery::mock(ContainerInterface::class);
        $normalizer = new ExceptionNormalizer();
        $container->shouldReceive('get')->with(ExceptionNormalizer::class)->andReturn($normalizer);
        $container->shouldReceive('has')->with(Mockery::any())->andReturn(true);
        $container->shouldReceive('get')->with(TaskExecutor::class)->andReturn(new TaskExecutor(new ChannelFactory(), $normalizer));
        $container->shouldReceive('get')->with(Foo::class)->andReturn(new Foo());

        return $container;
    }
}
