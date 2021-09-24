<?php
@ini_set('max_execution_time', '0');
include_once('../../includes/admin/global_notif.php');
$gn = new global_notif($template_id, $object, $_POST, $created_on);
$mailer_err = @$gn->process_notifs();
?>