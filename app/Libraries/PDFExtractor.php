<?php

//    _____  .__         .__        
//   /  _  \ |  | _____  |__| ____  
//  /  /_\  \|  | \__  \ |  |/    \ 
// /    |    \  |__/ __ \|  |   |  \
// \____|__  /____(____  /__|___|  /
//         \/          \/        \/

namespace App\Libraries;

/**
 * Description of PDFExtractor
 *
 * @author alain
 */
class PDFExtractor
{
    protected $filename = NULL;
    
    // -------------------------------------------------
    
    public function __construct($filename = '')
    {
        $this->setFilename($filename);
    }
    
    // -------------------------------------------------
    
    /**
     * Function: setFilename
     * 
     * will accept the full file path and verify if
     * is a PDF file
     * 
     * @access public
     * @param  string filename
     * @return none
     * 
     * **************************************************/
    public function setFilename($filename = '')
    {
        if(file_exists($filename) && is_readable($filename))
        {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if(finfo_file($finfo, $filename) == 'application/pdf')
            {
                $this->filename = $filename;
            }
        }
    }
    
    // -------------------------------------------------
    
    public function getPageCount()
    {
        if(empty($this->filename))
        {
            return 0;
        }
        
        $im = new \Imagick();
        $im->pingImage($this->filename);
        
        return $im->getNumberImages();
    }
    
    // -------------------------------------------------
    
    /**
     * Function: getThumbnail
     * 
     * will return the image in base64
     * 
     * @access public
     * @param  none
     * @return binary
     * 
     * *************************************************/
    public function getThumbnail()
    {
        if(empty($this->filename))
        {
            return "";
        }
        
        $file_parts = pathinfo($this->filename);
        
        $tmp_dir  = sys_get_temp_dir();
        $tmp_file = "{$tmp_dir}/{$file_parts['filename']}1.png";
        
        $im = new \Imagick();
        $im->readImage("{$this->filename}[0]");
        $im->setResolution(IMG_REG_X, IMG_REG_Y);
        $im->setIteratorIndex(0);
        $im->setImageFormat(IMG_FRMT);
        $im->writeImage($tmp_file);
        $im->clear();
        $im->destroy();
        
        $content = base64_encode(file_get_contents($tmp_file));
        unlink($tmp_file);
        
        return $content;
    }
    
    // -------------------------------------------------
   
    /**
     * Function: getThumbnailSmall
     * 
     * will return the image in base64
     * 
     * @access public
     * @param  none
     * @return binary
     * 
     * *************************************************/
    public function getThumbnailSmall()
    {
        if(empty($this->filename))
        {
            return "";
        }
        
        $file_parts = pathinfo($this->filename);
        
        $tmp_dir  = sys_get_temp_dir();
        $tmp_file = "{$tmp_dir}/{$file_parts['filename']}2.png";
        
        $im = new \Imagick();
        $im->readImage("{$this->filename}[0]");
        $im->setResolution(IMG_SML_X, IMG_SML_Y);
        // $im->setIteratorIndex(0);
        $im->setImageFormat(IMG_FRMT);
        $im->writeImage($tmp_file);
        $im->clear();
        $im->destroy();
        
        $content = base64_encode(file_get_contents($tmp_file));
        unlink($tmp_file);
        
        return $content;
    }
    
    // -------------------------------------------------
    
    /**
     * Function: getISBN
     * 
     * will recover the ISBN from the PDF if exist
     * 
     * @access public
     * @param  none
     * @return string
     * 
     * *************************************************/
    public function getISBN()
    {
        if(empty($this->filename))
        {
            return "";
        }
        
        $cmd = "pdfgrep -h --color never ISBN ".$this->filename;
        
        $isbn = shell_exec($cmd);
        
        return $isbn;
    }
    
    // -------------------------------------------------
}
