<?php

//    _____  .__         .__        
//   /  _  \ |  | _____  |__| ____  
//  /  /_\  \|  | \__  \ |  |/    \ 
// /    |    \  |__/ __ \|  |   |  \
// \____|__  /____(____  /__|___|  /
//         \/          \/        \/

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Download;

use App\Libraries\Vd;

/**
 * Description of DownloadsModel
 *
 * @author alain
 */
class DownloadsModel extends Model
{
    protected $table = 'Downloads';
    protected $primaryKey = 'id';
    protected $dates = [ FLD_CREATED_AT ];
    
    protected $returnType = 'App\Entities\Download';
    protected $allowedFields = [
        FLD_ITEM_ID,
        FLD_DELAY,
        FLD_START_BYTE,
        FLD_BYTE_COUNT,
        FLD_FILE_SIZE,
        FLD_CREATED_AT,
        FLD_CREATED_BY
    ];
    
    // ---------------------------------------------------------------------
    
    /**
     * Function: count_by_user_id
     * 
     * will count how many times user logged 
     * last year
     * 
     * @access public
     * @param  integer user_id
     * @return integer
     * 
     * ******************************************/
    public function count_by_user_id($user_id = 0, $datetime = NULL)
    {
        $result = $this
                ->where(FLD_CREATED_BY, $user_id)
                ->where(FLD_CREATED_AT." >= ", $datetime)
                ->countAllResults();
        
        log_message('debug', (string)$this->getLastQuery());
        
        return $result;
    }
    
    // ---------------------------------------------------------------------
    
    
    /**
     * Function: count_by_user_id
     * 
     * will count how many times user logged 
     * last year
     * 
     * @access public
     * @param  integer user_id
     * @return integer
     * 
     * ******************************************/
    public function count_all($datetime = NULL)
    {
        $result = $this
                ->where(FLD_CREATED_AT." >= ", $datetime)
                ->countAllResults();
        
        log_message('debug', (string)$this->getLastQuery());
        
        return $result;
    }
    
    // ---------------------------------------------------------------------    
    
    /**
     * Function: retrieve_by_month
     * 
     * will retrieve by months all logged
     * 
     * @access public
     * @param  integer
     * @param  date
     * @return array
     * 
     * ********************************************/
    public function retrieve_by_month($user_id = 0, $datetime = NULL)
    {
        $datetime = date(MYSQL_DATE_FORMAT, $datetime);   
        
        $results = $this
                ->select('EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME('.FLD_CREATED_AT.')) AS _yearmonth_')
                ->select('COUNT(*) AS counter')
                ->where(FLD_CREATED_BY, $user_id)
                ->where("FROM_UNIXTIME(".FLD_CREATED_AT.") >= ", $datetime)
                ->groupBy('EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME('.FLD_CREATED_AT.'))')
                ->get();
        
        log_message('debug', (string)$this->getLastQuery());
        
        $outcome = [];
        
        $start      = (new \Datetime($datetime))->modify('first day of this month');
        $ends       = (new \Datetime(date(MYSQL_DATE_FORMAT)))->modify('first day of next month');
        $interval   = \DateInterval::createFromDateString('1 month');
        $period     = new \DatePeriod($start, $interval, $ends);
        
        foreach($period as $dt)
        {
            $outcome[$dt->format('Ym')] = 0;
        }

        foreach($results->getResult() as $row)
        {
            $outcome[$row->{'_yearmonth_'}] = $row->{'counter'};
        }

        return $outcome;
    }
    
    // ---------------------------------------------------------------------
    
    /**
     * Function: retrieve_by_month
     * 
     * will retrieve by months all logged
     * 
     * @access public
     * @param  integer
     * @param  date
     * @return array
     * 
     * ********************************************/
    public function retrieve_all_by_month($datetime = NULL)
    {
        $datetime = date(MYSQL_DATE_FORMAT, $datetime);   
        
        $results = $this
                ->select('EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME('.FLD_CREATED_AT.')) AS _yearmonth_')
                ->select('COUNT(*) AS counter')
                ->where("FROM_UNIXTIME(".FLD_CREATED_AT.") >= ", $datetime)
                ->groupBy('EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME('.FLD_CREATED_AT.'))')
                ->get();
        
        log_message('debug', (string)$this->getLastQuery());
        
        $outcome = [];
        
        $start      = (new \Datetime($datetime))->modify('first day of this month');
        $ends       = (new \Datetime(date(MYSQL_DATE_FORMAT)))->modify('first day of next month');
        $interval   = \DateInterval::createFromDateString('1 month');
        $period     = new \DatePeriod($start, $interval, $ends);
        
        foreach($period as $dt)
        {
            $outcome[$dt->format('Ym')] = 0;
        }

        foreach($results->getResult() as $row)
        {
            $outcome[$row->{'_yearmonth_'}] = $row->{'counter'};
        }

        return $outcome;
    }
    
    // ---------------------------------------------------------------------    
    
    /**
     * Function: most_downloaded
     * 
     * will retrieve most downloaded books
     * 
     * @access public
     * @param  integer
     * @param  date
     * @return array
     * 
     * ********************************************/
    public function most_downloaded($limit = 5)
    {
        $rows = $this
                ->select("item_id AS isbn, COUNT(*) AS amount", FALSE)
                ->groupBy("item_id")
                ->orderBy("Amount", "DESC")
                ->findAll($limit);
        
        log_message('debug', (string)$this->getLastQuery());
        
        $result = [];
        if(is_array($rows) && count($rows) > 0)
        {
            foreach($rows as $row)
            {
                $entry = new \stdClass();
                
                $entry->{FLD_ISBN}  = $row->{FLD_ISBN};
                $entry->{'amount'}  = $row->amount;
                
                $result[] = $entry;
            }
        }
        
        return $result;
    }
    
    // ---------------------------------------------------------------------
    
    /**
     * Function: most_downloaded
     * 
     * will retrieve most downloaded books
     * 
     * @access public
     * @param  integer
     * @param  date
     * @return array
     * 
     * ********************************************/
    public function retrieve_all($offset = DFLT_OFFSET, $pagesize = DFLT_PAGESIZE)
    {
        $fields = [
            "{$this->table}.".FLD_ID,
            "{$this->table}.".FLD_ITEM_ID,
            "{$this->table}.".FLD_DELAY,
            "{$this->table}.".FLD_START_BYTE,
            "{$this->table}.".FLD_BYTE_COUNT,
            "{$this->table}.".FLD_FILE_SIZE,
            "{$this->table}.".FLD_CREATED_AT,
            "{$this->table}.".FLD_CREATED_BY,
            "Items.".FLD_FILENAME,
            "Items.".FLD_TYPE,
            "Items.".FLD_STATUS
        ];
        
        $rows = $this
                ->select(implode(", ", $fields), FALSE)
                ->join("Items", "{$this->table}.".FLD_ITEM_ID."= Items.".FLD_ITEM_ID)
                ->orderBy("{$this->table}.".FLD_CREATED_AT, "DESC")
                ->findAll($pagesize, $offset);
        
        log_message('debug', (string)$this->getLastQuery());
        
        $fields = [
            FLD_ID,
            FLD_ITEM_ID,
            FLD_DELAY,
            FLD_START_BYTE, 
            FLD_BYTE_COUNT,
            FLD_FILE_SIZE,
            FLD_CREATED_AT,
            FLD_CREATED_BY,
            FLD_FILENAME,
            FLD_TYPE,
            FLD_STATUS
        ];
        
        $result = [];
        if(is_array($rows) && count($rows) > 0)
        {
            foreach($rows as $row)
            {
                $rec = [];
                foreach($fields as $field)
                {
                    $rec[$field] = $row->{$field};
                }
                
                $result[] = $rec;
            }
        }      
        
        return $result;

    }
    
    // ---------------------------------------------------------------------
    
}