<?
/**
 * Cut long string (like a paragraph) and add `...` at the end of it.
*/
function cut_str($post, $size=80)
{
	$output=$post;
	if(strlen($output)>$size)
		$output=substr($output, 0, ($size))." ...";

	return $output;
}//end func.....
?>