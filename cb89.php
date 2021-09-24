<?php
/**
 * function cb89 (key set)
 * Set top key of a multi-dimensional array from a kay=>value in its 2nd level
 * USAGE Example: $contact_res_t = cb89($contact_res, 'id', 'int');
*/
function cb89($a, $set_key, $typecast='')
{
    $ret=array();
    foreach($a as $v)
    {
        $k = $v[$set_key];
        if($typecast=='int'){
        $k = (int)$k;
        }

        $ret[$k]= $v;
    }
    return $ret;
}
?>