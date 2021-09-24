<?php
/**
 * Example of how to TAR and UNTAR (archives) using PHP
*/

#/ Runtime Configurations
error_reporting(E_ALL);
ini_set('display_errors', 'On');

set_time_limit(0);
ini_set('max_execution_time', 0);

////////////////////////////////////////////////////////////////////////

$src = "./my_directory";
$dst = "my_directory.tar.gz";

exec("tar cvf $dst $src", $output, $result);

echo "Result: $result <br>";
var_dump('<pre>', $output);
?>