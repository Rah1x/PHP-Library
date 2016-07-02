<?php
/**
 * One way to pass params to a callback func is to do it as following:
**/
class get_ar
{
    var $key;

    function get_ar($to_srch)
    {
        $this->key = $to_srch;
    }

    function key_array($v)
    {
        if($v['res_id']==$this->key)
        return true;
    }
}

$page_res_0 = array_filter($page_res, array(new get_ar(0), 'key_array'));
?>