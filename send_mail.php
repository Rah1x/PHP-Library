<?php
function mail_body($heading, $body_in, $frm_nm, $act_link='')
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    ##define Body
    $messg  = '';


    /*$messg .= '<div style="width:650px; padding:2px; color:#464646; font-family:Arial, Helvetica, sans-serif !important; font-size:13px;">';
    $messg .= '<div style="padding:20px 15px 12px 15px;">';

    $messg .= '<div>';
    $messg .= '<div style="padding-left:1px; margin-left:-1px; background:#EE383A;"><a href="'.$site_url.'" target="_blank"><img src="'.$site_url.'assets/images/oc_logo2.png" border="none" /></a></div>';

    $messg .= '<div style="padding-top:5px;">';
    //$messg .= "<div style='padding:3px 5px 3px 1px; border:none; border-bottom:solid 1px #EE383A;'><b style='font-size:15px; color:#EE383A;'>".$heading."</b></div><br />";
    $messg .= "<div style='padding:10px; border:none; background:#EE383A; border-radius:3px;'><b style='font-size:15px; color:#FFFFFF;'>".$heading."</b></div><br />"; //2nd design
    $messg .= "<div style='padding:10px 10px 3px 0; color:#464646;'>".$body_in."</div>";
    $messg .= "<br /><b style='color:#EE383A; font-size:14px;'>Support Team,<br /><a href=\"{$site_url}\" target=\"_blank\" style='color:#EE383A; text-decoration:none;'>PickTheWinner.com.au</a></b>";

    $messg.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #eee; margin:10px 0 5px 0; width:90%;' />";
    $messg .= "<div style='font-size:11px; color:#aaa; font-style:italic;'>This is an auto-generated email. Please do not reply as it will not be received.</div>";

    $messg .= '</div>';

    $messg .= '</div><br />';

    $messg .= '</div>';
    $messg .= '</div>';
    */

    $messg .= '<body bgcolor="#F4F4F4">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#F4F4F4">
    <tr>
    <td>
    <table width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" border="0" align="center" style="font-family: Arial, sans-serif; font-size: 15px; color: #5E5348;">
    ';

    $messg .= '
    <tbody>
    <tr>
        <td style="text-align: center;">
            <a href="'.$site_url.'" target="_blank" title="PickTheWinner">
                <img src="'.$site_url.'assets/images/email-header.jpg" width="600" height="236" alt="PickTheWinner" title="PickTheWinner" style="display: block; border:none;" />
            </a>
        </td>
    </tr>

    <tr><td style="text-align: center; padding: 20px 0; color: #968400;"><h3 style="margin: 0;">'.$heading.'</h3></td></tr>
    <tr><td style="padding: 15px 25px 0 25px;"><p style="margin: 0;">'.$body_in.'</p></td></tr>
    <tr><td style="padding-top:0px;">&nbsp;</td></tr>';

    if(is_array($act_link) && isset($act_link['url'])){
    $messg .= '<tr>
    <td style="background: #222222; text-align: center; padding: 40px 0;">
    <a href="{#BUTTON-HREF#}" target="_blank" title="'.@$act_link['title'].'" style="text-decoration: none; color:#ffffff; text-transform: uppercase; background: #968400; padding: 15px 30px; font-size: 16px;">
    <span>'.@$act_link['title'].'</span></a>
    </td>
    </tr>
    ';
    $act_link = @$act_link['url'];
    }

    $messg .= "<tr>
    <td style=\"background: #111111; text-align: left; padding: 15px 25px; border-top: 2px solid #dbc001; color: #cccccc;\">
    <b>Support Team,<br />
    <a href=\"{$site_url}\" target=\"_blank\" style='color:#cccccc !important; text-decoration:none;'>PickTheWinner.com.au</a></b>
    </td>
    </tr>
    </tbody>
    ";

    $messg .= '<tfoot style="background: #F8F8F8; color: #555555;">
    <tr>
        <td style="padding: 10px 0 0 0; text-align: center; font-size: 11px;">
        Suite 209/838 Collins Street - Docklands - VIC 3008</td>
    </tr>

    <tr>
        <td style="padding: 10px 0; text-align: center; font-size: 10px; font-style:italic;";>
        This is an auto-generated email. Please do not reply as it will not be received.</td>
    </tr>
    </tfoot>
    ';

    $messg .= '
    </table>
    </td></tr></table>
    </body>';

    if(stristr($messg, '{#BUTTON-HREF#}')){
    $messg = str_ireplace('{#BUTTON-HREF#}', $act_link, $messg);
    }

    //echo $messg; die();
    return $messg;

}//end mail body...


///////////////////////////////////////////////////--------------------------------


function send_mail($to, $subject, $heading, $body_in, $act_link='', $return_html_only=false, $frm_nm='PickTheWinner.com.au', $frm_email='noreply@PickTheWinner.com.au')
{
    #/ define Body
    $messg = mail_body($heading, $body_in, $frm_nm, $act_link);
    if($return_html_only){
    return $messg;
    }
    $messg = chunk_split(base64_encode($messg));


    #/ Hide PHP Script Identifiers (X-PHP-Script)
    $phpself = $_SERVER['PHP_SELF'];
    $phpremoteaddr = $_SERVER['REMOTE_ADDR'];
    $phpservername = $_SERVER['SERVER_NAME'];
    $_SERVER['PHP_SELF'] = "/";
    $_SERVER['REMOTE_ADDR'] = "0.0.0.0";
    $_SERVER['SERVER_NAME'] = "none";


    //$to = 'raheelhasan.fsd@gmail.com';
    ## Defining Header
    $hdr='';
    $hdr.='MIME-Version: 1.0'."\n";
    $hdr.='Content-type: text/html; charset=utf-8'."\n";
    $hdr.='Content-Transfer-Encoding: base64'."\n";
	$hdr.="From: {$frm_nm}<{$frm_email}>\n";
    $hdr.="Reply-To: {$frm_nm}<{$frm_email}>\n";
    $hdr.="Return-Path: <{$frm_email}>\n";
    $hdr.="Message-ID: <".time()."-{$frm_email}>\n";
    $hdr.='X-Mailer: DSP/1.0';


    #/ Debug
    /*$messg = "TEST";
    $subject = "TEST";
    $hdr = '';#*/

    #/ Send Email
    $x = @mail($to, $subject, $messg, $hdr);
    if($x==0)
    {
    	$to = str_replace('@', '\@', $to);
    	$hdr = str_replace('@', '\@', $hdr);
    	$x = @mail($to, $subject, $messg, $hdr);
    }
    //var_dump($x); exit;

    #/ restore obfuscated server variables
    $_SERVER['PHP_SELF'] = $phpself;
    $_SERVER['REMOTE_ADDR'] = $phpremoteaddr;
    $_SERVER['SERVER_NAME'] = $phpservername;

    return $x;

}//end func.....

///////////////////////////////////////////////////////////////////////////////////////////////////////

#/*
//Works only if SSL is enabled
function send_mail2($to, $subject, $heading, $body_in, $act_link='', $return_html_only=false, $frm_nm='PickTheWinner.com.au', $frm_email='noreply@PickTheWinner.com.au')
{
    #/ Use PHPMailer Instead
    if (!class_exists('PHPMailer')) {
    @include_once('../../includes/PHPMailer/class.phpmailer.php');
    @include_once('../includes/PHPMailer/class.phpmailer.php');
    }

    if (class_exists('PHPMailer'))
    {

        #/ define Body
        $messg = mail_body($heading, $body_in, $frm_nm, $act_link);
        //echo $messg; die();
        if($return_html_only){
        return $messg;
        }


        #/ Hide PHP Script Identifiers (X-PHP-Script)
        $phpself = $_SERVER['PHP_SELF'];
        $phpremoteaddr = $_SERVER['REMOTE_ADDR'];
        $phpservername = $_SERVER['SERVER_NAME'];
        $_SERVER['PHP_SELF'] = "/";
        $_SERVER['REMOTE_ADDR'] = "0.0.0.0";
        $_SERVER['SERVER_NAME'] = "none";



        $mailx = new PHPMailer;
        $mailx->isSMTP();

        #/ Setup Settings
        if(in_array($phpservername, array('oc-local', 'oc-local.com', 'oc-local-old', 'localhost'))==false){ //SERVER
        @include_once('../../includes/PHPMailer/class.smtp.php');
        @include_once('../includes/PHPMailer/class.smtp.php');
        $mailx->Host = SMTP_HOSTX;
        $mailx->SMTPAuth = true;
        $mailx->SMTPKeepAlive = true;
        $mailx->Port = 587;
        $mailx->SMTPSecure = 'tls';
        $mailx->Username = SMTP_USERX;
        $mailx->Password = SMTP_PASSWORDX;
        //$mailx->SMTPDebug = 4;
        //var_dump("<pre>", $mailx->smtpConnect(), $mailx->ErrorInfo); $mailx->smtpClose(); die('x'); //debug connection
        } else {
        $mailx->Mailer = 'mail';
        }

        $mailx->XMailer = "DSP/1.0";
        $mailx->isHTML(true);
        $mailx->CharSet = 'utf-8';
        $mailx->Encoding = 'base64';
        $mailx->addCustomHeader('Content-Transfer-Encoding', 'base64');

        $mailx->setFrom(SMTP_USERX, $frm_nm);
        $mailx->addReplyTo(SMTP_USERX, $frm_nm);
        $mailx->ReturnPath = SMTP_USERX;


        #/ Setup EMail
        $mailx->Subject = $subject;

        if(strstr($to, ',')){
        $tos = explode(',', $to);
        foreach($tos as $tv){$mailx->addAddress($tv);}
        } else {
        $mailx->addAddress($to);
        }

        $mailx->msgHTML($messg);


        #/ Send Email
        $mailer_err = '';
        if(!$mailx->send()){
        $mailer_err = "Mailer Error (".str_replace("@", "&#64;", $to).') '.$mailx->ErrorInfo.'<br />';
        //var_dump_p($mailer_err); die();
        }

        $mailx->clearAddresses();
        $mailx->clearAttachments();


        if(in_array($phpservername, array('oc-local', 'oc-local.com', 'oc-local-old', 'localhost'))==false){ //SERVER
        $mailx->smtpClose();
        }


        #/ restore obfuscated server variables
        $_SERVER['PHP_SELF'] = $phpself;
        $_SERVER['REMOTE_ADDR'] = $phpremoteaddr;
        $_SERVER['SERVER_NAME'] = $phpservername;


        return (empty($mailer_err)? true:$mailer_err);

    }
    else
    {
        return send_mail($to, $subject, $heading, $body_in, $act_link, $return_html_only, $frm_nm, $frm_email);

    }//end if not PHPMAiler...

}//end func.....


///////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Send Email via PHPMAiler - input is array
*/
function send_mail2Ar($arr_in)
{
    if(empty($arr_in)){return false;}
    $rtn = array();


    #/ Use PHPMailer Instead
    if (!class_exists('PHPMailer')) {
    @include_once('../../includes/PHPMailer/class.phpmailer.php');
    @include_once('../includes/PHPMailer/class.phpmailer.php');
    }

    if (class_exists('PHPMailer'))
    {

        #/ Hide PHP Script Identifiers (X-PHP-Script)
        $phpself = $_SERVER['PHP_SELF'];
        $phpremoteaddr = $_SERVER['REMOTE_ADDR'];
        $phpservername = $_SERVER['SERVER_NAME'];
        $_SERVER['PHP_SELF'] = "/";
        $_SERVER['REMOTE_ADDR'] = "0.0.0.0";
        $_SERVER['SERVER_NAME'] = "none";



        $mailx = new PHPMailer;
        $mailx->isSMTP();


        #/ Setup HEader & Settings
        if(in_array($phpservername, array('oc-local', 'oc-local.com', 'oc-local-old', 'localhost'))==false){ //SERVER
        @include_once('../../includes/PHPMailer/class.smtp.php');
        @include_once('../includes/PHPMailer/class.smtp.php');
        $mailx->Host = SMTP_HOSTX;
        $mailx->SMTPAuth = true;
        $mailx->SMTPKeepAlive = true;
        $mailx->Port = 587;
        $mailx->SMTPSecure = 'tls';
        $mailx->Username = SMTP_USERX;
        $mailx->Password = SMTP_PASSWORDX;
        //$mailx->SMTPDebug = 4;
        //var_dump("<pre>", $mailx->smtpConnect(), $mailx->ErrorInfo); $mailx->smtpClose(); die('x'); //debug connection
        } else {
        $mailx->Mailer = 'mail';
        }

        $mailx->XMailer = "DSP/1.0";
        $mailx->isHTML(true);
        $mailx->CharSet = 'utf-8';
        $mailx->Encoding = 'base64';
        $mailx->addCustomHeader('Content-Transfer-Encoding', 'base64');


        ##/ Setup EMail
        //$to, $subject, $heading, $body_in, $theme_color='63221B', $logo='', $frm_nm='PickToWin.com.au', $frm_email='noreply@picktowin.com.au', $template='';
        $frm_nm='PickTheWinner.com.au';
        $frm_email='noreply@PickTheWinner.com.au';

        $mailer_err = '';
        $err_count = $succ_count = 0;
        foreach($arr_in as $arv)
        {
            #/ From
            $mailx->ReturnPath = $frm_email;
            //$mailx->setFrom($frm_email, $frm_nm); //failure due to verification
            $mailx->setFrom(SMTP_USERX, $frm_nm);
            $mailx->addReplyTo($frm_email, $frm_nm);

            #/ Subject
            $mailx->Subject = $arv['subject'];

            #/ To
            if(strstr($arv['to'], ',')){
            $tos = explode(',', $arv['to']);
            foreach($tos as $tv){$mailx->addAddress($tv);}
            } else {
            $mailx->addAddress($arv['to']);
            }

            #/ Body
            $messg = mail_body($arv['heading'], $arv['body_in'], $frm_nm, $arv['act_link']);
            $mailx->msgHTML($messg);
            //echo $messg; die();


            #/ Send Email
            if(!$mailx->send()){
            $mailer_err.= "Mailer Error (".str_replace("@", "&#64;", $arv['to']).') '.$mailx->ErrorInfo.'<br />';
            $err_count++;
            }

            #/ Reset
            $mailx->clearAddresses();
            $mailx->clearAttachments();

            $succ_count++;

        }///end foreach...
        //var_dumpx($mailer_err);


        #/ Close
        if(in_array($phpservername, array('oc-local', 'oc-local.com', 'oc-local-old', 'localhost'))==false){ //SERVER
        $mailx->smtpClose();
        }


        #/ restore obfuscated server variables
        $_SERVER['PHP_SELF'] = $phpself;
        $_SERVER['REMOTE_ADDR'] = $phpremoteaddr;
        $_SERVER['SERVER_NAME'] = $phpservername;

        //var_dumpx($mailer_err);
        //return (empty($mailer_err)? true:false);
        return array($succ_count, $err_count, $mailer_err);

    }
    else
    {
        foreach($arr_in as $arv){
        $rtn[] = send_mail($arv['to'], $arv['subject'], $arv['heading'], $arv['body_in'], $arv['act_link'], false, $frm_nm, $frm_email);
        }

        return $rtn;

    }//end if not PHPMAiler...

}//end func.....
#*/
?>