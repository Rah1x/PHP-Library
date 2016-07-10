<?php
/**
 * Special Code to stop get_magic_quotes_gpc
 * This is for servers running old PHP versions (<5.3)
 * You will have to manually do it for parse_str function as it will insert magic quote automatically
*/
function stop_magic_quotes($in)
{
    $out = $in;


	if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
    {
        if(is_array($out))
        {
            foreach($out as $k=>$v)
            {
                $v = stop_magic_quotes($v);
                $out[$k] = $v;
            }
        }
        else
        {
            $out = stripslashes($out);
        }
    }

	return $out;
}//end func................

if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
{
    $_GET = array_map('stop_magic_quotes', $_GET);
    $_POST = array_map('stop_magic_quotes', $_POST);
}//end if....
?>