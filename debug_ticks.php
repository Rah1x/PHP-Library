<?php
/**
 *
 * [USAGE]
 //$focus_tick_file = 'FrontController.php';
 declare(ticks=1);
 include_once 'debug_ticks.php';
 *
*/

register_tick_function(function()
{
    //global $focus_tick_file;

    $backtrace = debug_backtrace();
    $line = $backtrace[0]['line'] - 1;
    $file = $backtrace[0]['file'];

    if ($file == __FILE__) return;
    //if(stristr($file, $focus_tick_file)==false) return;

    /*
    static $fp, $cur, $buf;
    if (!isset($fp[$file])) {
        $fp[$file] = fopen($file, 'r');
        $cur[$file] = 0;
    }

    if (isset($buf[$file][$line])) {
        $code = $buf[$file][$line];
    }
    else
    {
        do
        {
            $code = fgets($fp[$file]);
            $buf[$file][$cur[$file]] = $code;
        } while (++$cur[$file] <= $line);
    }
    */

    $line++;
    echo "<pre>{$file} on line: {$line}</pre><br />"; //{$code} called in
});

?>