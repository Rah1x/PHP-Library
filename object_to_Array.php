<?php
function ar_obj($a){return (is_object($a)? get_object_vars($a):$a);}

//usage
$fcc_list = array_map('ar_obj', $fcc_resx_docs);


/////////////////////////////////////////////////////
function objectToArray( $object )
{
    if( !is_object( $object ) && !is_array( $object ) )
    {
        return $object;
    }
    if( is_object( $object ) )
    {
        $object = get_object_vars( $object );
    }
    return array_map( 'objectToArray', $object );
}
?>