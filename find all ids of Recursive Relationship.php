<?php
/**
 * find all CHILD ids of Recursive Relationship -
 * (note, its better to use stored procs instead as in a large dataset this will be not so optomized solution)
 * @param $f1 = top root / parent id
*/
function get_inners($f1, $table_name)
{
    $frm  = '';

    $sql = "select forum_id from {$table_name} where parent_id='{$f1}'";
    $res = mysql_exec($sql);

    foreach($res as $k=>$v)
    {
        $frm .= "{$v['forum_id']},";

        $t1 = get_inners($v['forum_id'], $table_name);

        if(!empty($t1))
        $frm .= $t1;

    }//end foreach....

    return $frm;
}///end func........


$frm = get_inners('29', 'my_table_name');
$sub_in = substr($frm, strlen($frm)-1, strlen($frm));
if($sub_in==',')
$frm = substr($frm, 0, strlen($frm)-1);

echo "<pre>";
var_dump($frm);
echo "</pre>";



/////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////


/**
 * find all PARENT ids of Recursive Relationship - with just 1 sql query call
 *
 * @param $f1 = node id
 * @param $table_name = name of the table
 * @param $dir = direction of breadcrumbs
 * @param $parent_field_name = field name of parent id
*/
function get_uppers($f1, $table_name, $dir='left', $parent_field_name='parent_id', $ori='1')
{
    $frm  = array();
    static $res_ary = array();
    $res = false;


    $breadcr = '&laquo;&laquo;';
    if($dir=='right')
    $breadcr = '&raquo;&raquo;';


    ## get dataset
    if($ori!='0')
    {
        $sql = "select id, {$parent_field_name}, title from {$table_name}"; //echo $sql;
        $res = mysql_exec($sql);

        if($res!==false){
        foreach($res as $k=>$v)
        {
            $res_ary["{$table_name}"][$v['id']]= array();
            $res_ary["{$table_name}"][$v['id']][]= $v;
        }
        }
    }
    $res = @$res_ary["{$table_name}"][$f1];
    //var_dump($res_ary); //die();
    ##--


    ## process dataset recursively
    if($res!=false){
    foreach($res as $k=>$v)
    {
        $frm []= format_str($v['title']);

        $t1 = get_uppers($v[$parent_field_name], $table_name, $dir, $parent_field_name, '0');
        if((!empty($t1))){
        $frm = array_merge($frm, $t1);
        }

    }//end foreach....

    if($ori!='0')
    {
        //var_dump($frm);
        $frm = array_reverse($frm);
        $frm[count($frm)-1] = '<u>'.$frm[count($frm)-1].'</u>';
        $frm = implode(" {$breadcr} ", $frm);
    }
    }
    ##--

    return $frm;

}//end func....


$tree = get_uppers($id);
?>