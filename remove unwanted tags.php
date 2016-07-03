<?php
/**
 * Remove Some Unwanted Tags
 **/
function remove_some_tags($listing_2x)
{
    $srch = array('/<script.*?>.*?<\/script>/ims',
    '/<iframe.*?>.*?<\/iframe>/ims',
    '/<meta.*?>.*?<\/meta>/ims',
    '/<noscript.*?>.*?<\/noscript>/ims',
    '/<style.*?>.*?<\/style>/ims',
    '/<label.*?>.*?<\/label>/ims',
    '/<input.*?>/ims',
    '/<img.*?>/ims',
    '/<head.*?>.*?<\/head>/ims');
    $listing_2x = preg_replace($srch, '', $listing_2x);

    return $listing_2x;
}//end func.........
?>