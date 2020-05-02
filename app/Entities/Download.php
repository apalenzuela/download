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
 * Description of Download
 *
 * @author alain
 */
class Download extends Entity 
{
    protected $attributes = [
        FLD_ID          => NULL,
        FLD_ITEM_ID     => NULL,
        FLD_DELAY       => NULL,
        FLD_START_BYTE  => NULL,
        FLD_BYTE_COUNT  => NULL,
        FLD_FILE_SIZE   => NULL,
        FLD_CREATED_AT  => NULL,
        FLD_CREATED_BY  => NULL
    ];
    
    // ------------------------------------------
    
    /**
     * Function: __construct
     * 
     * get instance of the class
     * 
     * ******************************************/
    public function __construct(mixed $data = null)
    {
        parent::__construct($data);
    }
}
