<?php

//    _____  .__         .__        
//   /  _  \ |  | _____  |__| ____  
//  /  /_\  \|  | \__  \ |  |/    \ 
// /    |    \  |__/ __ \|  |   |  \
// \____|__  /____(____  /__|___|  /
//         \/          \/        \/

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

use App\Libraries\Authenticate;

/**
 * Description of BookFilter
 *
 * @author alain
 */
class BookFilter implements FilterInterface
{
    /**
     * Function: before
     * 
     * will be executed before any of the Members 
     * class methods
     * 
     * @access public
     * @param  RequestInterface
     * @return mixed
     * 
     * *********************************************/
    public function before(RequestInterface $request)
    {
        $authenticate = new Authenticate();
        
        $allow_methods = [
            HTTP_GET,
            HTTP_POST,
            HTTP_PUT,
            HTTP_PATCH,
            HTTP_DELETE
        ];

        if($request->hasHeader('TOKEN'))
        {
            $token = $request->getHeader('TOKEN')->getValue();
            
            if( ! $authenticate->isValid($token))
            {
                return Services::response()
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }
        }
        
        if( ! in_array($request->getMethod(TRUE), $allow_methods))
        {
            return Services::response()
                    ->setHeader("Content-Type", "application/json")
                    ->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }
        
        return;
    }
    
    //--------------------------------------------------------------------
    
    /**
     * Function: after
     * 
     * will be executed after all members class 
     * methods
     * 
     * @access public
     * @param  RequestInterface
     * @param  ResponseInterface
     * @return mixed
     * 
     * ************************************************/
    public function after(RequestInterface $request, ResponseInterface $response)
    {
        
    } 
}
