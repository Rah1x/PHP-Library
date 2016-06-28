<?php
/**
 * function cb89
 * Set top key of a multi-dimensional array from a kay=>value in its 2nd level
 * USAGE Example: $contact_res_t = cb89($contact_res, 'id');
 */
function cb89($a, $set_key){$ret=array(); foreach($a as $v){$ret[$v[$set_key]]=$v;} return $ret;}
?>