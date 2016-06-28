<?php
/**
 * function get_csv
 * Returns a csv of items matched from array using seek key
*/
function get_csv($arr, $var_seek)
{
    $ret = ''; $ret_a = array();
    foreach($arr as $p2k=>$p2v)
    {
        if(stristr($p2k, $var_seek))
        {
            $ret_a[] = $p2v;
        }
    }
    $ret = implode(', ', $ret_a);

    return $ret;
}//end func....
?>