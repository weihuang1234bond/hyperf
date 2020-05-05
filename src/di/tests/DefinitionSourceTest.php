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

namespace HyperfTest\Di;

use Hyperf\Di\Container;
use Hyperf\Di\Definition\DefinitionSource;
use Hyperf\Di\Definition\ScanConfig;
use HyperfTest\Di\Stub\Foo;
use HyperfTest\Di\Stub\FooFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class DefinitionSourceTest extends TestCase
{
    public function testAddDefinition()
    {
        $container = new Container(new DefinitionSource([], new ScanConfig()));
        $container->getDefinitionSource()->addDefinition('Foo', function () {
            return 'bar';
        });
        $this->assertEquals('bar', $container->get('Foo'));
    }

    public function testDefinitionFactory()
    {
        $container = new Container(new DefinitionSource([], new ScanConfig()));
        $container->getDefinitionSource()->addDefinition('Foo', FooFactory::class);

        $foo = $container->get('Foo');
        $this->assertInstanceOf(Foo::class, $foo);
    }
}
