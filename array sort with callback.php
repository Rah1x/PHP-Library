<?php
//Simple Function (without callback)
function sort_ar($a, $b){return strcasecmp($a['t_val'], $b['t_val']);}

/////////////////////////////////////////////////////////////

//Complicated example thats better sorted with a callback function
function cmp($a, $b)
{
	global $com_item, $dir;
	if($dir=='up')
	{
		if($com_item=='price')
		{
			if($a['LIST_RATE'][0]['LIST_PRICE']["PRICE"] < $b['LIST_RATE'][0]['LIST_PRICE']["PRICE"])
				return -1;
			else if($a['LIST_RATE'][0]['LIST_PRICE']["PRICE"] > $b['LIST_RATE'][0]['LIST_PRICE']["PRICE"])
				return 1;
			else
				return 0;
		}
		else if($com_item=='hotel')
			return strcasecmp($a["HOTEL"]["NAME"], $b["HOTEL"]["NAME"]);
	}
	else
	{
		if($com_item=='price')
		{
			if($b['LIST_RATE'][0]['LIST_PRICE']["PRICE"] < $a['LIST_RATE'][0]['LIST_PRICE']["PRICE"])
				return -1;
			else if($b['LIST_RATE'][0]['LIST_PRICE']["PRICE"] > $a['LIST_RATE'][0]['LIST_PRICE']["PRICE"])
				return 1;
			else
				return 0;
		}
		else if($com_item=='hotel')
			return strcasecmp($b["HOTEL"]["NAME"], $a["HOTEL"]["NAME"]);
	}
}//end func....
usort($trips['LIST_HOTEL'], "cmp");

///////////////////////////////////////////////////////////
?>