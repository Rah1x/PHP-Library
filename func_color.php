<?php
/** Determine Contrst color
*/
function invert_colour($start_colour)
{
    $colour_red = hexdec(substr($start_colour, 1, 2));
    $colour_green = hexdec(substr($start_colour, 3, 2));
    $colour_blue = hexdec(substr($start_colour, 5, 2));

    $new_red = dechex(255 - $colour_red);
    $new_green = dechex(255 - $colour_green);
    $new_blue = dechex(255 - $colour_blue);

    if (strlen($new_red) == 1) {$new_red .= '0';}
    if (strlen($new_green) == 1) {$new_green .= '0';}
    if (strlen($new_blue) == 1) {$new_blue .= '0';}

    $new_colour = '#'.$new_red.$new_green.$new_blue;

    return $new_colour;
}

/**
 * Find B/W color based on bg
**/
function black_or_white($start_colour)
{
    $colour_red = hexdec(substr($start_colour, 1, 2));
    $colour_green = hexdec(substr($start_colour, 3, 2));
    $colour_blue = hexdec(substr($start_colour, 5, 2));

    $tot_clr = $colour_red*0.299 + $colour_green*0.587 + $colour_blue*0.114;
    if (($tot_clr) > 120)
    {
        $new_colour = "#000";
    }
    else
    {
        $new_colour = "#fff";
    }

    return $new_colour;
}

function hex2dec($hex)
{
    $color = STR_REPLACE('#', '', $hex);

    $ret = ARRAY(
    'r' => HEXDEC(SUBSTR($color, 0, 2)),
    'g' => HEXDEC(SUBSTR($color, 2, 2)),
    'b' => HEXDEC(SUBSTR($color, 4, 2))
    );

    RETURN $ret;
}///end func............
?>