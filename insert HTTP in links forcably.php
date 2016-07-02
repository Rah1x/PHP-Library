<?php
if((!empty($v['link'])) && (!preg_match('/^http/i', $v['link']))) $v['link'] = 'http://'.$v['link'];
?>