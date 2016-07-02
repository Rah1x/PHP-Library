<?php
function build_tree($rows, $id_column, $parent_column)
{
    $tree = array();
    $tree_index = array();

    while(count($rows) > 0){
    foreach($rows as $row_id => $row){
    if($row[$parent_column]){
    if((!array_key_exists($row[$parent_column], $rows)) and (!array_key_exists($row[$parent_column], $tree_index))){
    unset($rows[$row_id]);
    }
    else{
    if(array_key_exists($row[$parent_column], $tree_index)){
    $parent = & $tree_index[$row[$parent_column]];
    $parent['children'][$row_id] = array("node" => $row, "children" => array());
    $tree_index[$row_id] = & $parent['children'][$row_id];
    unset($rows[$row_id]);
    }
    }
    }
    else{
    $tree[$row_id] = array("node" => $row, "children" => array());
    $tree_index[$row_id] = & $tree[$row_id];
    unset($rows[$row_id]);
    }
    }
    }

    unset($tree_index);

    return $tree;
}//end func.....



/**
 * for printing in proper tree format
 */
function print_node($allowed_pages_t, $v, $padding_left=1)
{
    $ret = '';

    $node = $v['node'];
    $children = $v['children'];

    if(!empty($allowed_pages_t))
    {
        $allowed_pages = explode(', ', $allowed_pages_t);
    }


    $ret.= "<div style='padding-left:{$padding_left}px; padding-bottom:10px;'>
    <input type=\"checkbox\" onclick=\"ori_node=0; set_acl('{$node['id']}');\" class=\"checkbox\" name=\"acl[]\" id=\"acl_sections_{$node['id']}\"
    ";

    if( !empty($allowed_pages_t) && (in_array($node['title'], $allowed_pages)) ) $ret.= "checked='checked'";

    $ret.= "value='{$node['title']}' /><label for=\"acl_sections_{$node['id']}\">{$node['display_name']}</label>
    </div>";

    if(!empty($children))
    {
        foreach($children as $ch)
        $ret.= print_node($allowed_pages_t, $ch, ($padding_left+20));
    }

    return $ret;

}//end func.....


/**
 * javascript array for actions
 */
function get_acl_js2($v, $acl='acl')
{
    $ret = '';

    $node = $v['node'];
    $children = $v['children'];


    $ret.= "
    len = {$acl}.length;
    {$acl}[len] = [];
    {$acl}[len]['parent_id'] = '{$node['parent_id']}';
    {$acl}[len]['id'] = '{$node['id']}';
    ";

    if(!empty($children))
    {
        foreach($children as $ch)
        $ret.= get_acl_js2($ch, $acl);
    }

    return $ret;
}//end func.....
?>


<script>
var ori_node = 0;
function set_acl(node_id, dire)
{
    if(document.getElementById('acl_sections_'+node_id)==null){ return false; }

    if(ori_node==0)
    ori_node = node_id;

    var direction = '';
    if(typeof(dire)!='undefined')
    direction = dire;

    var cur_checked = true;
    cur_checked = document.getElementById('acl_sections_'+node_id).checked;

    for(var i in acl)
    {
        var id = parseInt(acl[i]['id']);
        var par = parseInt(acl[i]['parent_id']);

        //moving backwords up-tree to uncheck (only if current is unchecked)
        if( (id==node_id) && (cur_checked!=true) && ((direction=='') || (direction=='backward')) )
        {
            if(document.getElementById('acl_sections_'+par)!=null)
            {
                document.getElementById('acl_sections_'+par).checked=cur_checked;
                ret = set_acl(par, 'backward');

                if(id!=ori_node)
                return ret;
            }
            else
            {
                if(direction=='backward')
                return false;
            }
        }


        //moving forward down-tree to check/uncheck
        if(par==node_id)
        {
            document.getElementById('acl_sections_'+id).checked=cur_checked;
            set_acl(id, 'forward');
        }

    }//end for...

}//end func....
</script>