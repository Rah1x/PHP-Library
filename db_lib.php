<?php
/**
* MySQL Execution extension
* collboration with Shared Memory

* @author Raheel Hasan
* @version 4.0 (handling pconnection for cron jobs)

* @param $query = SQL Query
* @param $type = [single{=multiple columns of a single record}, multi{=multiple records}, save{=insert/delete/update}], default=multi
* @param $mem_key = Key for Shared memory
* @param $refresh_cache (default=false) = refresh cache

* @return array
* Dependency = shared_mem_model.php
*/

$db_conn_res = DB_CONN_RES;
function mysql_exec($query, $type='', $mem_key='', $refresh_cache=false)
{
	$data = $mem_val = '';
    $cached=false;

    $db_conn_res = DB_CONN_RES;
    if(defined('DB_CONN_PERSISTENT'))
    $db_conn_res = DB_CONN_PERSISTENT;

    ##/ Cache Management
    if(!empty($mem_key))
    {
        if(!function_exists('smem_fetch')){
        @include_once('../includes/shared_mem_model.php');}
        $cached = is_smem_enabled(); //true or false based on smem is enabled or not
        //var_dump($cached); die();
    }

    if($cached==true)
    {
        if(($refresh_cache==false) && ($type!='save'))
        {
            $mem_val = @smem_fetch($mem_key); //get
            if(!empty($mem_val)){
            return $mem_val;
            }
        }
        else
        {
            smem_remove($mem_key); //delete
        }
       //var_dump($query, $mem_key); die();
    }
    #-


    ##/ Perform MYSQL Operations
    $result = mysql_query($query, $db_conn_res);
	if($result!=false)
	{
        if($type=='single')
		{
			$data = mysql_fetch_array($result, MYSQL_ASSOC);
		}
        else if($type=='save')
		{
			$data = $result;
		}
		else
		{
			$data = array();
            while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$data[] = $row;
		}
	}
    else{$data = false;}
    #-


    #/ Insert/Update Cache
    if(($cached==true) && empty($mem_val) && ($type!='save')){
    smem_save($mem_key, $data); //set
    }

    return $data;
}///////////end function ......


///////////////////////////////////////////////


/**
 * must call close_pconnect() after use is done
*/
function get_pconnect()
{
    $cn = @mysql_pconnect(DB_HOST, DB_USER, DB_PASS);
    $db = @mysql_select_db(DB_NAME_T1, $cn);

    define('DB_CONN_PERSISTENT', $cn);

}///////////end function ......


function close_pconnect()
{
    mysql_close(DB_CONN_PERSISTENT);

}///////////end function ......
?>