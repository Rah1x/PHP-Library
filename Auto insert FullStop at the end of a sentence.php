<?php
## Auto insert FullStop at the End of the Sentence
function format_fullstop($in)
{
    $out = trim($in);

    $sub_in = substr($out, strlen($out)-1, strlen($out));
    $punct = array('?', ',', '.', ';');

    if(!in_array($sub_in, $punct))
    $out .= '.';

    return trim($out);

}//end func....
##--

## remove Punctions at the end of a senstence
function remove_punc($in)
{
    $out = trim($in);

    $sub_in = substr($out, strlen($out)-1, strlen($out));
    $punct = array('?', ',', '.', ';');

    if(in_array($sub_in, $punct))
    $out = substr($out, 0, strlen($out)-1);

    return trim($out);

}//end func....


$prt = array_map('remove_punc', $prt);
$prt = array_map('format_fullstop', $prt);
?>