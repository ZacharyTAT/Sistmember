<?php
	function test(&$var) 
	{
		$var = array("fadsfa",false,"ccccc");
	}
	test($var);
	var_dump($var);
	
	$arr = array(
			"key1" => "val1",
			"key2" => "val2",
			"key3" => "val3"
	);
	
	foreach ($arr as $key => $val) {
		echo $key . ":" . $val . "<br/>";
	}
?>