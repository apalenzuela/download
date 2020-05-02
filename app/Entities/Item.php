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
 * Description of Item
 *
 * @author alain
 */
class Item extends Entity 
{
    protected $dates  = [ FLD_CREATED_AT ];
    
    // --------------------------------------------------
    
    /**
     * Function: setFilename
     * 
     * will assign filename to the field and make an MD5
     * hash to ITEM_ID
     * 
     * @access public
     * @param  string filename
     * @return none
     * 
     * 
     * **************************************************/
    public function setFilename($filename = '')
    {
        $this->{FLD_FILENAME} = $filename;
        $this->{FLD_ITEM_ID} = hash(PSW_MD5, $this->{FLD_FILENAME});
    }
    
    // --------------------------------------------------
}
