<?php
namespace App\Controller;
use Hyperf\View\RenderInterface;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Model\User;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\WebSocketClient\ClientFactory;
use Hyperf\Utils\ApplicationContext;
use Swoole\WebSocket\Server;
//专门的task类吧
use Hyperf\Server\ServerFactory;
use Hyperf\DbConnection\Db;


/**
*	@AutoController();
*  
*/
class UserController extends AbstractController
{
	
	private $render;
	
	/**
     * @Inject
     * @var ClientFactory
     */
    protected $clientFactory;
	
	/**
     * @Inject()
     * @var \Hyperf\Contract\SessionInterface
     */
    private $session;
	
	public function __construct(RenderInterface $render){
		$this->render=$render;
		
		
	}
	
	
	function index(RequestInterface $request){
		$id=$request->input('id');
		$res=User::query()->where('id',$id)->first();
		//这边应该加个数据，update变成公共的，不行 数据跑不同。
		return 	$this->render->render('home/user_update',['res'=>$res]);
	}
	
	
	function update(RequestInterface $request){
		$data=$request->all();
		$id=array_splice($data,'id');
		$res=User::query()->where('id',$id)->update($data);
		
		return 	$res?true:false;
	}
	
	
	function del(RequestInterface $request){
		$id=$request->input('id');
		$res=User::where('id',$id)->delete();
		return $res?true:false;
	}
	
	function chat(RequestInterface $request){
		
			$content=$request->all();
		
		$res=strcmp($content['save'],$content['master']);
		
	 if($res<0){
      $data=Db::table($content['save'].'-'.$content['master'])->limit(20)->get();
    }else{
      $data=Db::table($content['master'].'-'.$content['save'])->limit(20)->get();
    }
   
	 return  $this->render->render('home/chat',['master'=>$content['master'],'save'=>$content['save'],'data'=>$data]);
	}	
	
	
	function send(RequestInterface $request){
		//数据到这 要发送给对应的用户
		$all=$request->all();
		
		$all['time']=date('Y-m-d h:i:s');
	
		$redis=$this->container->get(\Redis::class);
			//获得websocketserver 真是麻烦 官方文档还说，直接是获取不到的。
			$server=$this->container->get(ServerFactory::class)->getServer()->getServer();
			
			// var_dump($server);
		// $server=$redis->get('server');
		 $fd=$redis->get($all['save']);
		 
		if(!$fd){
			//对方现在不再线，将数据存储到数据库。用户多的时候就储存到redis，这个到时候做个通用方法，减少代码量
				$res=strcmp($all['save'],$all['master']);
		
				 if($res<0){
			      $data=Db::table($all['save'].'-'.$all['master'])->insert($all);
			    }else{
			      $data=Db::table($all['master'].'-'.$all['save'])->insert($all);
			    }
		}else{
			$server->push($fd,json_encode($all));
		}
		
		
	}
	
	
	
}