<?php
function wrap_space($str, $size, $str_brk=' ')
{
    $out = trim($str);
    $out = wordwrap($out, $size, $str_brk, true);
    $out = trim($out);

    return $out;

}//end func.....


function cut_str($post, $size=80)
{
	$output=$post;
	if(strlen($output)>$size)
		$output=substr($output, 0, ($size))."...";
	return $output;
}//end func.....


function format_str($in, $all=true)
{
	$out = $in;

	if($all){
    $out = str_replace('<', '&lt;', $out);
	$out = str_replace('>', '&gt;', $out);
    }

    $out = str_replace("'", '&#39;', $out);
	$out = str_replace('"', '&quot;', $out);
    $out = trim($out);

	return $out;
}//end func.....


## Get Current Page / Section
function cur_page()
{
    $cur_page='';
    if(isset($_SERVER['PHP_SELF']) && $_SERVER['PHP_SELF']!='')
    {
        $temp_var1 = explode('/', $_SERVER['PHP_SELF']);
        $cur_page = $temp_var1[count($temp_var1)-1];
    }
    else if(isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME']!='')
    {
        $temp_var1 = explode('/', $_SERVER['SCRIPT_NAME']);
        $cur_page = $temp_var1[count($temp_var1)-1];
    }
    else if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI']!='')
    {
        $temp_var1 = explode('/', $_SERVER['REQUEST_URI']);
        $cur_page = $temp_var1[count($temp_var1)-1];
        $temp_var2 = explode('?', $cur_page);
        $cur_page = $temp_var2[0];
    }
    else if(isset($_SERVER['SCRIPT_FILENAME']) && $_SERVER['SCRIPT_FILENAME']!='')
    {
        $temp_var1 = explode('/', $_SERVER['SCRIPT_FILENAME']);
        $cur_page = $temp_var1[count($temp_var1)-1];
    }
    return $cur_page;
}//end func.....
////////////////////////////////////////////////////////////////////////
?>