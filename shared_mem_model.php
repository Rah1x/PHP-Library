<?php
/**
 * SHARED MEMORY Module (via REDIS)
 *
 * @author Raheel Hasan
 * @version 2.0
 *
 * Please create a global object $redis
 *
 * @example
 * [Simple Usage]
 * include_once('includes/shared_mem_func.php'); //include
 * $abc = @smem_fetch('abc'); //get
 *
 * if(empty($abc)){ //check
 * $abc = "some processing & data generation = array, string, or anything to save";
 * smem_save('abc', $abc); //set
 * }
 *
 * Additionally, use of the following when including this file to ensure server is connected
 * #// Redis Cache Server
   try{
   $redis = new Redis();
   $rc = @$redis->connect('127.0.0.1', 6379, 2);
   } catch (Exception $e){
   $redis = $rc = false;
   }
   if(!$rc || $redis->ping()!='+PONG') {
   $redis = false;
   }
 *
*/


/**
 * Check and return if shared memory extension is enabled
*/
function is_smem_enabled()
{
    global $redis;

    if($redis){ // && $redis->ping()=='+PONG'
    return true;
    } else {
    return false;
    }

}//end func...............



/**
 * return value saved in Shared mem
 * $mem_key = Key with which data is saved in DB
 * $type = [string / array]
 * $debug = set to true to not return value from memory. it will allow you to reset as well
 */

function smem_fetch($mem_key, $type='array', $debug=false)
{
    if($debug!=false)
    return '';

    global $redis;
    $ret_var = '';

    if($type=='array')
    $ret_var = @$redis->hgetall($mem_key);
    else
    $ret_var = @$redis->get($mem_key);

    if(!empty($ret_var) && is_array($ret_var)){
    //var_dumpx('2', $ret_var);
    $ret_var = array_map('unserialize', $ret_var); // unserialize for Redis
    }

    return $ret_var;

}//end func...............


/**
 * Save Data into the Shared Memory (without DB fetching & non-pregressively)
 * $mem_key = Key with which data is to be saved in DB
 * $mem_val = data to save (array, string etc)
 * $type = [string / array]
 *
*/
function smem_save($mem_key, $mem_val, $type='array')
{
    if(empty($mem_val))
    return false;

    global $redis;
    $ret = false;

    //# serialize for Redis
    if(is_array($mem_val)){
    $mem_val = array_map('serialize', $mem_val);
    //$mem_val = array_map('unserialize', $mem_val);//debug
    //var_dumpx('1', $mem_val);
    }

    if($type=='array')
    $ret = @$redis->hmset($mem_key, $mem_val);
    else
    $ret = @$redis->set($mem_key, $mem_val);

    return $ret;

}//end func...


/**
 * Remove saved Data from the Shared Memory
 * $mem_key = Key with which data is to be saved in DB
 *
*/
function smem_remove($mem_key)
{
    global $redis;
    $ret = false;

    if(!empty($mem_key)){
    $ret = (bool)@$redis->delete($mem_key);
    }

    return $ret;

}//end func...


////////////////////////////////////////////////////////////////////////////////////////////////////////

/** //TODO = complete + Test
 *
 * Build progressively Array Data into the Shared Memory
 * i.e. Find info from DB for the New List AND append this to Current List
 *
 * @return the updated Data Array
 *
 * @param
 * $new_list_ar = keys to Fetch
 * $save_as = SMM main memory key
 * $cb89_field = Group By field for the cb89 func
 * $sql_1x = the SQL query. It must have placeholder ##LIST_OF_FV##, this will be replaced by the Keys to find in DB
 * $reset = force a reset of the cache
 *
 * @author Raheel
 * @version 2.0 [Sept 2016]
*/
function sm_var($new_list_ar, $save_as, $cb89_field, $sql_1x, $reset = false)
{
    $return_ar = array();

    if(!empty($new_list_ar))
    {
        $new_list_ar_keys = ($new_list_ar); //new list
        $temp_ar_1 = $new_list_ar_keys;

        $ret_var = array();
        if($reset==false){
        $ret_var = @smem_fetch($save_as); //current list
        }

        if(!@empty($ret_var))
        {
            $ret_var_keys = @array_keys($ret_var);
            $temp_ar_1 = @array_diff($new_list_ar_keys, $ret_var_keys); //remove all that have previously been fetched
        }
        //var_dump_p($ret_var, $new_list_ar_keys, $temp_ar_1); exit;

        #/ get list of All
        if(!empty($temp_ar_1))
        {
            $list_of_fv = "'".implode('\',\'', $temp_ar_1)."'";

            $sql_1x = str_replace('##LIST_OF_FV##', $list_of_fv, $sql_1x); //the SQL must have ##LIST_OF_FV##
            $sql_res = mysqli_exec($sql_1x);
            //var_dump_p($sql_1x, $sql_res); exit;


            if(!empty($sql_res))
            {
                $sql_res_89 = cb79($sql_res, $cb89_field, false);
                $return_ar = (empty($ret_var))? $sql_res_89:array_merge($sql_res_89, $ret_var);
            }
            else
            {
                $return_ar = $ret_var;
            }

            if(($return_ar != $ret_var)){
            @smem_save($save_as, $return_ar);
            }
        }
        else
        {
            $return_ar = $ret_var;
        }
    }//end if...

    //$return_ar = @array_intersect_key($return_ar, $new_list_ar);
    //var_dump_p($return_ar); exit;

    return $return_ar;

}//end func...

//////////////////////////////////////////////////////////////////////////
?>