<?php
/**
* Custom Error handling and Loging
* Version 1.0
* Author: Raheel Hasan
*/
class er_handling
{

    var $log_file_dir, $log_file, $root, $ignore_msgs;


    /**
     * Constructor Function
     * Use it to set error log file and the files directory
    */
    function er_handling($log_file='', $log_file_dir='')
    {
        $this->root = $_SERVER['DOCUMENT_ROOT'].'/';

        #/define ignore list
        $this->ignore_msgs = array(
        'DOMDocument::loadHTML()'
        );
        #-

        #/Open Errors
        @ini_set('display_errors', 'on');
        @error_reporting(E_ALL);

        #/ define Dir
        if(empty($log_file_dir))
        $this->log_file_dir = dirname (__FILE__);
        else
        $this->log_file_dir = $log_file_dir;

        #/ define file
        if(empty($log_file))
        $this->log_file = "custom_err.log";
        else
        $this->log_file = $log_file;


        #/ Call func1
        set_error_handler(array($this, 'e_handler'), E_ALL);

        #/ Call func2
        register_shutdown_function(array($this, 'e_handler_shutdown'));

    }//end func......


    /**
     * Function for handling execution shutdowns; like fatal errors
     * called from register_shutdown_function
     * This function will call the custom error handler 'e_handler' defined below
    */
    function e_handler_shutdown()
    {
        $errfile = $errline = $emsg = '';

        #/ Error Info
        $errorx = error_get_last();
        $errno = $errorx['type'];
        $errstr = $errorx['message'];
        $errfile = $errorx['file'];
        $errline = $errorx['line'];

        if(!empty($errstr))
        $this->e_handler($errno, "[register_shutdown] ".$errstr, $errfile, $errline, 1);

        exit();

    }//end func......


    /**
     * Function to get list of all php errors by their names
    */
    function get_er_names()
    {
        $consts = get_defined_constants();
        $consts_e = array();
        foreach($consts as $k=>$v)
        {
            if(preg_match('/^E_/', $k)>0)
            $consts_e[$v] = (string)$k;
        }
        return $consts_e;

    }//end func......


    /**
     * Function to handle regular errors
     * It will log the errors
    */
    function e_handler($errno, $errstr, $errfile, $errline, $cus=false)
    {

        #/ check ignore list
        foreach($this->ignore_msgs as $v)
        {
            if(stristr($errstr, $v)!=false)
            return true;
        }//end foreach...
        #-

        $consts_e = $this->get_er_names();
        $errno_name = @$consts_e[$errno];
        if(empty($errno_name)) $errno_name = $errno;


        $error_msg = "\r\n";
        $error_msg .= "---------[".date('d/m/Y H:i:s')."]\r\n";
        $error_msg .= "File = {$errfile}\r\n";
        $error_msg .= "Line = {$errline}\r\n";
        $error_msg .= "ErrNo = {$errno_name}\r\n";
        $error_msg .= "ErrStr = {$errstr}\r\n";

        if($cus)
        {
            $ap = $this->log_file_dir."/".$this->log_file;
            //$ap = $this->log_file;
            $el = @file_put_contents($ap, $error_msg, FILE_APPEND);

            if($el==false)
            {
                $fh = fopen($ap, 'a+');
                fputs($fh, $error_msg);
                fclose($fh);
            }
        }
        else
        {
            error_log($error_msg, 3, $this->log_file);
        }

        return true;

    }//end func....

}//end class.
//////////////////////////////////////////////////////////////////////////////
//$er_obj = new er_handling();

//echo '<pre>';
//$vb = 1;
//cho($vb);
//echo 'Z';
?>