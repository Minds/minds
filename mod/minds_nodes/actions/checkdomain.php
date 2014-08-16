<?php

$ia = elgg_set_ignore_access();

$node = new MindsNode();
$node->domain = get_input('domain');

try{

	$node->checkDomain();
	echo "ok";
	
}catch(Exception $e){
	echo $e->getMessage();
}
exit;