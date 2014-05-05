<?php
$user = elgg_extract('user', $vars);

elgg_register_menu_item('channel', array(
	'name' => 'channel:subscribed',
	'text' => 'Subscribers ('. $user->getSubscribersCount() .')',
	'href' => $user->username . '/subscribers' ,
	'priority' => 1
));
elgg_register_menu_item('channel', array(
	'name' => 'channel:subscriptions',
	'text' => 'Subscriptions ('. $user->getSubscriptionsCount() .')',
	'href' => $user->username . '/subscriptions',
	'priority' => 2
));

elgg_register_menu_item('channel', array(
	'name' => 'channel:news',
	'text' => '<span class="entypo">&#59194;</span> News',
	'href' => $user->username . '/news',
	'priority' => 100
));
elgg_register_menu_item('channel', array(
	'name' => 'channel:blog',
	'text' => '<span class="entypo">&#59396;</span> Blogs',
	'href' => $user->username . '/blogs',
	'priority' => 101
));
elgg_register_menu_item('channel', array(
	'name' => 'channel:archive',
	'text' => '<span class="entypo">&#128193;</span>Archive',
	'href' => $user->username . '/archive',
	'priority' => 102
));
if($user->canEdit()){
	elgg_register_menu_item('channel', array(
		'name' => 'channel:custom',
		'text' => 'Custom',
		'href' => $user->username . '/custom',
		'priority' => 103
	));
}

//avatar
echo elgg_view('output/img', array(
		'title'=>$user->name, 
		'src'=>$user->getIconURL('large'), 
		'class'=>'minds-fixed-avatar'
	));
if($user->canEdit() ){
		$url = elgg_get_site_url() . "channel/$user->username/avatar";
		echo "<a class=\"avatar-edit\" href=\"$url\">Edit</a>";
	}
?>
<h1><?= $user->name ?></h1>
<?= $user->website ? elgg_view('output/url', array('text'=>$user->website, 'href'=>$user->website)) : false ?>

<?php 
	echo $user->guid != elgg_get_logged_in_user_guid() ? elgg_view('channel/subscribe', array('entity'=>$user)) : '';
	
	echo elgg_view('channel/social_icons', array('user'=>$user));
	echo elgg_view_menu('channel', array('sort_by'=>'priority'));
	echo elgg_view('channel/about', array('user'=>$user));