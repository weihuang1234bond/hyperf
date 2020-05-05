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

namespace HyperfTest\DbConnection\Stubs;

use Hyperf\DbConnection\Frequency;

class FrequencyStub extends Frequency
{
    protected $time = 2;

    public function getHits(): array
    {
        return $this->hits;
    }
}
