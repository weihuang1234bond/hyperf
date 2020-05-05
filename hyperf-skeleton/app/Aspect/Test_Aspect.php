<?php
namespace App\Aspect;

use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
class Test_Aspect extends AbstractAspect
{
	public $class=[
			Test::class.'::'.asp,
		];
	public $annotation=[
			
			];
			
		function process(ProceedingJoinPoint $proceedingJoinPoint){
			// 切面切入后，执行对应的方法会由此来负责
        // $proceedingJoinPoint 为连接点，通过该类的 process() 方法调用原方法并获得结果
        // 在调用前进行某些处理
        $result = $proceedingJoinPoint->process();
        // 在调用后进行某些处理
         $result++;
        return $result;
		}
}