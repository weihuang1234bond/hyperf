<?php
// 公用方法
//use Hyperf\View\RenderInterface;
//use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\ApplicationContext;
//use APP\Controller\View;

// function vview($path){
// 	$view= new Goview;
// 	$view->vview($path);
// }

// function url(ResponseInterface $rsponse,$path){
// 	if(empty($path)){
// 		return '参数缺失';
// 	}
// 	//第一个字符串是不是/ 不是补上，要不就不管。直接打的时候注意点
// 	$http=CLIENT_HOST.$path;
// 	return $response->raw($http);
	
// }
function level(){
	$text=ApplicationContext::getContainer();
	 $redis = $text->get(\Redis::class);
	 return $redis->get('id');
}
?>