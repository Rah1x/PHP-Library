<title>Radis Test</title>
<?php
#/ Test 1
$redis = new Redis();
$redis->connect('127.0.0.1');

#/ Test 2
$res = $redis->set('foo', 'bar');
$value = $redis->get('foo');
//@var_dump($res, $value); exit;

#/ Test 3
//$res = (bool)@$redis->delete('arr');
$res = $redis->hmset('arr', array(serialize(array('k'=>'v', 'k2'=>'v2', 'a'=>'s', 0=>'s2'))));
//$value = $redis->hmget('arr', array('k2')); //specific field within key
$value = $redis->hgetall('arr');
@var_dump("<pre>", $value, unserialize($value[0]));
?>