<?php
/** Function buildTree
 * Produce tree of Parent-Child from an array
 * USAGE: $services_pxx = buildTree($services_all);
 */
function buildTree(array $elements, $parentId = 0, $my_id = 'id', $parent_id='parentid')
{
    $branch = array();

    foreach ($elements as $element)
    {
        if ($element[$parent_id] == $parentId)
        {
            $children = buildTree($elements, $element[$my_id]);

            if ($children) {
            $element['children'] = $children;
            }

            $branch[] = $element;
        }
    }

    return $branch;

}//end func....


/** function print_parent_ch_tree
 * To print Parent-Child tree produced from buildTree()
 */
function print_parent_ch_tree($arx, $level=0)
{
    $ret = '';
    foreach($arx as $s_rows)
    {
        $mrk = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level);

        $ret.= "<option value=\"{$s_rows["id"]}\">{$mrk}{$s_rows["title"]}</option>";
        if(isset($s_rows['children']) && is_array($s_rows['children']))
        {
           $lb = $level+1;
           $ret.= print_parent_ch_tree($s_rows['children'], $lb);
        }
    }

    return $ret;

}//end func.....
?>