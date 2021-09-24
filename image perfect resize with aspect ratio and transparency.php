<?php
###
# Resize Images with Aspect ratio preserved
#
# USAGE:
# studio.php?do=resize&img=unicorn_black.png&w=100&h=
###

if(isset($_GET['do']))
{
    if($_GET['do']=='resize')
    {
        $img = $_GET['img'];
        $old_img = 'images/designs/'.$img;

        ### get Image Info
        $img_type_t = explode('.', $img);
        $img_type = $img_type_t[count($img_type_t)-1];
        $img_type = strtolower($img_type);

        list($cur_width, $cur_height, $cur_mime) = getimagesize($old_img);

        $new_width = $_GET['w'];
        $new_height = $_GET['h'];
        if($new_height==''){
        $new_height = $cur_height*($new_width/$cur_width);
        }



        ## Headers
        header("Cache-Control: no-cache");
        //header("Content-type: image/png");

        $mime = image_type_to_mime_type($cur_mime);
        header("Content-type: {$mime}");



        ## Create New Image
        $new_img = imagecreatetruecolor($new_width, $new_height);

        switch($img_type)
        {
            case 'png':
            $pic = imagecreatefrompng($old_img);
            $trnprt_indx = imagecolortransparent($pic);
            break;

            case 'jpg':
            case 'jpeg':
            $pic = imagecreatefromjpeg($old_img);
            break;

            case 'gif':
            $pic = imagecreatefromgif($old_img);
            $trnprt_indx = imagecolortransparent($pic);
            break;
        }//end switch..


        ## Preserve Transparency
        if ($trnprt_indx >= 0)
        {
            $trnprt_color = imagecolorsforindex($pic, $trnprt_indx);

            $trnprt_indx = imagecolorallocate($new_img, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);

            imagefill($new_img, 0, 0, $trnprt_indx);
            imagecolortransparent($new_img, $trnprt_indx);

        }
        elseif ($img_type=='png')
        {
            imagealphablending($new_img, false);

            $color = imagecolorallocatealpha($new_img, 0, 0, 0, 127);
            imagefill($new_img, 0, 0, $color);
            imagesavealpha($new_img, true);
        }

        ## Resample / Resize
        imagecopyresampled($new_img, $pic, 0, 0, 0, 0, $new_width, $new_height, $cur_width, $cur_height);

        ## Output image
        ImagePng($new_img);

    }////end if resize..............................
    ////////////////////////////////////////////////////////////////
}