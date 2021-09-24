<?php
$_POST = array_map('format_str', $_POST);
$_GET = array_map('format_str', $_GET);

if( (empty($_SESSION['code'])) || (empty($_POST['vercode'])) || ($_SESSION['code']!=$_POST['vercode']) )
{
    $_SESSION["SSN_ORDERMESSAGE"] = array(false, 'ERROR: The Verification Code you entered doesnot match the one given in the image! Please try again.');


    $form = '';
    foreach($_POST as $k=>$v)
    {
        if($k!='vercode')
        $form .= "{$k}={$v}&";
    }
    $form = str_replace("'", '&#39;', $form);
    $form = str_replace("#", '', $form);
    $form = preg_replace("/[\n\r]/", 'Xnl2nlX', $form);

    echo "<script language=\"javascript\">location.href='{$cur_page}?{$form}';</script>";
    exit;

}//end captcha check.........
//////////////////////////////////////////////////////////////////////
?>


<li>
    <div style="width:170px;">Security Code:</div>

    <div>
        <span style="font-weight:normal; color:inherit;">Use the code you see at the right side of the input box:</span><br /><br />
        <?php $title = 'Enter the Verification Code you see in the image into the field to its left. This helps keep our site free of spam. If you have trouble reading the code, click on REFRESH to re-generate it.'; ?>
        <div style="float:left;"><input name="vercode" id="vercode" type="text" class="text-i validate[required]" style="width:90px;" maxlength="10" />&nbsp;</div>
        <div style="float:left; padding:0 5px;"><img src='includes/secure_captcha.php' id='secure_image' border='0' height='20' width='67' /></div>
        <div style="float:left;"><div style="cursor:pointer; margin-top:4px;" onclick="document.getElementById('secure_image').src=document.getElementById('secure_image').src+'?<?php echo time(); ?>';">Refresh</div></div>
        <div style="float:left; padding:3px 7px 0px 3px;"><img src='images/tip2.gif' border='0' title='<?php echo $title; ?>' /></div>
        <div style="clear: both;">&nbsp;</div>
    </div>
</li>