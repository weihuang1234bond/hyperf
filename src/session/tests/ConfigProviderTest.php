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

namespace HyperfTest\Session;

use Hyperf\Session\ConfigProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Hyperf\Session\ConfigProvider
 */
class ConfigProviderTest extends TestCase
{
    public function testConfigProvider()
    {
        $provider = new ConfigProvider();
        $this->assertArrayHasKey('annotations', $provider());
        $this->assertArrayHasKey('publish', $provider());
    }
}
