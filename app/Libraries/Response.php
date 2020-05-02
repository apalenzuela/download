<?php

//    _____  .__         .__        
//   /  _  \ |  | _____  |__| ____  
//  /  /_\  \|  | \__  \ |  |/    \ 
// /    |    \  |__/ __ \|  |   |  \
// \____|__  /____(____  /__|___|  /
//         \/          \/        \/

namespace App\Libraries;

use CodeIgniter\HTTP\ResponseInterface;

use App\Libraries\Vd;

/**
 * Description of Response
 *
 * @author alain
 */
class Response
{
    // -----------------------------------------
    // Attributes
    // -----------------------------------------
    protected $info     = NULL;
    protected $payload  = NULL;
    protected $headers  = [];
    protected $line     = "";
    
    private $http_codes = array(
        
        // [ Informational 1xx ]
        100 => "Continue",
        101 => "Switching Protocols",
        102 => "Processing",
        103 => "Checkpoint",                    // Unofficial
        
        // [ Successful 2xx ]
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        203 => "Non-Authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        207 => "Multi-Status",
        208 => "Already Reported",
        218 => "This is fine",                  // Unofficial
        226 => "IM Used",
        
        // [ Redirection 3xx ]
        300 => "Multiple Choices",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        306 => "Switch Proxy",
        307 => "Temporary Redirect",
        308 => "Permanent Redirect",
        
        // [ Client Error 4xx ]
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        407 => "Proxy Authentication Required",
        408 => "Request Timeout",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Payload Too Large",
        414 => "Request-URI Too Long",
        415 => "Unsupported Media Type",
        416 => "Range Not Satisfiable",
        417 => "Expectation Failed",
        418 => "I'm a teapot",
        419 => "Page Expired",                      // Unofficial
        420 => "Method Failure",                    // Unofficial
        421 => "Misdirected Request",
        422 => "Unprocessable Entity",
        423 => "Locked",
        424 => "Failed Dependency",
        425 => "Too Early",
        426 => "Upgrade Required",
        428 => "Precondition Required",
        429 => "Too Many Requests",
        430 => "Request Header Fields Too Large",   // Unofficial
        431 => "Request Header Fields Too Large",
        440 => "Login Time-out",                    // Unofficial
        444 => "No Response",                       // Unofficial
        449 => "Retry With",                        // Unofficial
        450 => "Blocked by Windows Parental Controls", // Unofficial
        451 => "Unavailable For Legal Reasons",     // Unofficial - Redirect IIS
        460 => "Client Close the connection",       // Unofficial
        463 => "The load balancer received an X-Forwarded-For", // Unofficial
        494 => "Request header too large",          // Unofficial
        495 => "SSL Certificate Error",             // Unofficial
        496 => "SSL Certificate Required",          // Unofficial
        497 => "HTTP Request Sent To HTTPS Port",   // Unofficial
        498 => "Invalid Token",                     // Unofficial
        499 => "Token Required",                    // Unofficial
    //  499 => "Client Closed Request",             // Unofficial - Nginx

        // [ Server Error 5xx ]
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway",
        503 => "Service Unavailable",
        504 => "Gateway Timeout",
        505 => "HTTP Version Not Supported",
        506 => "Variant Also Negotiates",
        507 => "Insufficient Storage",
        508 => "Loop Detected",
        509 => "Bandwidth Limit Exceeded",          // Unofficial
        510 => "Not Extended",
        511 => "Network Authentication Required",
        520 => "Web Server Returned an Unknown Error", // Unofficial
        521 => "Web Server Is Down",                // Unofficial
        522 => "Connection Timed Out",              // Unofficial
        523 => "Origin is Unreachable",             // Unofficial
        524 => "A Timeout Occurred",                // Unofficial
        525 => "SSL Handshake Failed",              // Unofficial
        526 => "Invalid SSL Certificate",           // Unofficial
        527 => "Railgun Error",
        529 => "Site is Overloaded",                // Unofficial
        530 => "Site is frozen",                    // Unofficial
        598 => "(Informal convention) Network read timeout error", // Unofficial
    );

    
    protected $status = [
        0   => "Failure",
        1   => "Information",
        2   => "Success",
        3   => "Redirection",
        4   => "ClientError",
        5   => "ServerError"
    ];
    
    // -----------------------------------------
    // Methods
    // -----------------------------------------
    
    public function __construct(){}
    
    // -----------------------------------------    
    
    public function import($output, $httpInfo)
    {
        $this->info     = $httpInfo;
        
        $headers = substr($output, 0, $this->info['header_size']);
        $body    = substr($output, $this->info['header_size']);
        
        // list($headers, $body) = explode("\r\n\r\n", $output, 2);
        
        $this->headers = $this->parseHeaders($headers);
        $this->payload = $this->parsePayload($body);
    }
    
    // -------------------------------------------

    
    // -------------------------------------------
    
    /**
     * Function: getHTTPStatusCode
     * 
     * will return an integer with the HTTP Code
     * if a response was imported
     * 
     * @access public
     * @param  none
     * @return integer 
     * 
     * *******************************************/
    public function getHTTPStatusCode(): int
    {
        $http_code = 0;
        if($this->info != NULL && isset($this->info['http_code']))
        {
            return $this->info['http_code'];
        }
        
        return $http_code;
    }
    
    // -------------------------------------------
    
    /**
     * Function: getHTTPStatusMessage
     * 
     * will return an string with the meaning of 
     * the HTTP code
     * 
     * @access public
     * @param  none
     * @return string
     * 
     * ******************************************/
    public function getHTTPStatusMessage(): string
    {
        $http_code      = $this->getHTTPStatusCode();
        $http_message   = "Unknown HTTP code message";
        if(in_array($http_code, array_keys($this->http_codes)))
        {
            $http_message = $this->http_codes[$http_code];
        }
        
        return $http_message;
    }
    
    // -------------------------------------------
    
    /**
     * Function: getStatusCode
     * 
     * Will return a code, based on the stack of errors
     * 
     * @access public
     * @param  none
     * @return integer
     * 
     * ******************************************/
    public function getStatusCode(): int
    {
        $current_status = 0;
        if($this->info != NULL && isset($this->info['http_code']))
        {
            switch(TRUE)
            {
                case (100 <= $this->info['http_code'] && $this->info['http_code'] < 200):
                    $current_status = 1;
                    break;
                case (200 <= $this->info['http_code'] && $this->info['http_code'] < 300):
                    $current_status = 2;
                    break;
                case (300 <= $this->info['http_code'] && $this->info['http_code'] < 400):
                    $current_status = 3;
                    break;
                case (400 <= $this->info['http_code'] && $this->info['http_code'] < 500):
                    $current_status = 4;
                    break;
                case (500 <= $this->info['http_code'] && $this->info['http_code'] < 600):
                    $current_status = 5;
                    break;
                default:
                    $current_status = 0;
            }
        }
        
        return $current_status;
    }
        
    // -------------------------------------------
    
    /**
     * Function: getStatus
     * 
     * will return the string of the passed CODE
     * 
     * @access public
     * @param  none
     * @return string
     *  
     * *******************************************/
    public function getStatus(): string
    {
        return $this->status[$this->getStatusCode()];
    }
    
    // -------------------------------------------
    
    /**
     * Function: __get (magic function)
     * 
     * will return the value of the passed entry into 
     * the array, NULL on the case the variable doesn't exit
     * 
     * @access public
     * @param  string
     * @return mixed
     * 
     * *******************************************/
    public function __get(string $name)
    {
        if($this->info != NULL && in_array($name, array_keys($this->info)))
        {
            return $this->info[$name];
        }
        
        return NULL;
    }
    
    // -------------------------------------------
    
    public function __call($name, $arguments)
    {
        $entry = substr($name, strlen('get'));
        preg_match_all('/[A-Z]/', $entry, $matches, PREG_OFFSET_CAPTURE);

        if(count($matches[0]) > 0)
        {
            foreach($matches[0] as $idx => $match)
            {
                $entry = substr($entry, 0, $match[1]).
                        str_replace($match[0], "_".strtolower($match[0]), substr($entry, $match[1]));
            }
            
            $entry = substr($entry, 1);
        }
        
        return $this->{$entry};
    }
    
    // -------------------------------------------
    
    /**
     * Function: http_code_message
     * 
     * will return the meaning of the HTTP code number
     * 
     * @access public
     * @param  none
     * @return string
     * 
     * ******************************************/
    /*
    public function httpCodeMessage(): string 
    {
        $code_message = "No HTTP code message";
        if($this->info != NULL && in_array('http_code', array_keys($this->info)))
        {
            $code_message = in_array($this->info['http_code'], array_keys($this->http_codes))
                    ? $this->http_codes[$this->info['http_code']]
                    : $code_message;
        }
            
        return $code_message;
    }
     */
    
    // -------------------------------------------
    
    /**
     * Function: getPayload
     * 
     * will return the content of the request
     * 
     * @access public
     * @param  none
     * @return mixed
     * 
     * *******************************************/
    public function getPayload()
    {
        return $this->payload;
    }
    
    // -------------------------------------------
    
    public function getHeaders()
    {
        return $this->headers;
    }
    
    // -------------------------------------------
    
    /**
     * Function: parsePayload
     * 
     * will parse the content of the body
     * 
     * @access private
     * @param  string body
     * @return mixed
     * 
     * *******************************************/
    private function parsePayload($body = '')
    {
        $payload = $body;
        if(isset($this->headers['Content-Type']))
        {
            $payload = $this->headers['Content-Type'] == 'application/json'
                    ? json_decode($body, FALSE)
                    : $body;
        }

        return $payload;
    }
    
    // -------------------------------------------
    
    /**
     * Function: parseHeaders
     * 
     * will return an array with the headers recovered
     * from the passed content
     * 
     * @access private
     * @param  string
     * @return array
     * 
     * ********************************************/
    private function parseHeaders($lines = ''): array
    {
        $headers = [];
        foreach(explode("\r\n", $lines) as $idx => $line)
        {
            if($idx != 0)
            {
                list($header, $value) = explode(": ", $line);
                $headers[$header] = $value;
            }
        }
        
        return $headers;
    }
    
    // -------------------------------------------
}