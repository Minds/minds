<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

$offset = "100000000000168382";
while(1) {

	try{
	$blogs = elgg_get_entities(array('type'=>'object', 'subtype'=>'blog', 'limit'=>800, 'offset'=>$offset));

	 $new_offset = end($blogs)->guid;

        if ($new_offset != $offset) {
                $offset = $new_offset;
        } else {
                break;
        }
	
	foreach($blogs as $blog){

		if(!$blog->perma_url){
			echo "no perma_url set for $blog->guid\n";
			$blog->perma_url = $blog->getUrl();
			$blog->save();
		}
	}
	}catch(Exception $e){
	}
}
