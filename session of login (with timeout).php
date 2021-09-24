<?php
if(!isset($_SESSION["ldbadmin_usr_id"]) || empty($_SESSION["ldbadmin_usr_id"]))
{
    @header("Location: {$consts['DOC_ROOT_ADMIN']}login");
    echo "<script language=\"javascript\">location.href='{$consts['DOC_ROOT_ADMIN']}login';</script>";
	exit;
}
else
{
    $LAST_ACTIVITY = @$_SESSION['LAST_LDBAdmin_ACTIVITY'];

    #/ logout if last activity is over 30 minutes old
    if (time() - $LAST_ACTIVITY > 1800)
    {
        @header("Location: {$consts['DOC_ROOT_ADMIN']}logout");
        echo "<script language=\"javascript\">location.href='{$consts['DOC_ROOT_ADMIN']}logout';</script>";
    	exit;
    }

    $_SESSION['LAST_LDBAdmin_ACTIVITY'] = time();
    //var_dump("<pre>", $_SESSION); die();
}
?>