<?php
/** Usage = inside usort callback func
 * switch($orderby_ix)
    {
        case '1':
        if($orderdi_ix=='ASC'){return val_cmp($a["event_id"], $b["event_id"], 'numbers');} else {return val_cmp($b["event_id"], $a["event_id"], 'numbers');}
        break;

        case '2':
        if($orderdi_ix=='ASC'){return strcasecmp($a["event_status"], $b["event_status"]);} else {return strcasecmp($b["event_status"], $a["event_status"]);}
        break;
        .
        .
        .
        .

 **/

function val_cmp($a, $b, $type)
{
    switch ($type)
    {
        case 'numbers':if($a<$b) return -1; else if($a>$b) return 1; else return 0; break;
        case 'date': $a = strtotime($a); $b = strtotime($b); if($a<$b) return -1; else if($a>$b) return 1; else return 0; break;
    }

}//end func...
?>