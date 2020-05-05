<?php

namespace App\Controller;
use Hyperf\View\RenderInterface;
use Hyperf\HttpServer\RequestInterface;
use Hyperf\Utils\Context;
use Hyperf\HttpServer\Annotation\AutoController;
/**
*@AutoController();
*/
class Common 
{
	private $render;
	private $reqeust;
	
	function __construct(RenderInterface $render){
		$this->render=$render;
		
	}
	
	public function test_view($path){
	var_dump($path);
		//return $this->render->render($path);
	
	}
	
	public function res(){
			//对$request的处理。但是如果DI管理的对象的属性进行更改的时候。其他别的请求会混淆
			$id=$request->input('id');
			Context::set('test',$id);
			echo Context::get('test');
	}
	
	public function login(RequestInterface $request){
		//当多人同时登陆的时候。但是他不是DI管理的。所以应该不是单例
			$a=$request->input('user');
			var_dump($a);
	}
}