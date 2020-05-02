<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Libraries;

use App\Libraries\Vd;

use App\Libraries\Request;
use App\Libraries\Response;

/**
 * Description of Authenticate
 *
 * @author alain
 */
class Authenticate
{
    // -------------------------------------------
    
    public function isValid($token = '')
    {
        $request  = new Request();
        $response = $request->set_payload(array('t' => $token))
                            ->get(URL_TOKEN);

        if($response->getStatusCode() == 2)
        {   
            return $response->getPayload();
        }
        
        return FALSE;
    }
    
    // -------------------------------------------
    
    
}