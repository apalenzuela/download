<?php

//    _____  .__         .__        
//   /  _  \ |  | _____  |__| ____  
//  /  /_\  \|  | \__  \ |  |/    \ 
// /    |    \  |__/ __ \|  |   |  \
// \____|__  /____(____  /__|___|  /
//         \/          \/        \/

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Transaction;

/**
 * Description of TransactionsModel
 *
 * @author alain
 */
class TransactionsModel extends Model
{

    protected $table      = 'transactions';
    protected $primaryKey = 'id';
    protected $returnType    = 'App\Entities\Transaction';
    protected $allowedFields = [
        FLD_ID,
        FLD_EVENT,
        FLD_ITEMS,
        FLD_CREATED_AT,
        FLD_CREATED_BY
    ];

    // ------------------------------------------

    /**
     * Function: save
     * 
     * will save the information into the table and
     * will log the query
     * 
     * @access public
     * @param  mixed 
     * @return none
     * 
     * ****************************************** */
    public function save($data): bool
    {
        $return = parent::save($data);
        log_message('debug', (string) $this->getLastQuery());
        return $return;
    }

    // ---------------------------------------------

    /**
     * Function: retrieve_before_date
     * 
     * will return all entries before the date passed
     * 
     * @access public
     * @param  string (Date)
     * @return array
     * 
     * ******************************************** */
    public function retrieve_before_date($date): array
    {
        $date_for_search = strtotime($date);
        $result          = $this->where(FLD_CREATED_AT . " <= ", $date_for_search);
        log_message('debug', (string) $this->getLastQuery());
        return $result;
    }

    // ---------------------------------------------

    /**
     * Function: retrieve_after_date
     * 
     * will return all entries after the date passed
     * 
     * @access public
     * @param  string (date)
     * @return array
     * 
     * ******************************************* */
    public function retrieve_after_date($date): array
    {
        $date_for_search = strtotime($date);
        $result          = $this->where(FLD_CREATED_AT . " >= ", $date_for_search);
        log_message('debug', (string) $this->getLastQuery());
        return $result;
    }

    // ---------------------------------------------
}
