<?php
if(stristr($mv['cost'], '.')){
$cost = number_format((float)$mv['cost'], 1);
} else {
$cost = (int)$mv['cost'];
}


function leadingZeros($num, $numDigits) {
return sprintf("%0".$numDigits."d", $num);
}
?>