<?php
function signup_success($name, $user_id, $verification_str, $insert_msg='', $chk_pkg, $admin_added=false)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $is_basic = (int)@$chk_pkg['is_basic'];

    $body_in = "";
	$body_in .= "Dear <b>{$name}</b>,<br /><br />";

    if($admin_added==false)
    $body_in .= "Thank you for Signing up at <b>CollaborateUSA&#46;com</b>, your Account has been successfully setup. {$insert_msg}<br /><br />";
    else
    $body_in .= "Your Account has been successfully setup at <b>CollaborateUSA&#46;com</b>. {$insert_msg}<br /><br />";

    if($admin_added==false)
    $body_in .= "However, your account has NOT been activated yet. You are required to verify that you are the actual owner of this Email Address.<br /><br />";
    else
    $body_in .= "Furthermore, your account may not have been activated yet. if so, you are required to verify that you are the actual owner of this Email Address.<br /><br />";

    $body_in .= "Please click on the following link to <b>Verify</b> your email address and <b>Activate</b> your account:<br />";

    $body_in .= "<a href='{$site_url}account-confirm/?verify={$user_id}.|.{$verification_str}' target='_blank' style='color:#2CA1F4; text-decoration:none;'>";
    $body_in .= "{$site_url}account-confirm/?verify={$user_id}.|.{$verification_str}</a>";

    $body_in .= "<br /><br />[IMP] We are continuously working on bringing you the top service you would want. Therefore, please pardon our progress while we complete our system to allow you full functionality.";

	$body_in.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #ddd; margin:20px 0; width:90%;' />";

    $body_in .= "As a member of <a href='{$site_url}' target='_blank' style='color:#2CA1F4; text-decoration:none;'>CollaborateUSA&#46;com</a>, you will want to get involved right away.<br /><br />";
    $body_in .= "<ol><li>First, make sure you have completed your <b>Profile</b></li>";
    if($is_basic==0){
    $body_in .= "<li>Complete your W-9, so you can EARN income from our website</li>";
    }
    $body_in .= "<li>Express your ideas, causes, etc. as a <b>VOICE</b> on the website</li>";
    $body_in .= "<li><b>VOTE</b> for other Voices</li></ol>";

    if($is_basic==1)
    {
        $body_in .= "Also, every time you use the website and its various functions you will begin to earn \"Patronage Points\". ";
        $body_in .= "However, as a <b>Basic Member</b> you cannot <b>cash</b> these in and EARN income from the website. When you budget permits, may I suggest you upgrade to Share Membership and help us Crowd-Source the advertising of the website. ";
        $body_in .= "Your <b>ONETIME Share Fee</b> gets you all the benefits of our website!<br /><br />";
        $body_in .= "You can <b>Upgrade</b> at anytime by visiting your 'Account Info' section on the website, but you lose your points each month until you upgrade.";
    }
    else
    {
        $body_in .= "Also, every time you use the website and its various functions you will begin to earn \"Patronage Points\". ";
        $body_in .= "As a <b>Share Member</b>, your ONETIME Share Fee qualifies you to be able to EARN income from our website. You have been credited 240 points for signing up today.<br /><br />";
        $body_in .= "Even though we won't be making distributions until after March 2015, these points will help you EARN more when the big payout comes in October 2015. Share Members joining after March 1, don't get this Bonus! ";
        $body_in .= "Thank you for joining early.  New functions and features are coming online all the time, look for the changes.";
    }


    $body_in .= "";

    return $body_in;

}//end func...


function signup_notice_to_admin($POST, $joined_on, $is_basic, $user_id)
{
    $body_in_admin = "Dear Admin,<br /><br />";
    $body_in_admin.= "A new Member has signup at CollaborateUSA&#46;com and their Account has been successfully setup. ";
    $body_in_admin.= "Here are the <b>Account Details</b>:<br /><br />";

    $body_in_admin.= "Membership ID:&nbsp;<b>{$user_id}</b><br />";
    $body_in_admin.= "Full Name:&nbsp;<b>{$POST['first_name']} {$POST['middle_name']} {$POST['last_name']}</b><br />";
    //$body_in_admin.= "Organization Name:&nbsp;<b>{$POST['company_name']}</b><br />";
	$body_in_admin.= "Email Address:&nbsp;<b>{$POST['email_add']}</b><br />";
	$body_in_admin.= "Membership Type:&nbsp;<b>".(($is_basic=='1')? 'Basic':'Share')."</b><br />";
	$body_in_admin.= "Joined On:&nbsp;<b>{$joined_on}</b><br /><br />";

	$body_in_admin.= "You can track this Member from <b>CUSA-Admin</b> matching their Email Address.";

    return $body_in_admin;

}//end func...


function resend_activation($user_info, $act_link, $insert = '')
{
    $body_in = "";
    $body_in.= "Dear <b>{$user_info['first_name']}</b>,<br /><br />";
    $body_in.= "{$insert}Here is your requested <b>Activation Code</b> for your Account.<br /><br />";

    $body_in.= "Please click on the following link to <b>Verify</b> your email address and <b>Activate</b> your account:<br />";
    $body_in.= "<a href=\"{$act_link}\" style='color:#2CA1F4; text-decoration:none;'>{$act_link}</a>";

    return $body_in;

}//end func...


function welcome_new_user($user_info, $insert = '')
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $body_in = "";
    $body_in .= "Dear <b>{$user_info['first_name']}</b>,<br /><br />";
    $body_in .= "Your Email Address has been <b>Verified</b> and your account has been successfully <b>Activated</b>. Welcome to CollaborateUSA&#46;com<br /><br />{$insert}";
    $body_in .= "Please <a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Click Here to Sign-in</a> to your account using your Email Address & Password.<br />";

    return $body_in;

}//end func...


function account_recover_access($user_info, $new_pass)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $body_in = "";
    $body_in.= "Dear <b>{$user_info['first_name']}</b>,<br /><br />";

    $body_in.= "We have received a request from you to Recover the Access of your account. As a result, we have setup a <b>Temporary Password</b> for you. Please find the details below:<br /><br />";
    $body_in.= "<b>Sign-in Email Address:</b> {$user_info['email_add']}<br />";
    $body_in.= "<b>Temporary Password:</b> {$new_pass}<br /><br />";
    $body_in.= "<u>Note</u>: This is an auto-generated password. You are requested to change it after you sign-in.<br /><br />";

    $body_in.= "Please use the following link to <a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Sign-in</a> to your account using your Email Address & Password:<br />";
    $body_in.= "<a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>{$site_url}signin</a>";

    return $body_in;

}//end func...



function payment_invoice($invoice_id, $resArray, $POST)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $body_in = "";

    $body_in .= "Dear Admin,<br /><br />";

    $body_in .= "Here are the <b>Payment Details</b> for the new <b>paid Membership</b> joining received at CollaborateUSA&#46;com.<br /><br />";

    $body_in .= "For this Transaction, you will also receive a separate email from <b>PayPal</b> as well - matching the <b>Transaction ID</b>. ";
    $body_in .= "Furthermore, a <b>Signup Notification</b> email has also been sent to you that contain the full Member's Info.<br /><br /><br />";

    $body_in .= "<b style='color:#2CA1F4; text-decoration:underline;'>Member's Info</b><br /><br />";
    $body_in .= "<b>Full Name:</b> {$POST['first_name']} {$POST['middle_name']} {$POST['last_name']}<br />";
    $body_in .= "<b>Email Address:</b> {$POST['email_add']}<br />";

    $body_in.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #ddd; margin:10px 0; width:90%;' />";

    $body_in .= "<b style='color:#2CA1F4; text-decoration:underline;'>Payment Details</b><br /><br />";
    $body_in .= "<b>Payment Gateway:</b> PayPal<br />";
    $body_in .= "<b>Invoice Number:</b> <span style='color:#2CA1F4;'>{$invoice_id}</span><br />";
    $body_in .= "<b>Transaction ID:</b> {$resArray['transaction_id']}<br />";
    $body_in .= "<b>Amount:</b> \${$resArray['amount']}<br />";
    $body_in .= "<b>Payment Status:</b> {$resArray['payment_status']}<br /><br />";
    $body_in .= "<b>Full Gateway Message (unformatted direct copy):</b><br />{$resArray['gateway_msg']}";

    $body_in.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #ddd; margin:10px 0 20px 0; width:90%;' />";

    $body_in .= "You can track this Payment via <b>CUSA Admin</b> with the given Invoice Number.<br />";

    return $body_in;

}//end func...


function payment_receipt($invoice_id, $resArray, $POST)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $body_in = "";

    $body_in .= "Dear <b>{$POST['first_name']}</b>,<br /><br />";
    $body_in .= "Thank you for your <b>Payment at CollaborateUSA&#46;com</b>. We have received your Payment Details and this is your Confirmation Receipt.<br /><br /><br />";

    $body_in .= "<b style='color:#2CA1F4; text-decoration:underline;'>Member's Info</b><br /><br />";
    $body_in .= "<b>Full Name:</b> {$POST['first_name']} {$POST['middle_name']} {$POST['last_name']}<br />";
    $body_in .= "<b>Email Address:</b> {$POST['email_add']}<br /><br /><br />";

    $body_in .= "<b style='color:#2CA1F4; text-decoration:underline;'>Payment Details</b><br /><br />";
    $body_in .= "<b>Invoice Number:</b> <span style='color:#2CA1F4;'>{$invoice_id}</span><br />";
    $body_in .= "<b>Payment Gateway:</b> PayPal<br />";
    $body_in .= "<b>Transaction ID:</b> {$resArray['transaction_id']}<br />";
    $body_in .= "<b>Amount:</b> \${$resArray['amount']}<br />";
    $body_in .= "<b>Payment Status:</b> {$resArray['payment_status']}";

    $body_in.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #ddd; margin:10px 0 20px 0; width:90%;' />";

    $body_in .= "Once again, we would like to thank you for your time & money at CollaborateUSA&#46;com.<br /><br />";
    $body_in .= "Please <a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Click Here to Sign-in</a> to your account using your Email Address & Password.<br />";

    return $body_in;

}//end func...


function notification_email($from_usr, $to_usr, $notification, $created_on, $visit_url='', $notif_details='')
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    #/ Set from-DP
    if(!empty($from_usr))
    {
        $prf_pic = "{$site_url}assets/images/ep_th.png";
        if(!empty($from_usr['profile_pic'])){
        $prf_pic = "{$site_url}user_files/prof/{$from_usr['user_id']}/{$from_usr['profile_pic']}";
        }
    }

    $body_in = "";
    $body_in.= "Dear <b>{$to_usr['user_ident']}</b>,<br /><br />";
    $body_in.= "You have received the following <b>Notification</b> from CollaborateUSA&#46;com:<br />";

    $body_in.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #ddd; margin:20px 0 10px 0; width:90%;' />";
    $body_in.= "<table style='padding-top:2px;' cellpadding='0' cellspacing='0'><tr>";

        if(!empty($from_usr)){
        $body_in.= "<td style=\"width:58px; height:52px; vertical-align:top;\"><img src=\"{$prf_pic}\" style='width:50px; height:50px; border-radius:5px;' /></td>";
        }

        $body_in.= "<td style=\"color:color:#464646; font-family:Arial, Helvetica, sans-serif; font-size:13px; border-left:dotted 1px #eee; padding-left:5px; vertical-align:top;\">
            <div style=\"color:#666; font-style:italic; font-size:11px; padding-bottom:4px;\">{$created_on}</div>
            <div style=\"margin-bottom:2px;\"><a href=\"{$site_url}{$visit_url}\" style='color:#2CA1F4; text-decoration:none;'>{$notification}.</a></div>";

        if(!empty($notif_details)) {
        $body_in.= "<div style='margin-top:3px;'>{$notif_details}</div>";
        }

        $body_in.= "</td>";

    $body_in.= "</tr></table>";
    $body_in.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #ddd; margin:10px 0 20px 0; width:90%;' />";


    $body_in.= "In order to review the details, please <a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Sign-in</a> to your account and see the notification area / dropdown. ";
    //$body_in.= "If you wish not to receive this notification in future, you may change the <b>Notification Settings</b> after you login.<br />";

    //die($body_in);
    return $body_in;

}//end func...


function password_updated($user_info, $new_pass)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $updated_on = date('Y-m-d H:i:s');

    $body_in = "";
    $body_in.= "Dear <b>{$user_info['first_name']}</b>,<br /><br />";

    $body_in.= "This is a confirmation that you have successfully <b>Updated</b> your Account Password at CollaborateUSA&#46;com. However, if the Password Change was not initiated by you, please feel free to contact the Admin.<br /><br />";
    $body_in.= "Please find the details below:<br /><br />";
    $body_in.= "<b>New Password:</b> {$new_pass}<br />";
    $body_in.= "<b>Updated On:</b> {$updated_on}<br /><br />";

    $body_in.= "Please use the following link to <a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Sign-in</a> to your account using your Email Address & Password:<br />";
    $body_in.= "<a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>{$site_url}signin</a>";

    return $body_in;

}//end func...


function acc_recovery_updated($user_info)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $updated_on = date('Y-m-d H:i:s');

    $body_in = "";
    $body_in.= "Dear <b>{$user_info['first_name']}</b>,<br /><br />";

    $body_in.= "This is a confirmation that you have successfully UPDATED your <b>Account Recovery Settings</b> at CollaborateUSA&#46;com. ";
    $body_in.= "However, if the change was not initiated by you, please contact the Admin ASAP.<br /><br />";

    $body_in.= "Please use the following link to <a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Sign-in</a> to your account using your Email Address & Password:<br />";
    $body_in.= "<a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>{$site_url}signin</a>";

    return $body_in;
}//end func...


function compose_msg($msg, $user_info, $created_on, $visit_url, $is_reply=false)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $body_in = "";
    $body_in.= "Dear Member,<br /><br />";

    if($is_reply==false)
    $body_in.= "You have received a new Member-message at CollaborateUSA&#46;com<br />";
    else
    $body_in.= "You have received a new <b>Reply</b> on your Message Thread at CollaborateUSA&#46;com<br />";

    $body_in.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #ddd; margin:20px 0 10px 0; width:90%;' />";

    $body_in.= "<table style='padding-top:2px;' cellpadding='0' cellspacing='0'><tr>";

        if(!empty($user_info))
        {
            $prof_pic = $site_url."assets/images/ep.png";
            if(!@empty($user_info["profile_pic"])){
            $prof_pic = $site_url."user_files/prof/{$user_info['user_id']}/{$user_info["profile_pic"]}";
            }
            $prof_pic_th = @substr_replace($prof_pic, '_th.', @strrpos($prof_pic, '.'), 1);

            $body_in.= "<td style=\"width:58px; height:52px; vertical-align:top;\"><img src=\"{$prof_pic_th}\" style='width:50px; height:50px; border-radius:5px;' /></td>";
        }

        $body_in.= "<td style=\"color:color:#464646; font-family:Arial, Helvetica, sans-serif; font-size:13px; border-left:dotted 1px #eee; padding-left:5px; vertical-align:top;\">
            <div style=\"color:#666; font-style:italic; font-size:11px; margin-bottom:2px;\">{$created_on}</div>";

        if(!empty($msg)) {
        $body_in.= "<div style='margin-top:3px;'><a href=\"{$site_url}{$visit_url}\" style='color:#2CA1F4; text-decoration:none;'>{$msg}</a></div>";
        }

        $body_in.= "</td>";

    $body_in.= "</tr></table>";
    $body_in.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #ddd; margin:10px 0 20px 0; width:90%;' />";


    $body_in.= "In order to review the details, please <a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Sign-in</a> to your account and see the Messages area / dropdown.";

    return $body_in;

}//end func...


function upgrade_success($name, $user_id, $chk_pkg, $invoice_id, $admin_added=false, $insert_msg='')
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $is_basic = (int)@$chk_pkg['is_basic'];

    $body_in = "";
	$body_in .= "Dear <b>{$name}</b>,<br /><br />";

    $body_in .= "This is a confirmation that Your Account has been successfully <b>Upgraded to \"{$chk_pkg['title']}\"</b> at CollaborateUSA&#46;com (Invoice ID <b>\"{$invoice_id}\"</b>). ";
    if($admin_added==false)
    $body_in .= "Thank you for Upgrading your Membership. {$insert_msg}<br /><br />";
    else
    $body_in .= "{$insert_msg}<br /><br />";

    $body_in .= "Do note, in order for the upgrade to take effect, you are required to re-Signin to your account.<br /><br />";

    $body_in.= "Please use the following link to <a href='{$site_url}logout' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Sign-in</a> to your account using your Email Address & Password:<br />";
    $body_in.= "<a href='{$site_url}logout' target='_blank' style='color:#2CA1F4; text-decoration:none;'>{$site_url}logout</a>";

    return $body_in;

}//end func...


function upgrade_notice_to_admin($POST, $paid_on, $chk_pkg, $invoice_id)
{
    $is_basic = (int)@$chk_pkg['is_basic'];

    $body_in_admin = "Dear Admin,<br /><br />";
    $body_in_admin.= "A Member has successfully Upgraded their Membership Package at CollaborateUSA&#46;com. ";
    $body_in_admin.= "Here are the <b>Account Details</b>:<br /><br />";

    $body_in_admin .= "Invoice for Upgrade:&nbsp;<b><span style='color:#2CA1F4;'>{$invoice_id}</span></b><br />";
    $body_in_admin .= "Full Name:&nbsp;<b>{$POST['first_name']} {$POST['middle_name']} {$POST['last_name']}</b><br />";
    $body_in_admin .= "Organization Name:&nbsp;<b>{$POST['company_name']}</b><br />";
	$body_in_admin .= "Email Address:&nbsp;<b>{$POST['email_add']}</b><br />";
	$body_in_admin .= "Membership Package:&nbsp;<b>{$chk_pkg['title']}</b><br />";
	$body_in_admin .= "Membership Type:&nbsp;<b>".(($is_basic=='1')? 'Basic':'Share')."</b><br />";
	$body_in_admin .= "Upgraded On:&nbsp;<b>{$paid_on}</b><br /><br />";

	$body_in_admin .= "You can track this Member from <b>CUSA-Admin</b> by matching their Email Address.";

    return $body_in_admin;

}//end func...


function payment_invoice_upgrade($invoice_id, $resArray, $POST)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $body_in = "";

    $body_in .= "Dear Admin,<br /><br />";

    $body_in .= "Here are the <b>Payment Details</b> for the <b>Membership Upgrade</b> received at CollaborateUSA&#46;com.<br /><br />";

    $body_in .= "For this Transaction, you will also receive a separate email from <b>PayPal</b> as well - matching the <b>Transaction ID</b>. ";
    $body_in .= "Furthermore, an <b>Upgrade Notification</b> email has also been sent to you that contain the full Member's Info.<br /><br /><br />";

    $body_in .= "<b style='color:#2CA1F4; text-decoration:underline;'>Member's Info</b><br /><br />";
    $body_in .= "<b>Full Name:</b> {$POST['first_name']} {$POST['middle_name']} {$POST['last_name']}<br />";
    $body_in .= "<b>Email Address:</b> {$POST['email_add']}<br />";

    $body_in.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #ddd; margin:10px 0; width:90%;' />";

    $body_in .= "<b style='color:#2CA1F4; text-decoration:underline;'>Payment Details</b><br /><br />";
    $body_in .= "<b>Invoice Number:</b> <span style='color:#2CA1F4;'>{$invoice_id}</span><br />";
    $body_in .= "<b>Payment Gateway:</b> PayPal<br />";
    $body_in .= "<b>Transaction ID:</b> {$resArray['transaction_id']}<br />";
    $body_in .= "<b>Amount:</b> \${$resArray['amount']}<br />";
    $body_in .= "<b>Payment Status:</b> {$resArray['payment_status']}<br /><br />";
    $body_in .= "<b>Full Gateway Message (unformatted direct copy):</b><br />{$resArray['gateway_msg']}";

    $body_in.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #ddd; margin:10px 0 20px 0; width:90%;' />";

    $body_in .= "You can track this Payment via <b>CUSA Admin</b> with the given Invoice Number.<br />";

    return $body_in;

}//end func...


function refer_invitation($user_info, $isv_name, $ppoints_key)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $ppoints_key = rand(2, 20).".|".$ppoints_key;
    $signup_path = "{$site_url}signup/?{$ppoints_key}";
    $msg = "You have have been invited by <b>{$user_info['first_name']} {$user_info['last_name']}</b> to join CollaborateUSA&#46;com<br />";
    $created_on = date('jS M, Y');

    $body_in = "";
    $body_in .= "Dear {$isv_name},<br /><br />";


    $body_in.= "<table style='padding-top:2px; padding-bottom:10px;' cellpadding='0' cellspacing='0'><tr>";

        if(!empty($user_info))
        {
            $prof_pic = $site_url."assets/images/ep.png";
            if(!@empty($user_info["profile_pic"])){
            $prof_pic = $site_url."user_files/prof/{$user_info['user_id']}/{$user_info["profile_pic"]}";
            }
            $prof_pic_th = @substr_replace($prof_pic, '_th.', @strrpos($prof_pic, '.'), 1);

            $body_in.= "<td style=\"width:58px; height:52px; vertical-align:top;\"><img src=\"{$prof_pic_th}\" style='width:50px; height:50px; border-radius:5px;' /></td>";
        }

        $body_in.= "<td style=\"color:color:#464646; font-family:Arial, Helvetica, sans-serif; font-size:13px; border-left:dotted 1px #eee; padding-left:5px; vertical-align:top;\">
            <div style=\"color:#666; font-style:italic; font-size:11px; padding-bottom:2px;\">{$created_on}</div>
            <div style='padding-top:3px;'><a href=\"{$signup_path}\" style='color:#2CA1F4; text-decoration:none;'>{$msg}</a></div>

            <div style='padding-top:10px;'>";
            $body_in.= "<a href='{$signup_path}' target='_blank' style='display:block; text-decoration:none; font-size:12px; line-height:25px; cursor:pointer; border-radius:5px; background:#53A9E9; box-shadow:1px 1px 4px #AAA; color:#FFF !important; font-family:arial; font-weight:700; height:25px; margin:0; text-align:center; width:90px;'>&nbsp;REPLY&nbsp;</a>";
            $body_in.= "
            </div>";

        $body_in.= "</td>";
        $body_in.= "</td>";

    $body_in.= "</tr></table>";

    $body_in.= "<hr style='text-align:left; border:none; background:none; height:1px; border-bottom:solid 1px #ddd; margin:5px 0 20px 0; width:100%;' />";

    $body_in .= "The CollaborateUSA is both collaborative and social, and the activities are considerate. Every time you use the website and its various functions you will begin to earn <b>\"Patronage Points\"</b>.<br /><br />";
    $body_in .= "There are 2 types of Memberships; Basic and Share. As a <b>Basic Member</b> you cannot <b>cash</b> your Points and EARN income from the website. However, when you budget permits, may I suggest you upgrade to Share Membership and help us Crowd-Source the advertising of the website.<br /><br />";
    $body_in .= "As a <b>Share Member</b>, your ONETIME Share Fee qualifies you to get all the benefits of our website, and to be able to EARN income from our website.<br /><br />";

    $body_in.= "Please use the following link to <a href='{$signup_path}' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Signup at CollaborateUSA&#46;com</a>:<br />";
    $body_in.= "<a href='{$signup_path}' target='_blank' style='color:#2CA1F4; text-decoration:none;'>{$signup_path}</a>";

    return $body_in;

}//end func...



function tax_info_updated($user_info)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $body_in = "";
    $body_in.= "Dear <b>{$user_info['first_name']}</b>,<br /><br />";

    $body_in.= "This is a confirmation that you have successfully UPDATED your <b>Tax Information</b> at CollaborateUSA&#46;com. ";
    $body_in.= "You can review it from the <a href='{$site_url}tax-info' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Tax Info</a> page.<br /><br />";
    $body_in.= "However, if the change was not initiated by you, please contact the Admin ASAP.<br /><br />";

    $body_in.= "Please use the following link to <a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Sign-in</a> to your account using your Email Address & Password:<br />";
    $body_in.= "<a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>{$site_url}signin</a>";

    return $body_in;
}//end func...


function tax_info_updated_admin($user_info, $user_id)
{
    $is_basic = (int)$user_info['is_basic'];

    $body_in_admin = "Dear Admin,<br /><br />";
    $body_in_admin.= "A Member has Added/Updated their <b>Tax Info (Form W-9)</b> at CollaborateUSA&#46;com.<br /><br />";

    $body_in_admin.= "Here are the <b>Account Details</b>:<br /><br />";
    $body_in_admin.= "Membership ID:&nbsp;<b>{$user_id}</b><br />";
    $body_in_admin.= "Full Name:&nbsp;<b>{$user_info['first_name']} {$user_info['middle_name']} {$user_info['last_name']}</b><br />";
    $body_in_admin.= "Email Address:&nbsp;<b>{$user_info['email_add']}</b><br />";
	$body_in_admin.= "Membership Type:&nbsp;<b>".(($is_basic=='1')? 'Basic':'Share')."</b><br /><br />";

	$body_in_admin.= "You can track this Member and their Tax Form using the <b>CUSA-Admin</b> matching their Email Address.";

    return $body_in_admin;

}//end func...

/////////////////////////////////////////////////////



function order_status_updated($first_name, $invoice, $order_status)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $body_in = "";
    $body_in.= "Dear <b>{$first_name}</b>,<br /><br />";
    $body_in.= "This is a notification to inform you that the <b>Status</b> for your <b style='color:#2CA1F4;'>Order {$invoice}</b> has been updated. Please find the details below:<br /><br />";
    $body_in.= "Order Invoice Number: <b>{$invoice}</b><br />";
    $body_in.= "Order Status: <b style='color:#2CA1F4;'>{$order_status}</b><br /><br /><br />";

    $body_in.= "You can review full Details of your Order from \"My Accounts\" menu at the CollaborateUSA&#46;com after you sign-in. ";
    $body_in.= "Please use the following link to <a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Sign-in</a> to your account:<br />";
    $body_in.= "<a href='{$site_url}signin' target='_blank' style='color:#2CA1F4; text-decoration:none;'>{$site_url}signin</a>";

    return $body_in;

}//end func....


function subscription_request_received($email_add, $nl_subscriber_id, $verification_str)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $body_in = "";
    $body_in.= "Dear User,<br /><br />";
    $body_in.= "This is a confirmation that we have successfully received your <b>Subscription</b> request for our periodic <b>Newsletter Service</b>.<br /><br />";

    $body_in.= "The following is your Email Address with which you have been subscribed:<br />";
    $body_in.= "<b>{$email_add}</b><br /><br />";

    $body_in .= "However, you are required to verify that you are the actual owner of this Email Address.<br /><br />";
    $body_in .= "Please click on the following link to <b>Verify</b> your Email Address and <b>Confirm</b> your Subscription:<br />";

    $body_in .= "<a href='{$site_url}newsletter-confirm/?verify={$nl_subscriber_id}.|.{$verification_str}' target='_blank' style='color:#2CA1F4; text-decoration:none;'>";
    $body_in .= "{$site_url}newsletter-confirm/?verify={$nl_subscriber_id}.|.{$verification_str}</a><br />";

    return $body_in;

}//end func....


function subscription_confirmation($email_add)
{
    $body_in = "";
    $body_in.= "Dear User,<br /><br />";
    $body_in.= "This is a confirmation that you have successfully <b>Subscribed</b> to our <b>Newsletter Service</b> and your Email Address has been successfully Confirmed. Thank you for joining our service.<br /><br />";

    $body_in.= "The following is your Email Address with which you have been subscribed:<br />";
    $body_in.= "<b>{$email_add}</b><br /><br />";

    $body_in.= "Periodically, we send interesting & informative Newsletters within the domain of our business (i.e. Logo & Associated Designs). ";
    $body_in.= "Please make sure our email address is added into your Safe List and our Emails are not going to your Junk mail.<br /><br />";

    $body_in.= "Once again, we would like to thank you for your support.<br />";

    return $body_in;

}//end func....


function unsubscription_confirmation($email_add)
{
    global $consts;
    $site_url = $consts['SITE_URL'];

    $body_in = "";
    $body_in.= "Dear User,<br /><br />";
    $body_in.= "This is a confirmation that we have received your <b>unsubscription</b> request from our <b>Newsletter Service</b> against your Email Address. ";
    $body_in.= "Please allow us <b>7 working days</b> to completely remove you from our email Services.<br /><br />";

    $body_in.= "Though, we are sad to see you leave, you are always welcome to <b>Subscribe back</b> again to our Newsletters by following the <a href='{$site_url}newsletter-subscription' target='_blank' style='color:#2CA1F4; text-decoration:none;'>Subscription link</a> given below:<br /><br />";
    $body_in .= "<a href='{$site_url}newsletter-subscription' target='_blank' style='color:#2CA1F4; text-decoration:none;'>{$site_url}newsletter-subscription</a><br /><br />";

    $body_in.= "Once again, we would like to thank you for your support.<br />";

    return $body_in;
}
?>