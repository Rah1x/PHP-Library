<?php
/**
 * Function cb79 (Grouping)
 * Purpose = re-arange array into Parent-Child with 1st dimension's key set with groupby_key
 * @param $a = array
 * @param $groupby_key = key to find (for grouping)
 * USAGE Example: $frustrated_users = cb79($frustrated_users, 'event_cat');
*/
function cb79($a, $groupby_key, $set_key=true)
{
    $ret=array();

    if(!empty($a))
    foreach($a as $k=>$v)
    {
        if(!isset($ret[$v[$groupby_key]])) $ret[$v[$groupby_key]] = array();
        if($set_key){
        $ret[$v[$groupby_key]][$k] = $v;
        } else {
        $ret[$v[$groupby_key]][] = $v;
        }
        continue;
    }
    return $ret;
}



/**
 * Function cb791 (grouping and keyset)
 * Purpose = re-arange array into Parent-Child with 1st dim and 2nd dim keys set
 * @param $a = array
 * @param $groupby_key = key for grouping of 1st dim
 * @param $key_id = key for grouping of 2nd dim
 * USAGE Example: $cats = @cb791($cats, 'parent_id', 'id');
*/
function cb791($a, $groupby_key, $key_id)
{
    $ret=array();

    if(!empty($a))
    foreach($a as $k=>$v)
    {
        if(!isset($ret[$v[$groupby_key]])) $ret[$v[$groupby_key]] = array();
        $ret[$v[$groupby_key]][$v[$key_id]] = $v;
        continue;
    }
    return $ret;
}
?>