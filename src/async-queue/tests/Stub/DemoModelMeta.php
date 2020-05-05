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

namespace HyperfTest\AsyncQueue\Stub;

use Hyperf\Contract\CompressInterface;
use Hyperf\Contract\UnCompressInterface;
use Hyperf\Utils\Context;

class DemoModelMeta implements UnCompressInterface
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function uncompress(): CompressInterface
    {
        $data = Context::get('test.async-queue.demo.model.' . $this->id);

        return new DemoModel($this->id, ...$data);
    }
}
