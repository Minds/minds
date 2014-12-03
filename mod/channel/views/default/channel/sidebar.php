<?php
$user = elgg_extract('user', $vars);

elgg_register_menu_item('channel', array(
	'name' => 'channel:subscribed',
	'text' => 'Subscribers ('. $user->getSubscribersCount() .')',
	'href' => elgg_get_site_url() . $user->username . '/subscribers' ,
	'priority' => 1
));
elgg_register_menu_item('channel', array(
	'name' => 'channel:subscriptions',
	'text' => 'Subscriptions ('. $user->getSubscriptionsCount() .')',
	'href' => elgg_get_site_url() . $user->username . '/subscriptions',
	'priority' => 2
));

elgg_register_menu_item('channel', array(
	'name' => 'channel:news',
	'text' => '<span class="entypo">&#59194;</span> Wall',
	'href' => elgg_get_site_url() . $user->username . '/news',
	'priority' => 100
));
elgg_register_menu_item('channel', array(
	'name' => 'channel:blog',
	'text' => '<span class="entypo">&#59396;</span> Blogs',
	'href' => elgg_get_site_url() . $user->username . '/blogs',
	'priority' => 101
));
elgg_register_menu_item('channel', array(
	'name' => 'channel:archive',
	'text' => '<span class="entypo">&#128193;</span> Uploads',
	'href' => elgg_get_site_url() . $user->username . '/archive',
	'priority' => 102
));

 elgg_register_menu_item('channel', array(
                'name' => 'channel:groups',
                'text' => '<span class="entypo">&#59397;</span> Groups',
                'href' => elgg_get_site_url() . $user->username . '/groups',
                'priority' => 105
        ));

if($bitcoin = \elgg_get_plugin_user_setting('wallet_guid', $user->guid, 'bitcoin')){
	elgg_register_menu_item('channel', array(
		'name' => 'channel:bitcoin',
		'text' => '<span class="entypo">&#59408;</span> Send bitcoin',
		'href' => elgg_get_site_url() . '/bitcoin/send/?address='.$user->username,
		'priority' => 105
	));
}

if($user->canEdit()){
	
	elgg_register_menu_item('channel', array(
        'name' => 'channel:carousel',
        'text' => '<span class="entypo">&#59404</span>Edit Carousel',
        'href' => elgg_get_site_url() . $user->username . '/carousel',
        'priority' => 106
	));

	elgg_register_menu_item('channel', array(
		'name' => 'channel:custom',
		'text' => 'Custom',
		'href' => elgg_get_site_url() . $user->username . '/custom',
		'priority' => 110
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
	
	$menu = elgg_trigger_plugin_hook('register', "menu:user_hover", array('entity'=>$user), $menu);
	$builder = new ElggMenuBuilder($menu);
	$menu = $builder->getMenu($sort_by);
	// admin
	if (elgg_is_admin_logged_in() && $menu['admin']) {
		
		echo elgg_view('navigation/menu/elements/section', array(
			'class' => 'elgg-menu channel-admin-menu',
			'items' => $menu['admin'],
		));
		
	}
}
?>
<a href="<?=$user->getURL()?>" class="name"><h1><?= $user->name ?></h1></a>
<?= $user->website ? elgg_view('output/url', array('text'=>$user->website, 'href'=>$user->website)) : false ?>

<?php 
	echo $user->guid != elgg_get_logged_in_user_guid() ? elgg_view('channel/subscribe', array('entity'=>$user)) : '';
	
	echo elgg_view('channel/social_icons', array('user'=>$user));
	echo elgg_view_menu('channel', array('sort_by'=>'priority'));
	echo elgg_view('channel/about', array('user'=>$user));
