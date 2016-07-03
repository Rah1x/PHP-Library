<?php
$a = "aaaabbbbccccddddeeeeffffgggghhhhiiiijjjjkkkkllllmmmmnnnnoooo";
$a = preg_replace('/([^\s]{10})/ims', '$1 ', $a);
var_dump($a);

//////////////////////////////////////////////
function put_space($in)
{
    $in = preg_replace('/([^\s]{10})/ims', '$1 ', $in);
    return $in;
}//end func...
?>