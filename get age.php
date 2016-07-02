<?php
# $birthDate = mm/dd/yyyy
function getAge($birthDate)
{
    $birthDate = explode("/", $birthDate);
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[0], $birthDate[2]))) > date("md") ? ((date("Y")-$birthDate[2])-1):(date("Y")-$birthDate[2]));
    return $age;
}//end func.....
?>