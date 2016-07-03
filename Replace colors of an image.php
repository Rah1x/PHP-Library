<?php
////////////////////////////////////////


$old_img = "ERA1_black.jpg";

## get Image Info
$img_type_t = explode('.', $old_img);
$img_type = $img_type_t[count($img_type_t)-1];
$img_type = strtolower($img_type);

list($cur_width, $cur_height, $cur_mime) = getimagesize($old_img);

$new_width = $cur_width;
$new_height = $cur_height;



## Headers
header("Cache-Control: no-cache");

$mime = image_type_to_mime_type($cur_mime);
header("Content-type: {$mime}");


## Create New Image
//$new_img = imagecreatetruecolor($new_width, $new_height);

switch($img_type)
{
    case 'png':  $pic = imagecreatefrompng($old_img); break;
    case 'gif':  $pic = imagecreatefromgif($old_img); break;
    case 'jpg':
    case 'jpeg': $pic = imagecreatefromjpeg($old_img); break;
}//end switch..


## Set Transparency
//imagealphablending($new_img, false);
//imagesavealpha($new_img, true);
//$transparent = imagecolorallocatealpha($new_img, 0, 0, 0, 127);
//imagefill($new_img, 0, 0, $transparent);

//$trnprt_indx = imagecolorallocate($new_img, 255, 255, 255);
//imagecolortransparent($new_img, $trnprt_indx);

## Resample / Resize
//imagecopyresampled($new_img, $pic, 0, 0, 0, 0, $new_width, $new_height, $cur_width, $cur_height);
//imagecopymerge($new_img, $pic, 0, 0, 0, 0, $new_width, $new_height, 100);



## Replace Color
$new_img = $pic;
//imagetruecolortopalette($new_img, false, 256);
//$index = imagecolorclosest($new_img, 0, 0, 0);
//imagecolorset($new_img, $index, 255, 0, 0);


imagefilter($new_img, IMG_FILTER_GRAYSCALE); //Gray Scale

## imagefilter($new_img, IMG_FILTER_COLORIZE, 7, 38, 13); //Green

//imagefilter($new_img, IMG_FILTER_COLORIZE, 0, 0, 0, 0); //Black
//imagefilter($new_img, IMG_FILTER_COLORIZE, 0, 0, 255, 80); //Blue
//imagefilter($new_img, IMG_FILTER_COLORIZE, 255, 0, 0, 70); //Burgundy
//imagefilter($new_img, IMG_FILTER_COLORIZE, 0, 255, 0, 110); //Green
imagefilter($new_img, IMG_FILTER_COLORIZE, 255, 0, 0, 10); //Red
//imagefilter($new_img, IMG_FILTER_COLORIZE, 135, 114, 44, 50); //Gold
//imagefilter($new_img, IMG_FILTER_COLORIZE, 135, 135, 135, 60); //Silver

## imagefilter($new_img, IMG_FILTER_SMOOTH, 10); //Smooth - Blur




## Output image
switch($img_type)
{
    case 'png':  ImagePng($new_img, NULL, 0); break;
    case 'gif':  ImageGif($new_img); break;
    case 'jpg':
    case 'jpeg': ImageJpeg($new_img, NULL, 100); break;
}//end switch..
?>