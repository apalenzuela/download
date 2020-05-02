<?php

//    _____  .__         .__        
//   /  _  \ |  | _____  |__| ____  
//  /  /_\  \|  | \__  \ |  |/    \ 
// /    |    \  |__/ __ \|  |   |  \
// \____|__  /____(____  /__|___|  /
//         \/          \/        \/

namespace App\Entities;

use CodeIgniter\Entity;
use App\Libraries\Vd;

/**
 * Description of Transaction
 *
 * @author alain
 */
class Transaction extends Entity
{
    protected $dates    = [ FLD_CREATED_AT ];
    protected $fields   = [
        FLD_ID,
        FLD_EVENT,
        FLD_ITEMS,
        FLD_CREATED_AT,
        FLD_CREATED_BY
    ];
    
    // --------------------------------------------------
    
    /**
     * Function: __construct
     * 
     * will be a new instance of the class
     * 
     * @access public
     * @param  string event
     * @param  string items
     * @param  integer
     * @return none
     * 
     * **************************************************/
    public function __construct($event = '', $items = '', $who = 0)
    {
        parent::__construct();
        
        $this->{FLD_EVENT}      = $event;
        $this->{FLD_ITEMS}      = $items;
        $this->{FLD_CREATED_AT} = strtotime('now');
        $this->{FLD_CREATED_BY} = $who;
    }
    
    // --------------------------------------------------
}