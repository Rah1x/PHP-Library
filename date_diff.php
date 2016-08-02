<?php
## calculate Date Difference between two timestamps
function cal_diff($d1, $d2)
{
    $dateDiff = abs($d2-$d1);
    $fullDays = floor($dateDiff/(60*60*24));
    return $fullDays;
}//end func.....
?>