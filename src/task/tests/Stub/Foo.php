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

namespace HyperfTest\Task\Stub;

class Foo
{
    public function get($id)
    {
        return $id;
    }

    public function exception()
    {
        throw new \RuntimeException('Foo::exception failed.');
    }

    public function getIdAndName($id, $name)
    {
        return ['id' => $id, 'name' => $name];
    }

    public function dump($id, ...$arguments)
    {
        return ['id' => $id, 'arguments' => $arguments];
    }
}
