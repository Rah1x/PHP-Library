<?php
session_start();

if(!function_exists('gd_info')) { exit(); }

//header("Cache-Control: no-cache");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 2007 05:00:00 GMT");

/////////////////////////////////////////////////////////////////

###/ Validate Requesting Party
$show_js = false;

#/ check domain
$allowed = array('localhost', 'www.collaborateusa.com', 'new.collaborateusa.com', 'collaborateusa.com', 'cusa-local');
if(in_array($_SERVER['SERVER_NAME'], $allowed))
$show_js = true;

#/ Check if called directly
if(!isset($_SERVER['HTTP_REFERER']))
$show_js = false;

#/ Die if invalid
if($show_js!=true)
die();

/////////////////////////////////////////////////////////////////

header("Content-type: image/png");

##/ Settings
$width = 67;
$height = 28;

$img_handle = @ImageCreate($width, $height);
//$back_color = @ImageColorAllocate($img_handle, 82, 83, 85); //dark bg
$back_color = @ImageColorAllocate($img_handle, 215, 223, 229); //light bg
//$transparent_bg = @ImageColorTransparent($img_handle, $back_color);//transparent bg

$noise_level = 8;
//$noise_color = @imagecolorallocate($img_handle, 200, 200, 200);//noice on dark bg
$noise_color = @imagecolorallocate($img_handle, 20, 20, 20);//noice on light bg

/////////////////////////////////////////////////////////////////

##/ Generate Text
$count = 0;
$code = ""; $size = '';
while($count<6)
{
  $count++;

  $x_axis = -5 + ($count * 10);
  $y_axis = rand(3, 10);

  #/ Define Text Color

  /*//For dark BG
  $color1 = rand(240, 210);
  $color2 = rand(080, 240);
  $color3 = rand(20, 235);*/

  //For light BG
  $color1 = rand(10, 30);
  $color2 = rand(080, 90);
  $color3 = rand(20, 75);

  $txt_color[$count] = @ImageColorAllocate($img_handle, $color1, $color2, $color3);


  $size_n = rand(5,12);
  if($size_n==$size)
  $size_n = rand(5,12);
  $size = $size_n;

  $number = rand(0,9);
  $code .= "{$number}";

  @ImageString($img_handle, $size, $x_axis, $y_axis, "$number", $txt_color[$count]);
}//end while...
#-


##/ Add some noise to the image.
for ($i = 0; $i < $noise_level; $i++) {
    for ($j = 0; $j < $noise_level; $j++) {
        imagesetpixel(
            $img_handle,
            rand(0, $width),
            rand(0, $height),//make sure the pixels are random and don't overflow out of the image
            $noise_color
        );
    }
}
#-

$pixel_color = @ImageColorAllocate($img_handle, 100, 100, 100);

$_SESSION['cap_code'] = $code;
@ImagePng($img_handle);
?>