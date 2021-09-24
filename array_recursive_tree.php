<?php
/**
$cats = @format_str(@mysql_exec("SELECT * FROM pages_categories ORDER BY parent_id, title"));
if(is_array($cats) && count($cats)>0){
$cats = @cb791($cats, 'parent_id', 'id');
$cats = @array_recursive_tree($cats);
ob_start();print_opts($cats[0]);$cat_opts.=ob_get_clean();
}
**/

////////////////////////////////////////////////////////////////

/**
 * Function array_recursive_tree
 * To convert recursive relation table into full deep parent-child (i.e. n-numbers)
 * @param $ret = array
 *
 * [IMP]
 * query must be ORDERed BY parent_id
 * Must pass data after cb791

 * [usage]
   $cats = @cb791($cats, 'parent_id', 'id');
   $cats = @array_recursive_tree($cats);
*/
function array_recursive_tree($ret)
{
    if(!is_array($ret) || count($ret)<0) return '';

    $ret = array_reverse($ret, true);
    //return $ret; //debug

    $rett = array();

    $loop = 0;
    foreach($ret as $k=>$v)
    {
        $loop++;

        if($k==0)
        {
            if(!array_key_exists($k, $rett))$rett[$k] = array();

            $merged = array_replace_recursive($rett[$k], $v);
            $rett[$k] = $merged;

            break;
        }
        else
        {
            for($i=0; $i<$k; $i++)
            {
                if(@isset($ret[$i][$k]))
                {
                    if(!array_key_exists($i, $rett))$rett[$i] = array();
                    if(!array_key_exists($k, $rett[$i]))$rett[$i][$k] = array();

                    $rett[$i][$k] = $v;

                    if(@isset($rett[$k]))
                    {
                        //echo "|{$k}|";
                        //array_push($rett[$i][$k], $rett[$k]);

                        $merged = array_replace_recursive($rett[$i][$k], $rett[$k]);
                        //var_dump("<pre>", $rett[$i][$k], $rett[$k], array_replace_recursive($rett[$i][$k], $rett[$k]), '---');
                        $rett[$i][$k] = $merged;

                        unset($rett[$k]);
                    }

                    break;
                }
            }
        }

        //var_dump($k);
        //if($loop==4){break;}

    }//end foreach...

    return $rett;

}//end func...




/**
 * Function print_opts
 * To Print deep Parent-Child array into Select's Options
 * @param $ar = Array to print (must already be processed from 'array_recursive_tree' function)
 * @param $sep = separator for childern (ignore as its auto generated)
 * @param $ignore_key = ignore this entire branch from being displayed
*/
function print_opts($ar, $sep='', $ignore_key=0)
{
    if(!is_array($ar) || count($ar)<0){return false;}
    foreach($ar as $k=>$v)
    {
        //var_dump_p($k, $v); echo '-XXX<br />';
        if((isset($v['id'])) && ($v['id']==$ignore_key)){continue;}

        if(isset($v['title']))
        {
            echo "<option value=\"{$v['id']}\">{$sep}{$v['title']}</option>";
        }

        //if($k==$ignore_key){continue;}

        if(is_array($v))
        foreach($v as $k2=>$v2)
        {
            if(is_numeric($k2))
            {
                print_opts(array(0=>$v2), ''.$sep.'&raquo;&nbsp;', $ignore_key);
            }
        }
    }

}//end func...