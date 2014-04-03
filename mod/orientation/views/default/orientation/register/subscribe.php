<?php 
$user = elgg_get_logged_in_user_entity();
?>
<div class="blurb">
	Subscribe to some trending channels and fill your newsfeed with awesome content!
</div>

<?php
	$limit =12;
	$offset =0;
	$options = array(
                	'timespan' => get_input('timespan', 'day')
       	 	);
	if(class_exists('MindsTrending')){
	$trending = new MindsTrending(null, $options);
	$guids = $trending->getList(array('type'=>'user', 'limit'=>$limit, 'offset'=>(int) $offset, 'full_view'=>false));
	$options['guids'] = $guids;
	$options['type'] = 'user';
	$options['pagination'] = false;

	echo elgg_list_entities($options);
	
	}
