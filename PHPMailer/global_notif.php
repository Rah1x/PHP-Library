<?php
/**
 * Example of a notification system
 * which uses PHPMailer to send emails as well
 * Notifications can be sent to everyone = *, particular Package users = P_, or specific users
**/
class global_notif
{
    var $template_id, $object, $POST, $created_on;
    var $pack_id, $notif_template, $fixed_users;
    var $per_loop, $lpx;
    var $heading, $mailx, $mailer_err;

    function global_notif($template_id, $object, $POST, $created_on)
    {
        $this->template_id = $template_id;
        $this->object = $object;
        $this->POST = $POST;
        $this->created_on = $created_on;

        $this->lpx = true;
        $this->per_loop = 20;
        $this->mailer_err = '';


        #/ Identify Package (if Packages based)
        $this->pack_id = 0;
        $this->fixed_users = false; //multiple users by default
        if(stristr($POST['user_ids'], 'P_')!=false) //specific package
        {
            $pack_id_r = @explode('P_', @$POST['user_ids']);
            $pack_id = (int)@$pack_id_r[1];
            $this->pack_id = $pack_id;
        } else if($POST['user_ids']!='*') { //specific user only
        $this->fixed_users = true;
        }


        #/ Identify Template
        include_once('../../includes/shared_mem_model.php');
        include_once('../../includes/notif_func.php');
        $this->notif_template = get_notif_template($template_id);
        if(empty($this->notif_template) || count($this->notif_template)<=0 || !array_key_exists('notification', $this->notif_template)){return false;}


        #/ Setup PHPMailer & Mail
        include_once('../../includes/send_mail.php');
        include_once('../../includes/email_templates.php');
        $this->heading = "New Notification from ******";

        include_once('../../includes/PHPMailer/class.phpmailer.php');
        $this->mailx = new PHPMailer;
        $this->mailx->isSMTP();

        if(in_array($_SERVER['SERVER_NAME'], array('proj-local', 'localhost'))==false){ //SERVER
        include_once('../../includes/PHPMailer/class.smtp.php');
        $this->mailx->Host = 'localhost'; //smtp.secureserver.net
        $this->mailx->SMTPAuth = true;
        $this->mailx->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
        $this->mailx->Port = 587;
        $this->mailx->SMTPSecure = 'tls';
        $this->mailx->Username = 'membersupport@******.com';
        $this->mailx->Password = '******';
        //var_dump($this->mailx->smtpConnect()); $this->mailx->smtpClose(); die('x'); //debug connection
        } else {
        $this->mailx->Mailer = 'mail';
        }

        $this->mailx->XMailer = "DSP/1.0";
        $this->mailx->isHTML(true);
        $this->mailx->CharSet = 'utf-8';
        $this->mailx->Encoding = 'base64';
        $this->mailx->addCustomHeader('Content-Transfer-Encoding', 'base64');

        $this->mailx->setFrom('membersupport@******.com', '******.com');
        $this->mailx->addReplyTo('membersupport@******.com', '******.com');
        $this->mailx->ReturnPath = 'membersupport@******.com';
        //$this->mailx->SMTPDebug = 2;
        //var_dump_p($this->mailx); die();

    }//end func...


    function process_notifs()
    {
        #/ Hide PHP Script Identifiers (X-PHP-Script)
        $phpself = $_SERVER['PHP_SELF'];
        $phpremoteaddr = $_SERVER['REMOTE_ADDR'];
        $phpservername = $_SERVER['SERVER_NAME'];
        $_SERVER['PHP_SELF'] = "/";
        $_SERVER['REMOTE_ADDR'] = "0.0.0.0";
        $_SERVER['SERVER_NAME'] = "none";

        $st = 0;
        while($this->lpx)
        {
            $this->send_notifs($st++);
        }

        #/ restore obfuscated server variables
        $_SERVER['PHP_SELF'] = $phpself;
        $_SERVER['REMOTE_ADDR'] = $phpremoteaddr;
        $_SERVER['SERVER_NAME'] = $phpservername;

        #/ Close SMTP
        if(in_array($_SERVER['SERVER_NAME'], array('proj-local', 'localhost'))==false){ //SERVER
        $this->mailx->smtpClose();
        }

        return $this->mailer_err;

    }//end func...


    function send_notifs($st)
    {
        $pack_id = $this->pack_id;
        $object = $this->object;
        $POST = $this->POST;

        $limit = $this->per_loop;
        $lp_from = $st*$limit;


        #/ Get Users
        $sql_u = "SELECT id as user_id, email_add, (CASE
        WHEN identify_by='screen_name' THEN screen_name
        WHEN identify_by='full_name' THEN CONCAT(first_name, ' ', middle_name, ' ', last_name)
        WHEN identify_by='company_name' THEN company_name
        ELSE 'Member'
        END) AS user_ident
        FROM users WHERE 1=1 ";

        if($pack_id){
        $sql_u.="AND package_id='{$pack_id}' ";
        }

        if ($this->fixed_users == true)
        {
            $user_ids = @explode(',', $POST['user_ids']);
            $user_ids_csv = @implode("','", $user_ids);
            $sql_u.="AND id IN ('{$user_ids_csv}') ";

            $sql_u.="ORDER BY id DESC ";
            $this->lpx = false;
        }
        else
        {
            $sql_u.="AND is_blocked='0' ";
            $sql_u.="LIMIT {$lp_from}, {$limit}";
        }


        $recipients = @format_str(@mysql_exec($sql_u));
        //var_dump_p($sql_u, $recipients); die();

        if(!is_array($recipients) || count($recipients)<=0)
        {
            $this->lpx = false;
            return false;
        }
        else
        {
            foreach($recipients as $rv)
            {
                #/ Setup Notification Msg
                @$rv['user_ident'] = str_replace('  ', ' ', @$rv['user_ident']);
                $to_user_intend = (empty($rv['user_ident'])) ? "":"<b>".(@$rv['user_ident'])."</b>";

                $notification = str_ireplace(
                array('{USER2}', '{OBJECT}', '{OBJECT2}'),
                array($to_user_intend, "<b>{$object}</b>", "<b>{$object}</b>"),
                $this->notif_template['notification']);

                $this->mailx->Subject = cut_str(strip_tags(reverse_format($notification)), 300);
                $this->mailx->addAddress($rv['email_add']);
                //$this->mailx->addBCC($rv['email_add']); //not using as the message is not always the same for each member


                #/ Setup Body
                $body_in = notification_email('', $rv, $notification, $this->created_on, @$POST['visit_url']); //load the template
                $body = send_mail('', '', $this->heading, $body_in, '', '', true);
                $this->mailx->msgHTML($body);
                //var_dump_p($notification, $this->notif_template, $object, $this->mailx); die();


                #/ Send & Test Errors
                if(!$this->mailx->send())
                {
                    $mailer_err = "Mailer Error (".str_replace("@", "&#64;", $rv['email_add']).') '.$this->mailx->ErrorInfo.'<br />';
                    //var_dump_p($mailer_err); die();
                }

                #/ Clear all addresses and attachments for next loop
                $this->mailx->clearAddresses();
                $this->mailx->clearAttachments();

                $this->mailer_err.= $mailer_err;

            }//end foreach..

        }//end else...

    }//end func...
}
?>