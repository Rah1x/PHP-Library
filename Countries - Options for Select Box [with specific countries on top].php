<?php
## Get Countries
function get_countries($val='')
{
    global $consts;

    $sql = "select * from ((select id, name, iso_code from {$consts['DB_GLOBAL']}.countries where id in (223, 38, 222) order by field(id, 223, 38, 222))) as a
    UNION all
    select * from ((select id, name, iso_code from {$consts['DB_GLOBAL']}.countries id where id not in (223, 38, 222) order by id)) as b ";
    $res = mysql_exec($sql);

    $frm = '';
    if($res!==false)
    {
		foreach($res as $k=>$v)
		{
            if($v['id'] == $val){
			$frm .= '<option value="'.$v['id'].'" selected="selected">'.$v['name'].'</option>';
			}
			else {
			$frm .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
			}

            #/ Label
            if($v['id']=='222')
            {
                $frm .= '<optgroup label="-----------------------------------"></optgroup>';
            }
		}//end foreach....
    }

    return $frm;
}//end func....

?>