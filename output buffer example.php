<?php
ob_start();
echo '1';
echo '2';
echo '3';
echo '4';
echo '5';

$ret = ob_get_clean();
ob_clean();

echo '6';
echo '7';
echo '8';
echo '9';
echo '10';

echo '<br />'.$ret;
?>