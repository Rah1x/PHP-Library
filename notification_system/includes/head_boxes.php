<link rel="stylesheet" href="<?=DOC_ROOT?>assets/css/post_login.css" type="text/css" />

<div class="head_boxes">

<div class="searchhead">
    <?php /*<div class="seartxt">Search</div>
    <div class="searinpt"><input name="" type="text" /></div>
    <div class="seargo"><a href="#">Go</a></div>*/ ?>

    <form name="search_form" id="search_form" action="<?=DOC_ROOT?>ecosystem/search" method="post">
        <div class="seartxt">Search</div>
        <div class="searinpt"><input name="sk" type="text" /></div>
        <div class="seargo"><a href="javascript:search_form.submit();">Go</a></div>
    </form>
</div>

<?php /*<div class="usericons"></div>*/ ?>


<?php
if(function_exists('com_temp')!=true){
include_once("includes/compose_msg.php");
}
echo com_temp();
?>

<div id="nav">
<ul>
    <!-- Messages -->
    <li>
        <?php
        #/*
        if(function_exists('time_elapsed_string')!=true){
        include_once('../includes/func_time.php');
        }

        #/ Get list of messages to display
        if(function_exists('count_msgs')!=true){
        include_once('../includes/model_messages.php');
        }

        $my_msgs = @get_list($user_idc, '', '', 8, true, true);
        $my_msgs_list = @$my_msgs[0];
        $my_msg_users_info = @$my_msgs[1];

        $msgs_count = (int)@count_msgs($user_idc);
        //var_dump("<pre>", $msgs_count, $my_msgs); die();
        ?>

        <?php /*
        <script>
        $(document).ready(function(){
            $.msgs_count = parseInt(<?=$msgs_count?>);
        });
        </script>
        */ ?>

        <div class="iconsusrnav">
            <a href="" class="prevent_def">
            <img src="<?=DOC_ROOT?>ecosystem/assets/images/message.jpg" /></a>
            <?php if($msgs_count>0){ ?>
            <span class="posabt" id="msgs_count" style="<?php if($msgs_count>10){ ?>margin-left:-5px;<?php } ?>"><?=$msgs_count?></span>
            <?php } ?>
        </div>

        <ul class="marginulin marginuliniph pad_1 nav_1">
            <li class="title width_390"><h2>Messages Inbox ::
            <a href="#compose_message" class="prevent_def compose_msg">/ Compose New</a>
            </h2></li>

            <?php
            if(is_array($my_msgs_list) && count($my_msgs_list)>0){
            foreach($my_msgs_list as $my_msgs_v)
            {
                #/ Get members data
                $members = $my_msgs_v['members'];
                $members_ar = @explode(',', $members);
                if(empty($members)){continue;}

                #/ Reorder - to show from_user at the top
                array_unshift($members_ar, $my_msgs_v['from_user_id']);
                $members_ar = array_unique($members_ar);


                #/ Determine is is read
                $is_read = '';
                if($my_msgs_v['is_read']=='0'){
                $is_read = 'unread';
                }

                #/ Set display Pic
                $img_v = '';
                if(!empty($my_msgs_v['from_user_id']) && isset($my_msg_users_info[$my_msgs_v['from_user_id']])){
                $img_vp = $my_msg_users_info[$my_msgs_v['from_user_id']]['user_profile_pic'];
                $img_vp = @substr_replace($img_vp, '_th.', @strrpos($img_vp, '.'), 1);
                $img_v = DOC_ROOT."user_files/prof/{$my_msgs_v['from_user_id']}/{$img_vp}";
                }


                //$target = DOC_ROOT."message/{$my_msgs_v['thread_id']}";
                $notif_dtx = @time_elapsed_string(@strtotime($my_msgs_v['last_message'])).' -';


                ##/ Display msgs
                echo "<li id=\"msgT_{$my_msgs_v['thread_id']}\" class=\"brdrnonebot width_390 hand {$is_read}\"
                onclick=\"$.visit_msgT('{$my_msgs_v['thread_id']}');\">";


                    #/ Pic
                    if(!empty($img_v))
                    echo "<div class=\"notsmall\"><img src=\"{$img_v}\" /></div>";


                    echo "<div class=\"notsmallp msgx_par\">";

                        #/ Profile Names
                        $m_name = array(); $nm_i = 0;
                        foreach($members_ar as $mav)
                        {
                            $mx1 = @$my_msg_users_info[$mav];
                            if(empty($mx1)){continue;}

                            $nm_i++;

                            if($nm_i<8)
                            $m_name[] = "<b>{$mx1['first_name']}</b>";
                            else{
                            $m_name[] = "<b>and more..</b>";
                            break;
                            }
                        }
                        echo implode(', ', $m_name).'<br />';


                        #/ Message blur
                        if(array_key_exists('msg', $my_msgs_v)) {
                        $msge = cut_str(@strip_tags($my_msgs_v['msg']), 90);
                        echo "<div class=\"msgx\">{$msge}</div>";
                        }

                    echo "</div>";


                    #/ Timestamp
                    echo "<div class=\"notsmallp dtx msg_dtx\">{$notif_dtx}</div>";

                echo "</li>";
                #-

            }//end foreach..

            echo "<li class=\"width_390\" style=\"background: #f4f6f8; padding:0px 5px 0 10px;\">";
            echo "<a href=\"".DOC_ROOT."messages\" class=\"active\">View More ...</a>";
            echo "</li>";
            } else {
            echo "<li class=\"width_390\">";
            echo "<i>you have no recent messages ...</i>";
            echo "</li>";

            echo "<li class=\"width_390\" style=\"background: #f4f6f8; padding:0px 5px 0 10px;\">";
            echo "<a href=\"".DOC_ROOT."messages\" class=\"active\">See all ...</a>";
            echo "</li>";
            }
            ?>
        </ul>
        <?php #*/ ?>
    </li>



    <!-- Notifications -->
    <li>
        <?php
        #/ Get list of notifications to display
        if(function_exists('generate_notification')!=true){
        include_once('../includes/notif_func.php');
        }
        $notifs_count = (int)@count_notification($user_idc);

        //$notifs = @read_notification($user_idc); //old method not using due to inefficiency
        $notifs = @get_notifications($user_idc, '', '', 8, true);
        $notifs_l = @$notifs[0];
        $notifs_ui = @$notifs[1];
        //var_Dump("<pre>", $notifs); die();
        ?>

        <script>
        $(document).ready(function(){
            $.notifs_count = parseInt(<?=$notifs_count?>);
        });
        </script>

        <div class="iconsusrnav">
            <a href="" class="prevent_def">
                <img src="<?=DOC_ROOT?>ecosystem/assets/images/ghanta.jpg" />
            </a>
            <?php if($notifs_count>0){ ?>
            <span class="posabt notif_count" id="notif_count" style="margin-left:-10px;"><?=$notifs_count?></span>
            <?php } ?>
        </div>

        <ul class="marginulin marginuliniph pad_1 nav_2">
            <li class="title width_390"><h2>Notifications ::</h2></li>

            <?php
            if(is_array($notifs_l) && count($notifs_l)>0){
            foreach($notifs_l as $notif_v)
            {
                if(empty($notif_v['notification'])){continue;}

                $notif_data = @$notif_v['notif_data'];
                $notif_data2 = @$notif_v['notification'];

                if(empty($notif_data) || empty($notif_data2)){continue;}
                //if(!is_array($notif_data2) || !array_key_exists('notification', $notif_data2)){continue;} //old method
                //var_dump("<pre>", $notif_data2); die();

                #/ Set Vars
                $is_read = 'read';
                if($notif_data['is_read']=='0'){
                $is_read = 'unread';
                }

                $target = '';
                if(strlen($notif_data['visit_url'])>3){
                $target = DOC_ROOT.$notif_data['visit_url'];
                }


                $from_user_i = @$notifs_ui[$notif_data['from_user_id']];

                $img_v = '';
                if(!empty($notif_data['from_user_id']) && !empty($from_user_i)){
                $img_vp = $from_user_i['profile_pic'];
                $img_vp = @substr_replace($img_vp, '_th.', @strrpos($img_vp, '.'), 1);
                $img_v = DOC_ROOT."user_files/prof/{$notif_data['from_user_id']}/{$img_vp}";
                }

                $notif_dtx = '';
                if($notif_data['template_id']!='5'){ //hide time for certain templates
                $notif_dtx = @time_elapsed_string(@strtotime($notif_data['created_on'])).' -';
                }


                #/ Display notif
                echo "<li id=\"notif_{$notif_data['id']}\" class=\"brdrnonebot width_390 hand {$is_read}\"
                onclick=\"$.process_notifi('{$notif_data['id']}');\" target=\"{$target}\">";

                if(!empty($img_v))
                echo "<div class=\"notsmall\"><img src=\"{$img_v}\" /></div>";

                echo "<div class=\"notsmallp\">{$notif_data2}</div>";
                echo "<div class=\"notsmallp dtx\">{$notif_dtx}</div>";
                echo "</li>";
            }

            echo "<li class=\"width_390\" style=\"background: #f4f6f8; padding:0px 5px 0 10px;\">";
            echo "<a href=\"".DOC_ROOT."notifications\" class=\"active\">View More ...</a>";
            echo "</li>";
            } else {
            echo "<li class=\"width_390\">";
            echo "<i>you have no new notifications / none found ..</i>";
            echo "</li>";
            }
            ?>
        </ul>
    </li>


    <!-- Accounts -->
    <li>
        <div class="iconsusrnav">
            <a href="<?=DOC_ROOT?>member" class="prevent_def">
            <img src="<?=@$prf_pic_th?>" style="width:35px; height:35px;" class="round_borders" title="<?=@($user_info_c['first_name'].' '.$user_info_c['last_name'])?>" /></a>
        </div>

        <ul class="marginuliniph pad_2 width_300 nav_3">
            <?php #/* ?><li class="title"><h2>My Account ::</h2></li>
            <li class="brdrnonebot"><a href="<?=DOC_ROOT?>ecosystem/">My Eco-System</a></li>
            <li class="brdrnonebot"><a href="<?=DOC_ROOT?>connections">Communication Central</a></li>
            <li class="brdrnonebot"><a href="<?=DOC_ROOT?>patronage-points">My Patronage Points</a></li>
            <li class="brdrnonebot"><a href="<?=DOC_ROOT?>member">My Profile</a></li>
            <li class="brdrnonebot"><a href="<?=DOC_ROOT?>my-account">Account Info</a></li><?php #*/ ?>
            <li class="brdrnonebot"><a href="<?=DOC_ROOT?>update-password">Update Password</a></li><?php #*/ ?>
            <li><a href="<?=DOC_ROOT?>ecosystem/logout">Sign Out</a></li>
        </ul>
    </li>
</ul>
</div>
</div>

<script type="text/javascript" src="<?=DOC_ROOT?>assets/js/head_boxes.js"></script>