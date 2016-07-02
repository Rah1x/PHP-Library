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
?>