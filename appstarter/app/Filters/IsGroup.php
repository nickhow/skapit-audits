<?php 

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class IsGroup implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        
        if (!session()->get('group_id') && !session()->get('is_admin')){
            $uri = current_url(true);
            if(isset($uri)){
                $uri = "?refer=".$uri;
            }
            return redirect()->to('/signin'.$uri);
        }
        
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}