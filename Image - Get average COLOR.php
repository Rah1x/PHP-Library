<?php
function average($imag)
{
    $img_type_t = explode('.', $imag);
    $img_type = $img_type_t[count($img_type_t)-1];
    $img_type = strtolower($img_type);

    switch($img_type)
    {
        case 'png':
        $img = imagecreatefrompng($imag);
        break;

        case 'jpg':
        case 'jpeg':
        $img = imagecreatefromjpeg($imag);
        break;

        case 'gif':
        $img = imagecreatefromgif($imag);
        break;
    }//end switch..


    $w = imagesx($img);
    $h = imagesy($img);
    $r = $g = $b = 0;
    for($y = 0; $y < $h; $y++) {
        for($x = 0; $x < $w; $x++) {
            $rgb = imagecolorat($img, $x, $y);
            $r += $rgb >> 16;
            $g += $rgb >> 8 & 255;
            $b += $rgb & 255;
        }
    }
    $pxls = $w * $h;
    $r = dechex(round($r / $pxls));
    $g = dechex(round($g / $pxls));
    $b = dechex(round($b / $pxls));
    if(strlen($r) < 2) {
        $r = 0 . $r;
    }
    if(strlen($g) < 2) {
        $g = 0 . $g;
    }
    if(strlen($b) < 2) {
        $b = 0 . $b;
    }

    //return "#" . $r . $g . $b;
    return $r . $g . $b;
}
?>