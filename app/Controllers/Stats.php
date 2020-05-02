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
 * Description of Stats
 *
 * @author alain
 */
class Stats extends BaseController
{
    // --------------------------------------------------
    
    /**
     * Function: user
     * 
     * will return all statistics from a user
     * 
     * 
     * 
     * 
     * *************************************************/    
    public function user(...$params)
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
                    EV_DOWN_VALID,
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
                (new TransactionsModel())->save(new Transaction(
                    EV_DOWN_VALID,
                    json_encode([EX_MSG => MSG_14]),
                    0
                ));
            
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                        ->setBody(json_encode([EX_MSG => MSG_19]));
            }

            $stats = new DownloadsModel();

            $data = [
                'down_total'    => $stats->count_all(strtotime('-365 days')),
                'down_last_week'=> $stats->count_all(strtotime('-7 days')),
                'down_by_month' => $stats->retrieve_all_by_month(strtotime('-365 days')),
                'down_last_five'=> $stats->most_downloaded(10)        
            ];
                
            (new TransactionsModel())->save(new Transaction(
                EV_DOWN_STATS,
                json_encode([EX_MSG => MSG_0]),
                $json->{FLD_ID}
            ));
            
            return $this->response
                    ->setHeader("Content-Type", "application/json")
                    ->setStatusCode(ResponseInterface::HTTP_OK)
                    ->setBody(json_encode($data));
            
        }
        catch(Exception $e)
        {
            log_message('error', $e->getMessage());
            
            (new TransactionsModel())->save(new Transaction(
                EV_STATS_ERROR,
                json_encode([EX_MSG => $e->getMessage()]),
                empty($json->{FLD_ID}) ? 0 : $json->{FLD_ID}
            ));
                
            return $this->response
                    ->setHeader("Content-Type", "application/json")
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setBody(json_encode([EX_MSG => MSG_8]));
        }
    }
    
    // --------------------------------------------------
    
    public function users(...$params)
    {
        
    }
    
    // ---------------------------------------------------
}