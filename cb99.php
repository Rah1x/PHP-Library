<?php
/**
 * Function cb99
 * Purpose = re-arange 2-dimensional array into Parent-Child and set top key from 1st dimension
 * @param $a = array
 * @param $find_key = key to find (for grouping)
 * @param $set_key = key to set
 * USAGE Example: $frustrated_users = cb99($frustrated_users, 'parent_id', 'comment_id');
 */
function cb99($a, $find_key, $set_key)
{
    $ret=array();

    if(!empty($a))
    foreach($a as $v)
    {
        #/if Parent is 0
        if($v[$find_key]==0){
        if(!isset($ret[$v[$set_key]])) $ret[$v[$set_key]] = array();
        $ret[$v[$set_key]]['parent'] = $v;
        continue;
        }

        #/if Parent is not 0 = form tree
        if(!isset($ret[$v[$find_key]])) $ret[$v[$find_key]] = array();
        $ret[$v[$find_key]][$v[$set_key]] = $v;
    }

    return $ret;

}//end func....?>