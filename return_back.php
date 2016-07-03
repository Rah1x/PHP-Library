<?php
/**
 * Function to Redirect
*/
function return_back($url_part='index', $call_attempts_func=false)
{
    global $consts;

    #/ Update Attempts
    if($call_attempts_func){
    update_attempt_counts();
    }

    #/ Redirect
    @header("Location: {$consts['DOC_ROOT']}{$url_part}");
    echo "<script language=\"javascript\">location.href='{$consts['DOC_ROOT']}{$url_part}';</script>";
    exit;

}//end func......


/**
 * Function to Redirect
*/
function return_back_post($url_main='index', $POST='')
{
    global $consts;

    #/ process POST data
    $url_part = '';
    if(!empty($POST))
    {
        $form = '';
        foreach($POST as $k=>$v)
        {
            if($k!='vercode'){
            $v = urlencode($v); //will require [$_GET = array_map('urldecode', $_GET);] at the top of the field display page
            $form .= "{$k}={$v}&";
            }
        }
        $form = str_replace("'", '&#39;', $form);
        $form = str_replace("#", '', $form);
        $form = preg_replace("/[\n\r]{1,}/", 'Xnl2nlX', $form);

        $url_part = "/?{$form}";
    }


    #/ Return
    @header("Location: {$consts['DOC_ROOT']}{$url_main}{$url_part}");
    echo "<script language=\"javascript\">location.href='{$consts['DOC_ROOT']}{$url_main}{$url_part}';</script>";
    exit;

}//end func......