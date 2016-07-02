<?php
/**
 * Function used to archive table data into its "archive" table, matching against the key
 * Archive Table must have the 'original_id' field.
 *
 * [Params]
 * $table_nm = primary Table ({$table_nm}_archive will be the archive table)
 * $keys = csv of keys
 * $key_field = primary key of primary table to match against $keys
 * $save_ts = save time stamp
 * $return_id_list = return primary id from primary table
 * $unarchive = run the function to unarchive instead of archive
 *
 * @author Raheel Hasan
 * @version 1.0 (Nov 2015)
*/
function archive_me($table_nm, $keys, $key_field='id', $save_ts=false, $return_id_list=false, $unarchive=false)
{
    if(empty($table_nm)) return false;
    if(empty($keys)) return false;


    #/ Set Table Name
    $tbl_primary = "{$table_nm}"; $tbl_2nd = "{$table_nm}_archive";
    if($unarchive){
    $tbl_primary = "{$table_nm}_archive"; $tbl_2nd = "{$table_nm}";
    }

    $moved_ofr_flag = false;
    $return_ids = ''; $return_ids_ar = array();


    #/ Get original data and fields
    $sql_1 = sprintf("SELECT * FROM {$tbl_primary} WHERE {$key_field} IN (%s)", $keys);
    $p_data = @mysql_exec($sql_1);
    $fields_set = @array_diff(@array_keys($p_data[0]), array('id', 'original_id', 'archived_on'));
    //var_dump_p($sql_1, $fields_set); die();
    if(empty($fields_set) || count($fields_set)<=0) return false;

    if(is_array($p_data) && count($p_data)>0)
    {
        #/ Setup Fields to save
        $sql_ins = '';
        $sql_ins_a = array();
        $sql_ins = "INSERT INTO {$tbl_2nd} (";
        if($unarchive==false){$sql_ins_a[] = 'original_id';}else{$sql_ins_a[] = 'id';}
        foreach($fields_set as $vd_k)
        {
            $sql_ins_a[] = $vd_k;
        }
        if($save_ts)$sql_ins_a[]= "archived_on";
        if(!empty($sql_ins_a))$sql_ins.= implode(', ', $sql_ins_a);

        $sql_ins.= ") VALUES";
        $sql_ins.="
        ";
        //var_dump_p($sql_ins); die();


        #/ Setup Data to save
        $sql_in_ar = array();
        foreach($p_data as $vd_v)
        {
            $sql_in_ar2 = array();
            if($unarchive==false){$sql_in_ar2[]= "'{$vd_v['id']}'";}else{$sql_in_ar2[]= "'{$vd_v['original_id']}'";}
            foreach($fields_set as $vd_k)
            {
                if(array_key_exists($vd_k, $vd_v)){
                $sql_in_ar2[]= "'".mysql_real_escape_string("{$vd_v[$vd_k]}")."'";
                } else {
                $sql_in_ar2[]= "";
                }
            }
            if($save_ts)$sql_in_ar2[]= "NOW()";
            if(!empty($sql_in_ar2)){$sql_in_ar[]= '('.@implode(', ', $sql_in_ar2).')';
            }
            //var_dump_p($sql_in_ar); die();

            if($return_id_list){
            if($unarchive==false){$return_ids_ar[] = $vd_v['id'];}else{$return_ids_ar[] = $vd_v['original_id'];}
            }

        }//end foreach...


        #/ Move Data
        if(count($sql_in_ar)>0)
        {
            $sql_ins.=@implode(', ', $sql_in_ar);
            //var_dump_p($sql_ins); die();
            $moved_ofr_flag = @mysql_exec($sql_ins, 'save');

            #/ Delete primary table data
            if($moved_ofr_flag == true){
            $query = sprintf("DELETE FROM {$tbl_primary} WHERE {$key_field} IN (%s)", $keys);
            @mysql_query($query);
            }
        }

    }//end if....

    if(!empty($return_ids_ar) && $return_id_list){return implode(',',$return_ids_ar);}
    return $moved_ofr_flag;

}//end func....



/**
 * Wrapper Function used to unarchive table data from its "archivce" table, matching against the key
 * Archive Table must have the 'original_id' field.
 *
 * [Params]
 * $table_nm = primary Table ({$table_nm}_archive will be the archive table)
 * $keys = csv of keys
 * $save_ts = save time stamp
 *
 * [IMP] -  this function is for general use only, it doesnot ensure parent Table (like Parent Brand) is NOT Archived before unarchiving these records.
 *
 * @author Raheel Hasan
 * @version 1.0
*/
function unarchive_me($table_nm, $keys, $key_field='id', $return_id_list=false)
{
    return archive_me($table_nm, $keys, $key_field, false, $return_id_list, true);
}
?>