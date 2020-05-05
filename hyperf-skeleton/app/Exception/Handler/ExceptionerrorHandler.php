<?php
namespace App\Exception\Handler;

use App\Exception\Exceptionerror;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;

use Throwable;

class ExceptionerrorHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // stopPagation是这个方法完 不继续下去。
        $this->stopPropagation();
        return $response->withStatus(500)->withBody(new SwooleStream('错误，请稍后刷新'));
       
    }
    
     public function isValid(Throwable $throwable):bool
    {
    	//直接返回true是处理所有的异常，只对可别对象的话 可以这样, 继承的是接口异常对象。
    	return $throwable instanceof Exceptionerror;
      
    }
}