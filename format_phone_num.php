<?php
function format_phone_num($in, $leading_zero=true)
{
    $out = $in;
    $out = preg_replace('/[^0-9]/', '', $out);

    if($leading_zero!=true)
    {
        $out = preg_replace('/^[0]{0,}/', '', $out);
    }

    return $out;
}
?>