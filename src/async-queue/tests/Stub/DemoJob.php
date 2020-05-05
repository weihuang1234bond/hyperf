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

use Hyperf\AsyncQueue\Job;

class DemoJob extends Job
{
    public $id;

    public $model;

    protected $maxAttempts = 1;

    public function __construct($id, $model = null)
    {
        $this->id = $id;
        $this->model = $model;
    }

    public function handle()
    {
    }
}
