<?php
namespace App\Controller;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use App\Controller\Common;
use Swoole\Table;
use Hyperf\Utils\ApplicationContext;
// 链接
use Hyperf\Pool\SimplePool\PoolFactory;
use Swoole\Coroutine\Http\Client;
use App\Controller\Connectionpool;

/**
*@AutoController()
* 
*/

class Test
{
	/**
	*@Inject
	* @var Common
	*/
	private $com;
	public function test(){
		$this->com->test_view();
	}
	
	public function asp(){
		//return 会报错 只能echo 或者$response
		
	}
	
	public function table(){
		//不行、 table只用于进程与进程之间。
		// $table= new \Swoole\Table(10);
		// $table->column('level',Table::TYPE_INT,1);
		// $table->create();
		// $table->set('login',['level'=>3]);
		// $b=$table->get('login');
		// var_dump($b);
		$text=ApplicationContext::getContainer();
		$redis = $text->get(\Redis::class);
		$redis->set('id',1);
		$v=$redis->get('id');
		echo $v;
	}
	
	
	public function gettable(){
		$table= new \Swoole\Table(10);
		$a=$table->get('login');
		var_dump($a);
	}
	
	public function gettextt(){
		$container=ApplicationContext::getContainer();
		$factory = $container->get(PoolFactory::class);
		$host='127.0.0.1';
		$port='3306';
		$ssl=true;
		$pool = $factory->get('Connectionpool', function () use ($host, $port, $ssl) {
		    return new Client($host, $port, $ssl);
		}, [
		    'max_connections' => 2
		]);
		
		$connection = $pool->get();
		
		$client = $connection->getConnection(); // 即上述 Client.
		
		// Do something.s
		var_dump($connection);
		$connection->release();
	}
}