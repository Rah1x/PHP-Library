<?php
#/ new Similar Function for mysql_error - to prevent mysql_error from being displayed on LIVE.
#/ But still visible on local
function mysql_error_1()
{
    if(SERVER_TYPE!='LOCAL')
    return false;
    else
    return mysql_error();
}
?>