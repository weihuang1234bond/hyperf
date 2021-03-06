<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */
//use app\Middleware\Check;

return [
    'http' => [
    	\Hyperf\Session\Middleware\SessionMiddleware::class,
    //	Check::class,
    ],
];
