<?
namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Paginator\Paginator;

/**
 * @AutoController()
 */
class ViewPage
{
    public function index(RequestInterface $request)
    {
    	
        $currentPage = $request->input('page', 1);
        $perPage = 10;
        $users = [
           
        ];
        return new Paginator($users, (int) $perPage, (int) $currentPage);
    }
}