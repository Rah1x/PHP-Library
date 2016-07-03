<?php
function smart_url($in)
{
	$out = $in;

    $find    = array(" ", ".", "!", "@", "*", "=", "~", "&", "\$", "%", "/", ">", "<", "__", "--", "\\", "," ,"amp;", "--", "'");
	$replace = array("-", "", "", "", "", "", "", "", "", "", "", "", "", "-", "-", "-", "", "", "-", "");

	$out = str_replace($find, $replace, $out);

	return $out;
}//end func....
?>