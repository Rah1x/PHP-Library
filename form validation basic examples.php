<?php
function return_bck($POST='', $global_msg=false)
{
    #/ process POST data
    $url_part = '';
    if(!empty($POST))
    {
        $form = '';
        foreach($POST as $k=>$v)
        {
            if($k!='vercode')
                $form .= "{$k}={$v}&";
        }
        $form = str_replace("'", '&#39;', $form);
        $form = str_replace("#", '', $form);

        $url_part = "/?{$form}";
    }

    #/ Update attempts count
    if(isset($_SESSION["au_wrongtry"])) $_SESSION["au_wrongtry"]++;
    else $_SESSION["au_wrongtry"] = 1;
    $_SESSION['au_last_attempt'] = time();

    #/ Set Global Msg
    if($global_msg){
    $_SESSION["ALEX_MSG_GLOBAL"] = array(false, 'There is an Error in your Signup Form! Please click on Signup to see.');
    }

    #/ Return
    @header("Location: {$consts['DOC_ROOT']}index{$url_part}");
    echo "<script language=\"javascript\">location.href='{$consts['DOC_ROOT']}index{$url_part}';</script>";
    exit;

}//end func......

/////////////////////////////////////////////////////////////////////

#// Check Empty
$ch_empty_fields = array('first_name', 'last_name', 'email_add', 'pass_w', 'confirm_pass1', 'expertise_area_id', 'tos_agree');
function chk_empty($a){global $_POST; if(isset($_POST[$a]) && !empty($_POST[$a]))return $a;}
$ch_empty = array_filter($ch_empty_fields, 'chk_empty');
if(count($ch_empty_fields)>count($ch_empty))
{
    $_SESSION["ALEX_MSG_SIGNUP"] = array(false, 'All fields marked with a star(*) must be filled! Please try again.');
    return_bck($_POST, true); exit;

}//end Empty check.........



###/ Misc Checking

#/ Email Address
$error_msgx = '';
if(preg_match('/^\p{L}{1,}[\p{L}0-9_\.\-]{1,}\@[\p{L}0-9_\.\-]{1,}$/i', $email_add)==false){
$error_msgx[]= "<li>Please enter a valid Email Address!</li>";
}

#/ Phone Number
//if(preg_match('/[0-9\+\-]{1,}/i', $_POST["phone_no"])==false){
//$error_msgx[]= "<li>Please enter a valid Phone Number!</li>";
//}

#/ Password Length
if(strlen($_POST['pass_w'])<6 || strlen($_POST['pass_w'])>11){
$error_msgx[]= "<li>Please enter Password between 6 to 11 characters!</li>";
}

#/ Confirm Password
if(strcmp($_POST['pass_w'], $_POST['confirm_pass'])!=0){
$error_msgx[]= "<li>Confirm Password doesnot match Password field!</li>";
}

if(!empty($error_msgx))
{
    $error_msgxa = implode('', $error_msgx);
    $_SESSION["ALEX_MSG_SIGNUP"] = array(false, "<ul class='err_ul'>".$error_msgxa."</ul>");
    return_bck($_POST, true); exit;
}
#- end Misc checking
////////////////////////////////////////////////////////////////////////


/*
#/ name
else if(preg_match('/^\p{L}{1,}[\p{L}0-9\,\.\-" ]{1,}$/i', $fname)==false)
{
    $error .= "<span class='error'>- Please enter a valid Name!</span><br>";
}

#/ Email
if(empty($email))
{
	$error .= "<span class='error'>- The Email Address cannot be empty!</span><br>";
}
else if(preg_match('/^\p{L}{1,}[\p{L}0-9_\.\-]{1,}\@[\p{L}0-9_\.\-]{1,}$/i', $email)==false)
{
    $error .= "<span class='error'>- Please enter a valid Email Address!</span><br>";
}

#/ phone
if( !empty($phone) && (preg_match('/[0-9\+\-]{1,}/i', $phone)==false) )
{
    $error .= "<span class='error'>- Please enter a valid Phone Number!</span><br>";
}
*/
?>