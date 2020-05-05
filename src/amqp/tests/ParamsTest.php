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

use Hyperf\Amqp\Params;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ParamsTest extends TestCase
{
    public function testConstructor()
    {
        $params = new Params([
            'insist' => false,
            'login_method' => 'AMQPLAIN',
            'login_response' => null,
            'locale' => 'en_US',
            'connection_timeout' => 3.0,
            'read_write_timeout' => 6.0,
            'context' => null,
            'keepalive' => false,
            'heartbeat' => 3,
            'close_on_destruct' => true,
        ]);

        $this->assertFalse($params->isInsist());
        $this->assertSame('AMQPLAIN', $params->getLoginMethod());
        $this->assertNull($params->getLoginResponse());
        $this->assertSame('en_US', $params->getLocale());
        $this->assertSame(3.0, $params->getConnectionTimeout());
        $this->assertSame(6.0, $params->getReadWriteTimeout());
        $this->assertNull($params->getContext());
        $this->assertFalse($params->isKeepalive());
        $this->assertSame(3, $params->getHeartbeat());
        $this->assertTrue($params->isCloseOnDestruct());
    }
}
