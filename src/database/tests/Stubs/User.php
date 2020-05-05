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

namespace HyperfTest\Database\Stubs;

use Hyperf\Database\ConnectionInterface;
use Hyperf\Database\ConnectionResolverInterface;
use Hyperf\Database\Model\Model;
use Hyperf\Utils\ApplicationContext;

class User extends Model
{
    protected $table = 'user';

    public function getConnection(): ConnectionInterface
    {
        return ApplicationContext::getContainer()->get(ConnectionResolverInterface::class)->connection();
    }
}
