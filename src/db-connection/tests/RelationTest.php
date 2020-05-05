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

namespace HyperfTest\DbConnection;

use Hyperf\Database\ConnectionResolverInterface;
use Hyperf\Database\Model\Register;
use Hyperf\Database\Model\Relations\Pivot;
use Hyperf\Utils\Context;
use HyperfTest\DbConnection\Stubs\ContainerStub;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class RelationTest extends TestCase
{
    protected function tearDown()
    {
        Mockery::close();
        Context::set('database.connection.default', null);
    }

    public function testPivot()
    {
        $container = ContainerStub::mockContainer();
        Register::setConnectionResolver($container->get(ConnectionResolverInterface::class));

        $pivot = Pivot::fromAttributes(new FooModel(), ['created_at' => '2019-12-15 00:00:00'], 'foo', true);

        $this->assertSame(['created_at' => '2019-12-15 00:00:00'], $pivot->toArray());
    }
}
