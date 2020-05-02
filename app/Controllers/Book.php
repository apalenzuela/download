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
 * Description of Book
 *
 * @author alain
 */
class Book extends BaseController 
{
    /**
     * Function: download
     * 
     * will download the book if credential
     * is valid
     * 
     * @access public
     * @param  array
     * @return binary
     * 
     * **************************************/
    public function download(...$params)
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
            
            $json = ($json == NULL)
                    ? $this->request->getGet(PRM_TOKEN)
                    : $json;
           
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
            
            $md5 = isset($params[0]) ? $params[0] : '';
   
            $booksModel = new BooksModel();
            if(($book = $booksModel->retreive_by_item_id($md5)) == NULL)
            {
                $tModel = new TransactionsModel();
                $tModel->save(new Transaction(
                    EV_DOWN_ERROR,
                    json_encode([EX_MSG => MSG_41]),
                    empty($json->id) ? 0 : $json->id
                ));
            
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                        ->setBody(json_encode([EX_MSG => MSG_41]));
            }
            
            $filename = BOOKS_PATH."{$book->{FLD_FILENAME}}";
            if(!file_exists($filename) || !is_readable($filename))
            {
                (new TransactionsModel())->save(new Transaction(
                    EV_DOWN_ERROR,
                    json_encode([EX_MSG => MSG_41]),
                    empty($json->id) ? 0 : $json->id
                ));
                
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                        ->setBody(json_encode([EX_MSG => MSG_41]));
            }
            
            $download = new Download();
            $download->setAttributes([
                FLD_ITEM_ID     => $book->{FLD_ITEM_ID},
                FLD_DELAY       => 0,
                FLD_START_BYTE  => 0,
                FLD_BYTE_COUNT  => filesize($filename),
                FLD_FILE_SIZE   => filesize($filename),
                FLD_CREATED_AT  => strtotime('now'),
                FLD_CREATED_BY  => empty($json->{FLD_ID}) ? 0 : $json->{FLD_ID}
            ]);
            $dModel = new DownloadsModel();
            $dModel->save($download);
            
            (new TransactionsModel())->save(new Transaction(
                EV_DOWN_DOWN,
                json_encode([EX_MSG => MSG_0]),
                empty($json->id) ? 0 : $json->id
            ));
                
            return $this->response
                    ->download($filename, NULL)
                    ->setFileName($book->{FLD_FILENAME});
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
    
    // ---------------------------------------
    
    public function upload(...$params)
    {
        
    }
    
    // ---------------------------------------
    
    public function update(...$params)
    {
        
    }
    
    // ---------------------------------------
    
    public function delete(...$params)
    {
        $authorized_roles = [
            USR_SUPER,
            BK_ADMIN,
            BK_LIST,
            BK_DELETE
        ];
        
        try
        {
            $authenticate = new Authenticate();
            $json = $this->request->hasHeader(PRM_TOKEN)
                    ? $authenticate->isValid($this->request->getHeader(PRM_TOKEN)->getValue())
                    : NULL;
            
            if($json == NULL)
            {
                $tModel = new TransactionsModel();
                $tModel->save(new Transaction(
                    EV_DOWN_ERROR,
                    json_encode([EX_MSG => MSG_14]),
                    empty($json->id) ? 0 : $json->id
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
                    EV_DOWN_ERROR,
                    json_encode([EX_MSG => MSG_19]),
                    empty($json->id) ? 0 : $json->id
                ));
                
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                        ->setBody(json_encode([EX_MSG => MSG_19]));
            }
            
            $md5 = isset($params[0]) ? $params[0] : '';
   
            $booksModel = new BooksModel();
            if(($book = $booksModel->retreive_by_item_id($md5)) == NULL)
            {
                $tModel = new TransactionsModel();
                $tModel->save(new Transaction(
                    EV_DOWN_ERROR,
                    json_encode([EX_MSG => MSG_41]),
                    empty($json->id) ? 0 : $json->id
                ));
                
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                        ->setBody(json_encode([EX_MSG => MSG_41]));
            }
            
            $filename = BOOKS_PATH."{$book->{FLD_FILENAME}}";
            if(!file_exists($filename) || !is_writable($filename))
            {
                $tModel = new TransactionsModel();
                $tModel->save(new Transaction(
                    EV_DOWN_ERROR,
                    json_encode([EX_MSG => MSG_41]),
                    empty($json->id) ? 0 : $json->id
                ));
                
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                        ->setBody(json_encode([EX_MSG => MSG_41]));
            }
            
            if(unlink($filename) && $booksModel->delete_by_item_id($book->{FLD_ITEM_ID}))
            {
                $tModel = new TransactionsModel();
                $tModel->save(new Transaction(
                    EV_DOWN_DELETE,
                    json_encode([EX_MSG => MSG_4]),
                    empty($json->id) ? 0 : $json->id
                ));
                
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_ACCEPTED)
                        ->setBody(json_encode([EX_MSG => MSG_4]));
            }
            else
            {
                $tModel = new TransactionsModel();
                $tModel->save(new Transaction(
                    EV_DOWN_ERROR,
                    json_encode([EX_MSG => MSG_46]),
                    empty($json->id) ? 0 : $json->id
                ));
                
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_CONFLICT)
                        ->setBody(json_encode([EX_MSG => MSG_46]));
            }
        }
        catch(Exception $e)
        {
            log_message('error', $e->getMessage());
            
            $tModel = new TransactionsModel();
            $tModel->save(new Transaction(
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
    
    // ---------------------------------------
    
    /**
     * Function: exists
     * 
     * will check if the book exists
     * 
     * @access public
     * @param  array
     * @return none
     * 
     * ***************************************/
    public function exists(...$params)
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
                $tModel = new TransactionsModel();
                $tModel->save(new Transaction(
                    EV_DOWN_ERROR,
                    json_encode([EX_MSG => MSG_14]),
                    empty($json->id) ? 0 : $json->id
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
                    EV_DOWN_ERROR,
                    json_encode([EX_MSG => MSG_19]),
                    empty($json->id) ? 0 : $json->id
                ));
                
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                        ->setBody(json_encode([EX_MSG => MSG_19]));
            }
            
            $md5 = isset($params[0]) ? $params[0] : '';
   
            $booksModel = new BooksModel();
            if(($book = $booksModel->retreive_by_item_id($md5)) == NULL)
            {
                $tModel = new TransactionsModel();
                $tModel->save(new Transaction(
                    EV_DOWN_ERROR,
                    json_encode([EX_MSG => MSG_41]),
                    empty($json->id) ? 0 : $json->id
                ));
                
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                        ->setBody(json_encode([EX_MSG => MSG_41]));
            }
            
            $filename = BOOKS_PATH."{$book->{FLD_FILENAME}}";
            if(!file_exists($filename) || !is_readable($filename))
            {
                $tModel = new TransactionsModel();
                $tModel->save(new Transaction(
                    EV_DOWN_ERROR,
                    json_encode([EX_MSG => MSG_41]),
                    empty($json->id) ? 0 : $json->id
                ));
                
                return $this->response
                        ->setHeader("Content-Type", "application/json")
                        ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                        ->setBody(json_encode([EX_MSG => MSG_41]));
            }
            
            $tModel = new TransactionsModel();
            $tModel->save(new Transaction(
                EV_DOWN_EXIST,
                json_encode([EX_MSG => MSG_44]),
                empty($json->id) ? 0 : $json->id
            ));
            
            return $this->response
                    ->setHeader("Content-Type", "application/json")
                    ->setStatusCode(ResponseInterface::HTTP_OK)
                    ->setBody(json_encode([EX_MSG => MSG_44]));
        }
        catch(Exception $e)
        {
            log_message('error', $e->getMessage());
            
            $tModel = new TransactionsModel();
            $tModel->save(new Transaction(
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
    
    // ---------------------------------------
}