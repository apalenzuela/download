<?php

//    _____  .__         .__        
//   /  _  \ |  | _____  |__| ____  
//  /  /_\  \|  | \__  \ |  |/    \ 
// /    |    \  |__/ __ \|  |   |  \
// \____|__  /____(____  /__|___|  /
//         \/          \/        \/

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Item;

use App\Libraries\Vd;

/**
 * Description of BookModel
 *
 * @author alain
 */
class BooksModel extends Model
{
    protected $table = "Items";
    protected $primaryKey = "id";
    
    protected $returnType = "App\Entities\Item";
    protected $allowedFields = [
        FLD_ID,
        FLD_ITEM_ID,
        FLD_FILENAME,
        FLD_TYPE,
        FLD_STATUS,
        FLD_CREATED_AT,
        FLD_CREATED_BY
    ];
    
    // ------------------------------------------------
    
    public function retreive_by_item_id($item_id = '')
    {
        $result = $this->where(FLD_ITEM_ID, $item_id)->first();
        log_message('debug', (string)$this->getLastQuery());
        return $result;
    }
    
    // -----------------------------------------------
    
    /**
     * Function: retrieve_by_filename
     * 
     * will return the first item that match the criteria
     * 
     * @access public
     * @param  string filename
     * @return Item/NULL
     * 
     * ************************************************/
    public function retrieve_by_filename($filename = ''): ?Item
    {
        if(empty($filename))
        {
            return NULL;
        }
        
        $result = $this->where(FLD_FILENAME, $filename)
                ->first();
        log_message('debug', (string)$this->getLastQuery());
        return $result;
    }
    
    
    /**
     * Function: retrieve_all
     * 
     * will return all items based on the TYPE
     * 
     * @access public
     * @param  string type
     * @return array
     * 
     * ************************************************/
    public function retrieve_all($type = ''): ?array
    {
        if( ! empty($type))
        {
            $this->where(FLD_TYPE, $type);
        }
        
        $result = $this->findAll();
        log_message('debug', (string)$this->getLastQuery());
        return $result;
    }
    
    // -----------------------------------------------
    
    /**
     * Function: retrieve_all_item_id
     * 
     * will return an array with all ITEM_ID based
     * on the status
     * 
     * @access public
     * @param  string type
     * @return array
     * 
     * ***********************************************/
    public function retrieve_all_filename($type = ''): ?array
    {
        if( ! empty($type))
        {
            $this->where(FLD_TYPE, $type);
        }
        
        $result = $this->findAll();
        log_message('debug', (string)$this->getLastQuery());
        $items = [];
        foreach($result as $item)
        {
            $items[] = $item->{FLD_FILENAME};
        }
        
        return $items;
    }
    
    // ------------------------------------------------
    
    /**
     * Function: delete_by_item_id
     * 
     * will DELETE the entry into the database, and the 
     * file physically, TRUE if both action were executed  
     * 
     * @access public
     * @param  string item_id
     * @param  string full_path
     * @return Boolean
     * 
     * ************************************************/
    public function delete_by_item_id($item_id)
    {
        $result = $this->delete([FLD_ITEM_ID => $item_id]);
        log_message('debug', (string)$this->getLastQuery());
        return ($result->affected_rows == 1);
    }
    
    // ------------------------------------------------
}
