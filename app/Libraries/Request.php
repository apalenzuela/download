<?php
//    _____  .__         .__        
//   /  _  \ |  | _____  |__| ____  
//  /  /_\  \|  | \__  \ |  |/    \ 
// /    |    \  |__/ __ \|  |   |  \
// \____|__  /____(____  /__|___|  /
//         \/          \/        \/

namespace App\Libraries;

use App\Libraries\Response;
use App\Libraries\Vd;

/**
 * Description of Request
 *
 * @author alain
 */
class Request
{
    // -----------------------------------------
    // Attributes
    // -----------------------------------------
    private $ch         = NULL;
    private $headers    = [];
    private $payload    = NULL;
    private $type       = NULL;
    
    // -----------------------------------------
    // Methods
    // -----------------------------------------
    
    public function __construct(){}
    
    // -----------------------------------------
    
    /**
     * Function: GET
     * 
     * will execute GET request
     * 
     * @access public
     * @param  string url
     * @return Response
     * 
     * *****************************************/
    public function get(string $url): Response 
    {
        $query_string = (is_array($this->payload) || is_object($this->payload))
                ? http_build_query($this->payload)
                : $this->payload;
        
        log_message('debug', "Request: GET {$url}?{$query_string}");

        return $this->execute(HTTP_GET, "{$url}?{$query_string}");
    }
    
    // -----------------------------------------
    
    /**
     * Function: POST
     * 
     * will execute POST request
     * 
     * @access public
     * @param  string url
     * @return Response
     * 
     * *****************************************/
    public function post(string $url): Response
    {
        return $this->execute(HTTP_POST, $url);
    }
    
    // -----------------------------------------
    
    public function put(string $url): Response
    {
        return $this->execute(HTTP_PUT, $url);
    }
    
    // -----------------------------------------
    
    public function patch(string $url): Response
    {
        return $this->execute(HTTP_PATCH, $url);
    }
    
    // -----------------------------------------
    
    public function delete(string $url): Response
    {
        return $this->execute(HTTP_DELETE, $url);
    }
    
    // -----------------------------------------
    
    public function options(string $url): Response
    {
        return $this->execute(HTTP_OPTIONS, $url);
    }
    
    // -----------------------------------------
    
    public function head(string $url)
    {
        return $this->execute(HTTP_HEAD, $url);
    }
    
    // -----------------------------------------
    
    /**
     * Function: set_header
     * 
     * will set new passed header and content
     * 
     * @access public
     * @param  string header
     * @param  string content
     * @return instance of the class
     * 
     * ****************************************/
    public function set_header(string $header = '', string $content = '')
    {
        if(empty($content) && strpos($header, ":") !== FALSE)
        {
            list($header, $content) = explode(":", $header);
        }
        
        $this->headers[trim($header)] = trim($content);
        
        return $this;
    }
    
    // -----------------------------------------
    
    /**
     * Function: get_headers
     * 
     * will build headers array
     * 
     * @access private
     * @param  none
     * @return array
     * 
     * ****************************************/
    private function get_headers(): array
    {
        if(count($this->headers) > 0)
        {
            $headers = [];
            foreach($this->headers as $header => $content)
            {
                $headers[] = "{$header}: {$content}";
            }
            
            return $headers;
        }
            
        return array();
    }
    
    // -----------------------------------------
    
    /**
     * Function: set_payload
     * 
     * will accept the passed payload to the class
     * 
     * @access public
     * @param  array/object
     * @return instance of the class
     * 
     * ******************************************/
    public function set_payload($payload)
    {
        $this->payload = $payload;
        return $this;
    }
    
    // -----------------------------------------
    
    /**
     * Function: execute
     * 
     * it will, with all information passed, execute
     * the CURL call any of the Micro-Service of the farm
     * 
     * @access private
     * 
     * ********************************************/
    private function execute($method, $url): Response 
    {
        $ch = curl_init();
        
        // ---------------------------------------
        // Pass URL 
        // ---------------------------------------
        curl_setopt($ch, CURLOPT_URL, $url);
        
        // ---------------------------------------
        // General assurance
        // ---------------------------------------
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
        
        // -----------------------------------------
        // Based on the method ...
        // -----------------------------------------
        switch($method)
        {
            case HTTP_POST:
                curl_setopt($ch, CURLOPT_POST, TRUE);
                if(count($this->payload) > 0)
                {
                    $json_payload = json_encode($this->payload);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
                    $this->set_header("Content-Length", strlen($json_payload));
                }
                break;
            case HTTP_PUT:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, HTTP_PUT);
                if(strlen($this->payload) > 0)
                {
                    $json_payload = json_encode($this->payload);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
                    $this->set_header('Content-Length', strlen($json_payload));
                }
                break;
            case HTTP_PATCH:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, HTTP_PATCH);
                if(strlen($this->payload) > 0)
                {
                    $json_payload = json_encode($this->payload);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
                    $this->set_header('Content-Length', strlen($json_payload));
                }
                break;
            case HTTP_OPTIONS:
                break;
            case HTTP_HEAD:
                break;
            case HTTP_DELETE:
                break;
            case HTTP_GET:
                break;
            default:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                break;
        }
        
        // ----------------------------------------
        // Pass Headers
        // ----------------------------------------
        $this->set_header("Content-Type", "application/json");
        $headers = $this->get_headers();
        if(count($headers) > 0)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        $response = new Response();
        if(($output = curl_exec($ch)) != FALSE)
        {   
            $httpInfo = curl_getinfo($ch);
            $response->import($output, $httpInfo);
            curl_close($ch);
        }
        else 
        {
            $response->import("", [503 => "Service Unavailable"]);
        }
        
        return $response;
    }
}