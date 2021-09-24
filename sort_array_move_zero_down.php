<?php
function sort_ext_qs($a0, $b0)
{
    $a = (int)$a0[0]['display_order'];
    $b = (int)$b0[0]['display_order'];

    if($a==$b) return 0;
    if ($a == 0 && $b != 0) return 1;
    if ($b == 0 && $a != 0) return -1;
    return ($a<$b)? -1:1;
}
usort($ext_prof_quest, 'sort_ext_qs');
?>