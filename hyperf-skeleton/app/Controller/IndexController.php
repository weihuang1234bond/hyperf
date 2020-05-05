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

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\View\RenderInterface;
 use Hyperf\HttpServer\Annotation\Middleware;
 use App\Middleware\Check;
use Hyperf\Di\Annotation\Inject;
// use App\Controller\Common;
// use App\Controller\common\ThrowContext;
use Hyperf\HttpServer\Contract\RequestInterface;
//use Hyperf\Config\Config;

//返回视图层的json数据。
use Hyperf\HttpServer\Contract\ResponseInterface;
//use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

//数据库类
use Hyperf\DbConnection\Db;
//容器类
//use Hyperf\Utils\ApplicationContext;
use App\Model\User;

/**
*	@AutoController();
*  
*/
class IndexController extends AbstractController
{
	
	private $render;
	/**
     * @Inject()
     * @var \Hyperf\Contract\SessionInterface
     */
    private $session;
	
	public function __construct(RenderInterface $render){
		$this->render=$render;
		
		
	}
	
    public function index()
    
    {
    	$redis=$this->container->get(\Redis::class);
    //	$redis->delete('id');
    	$level=$redis->get('id');
    	
    	if($level){
    		return $this->render->render('home/index',['level'=>$level]);
    	}else{
    		return $this->render->render('home/login');	
    	}
     //  if(!config('login.login')){
    	// 	return $this->render->render('home/login');
    	// }else{
    	// 	return $this->render->render('home/index');
    	// }
        //return $render->render('home/index', ['name' => 'Hyperf']);
      //return  $this->com->test_view();
      //return ThrowContext::test1();
    }
    
  
    public function login(RequestInterface $request,ResponseInterface $response){
    //这里本来还要验证合法性的。比如长度，JS请求还方便一点 真尼玛
    
   // $config= new Config(config('login'));
    
    
    if(!$request->input('user') || !$request->input('password')){
    	
    	 $data=['status'=>0,'text'=>'请输入账号和密码'];
    	  return $response->json($data);
    	}
		//单个还是直接用构造器。 如果多个就用model,还需要权限
    	$res=Db::table('adm')->where('name',$request->input('user'))->select('password','level')->get();
    	
    	
    	//这里后面要用Md5加密
    	if(!$res || !$res[0]->password==$request->input('password')){
    		$data=['status'=>0,'text'=>'请输入正确的账号或密码'];
    		return $response->json($data);
    	}
    //	$config->set('login.login',1);
    	//要想办法把等级保存起来。session ,redis ,这些服务应该一开始就加载好，全部都能直接用是最好的。
	  //  	$text=ApplicationContext::getContainer();
			// $redis = $text->get(\Redis::class);
			$redis=$this->container->get(\Redis::class);
			$redis->set('id',$res[0]->level);
			$this->session->set('master',$request->input('user'));
    		return $response->json(['status'=>1,'level'=>$res[0]->level]);
    }
    
    
    public function admin(){
    	// $path=$request->input('path');
    	// return $this->render->render($path);
    	return $this->render->render('home/index');
    }
    
    public function user(){
    	//直接返回的是json格式的数据
    	$data=User::paginate(5);
    	$master=$this->session->get('master');
    return $this->render->render('home/user',['data'=>$data,'master'=>$master]);
    }
    
    
    public function config(){
    	return $this->render->render('home/config');
    }
    
    public function page(){
    	$data=User::paginate(5);
    	return $data;
    }
    
    
    public function chat(RequestInterface $request){
    	//获取用户信息。 并且查询2个人间的对话信息。
    	$master=$request->input('master');
    	$save=$request->input('save');
    	if(strcmp($master,$save)>0){
    		$data=Db::table($master.'-'.$save)->limit(20)->get();
    	}else{
    		$data=Db::table($master.'-'.$save)->limit(20)->get();
    	}
    	return $this->render->render('home/chat',['data'=>$data]);
    }
}
