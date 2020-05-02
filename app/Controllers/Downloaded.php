<?php

//    _____  .__         .__        
//   /  _  \ |  | _____  |__| ____  
//  /  /_\  \|  | \__  \ |  |/    \ 
// /    |    \  |__/ __ \|  |   |  \
// \____|__  /____(____  /__|___|  /
//         \/          \/        \/

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

use App\Entities\Item;
use App\Entities\Transaction;
use App\Entities\Download;

use App\Models\BooksModel;
use App\Models\TransactionsModel;
use App\Models\DownloadsModel;

use App\Libraries\Authenticate;
use App\Libraries\Vd;

/**
 * Description of Downloaded
 *
 * @author alain
 */
class Downloaded extends BaseController 
{
    /**
     * Function: list_all
     * 
     * will return the list of all downloaded books
     * 
     * @access public
     * @param  array
     * @return binary
     * 
     * **************************************/
    public function list_all(...$params)
    {
        $authorized_roles = [
            USR_SUPER,
            USR_SELF,
            BK_ADMIN,
            BK_LIST,
        ];
        
        try
        {
            $authenticate = new Authenticate();
            $json = $this->request->hasHeader(PRM_TOKEN)
                    ? $authenticate->isValid($this->request->getHeader(PRM_TOKEN)->getValue())
                    : NULL;
            
            if($json == NULL)
            {
                (new TransactionsModel())->save(new Transaction(
                    EV_DOWN_STATS,
                    json_encode([EX_MSG => MSG_14]),
                    0
                ));
                
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                        ->setBody(json_encode([EX_MSG => MSG_14]));
            }
            
            if($json->roles == NULL
            || !count(array_intersect($json->roles, $authorized_roles)) > 0)
            {
                $tModel = new TransactionsModel();
                $tModel->save(new Transaction(
                    EV_DOWN_VALID,
                    json_encode([EX_MSG => MSG_14]),
                    0
                ));
            
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                        ->setBody(json_encode([EX_MSG => MSG_19]));
            }
            
            $offset = (($offset = $this->request->getGet(PRM_OFFSET)) != NULL)
                    ? $offset
                    : DFLT_OFFSET;
            
            $pagesize = (($pagesize = $this->request->getGet(PRM_PAGESIZE)) != NULL)
                    ? $pagesize
                    : DFLT_PAGESIZE;
            
            $downloads = new DownloadsModel();

            $result = [
                PRM_TOTAL   => $downloads->countAll(),
                PRM_ROWS    => $downloads->retrieve_all($offset, $pagesize) 
            ];
  
            return $this->response
                    ->setHeader("Content-Type", "application/json")
                    ->setStatusCode(ResponseInterface::HTTP_OK)
                    ->setBody(json_encode($result));
        }
        catch(Exception $e)
        {
            log_message('error', $e->getMessage());
            
            (new TransactionsModel())->save(new Transaction(
                EV_DOWN_ERROR,
                json_encode([EX_MSG => $e->getMessage()]),
                empty($json->id) ? 0 : $json->id
            ));
                
            return $this->response
                    ->setHeader("Content-Type", "application/json")
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setBody(json_encode([EX_MSG => MSG_8]));
        }
    }
    
    // --------------------------------------------------
    
    /**
     * Function: show
     * 
     * will return the list of all downloaded books for
     * a single user
     * 
     * @access public
     * @param  array
     * @return binary
     * 
     * **************************************/
    public function show(...$params)
    {
        
    }
    
    // --------------------------------------------------
}
