<?php
/** Function generate_notification
  * Purpose: Create and Save Notifications in DB
  * [params]
  * $notif_data = array containing keys (template_id, user_id, from_user_id, objects, object_id, object_location, visit_url, notif_details)
  * all fields within the param are compulsory/required.
*/
function generate_notification($notif_data, $send_email=false)
{
    if(!is_array($notif_data) || count($notif_data)<=0){return false;}
    if(!array_key_exists('user_id', $notif_data)){return false;}


    #/ Identify User & Type
    $type = 'direct';
    $process_user_id = @$notif_data['user_id'];
    if(strstr($notif_data['user_id'], ',') || strstr($notif_data['user_id'], '*') || strstr($notif_data['user_id'], 'P_')){
    $type = 'group';
    $send_email = false;
    }


    #/ Check Receiver's Notification settings
    if($type == 'direct'){
    $user_notif_settings = get_notif_perm($process_user_id, $notif_data['template_id']);
    if($user_notif_settings=='stop'){return false;}
    }


    $created_on = date('Y-m-d H:i:s');
    #/ Save in DB & Generate Notification
    $sql_notif = "INSERT INTO user_notifications
    (template_id, user_ids, from_user_id, objects, object_id, object_location, visit_url, created_on)
    VALUES ('{$notif_data['template_id']}', '{$notif_data['user_id']}', '{$notif_data['from_user_id']}', '{$notif_data['objects']}', '{$notif_data['object_id']}', '{$notif_data['object_location']}', '{$notif_data['visit_url']}', '{$created_on}')";
    @mysql_exec($sql_notif, 'save');


    if($send_email!=false)
    {

        #/ Get Notification & its Participant data
        $notification_data = get_notification_msg($notif_data); //, $send_email

        if(empty($notification_data) || !is_array($notification_data) || count($notification_data)<2){return false;}
        if(!array_key_exists('users_info', $notification_data) || !array_key_exists('notification', $notification_data)){return false;}
        //var_dump("<pre>", $notification_data); die();


        ##/ Send Email if Allowed
        if(function_exists('send_mail')!=true){
        include_once('../includes/send_mail.php');
        }

        $from_usr = (empty($notif_data['from_user_id']))? '':@$notification_data['users_info'][$notif_data['from_user_id']];
        $to_usr = @$notification_data['users_info'][$process_user_id];
        if(!is_array($to_usr) || count($to_usr)<=0){return false;}
        //var_dump($from_usr, $to_usr); die();

        if(function_exists('notification_email')!=true){
        include_once('../includes/email_templates.php');
        }
        $subject = strip_tags($notification_data['notification']);
        $heading = "New Notification from CollaborateUSA&#46;com";
        $body_in = notification_email($from_usr, $to_usr, $notification_data['notification'], $created_on, @$notif_data['visit_url'], @$notif_data['notif_details']);
        send_mail($to_usr['email_add'], $subject, $heading, $body_in);
        #-

    }//end if email...

}//end func...


/**
 * get specific Notification from Templates
*/
function get_notif_template($template_id, $get_all=false)
{
    $sql_ae = "SELECT * FROM notification_templates";
    $notif_templates = @format_str(@mysql_exec($sql_ae, '', 'notification_templates'));
    $notif_templates = @cb89($notif_templates, 'id');

    if($get_all==false)
    {
        //$sql_ae = "SELECT * FROM notification_templates WHERE id='{$template_id}'";
        //$notif_template = @format_str(@mysql_exec($sql_ae, 'single'));
        $notif_template = @$notif_templates[$template_id];
        //var_dump("<pre>", $notif_template); die();
        return $notif_template;
    }
    else
    {
        //var_dump("<pre>", $notif_templates); die();
        return $notif_templates;
    }

}//end func..


/**
 * get Notification Permission for a specific user
 * [params]
 * $user_id
 * $template_id (def=0) = provide template_id to check permission against it.
*/
function get_notif_perm($user_id, $template_id=0)
{
    $sql_not_set = "SELECT noti_templates_disallow FROM user_notif_settings WHERE user_id='{$user_id}'";
    $user_notif_settings = @mysql_exec($sql_not_set, 'single');
    //var_dump($sql_not_set, $user_notif_settings); die('x');

    if($template_id==0)
    return $user_notif_settings;

    #/ Test/Check permission for a specific Template
    if(isset($user_notif_settings['noti_templates_disallow']) && !empty($user_notif_settings['noti_templates_disallow']))
    {
        $user_noti_disallow_ar = @explode(',', $user_notif_settings['noti_templates_disallow']);
        if(in_array($template_id, $user_noti_disallow_ar)){
        return 'stop';
        }
    }

    return $user_notif_settings;

}//end func..


function get_notification_msg($notif_data, $send_email=false, $get_user_info=true)
{
    $user_id = $notif_data['user_id'];
    $from_user_id = $notif_data['from_user_id'];
    $template_id = $notif_data['template_id'];
    $objects = $notif_data['objects'];

    #/ Get user's info
    $users_info_sql = "SELECT id as user_id, email_add, profile_pic, (CASE
    WHEN identify_by='screen_name' THEN screen_name
    WHEN identify_by='full_name' THEN CONCAT(first_name, ' ', middle_name, ' ', last_name)
    WHEN identify_by='company_name' THEN company_name
    ELSE 'Member'
    END) AS user_ident
    FROM users WHERE id IN ('{$user_id}', '{$from_user_id}')";

    $users_info = @format_str(@mysql_exec($users_info_sql));
    $users_info = @cb89($users_info, 'user_id');
    //var_dump("<pre>", $users_info, $notif_data); die();

    if(empty($users_info) || !is_array($users_info)){return false;}
    if(($from_user_id<=0 && count($users_info)<1) || ($from_user_id>0 && count($users_info)<2)){return false;}


    #/ get Notification from Templates
    $notif_template = get_notif_template($template_id);
    if(empty($notif_template) || count($notif_template)<=0){return false;}


    ##/ generate notification string
    $objects_ar = array();
    if(stristr($objects, ':')!=false)
    {
        $objects_ar = @explode(':', $objects);
    }
    else
    {
        $objects_ar[0] = $objects;
        $objects_ar[1] = $objects;
    }

    @$users_info[$from_user_id]['user_ident'] = str_replace('  ', ' ', @$users_info[$from_user_id]['user_ident']);
    @$users_info[$user_id]['user_ident'] = str_replace('  ', ' ', @$users_info[$user_id]['user_ident']);

    $from_user_intend = (empty($users_info[$from_user_id]['user_ident'])) ? "":"<b>".(@$users_info[$from_user_id]['user_ident'])."</b>";
    $to_user_intend = (empty($users_info[$user_id]['user_ident'])) ? "":"<b>".(@$users_info[$user_id]['user_ident'])."</b>";

    $notification = str_ireplace(
    array('{USER}', '{USER2}', '{OBJECT}', '{OBJECT2}'),
    array($from_user_intend, $to_user_intend,  "<b>{$objects_ar[0]}</b>", "<b>{$objects_ar[1]}</b>"),
    $notif_template['notification']);
    #-


    #/ Return data
    if($get_user_info)
    return array('users_info'=>$users_info, 'notification'=>$notification); //'notif_details'=>$notif_details
    else
    return $notification;

}//end func..


function get_notification_msg_all($notif_data_ar, $notif_template, $users_info)
{
    if(empty($notif_template) || !is_array($notif_template) || count($notif_template)<=0){return false;}
    if(empty($notif_data_ar) || !is_array($notif_data_ar) || count($notif_data_ar)<=0){return false;}
    if(empty($users_info) || !is_array($users_info) || count($users_info)<=0){return false;}

    $return_ar = array();
    foreach($notif_data_ar as $notif_data)
    {
        $user_id = $notif_data['user_id'];
        $from_user_id = $notif_data['from_user_id'];
        $template_id = $notif_data['template_id'];
        $objects = $notif_data['objects'];

        //if(($from_user_id<=0 && count($users_info)<1) || ($from_user_id>0 && count($users_info)<2)){continue;}


        #/ Get Object(s)
        $objects_ar = array();
        if((strstr($objects, ':')!=false) && ($template_id!=28))
        {
            $objects_ar = @explode(':', $objects);
        }
        else
        {
            $objects_ar[0] = $objects;
            $objects_ar[1] = $objects;
        }
        //var_dump_p($objects_ar); die();


        ##/ generate notification string
        @$users_info[$from_user_id]['user_ident'] = @str_replace('  ', ' ', @$users_info[$from_user_id]['user_ident']);
        @$users_info[$user_id]['user_ident'] = @str_replace('  ', ' ', @$users_info[$user_id]['user_ident']);

        $from_user_intend = (empty($users_info[$from_user_id]['user_ident'])) ? "":"<a href='".DOC_ROOT."member/{$from_user_id}' class='noti_lk'><b>".(@$users_info[$from_user_id]['user_ident'])."</b></a>";
        $to_user_intend = (empty($users_info[$user_id]['user_ident'])) ? "":"<a href='".DOC_ROOT."member/{$user_id}' class='noti_lk'><b>".(@$users_info[$user_id]['user_ident'])."</b></a>";

        if($from_user_id>0 && empty($from_user_intend)){$from_user_intend = 'Someone';}

        $notification = @str_ireplace(
        array('{USER}', '{USER2}', '{OBJECT}', '{OBJECT2}'),
        array($from_user_intend, $to_user_intend, (($template_id==28)?"{$objects_ar[0]}":"<b>{$objects_ar[0]}</b>"), "<b>{$objects_ar[1]}</b>"),
        $notif_template[$template_id]['notification']);
        //var_dump_p($notification, $objects_ar[0]); die();
        #-

        #/ Replace and create links:
        if(($template_id==28) && (stristr($notification, 'http:') || stristr($notification, 'https:'))){
        $notification = preg_replace('/(http[s]{0,1}\:\/\/\S{4,})\s{0,}/ims', '<a href="$1" target="_blank" class="inln">$1</a> ', $notification);
        $notification = preg_replace('/\.{1,}\" target\=\"/ims', '" target="', $notification);
        }
        //var_dump_p($notification); die();

        #/ Build Return data
        $return_ar[] = array('notif_data'=>$notif_data, 'notification'=>$notification);

    }//end func...

    return $return_ar;

}//end func..


function get_notif_users_info($users)
{
    if(empty($users) || count($users)<=0){return false;}

    $users_str = implode(',', $users);

    #/ Get user's info
    $users_info_sql = "SELECT id as user_id, email_add, profile_pic, (CASE
    WHEN identify_by='screen_name' THEN screen_name
    WHEN identify_by='full_name' THEN CONCAT(first_name, ' ', middle_name, ' ', last_name)
    WHEN identify_by='company_name' THEN company_name
    ELSE 'Member'
    END) AS user_ident
    FROM users WHERE id IN ($users_str)";

    $users_info = @format_str(@mysql_exec($users_info_sql));
    $users_info = @cb89($users_info, 'user_id');
    //var_dump("<pre>", $users_info); die();

    return $users_info;

}//end func....


/**
 * Read Notifications
 * [params]
 * $user_id = pull notifications of this user
 * $notification_id = read specific notification only
 * $limits = limit results (def=8)
*/
function read_notification($user_id, $user_info, $notification_id='0', $limits=8)
{
    if($user_id<=0){return false;}
    $user_pack_id = (int)@$user_info['package_id'];

    $sql_user_pi = '';
    if($user_pack_id>0){$sql_user_pi.= " OR un.user_ids='P_{$user_pack_id}'";}

    #/*
    $sql_notif = "SELECT un.*,
    IF(ISNULL(unr.id), 0, 1) AS is_readx

    FROM user_notifications un
    LEFT JOIN user_notif_read unr ON unr.user_notif_id=un.id AND unr.user_id='{$user_id}'

    WHERE (FIND_IN_SET('{$user_id}', un.user_ids) OR un.user_ids='*' {$sql_user_pi})
    AND (mark_del='0' OR ISNULL(mark_del))";

    if($notification_id>0){$sql_notif.= " AND un.id='{$notification_id}'";}
    $sql_notif.= " ORDER BY un.created_on DESC";
    if($limits>0){$sql_notif.= " LIMIT {$limits}";}

    $notif_res = @format_str(@mysql_exec($sql_notif));
    //var_dump("<pre>", $sql_notif, $notif_res);
    if(!is_array($notif_res) || count($notif_res)<0){return false;}


    foreach($notif_res as $notif_v)
    {
        if(!is_array($notif_v) || !array_key_exists('template_id', $notif_v)){continue;}

        $type = 'd';
        if(strstr($notif_v['user_ids'], ',') || strstr($notif_v['user_ids'], '*') || strstr($notif_v['user_ids'], 'P_')){
        $type = 'g';
        }

        $notif_data = array(
        'id' => "{$notif_v['id']}",
        'template_id' => "{$notif_v['template_id']}",
        'user_id' => $user_id, //receiver
        'from_user_id' => "{$notif_v['from_user_id']}",
        'objects' => "{$notif_v['objects']}",
        'object_id' => "{$notif_v['object_id']}",
        'object_location' => "{$notif_v['object_location']}",
        'visit_url' => "{$notif_v['visit_url']}",
        'is_read' => ($type=='g'?$notif_v['is_readx']:$notif_v['is_read']),
        'created_on' => "{$notif_v['created_on']}",
        'notif_type' => $type,
        );

        $notification = @get_notification_msg($notif_data, false, true);
        $notifis[] = array('notif_data'=>$notif_data, 'notification'=>$notification);

    }//end foreach...
    //var_dump("<pre>", $notifis);

    return $notifis;
    #*/

}//end func..


/**
 * Count Notifications
 * [params]
 * $user_id = count notifications of this user
 * $only_unread (def=true) = count only unread.
*/
function count_notification($user_id, $user_info, $only_unread=true)
{
    if($user_id<=0){return false;}
    $user_pack_id = (int)@$user_info['package_id'];
    $joined_on = @$user_info['joined_on'];

    $sql_part = '';
    if(!empty($joined_on)){
    $sql_part.= "AND un.created_on>='{$joined_on}'";
    }

    $notif_count = 0;
    if($only_unread!=true) //i.e. all notifs
    {
        $sql_notif = "SELECT count(*) as cx
        FROM user_notifications un
        WHERE (FIND_IN_SET('{$user_id}', un.user_ids) OR un.user_ids='*' OR un.user_ids='P_{$user_pack_id}')
        {$sql_part}";

        $notif_res = @mysql_exec($sql_notif, 'single');
        if(!is_array($notif_res) || count($notif_res)<0){return false;}

        $notif_count = (int)@$notif_res['cx'];
    }
    else
    {
        $sql_notif = "SELECT count(*) as cx,
        IF(ISNULL(unr.id), un.is_read, 1) AS is_readx

        FROM user_notifications un
        LEFT JOIN user_notif_read unr ON unr.user_notif_id=un.id AND unr.user_id='{$user_id}'

        WHERE (FIND_IN_SET('{$user_id}', un.user_ids) OR un.user_ids='*' OR un.user_ids='P_{$user_pack_id}')
        AND (mark_del='0' OR ISNULL(mark_del))
        {$sql_part}

        GROUP BY is_readx
        HAVING is_readx='0'";
        //var_dump_p($sql_notif); die();

        $notif_res = @mysql_exec($sql_notif, 'single');
        if(!is_array($notif_res) || count($notif_res)<0){return false;}

        $notif_count = (int)@$notif_res['cx'];
    }

    return $notif_count;

}//end func..




/**
 * Function all notifications
 * [params]
 * $user_id = user
 * $seo_tag = for pageination links
 * $dName = display name for pagination = "Showing * out of * {$dName}"
 * $rows(=20) = pagination limits
 * $fixed(=false) = force to get without pagination, but only total rows as set via $rows
 * $template_id(=0) = get notifs for a particular template only
*/
function get_notifications($user_id, $user_info, $seo_tag, $dName, $rows=20, $fixed=false, $template_id=0)
{
    if($user_id<=0){return false;}
    $user_pack_id = (int)@$user_info['package_id'];
    $joined_on = @$user_info['joined_on'];


    $sql_part = '';
    if($template_id>0){
    $sql_part.= "AND template_id='{$template_id}'";
    }

    if(!empty($joined_on)){
    $sql_part.= "AND un.created_on>='{$joined_on}'";
    }


    #/ Get Notifs per page
    $sql_1 = "SELECT un.*,
    IF(ISNULL(unr.id), 0, 1) AS is_readx

    FROM user_notifications un
    LEFT JOIN user_notif_read unr ON unr.user_notif_id=un.id AND unr.user_id='{$user_id}'

    WHERE (FIND_IN_SET('{$user_id}', un.user_ids) OR un.user_ids='*' OR un.user_ids='P_{$user_pack_id}')
    AND (mark_del='0' OR ISNULL(mark_del))
    {$sql_part}

    ORDER BY created_on DESC
    ";

    if($fixed==false){
    include_once('../includes/page_it.php');
    $page_obj = new page_it($_GET, $sql_1, $rows, 1);
    $limits = $page_obj->query_limits(true);
    $sql_1.= $limits;
    } else {
    $sql_1.= " LIMIT 0, {$rows}";
    }

    $notif_res = @format_str(@mysql_exec($sql_1));
    //var_dump("<pre>", $sql_1, $notif_res); die();


    #/ get Templates
    $notif_template = get_notif_template('', true);


    #/ Setup Notifs Components
    $users = array(); $notif_data_ar = array();
    if(is_array($notif_res))
    foreach($notif_res as $notif_v)
    {
        if(!is_array($notif_v) || !array_key_exists('template_id', $notif_v)){continue;}

        $type = 'd';
        if(strstr($notif_v['user_ids'], ',') || strstr($notif_v['user_ids'], '*') || strstr($notif_v['user_ids'], 'P_')){
        $type = 'g';
        }

        $notif_v['user_id'] = $user_id; //(int)@$notif_v['user_id'];
        $notif_v['from_user_id'] = (int)@$notif_v['from_user_id'];

        if(!empty($notif_v['user_id']))
        $users[] = $notif_v['user_id'];

        if(!empty($notif_v['from_user_id']))
        $users[] = $notif_v['from_user_id'];


        $notif_data = array(
        'id' => "{$notif_v['id']}",
        'template_id' => "{$notif_v['template_id']}",
        'user_id' => "{$notif_v['user_id']}", //receiver
        'from_user_id' => "{$notif_v['from_user_id']}",
        'objects' => "{$notif_v['objects']}",
        'object_id' => "{$notif_v['object_id']}",
        'object_location' => "{$notif_v['object_location']}",
        'visit_url' => "{$notif_v['visit_url']}",
        'is_read' => ($type=='g'?$notif_v['is_readx']:$notif_v['is_read']),
        'created_on' => "{$notif_v['created_on']}",
        'notif_type' => $type,
        );

        $notif_data_ar[] = $notif_data;

    }//end foreach...


    #/ get Users Info
    $users = @array_unique($users);
    $users_info = get_notif_users_info($users);
    //var_dump("<pre>", $users, $users_info); die();


    #/ Convert Each Notif into Proper Message
    $notifis = @get_notification_msg_all($notif_data_ar, $notif_template, $users_info);
    //if(in_array($_SERVER['REMOTE_ADDR'], array('110.93.203.14', '39.48.53.168', '127.0.0.1', '::1'))!=false){
    //var_dump("<pre>", $notifis, $notif_data_ar, $notif_template, $users_info); die();
    //}



    #/ Return data
    if($fixed==false){
    $p_links = $page_obj->page_links('href', DOC_ROOT."{$seo_tag}/?page={PAGE_NO}-{TOTAL_RECS}", $dName);
    return array($notifis, $users_info, $p_links, $page_obj->tot);
    } else {
    return array($notifis, $users_info);
    }

}//end func...



/**
 * Delete Notifs(s)
 * [params]
 * $notif_ids = csv of Notif Ids
 * $user_id = loggedin user id
*/
function delete_notifs($notif_ids, $user_id)
{
    #/ Find Direct IDs
    $sql_0 = "SELECT GROUP_CONCAT(id) as d_ids FROM user_notifications WHERE user_ids='{$user_id}' AND id IN ({$notif_ids})";
    $res_0 = @mysql_exec($sql_0, 'single');
    $d_ids = @$res_0['d_ids'];
    //var_dump_p($notif_ids, $d_ids); die();

    #/ Delete Direct IDs
    $sql_1 = "DELETE FROM user_notifications WHERE user_ids='{$user_id}' AND id IN ({$d_ids})";
    @mysql_exec($sql_1, 'save');

    #/ Delete `user_notif_read` for Direct IDs
    //if(!empty($d_ids)){
    //$sql_1 = "DELETE FROM user_notif_read WHERE user_id='{$user_id}' AND user_notif_id IN ({$d_ids})";
    //@mysql_exec($sql_1, 'save');
    //}


    ##/ Process for Group Notifications
    $notif_ids_a = explode(',', $notif_ids);
    $d_ids_a = explode(',', $d_ids);
    $group_notif_ids = @array_diff($notif_ids_a, $d_ids_a);

    if(is_array($group_notif_ids) && count($group_notif_ids)>0)
    foreach($group_notif_ids as $notif_id)
    {
        $sql_1 = "INSERT INTO user_notif_read (user_notif_id, user_id, mark_del) VALUES('{$notif_id}', '{$user_id}', '1')
        ON DUPLICATE KEY UPDATE mark_del='1'";
        @mysql_exec($sql_1, 'save');
    }
    #-
}//end func...


/**
 * Mark as Read Notifs(s)
 * [params]
 * $notif_ids = csv of Notif Ids
 * $user_id = loggedin user id
*/
function mark_read_notifs($notif_ids, $user_id)
{
    #/ Find Direct IDs
    $sql_0 = "SELECT GROUP_CONCAT(id) as d_ids FROM user_notifications WHERE user_ids='{$user_id}' AND id IN ({$notif_ids})";
    $res_0 = @mysql_exec($sql_0, 'single');
    $d_ids = @$res_0['d_ids'];

    #/ Update Direct IDs
    $sql_1 = "UPDATE user_notifications SET is_read='1' WHERE user_ids='{$user_id}' AND id IN ({$d_ids})";
    @mysql_exec($sql_1, 'save');


    ##/ Process for Group Notifications
    $notif_ids_a = explode(',', $notif_ids);
    $d_ids_a = explode(',', $d_ids);
    $group_notif_ids = @array_diff($notif_ids_a, $d_ids_a);
    //var_dump_p($notif_ids_a, $d_ids_a, $group_notif_ids); die();

    if(is_array($group_notif_ids) && count($group_notif_ids)>0)
    foreach($group_notif_ids as $notif_id)
    {
        $sql_1 = "INSERT INTO user_notif_read (user_notif_id, user_id) VALUES('{$notif_id}', '{$user_id}')
        ON DUPLICATE KEY UPDATE user_id='{$user_id}'";
        @mysql_exec($sql_1, 'save');
    }
    #-
}//end func...
?>