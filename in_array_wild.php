<?php
/**
 * Searches like in_array - with wild card.
 * for example: will return true for 'a' in 'z abcd'
 * */
function in_array_wild($needle, $haystack)
{
    foreach($haystack as $k=>$v)
    {
        if(stristr($v, $needle)!==false){
        return true;
        }
    }
    return false;
}//end func.....


/**
 * Searches haystack(array) in needle(string)
 * for example: will return true for haystack('a') in 'z abcd'
 * */
function in_array_wild_reverse($needle, $haystack)
{
    foreach($haystack as $k=>$v)
    {
        if(stristr($v, $needle)!==false){
        return true;
        }
    }
    return false;
}//end func.....
?>