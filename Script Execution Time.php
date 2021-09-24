<?php
$script_start_time = (float) array_sum(explode(' ',microtime()));
function script_tm()
{
    global $script_start_time;

    $script_end_time = (float) array_sum(explode(' ',microtime()));
    $sc_dt = sprintf("%.4f", ($script_end_time-$script_start_time)).' Sec';

    return $sc_dt;
}//end func...
////////////////////////////////////////////////////////

//[USAGE]
var_dump(script_tm()); die();
