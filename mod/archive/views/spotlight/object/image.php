<?php
	$image = $vars['entity'];
	
	echo elgg_view('output/img', array('src'=>$image->getIconURL('xlarge')));