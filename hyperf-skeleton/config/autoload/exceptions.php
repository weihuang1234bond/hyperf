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
use App\Exception\Handler\ExceptionerrorHandler;
//处理完这个异常 会继续处理下个异常
return [
    'handler' => [
        'http' => [
        	ExceptionerrorHandler::class,
            App\Exception\Handler\AppExceptionHandler::class,
        ],
    ],
];
