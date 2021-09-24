<?php
/**
* MySQLi Execution extension
* collboration with Shared Memory

* @author Raheel Hasan
* @version 5.0 (updated to use mysqli, please setup a connection into $mysqli var)

* @param $query = SQL Query
* @param $type = [single{=multiple columns of a single record}, multi{=multiple records}, save{=insert/delete/update}], default=multi
* @param $mem_key = Key for Shared memory
* @param $refresh_cache (default=false) = refresh cache

* @return array
*/

$db_conn = $mysqli;
function mysqli_exec($query, $type='', $mem_key='', $refresh_cache=false)
{
    global $mysqli;

    $data = $mem_val = '';
    $cached=false;

    $db_conn = $mysqli;
    if(defined('DB_CONN_PERSISTENT')){
    global $mysqliP;
    $db_conn = $mysqliP;
    }

    ##/ Cache Management
    if(!empty($mem_key))
    {
        if(!function_exists('smem_fetch')){
        @include_once('../includes/shared_mem_model.php');}
        $cached = is_smem_enabled();
    }
    //var_dump($cached); die();

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
    $result = $db_conn->query($query);
	if($result!=false)
	{
        if($type=='single')
		{
			$data = $result->fetch_array(MYSQLI_ASSOC);
		}
        else if($type=='save')
		{
			$data = $result;
		}
		else
		{
            $data = array();
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $data[] = $row;
            }
		}
	}
    else{$data = false;}
    //var_dump("<pre>", $query, $data, $db_conn->error); exit;
    #-

    #/ Insert/Update Cache
    if(($cached==true) && empty($mem_val) && ($type!='save')){
    smem_save($mem_key, $data); //set
    }

    return $data;
}///////////end function ......


///////////////////////////////////////////////////////////////////


/**
 * must call close_pconnect() after use is done
*/
function get_pconnect()
{
    $mysqliP = new mysqli('p:'.DB['HOST'], DB['USER'], DB['PASS'], DB['NAME']);
    return $mysqliP;
}

function close_pconnect()
{
    global $mysqliP;
    $mysqliP->close();
}
?>