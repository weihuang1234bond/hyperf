<?php
namespace App\Controller; 
use Hyperf\Contract\ConnectionInterface;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Pool\Pool;
/**
*@AutoController();
*
*/
class Connectionpool extends  Pool{
	 public function createConnection(): ConnectionInterface
    {
        return new MyConnection();
    }
    
    
}