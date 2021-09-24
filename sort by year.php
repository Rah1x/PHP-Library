<?php
function sort_by_yr($a, $b)
{
    /*
    if ($a['year'] == $b['year']) {
    return 0;
    }
    return ($a['year'] > $b['year'])? -1:1;
    */

    if (strtotime($a['case_date']) == strtotime($b['case_date'])) {
    return 0;
    }
    return (strtotime($a['case_date']) > strtotime($b['case_date']))? -1:1;

}//end func....
usort($v_1['data'], 'sort_by_yr');
?>