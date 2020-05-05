<?php
declare(strict_types=1);

namespace App\Controller;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Di\Annotation\Inject;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
//这有个问题就是。 url地址的问题，或者说多端口的问题。
	/**
     * @Inject()
     * @var \Hyperf\Contract\SessionInterface
     */
    private $session;
    
    public function onMessage(WebSocketServer $server, Frame $frame): void
    {
    	$text=ApplicationContext::getContainer();
         $redis = $text->get(\Redis::class);
         //还是名字开头好,fd下次在来如果不一样是会修改的。所以不用管 不需要删除
         $redis->set($frame->data,$frame->fd);
    	
    	
    		// $server->push($frame->fd, 'Recv: ' . $frame->data);	
    	
        
    }

    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        // login的时候记录一个用户的等级，现在我还想删除以名字为键的fd
        $text=ApplicationContext::getContainer();
         $redis = $text->get(\Redis::class);
       //$redis=$this->container->get(\Redis::class);
        
        $a=$redis->del('id');
       
    }

    public function onOpen(WebSocketServer $server, Request $request): void
    {
    	
        $server->push($request->fd, 'Opened');
    }
}
?>