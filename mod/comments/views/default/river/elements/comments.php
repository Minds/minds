<?php

$item = $vars['item'];

if($item->action_type == 'create' || $item->action_type == 'feature'){
	echo \minds\plugin\comments\comments::display($item->getObjectEntity());
}




