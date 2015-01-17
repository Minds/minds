<?php

$group = $vars['entity'];

echo Minds\Core\entities::view(array(
	'type' => 'activity',
	'limit' => 5,
	'masonry' => false,
	'prepend' => $post,
	'list_class' => 'list-newsfeed',
	'container_guid' => $group->guid
));