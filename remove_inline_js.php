<?php
/**
 * Remove inline JS
 * $in =  input string/array
 */
function remove_inline_js($in)
{
    $out = $in;

    if(is_array($out))
    {
        $out_x = array();
        foreach($out as $k=>$v)
        {
            $out_x[$k] = remove_inline_js($v);
        }
        $out = $out_x;
    }
    else
    {
        $out = preg_replace('/<(.*?)(on[a-z]{1,}[\s]{0,}=[\s]{0,})(.*?)>/ims', '<$1 x$2 $3>', $out);
    }

    return $out;
}//end func.....
?>