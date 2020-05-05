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

namespace HyperfTest\Amqp;

use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\ConsumerManager;
use Hyperf\Amqp\Message\ConsumerMessageInterface;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Process\ProcessManager;
use HyperfTest\Amqp\Stub\ContainerStub;
use HyperfTest\Amqp\Stub\DemoConsumer;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ConsumerManagerTest extends TestCase
{
    protected function tearDown()
    {
        ProcessManager::clear();
    }

    public function testConsumerAnnotation()
    {
        $container = ContainerStub::getContainer();

        AnnotationCollector::collectClass(DemoConsumer::class, Consumer::class, new Consumer([
            'exchange' => $exchange = uniqid(),
            'routingKey' => $routingKey = uniqid(),
            'queue' => $queue = uniqid(),
            'nums' => $nums = rand(1, 10),
        ]));

        $manager = new ConsumerManager($container);
        $manager->run();

        $hasRegisted = false;
        foreach (ProcessManager::all() as $item) {
            if (method_exists($item, 'getConsumerMessage')) {
                $hasRegisted = true;
                /** @var ConsumerMessageInterface $message */
                $message = $item->getConsumerMessage();
                $this->assertTrue($item->isEnable());
                $this->assertSame($exchange, $message->getExchange());
                $this->assertSame($routingKey, $message->getRoutingKey());
                $this->assertSame($queue, $message->getQueue());
                $this->assertSame($nums, $item->nums);
                break;
            }
        }

        $this->assertTrue($hasRegisted);
    }

    public function testConsumerAnnotationNotEnable()
    {
        $container = ContainerStub::getContainer();

        AnnotationCollector::collectClass(DemoConsumer::class, Consumer::class, new Consumer([
            'exchange' => $exchange = uniqid(),
            'routingKey' => $routingKey = uniqid(),
            'queue' => $queue = uniqid(),
            'nums' => $nums = rand(1, 10),
            'enable' => false,
        ]));

        $manager = new ConsumerManager($container);
        $manager->run();

        $hasRegisted = false;
        foreach (ProcessManager::all() as $item) {
            if (method_exists($item, 'getConsumerMessage')) {
                $hasRegisted = true;
                $this->assertFalse($item->isEnable());
                break;
            }
        }

        $this->assertTrue($hasRegisted);
    }
}
