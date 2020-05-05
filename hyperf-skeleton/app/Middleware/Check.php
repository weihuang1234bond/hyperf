<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use App\Controller\Common;
use Hyperf\Di\Annotation\Inject;
use Hyperf\View\RenderInterface;

use App\Exception\Exceptionerror;
use Hyperf\Contract\SessionInterface;


class Check implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
	
    
    private $session;
    
    private $render;

    public function __construct(ContainerInterface $container,SessionInterface $session)
    {
        $this->container = $container;
        $this->session=$session;
    }
	
	
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
   		$path=$request->getServerParams();
   		
   		//var_dump($path['path_info']);
   		
    	if($path['path_info']=='/index/index' or $path['path_info']=='/index/login'){
    		return $handler->handle($request);
    	}
    	
  //  		$redis=$this->container->get(\Redis::class);
    
  //  		$level=$redis->get('id');
    	
    		
    		if(!$this->session->has('master')){
    			throw new Exceptionerror('error,plese login');
    		}
    		//echo $path['path_info'];
    		// var_dump(config('operation.'.$path['path_info']));
    		if(!config('operation.'.$path['path_info'])){
    			throw new Exceptionerror('error,请申请权限');
    		}
		// if(!$level || !config('operation.'.$path)){
		// 	//先要创建一个自定义的错误对象，然后再创一个处理错误对象的对象。比如这里的exception_errrorhandler
		// 	throw new Exceptionerror('error,plese login');
		// }
    	

			return $handler->handle($request);
    	
    	
       
    }
   
}