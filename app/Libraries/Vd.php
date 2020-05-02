<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Libraries;

/**
 * Description of Vd
 *
 * @author alain
 */
class Vd
{
    /**
     * Function: __construct
     * 
     * This function is the constructor of
     * the class
     * 
     * @access public
     * @param  none
     * @return none
     * 
     * *****************************/
    public function __construct(){}
    
    // ---------------------------------------------------------
    
    /**
     * Function: show
     *
     * This function make a loop with all arguments
     * to var_dump each 
     *
     * @access public
     * @param  array of args
     * @return void
     */
    public static function show()
    {
        $args = func_get_args();
        foreach($args as $arg)
        {
            echo "<pre>";
            var_dump($arg);
            echo "</pre>";
        }
    }
    
    public static function cli_show()
    {
        $args = func_get_args();
        foreach($args as $arg)
        {
            var_dump($arg);
        }
    }
    
    // ---------------------------------------------------------

    /**
     * Function: stop;
     *
     * This function call the function vd to show
     * the var_dump of the args passed and later stop 
     * the execution calling stop function
     *
     * @access public
     * @param  array of args
     * @return void
     */
    public static function stop()
    {
        $args = func_get_args();
        foreach ($args as $arg) 
        {
            self::show($arg);
        }
        self::stopped();
    }
    
    public static function cli_stop()
    {
        $args = func_get_args();
        foreach ($args as $arg) 
        {
            self::cli_show($arg);
        }
        self::stopped();
    }
    
        
    // ---------------------------------------------------------
    
    /**
     * Function: type
     *
     * This function will return the type of the variable
     * passed as parameter. 
     *
     * @access public
     * @param  mix
     * @return string
     */
    public static function type()
    {
        foreach(func_get_args() as $arg)
        {
            echo "<pre>";
            echo gettype($arg);
            echo "</pre>";
        }
    }

    // ---------------------------------------------------------
    
    /**
     * Function: filename
     *
     * This function save the echo of var_dump to
     * a file, the first argument is the name of the
     * filename where the echo will be saved
     *
     * @access public
     * @param  array of args
     * @return void
     */
    public static function filename()
    {
        $num_args = func_num_args();

        if($num_args >= 2)
        {
            $filename = func_get_arg(0);
            $output   = "";

            ob_start();

            for($i = 1; $i < $num_args; $i++)
            {
                var_dump(func_get_arg($i));
                $output .= ob_get_clean();

                var_dump($output); die;
            }

            file_put_contents($filename, $output);
        }
    }

    // ---------------------------------------------------------
    
    /**
     * Function: get_methods
     * 
     * will return on a loop all methods of a passed class
     * 
     * @access public
     * @param  string class_name
     * @param  string identifier
     * @return none
     * 
     * ********************************************************/
    static public function get_methods($class_name, $identifier = "Method: ")
    {
        foreach(get_class_methods($class_name) as $method)
        {
            echo "{$identifier}{$method}";
        }
    }
    
    // ---------------------------------------------------------
    
    /**
     * Function: stop
     *
     * This function stop the execution of the program
     * and show file and line where was called.
     *
     * @access private
     * @param  none
     * @return void
     */
    static private function stopped()
    {
        $debug = debug_backtrace(false);
        $entry = 1;                                                   
        $type  = '';                                                                               
        
        $file_path = isset($debug[$entry]['file']) ? $debug[$entry]['file'] : '';                     
        if(!empty($file_path))                                                               
        {
            if(!(strpos($file_path, 'controllers') === FALSE)) {  $type = '__CTRL__'; }
            if(!(strpos($file_path, 'core')        === FALSE)) {  $type = '__CORE__'; }
            if(!(strpos($file_path, 'helper')      === FALSE)) {  $type = '__HELP__'; }
            if(!(strpos($file_path, 'libraries')   === FALSE)) {  $type = '__LBRY__'; }
            if(!(strpos($file_path, 'models')      === FALSE)) {  $type = '__MDEL__'; }
            if(!(strpos($file_path, 'views')       === FALSE)) {  $type = '__VIEW__'; }
        }

        $class = isset($debug[$entry + 1]['class']) ? $debug[$entry + 1]['class'] : '';
        $func  = isset($debug[$entry + 1]['function']) ? $debug[$entry + 1]['function'] : '';
        $line  = isset($debug[$entry]['line']) ? $debug[$entry]['line'] : ''; 

        $message = "[{$type}] {$class}::{$func} ($line)";
        if(empty($class))
        {
            $pathinfo = pathinfo($file_path);
            $message  = "[{$type}] {$pathinfo['basename']} ($line)";            
        }

        die($message);
    }

    // ---------------------------------------------------------
    // ---------------------------------------------------------
}