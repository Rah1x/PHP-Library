<?php
/**
 * SHARED MEMORY Module (via APC)
 * (memory used by all users & all processes)
 *
 * @author Raheel Hasan
 * @version 1.3
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
 * Additionally, make use of the following when including this file
 * if(extension_loaded('apc') && ini_get('apc.enabled')){}else{}
 *
*/

/**
 * Check and return if shared memory extension is enabled
*/
function is_smem_enabled()
{
    if(extension_loaded('apc') && ini_get('apc.enabled')){
    return true;
    } else {
    return false;
    }

}//end func...............



/**
 * return value saved in Shared mem
 * $mem_key = Key with which data is saved in DB
 * $debug = set to true to not return value from memory. it will allow you to reset as well
 */

function smem_fetch($mem_key, $debug=false)
{
    $apc_var = '';
    if($debug==false)
    $apc_var = apc_fetch($mem_key);

    return $apc_var;

}//end func...............

/**
 * Save Data into the Shared Memory (without DB fetching & non-pregressively)
 * $mem_key = Key with which data is to be saved in DB
 * $mem_val = data to save (array, string etc)
 *
*/
function smem_save($mem_key, $mem_val)
{
    $ret = false;

    if(!empty($mem_val)){
    $ret = @apc_store($mem_key, $mem_val);
    }

    return $ret;

}//end func...


/**
 * Remove saved Data from the Shared Memory
 * $mem_key = Key with which data is to be saved in DB
 *
*/
function smem_remove($mem_key)
{
    $ret = false;

    if(!empty($mem_key)){
    $ret = @apc_delete($mem_key);
    }

    return $ret;

}//end func...


////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
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
 * @version 1.4 [Jan 2016]
*/
function sm_var($new_list_ar, $save_as, $cb89_field, $sql_1x, $reset = false)
{
    //if(@empty($_SESSION['case_srch'][$save_as])){ //SESSION based Cache
    //$_SESSION['case_srch'][$save_as] = array();
    //}

    $return_ar = array();

    if(!empty($new_list_ar))
    {
        $new_list_ar_keys = ($new_list_ar); //new list
        $temp_ar_1 = $new_list_ar_keys;

        $apc_var = array();
        if($reset==false){
        //$apc_var = @$_SESSION['case_srch'][$save_as];
        $apc_var = @apc_fetch($save_as); //current list
        }

        if(!@empty($apc_var))
        {
            $apc_var_keys = @array_keys($apc_var);
            $temp_ar_1 = @array_diff($new_list_ar_keys, $apc_var_keys); //remove all that have previously been fetched
        }
        //var_dump_p($apc_var, $new_list_ar_keys, $temp_ar_1); exit;

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
                $return_ar = (empty($apc_var))? $sql_res_89:array_merge($sql_res_89, $apc_var);
            }
            else
            {
                $return_ar = $apc_var;
            }

            if(($return_ar != $apc_var)){
            //$_SESSION['case_srch'][$save_as] = $return_ar;
            @apc_store($save_as, $return_ar);
            }
        }
        else
        {
            $return_ar = $apc_var;
        }
    }//end if...

    //$return_ar = @array_intersect_key($return_ar, $new_list_ar);
    //var_dump_p($return_ar); exit;

    return $return_ar;

}//end func...

//////////////////////////////////////////////////////////////////////////
?>