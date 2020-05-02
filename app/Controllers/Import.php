<?php

//    _____  .__         .__        
//   /  _  \ |  | _____  |__| ____  
//  /  /_\  \|  | \__  \ |  |/    \ 
// /    |    \  |__/ __ \|  |   |  \
// \____|__  /____(____  /__|___|  /
//         \/          \/        \/

namespace App\Controllers;

use App\Entities\Item;
use App\Models\BooksModel;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Libraries\PDFExtractor;

use App\Libraries\Vd;

/**
 * Description of Import
 *
 * @author alain
 */
class Import extends BaseController
{
    /**
     * Function: Index
     * 
     * will process the request
     * 
     * @access public
     * @param  array
     * @return none
     * 
     * *******************************************/
    public function index(...$params)
    {
        $options = [
            'book',
            'music',
            'plugin'
        ];
        
        $option = isset($params[0]) ? $params[0] : '';
        
        if(in_array($option, $options))
        {
            switch ($option)
            {
                case 'book':
                    $this->book($params);
                    break;
                case 'music':
                    break;
                case 'plugin':
                    break;
            }
        }
        else
        {
            echo "Error: Incorrect Option: {$option}";
        }
    }
    
    // --------------------------------------------
    
    /**
     * Function: book
     * 
     * will import a new book into DOWNLOAD and SEARCH
     * micro-services
     * 
     * @access public
     * @param  array
     * @return none
     * 
     * ********************************************/
    public function book(...$params)
    {           
        try
        {
            $ms_search = new Request();

            $ms_response = $ms_search->set_payload([
                'email'     => SRCH_USER,
                'password'  => SRCH_PSWD
            ])->post(URL_TOKEN);                         

            $token = "";
            if($ms_response->getStatusCode() == 2)
            {
                $token = $ms_response->getPayload()->{PRM_TOKEN};
            }
            else
            {
                throw new Exception(MSG_16);
            }
            
            log_message('debug', "Token validated successfully");
            
            $added_download = 0;
            $added_search   = 0;

            $booksModel = new BooksModel();
            $entries = $booksModel->retrieve_all_filename(TYPE_BOOK);
            if($handle = opendir(BOOKS_PATH))
            {
                while(FALSE != ($entry = readdir($handle)))
                {
                    if(in_array($entry, array('.', '..', '.htaccess', 'all_books')))
                    {
                        continue;
                    }

                    $full_path = BOOKS_PATH."{$entry}";
                    
                    log_message('debug', "Check if exist: {$full_path}");

                    if(file_exists($full_path) && !in_array($entry, $entries))
                    {
                        $item = new Item();

                        $item->setFilename($entry);
                        $item->{FLD_TYPE}       = TYPE_BOOK;
                        $item->{FLD_STATUS}     = ST_ACTIVE;
                        $item->{FLD_CREATED_AT} = strtotime('now');
                        $item->{FLD_CREATED_BY} = 'BookCron';

                        $booksModel->save($item);
                        
                        $added_download++;
                        
                        log_message('debug', "Book added: {$entry}");
                    }
                    
                    if(($item = $booksModel->retrieve_by_filename($entry)) != NULL)
                    {
                        $ms_search = new Request();
                        $response = $ms_search
                                ->set_header("TOKEN", $token)
                                ->head(URL_BOOKS."/".$item->{FLD_ITEM_ID});

                        if($response->getStatusCode() == 4)
                        {   
                            
                            $identifiers = [];
                            $identifiers[] = "CURL:".$item->{FLD_ITEM_ID};
                            
                            $pdf = new PDFExtractor($full_path);
                            
                            $pdf_pages              = $pdf->getPageCount();
                            $pdf_thumbnail          = $pdf->getThumbnail();
                            $pdf_thumbnail_small    = $pdf->getThumbnailSmall();
                            $pdf_isbn               = $pdf->getISBN();                       

                            if( ! empty($pdf_isbn))
                            {
                                $identifiers[] = strlen($pdf_isbn) == 10
                                        ? "ISBN_10:{$pdf_isbn}"
                                        : "ISBN_13:{$pdf_isbn}";
                            }
                            
                            $payload  = [
                                "filename" => $item->{FLD_ITEM_ID}.".".IMG_FRMT,
                                "image"    => $pdf_thumbnail 
                            ];
                            $ms_thumbnail  = new Request();
                            $response = $ms_thumbnail
                                    ->set_header("TOKEN", $token)
                                    ->set_payload($payload)
                                    ->post(URL_IMAGES);

                            $thumbnail_url = ($response->getStatusCode() == 2)
                                ? $response->getPayload()->thumbnail
                                : "";

                            $payload  = [
                                "filename" => $item->{FLD_ITEM_ID}.".".IMG_FRMT,
                                "image"    => $pdf_thumbnail_small
                            ];
                            $ms_image = new Request();
                            $response = $ms_image
                                    ->set_header("TOKEN", $token)
                                    ->set_payload($payload)
                                    ->post(URL_IMAGES);
                            $thumbnail_small_url = ($response->getStatusCode() == 2)
                                ? $response->getPayload()->thumbnail
                                : "";                      
                            
                            $payload = [
                                FLD_ISBN            => $item->{FLD_ITEM_ID},
                                FLD_TITLE           => str_replace(array("_", "-"), " ", $item->{FLD_FILENAME}),
                                FLD_AUTHORS         => "",
                                FLD_DESCRIPTION     => "",
                                FLD_PUBLISHER       => "",
                                FLD_PUBLISHED_DATE  => date(FRMT_DATE, strtotime("1970-08-03 00:00:00")),
                                FLD_IDENTIFIERS     => implode("::", $identifiers),
                                FLD_PAGE_COUNT      => $pdf_pages,
                                FLD_CATEGORIES      => "",
                                FLD_LANGUAGE        => "en",
                                FLD_THUMBNAIL       => $thumbnail_url,
                                FLD_THUMBNAIL_SMALL => $thumbnail_small_url,
                                FLD_TEXT_SNIPPED    => "",
                                FLD_STATUS          => ST_INACTIVE,
                                FLD_FILENAME        => $item->{FLD_FILENAME},
                                FLD_CREATED_AT      => strtotime('now'),
                                FLD_CREATED_BY      => 32,
                                FLD_MODIFIED_AT     => strtotime('now'),
                                FLD_MODIFIED_BY     => 32
                            ];
                        
                            $ms_search = new Request();
                            $response = $ms_search
                                    ->set_header("TOKEN", $token)
                                    ->set_payload($payload)
                                    ->post(URL_BOOKS);
                            
                            if($response->getStatusCode() == 2)
                            {
                                $added_search++;
                            }
                        }
                    }
                
                    if($added_search > 1)
                    {
                        continue;
                    }
                }
            }
            
            echo "Imported to Download: {$added_download}\n";
            echo "Imported into Search: {$added_search}\n";
        }
        catch(Exception $e)
        {
            log_message('error', $e->getMessage());
            echo "ERROR: {$e->getMessage()}";
        }
    }
    
    // --------------------------------------------
}
