<?php
/** define in Config */
define('ADMIN_SESSION_TIMEOUT', 7200);


//////////////////////////////////////////
/** function = validate session */
function validate_session()
{
    if( isset($_SESSION['ADMIN_SESSION_START_TIME']) )
    {
        $session_life = time() - $_SESSION['ADMIN_SESSION_START_TIME'];
    	if( $session_life > ADMIN_SESSION_TIMEOUT)
        {
    		logout();
    	}
    }

    $_SESSION['ADMIN_SESSION_START_TIME'] = time();

}//end func....
//////////////////////////////////////////


/** put in header */
validate_session();
echo '<meta http-equiv="refresh" content="'.(ADMIN_SESSION_TIMEOUT+5).'" />';
?>