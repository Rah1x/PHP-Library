<?php
/**
 * Function Time Elapsed
 * Convert time into Proper String
 * [params]
 * $ptime = timestamp of event
 * $dir = determine the direction = `past` or `future`
*/
function time_elapsed_string($ptime, $dir='past')
{
    if($dir=='past')
    $etime = time() - $ptime;
    else
    $etime = $ptime - time();
    //var_dump($etime, time(), $ptime, ($etime<1));

    if($etime<1)
    {
        return 'now';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'minute',
                                 1  =>  'second'
                );
    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days',
                       'hour'   => 'hours',
                       'minute' => 'minutes',
                       'second' => 'seconds'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            if($dir=='past')
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
            else
            return 'in '. $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
        }
    }
}//end func....



/** $ptime, $ptime2 as epoc timestamps **/
public static function time_diff_str($ptime, $ptime2)
{

    $date1 = new \DateTime($ptime);
    $date2 = new \DateTime($ptime2);

    $interval = $date1->diff($date2);
    //self::var_dumpx($interval);

    $rtn = [];

    if($interval->y==1)
    $rtn[]= $interval->y." year";
    if($interval->y>1)
    $rtn[]= $interval->y." years";

    if($interval->m==1)
    $rtn[]= $interval->m." month";
    if($interval->m>1)
    $rtn[]= $interval->m." months";

    if($interval->d==1)
    $rtn[]= $interval->d." day";
    else if($interval->d>1)
    $rtn[]= $interval->d." days";

    if($interval->h==1)
    $rtn[]= $interval->h." hour";
    if($interval->h>1)
    $rtn[]= $interval->h." hours";

    if($interval->i==1)
    $rtn[]= $interval->i." min";
    if($interval->i>1)
    $rtn[]= $interval->i." mins";

    return implode(', ', $rtn);

}//end func....
?>