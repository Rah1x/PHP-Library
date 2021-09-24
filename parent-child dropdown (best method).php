<?php
$categories = cb79($categories, 'parent_id');
//var_dump("<pre>", $categories); die();

$Rtn = "<select class=\"selector\" id=\"category_id\" name=\"category_id\">
<option value=''>-- Please Select --</option>";

if(is_array($categories))
foreach ($categories[0] as $cv1)
{
    $Rtn.= "<option value='{$cv1['id']}'>{$cv1['title']}</option>";
    if(isset($categories[$cv1['id']]) && is_array($categories[$cv1['id']]))
    {
        foreach ($categories[$cv1['id']] as $cv2) {
        $Rtn.= "<option value='{$cv2['id']}'>&nbsp;&nbsp;&raquo;&nbsp;{$cv2['title']}</option>";
        }
    }
}
$Rtn.= "</select>";