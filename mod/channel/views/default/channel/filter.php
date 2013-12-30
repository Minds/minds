<?php
$user = $vars['user'];
$selected = $vars['selected'] ?: 'news';

$items = array(	
				'about' => array(	
							'href' => elgg_get_site_url() . $user->username . '/about',
							'text' => elgg_echo('about'),
							'selected' => $selected == 'about' ? true : false
							),

				'news' => array(	
							'href' => elgg_get_site_url() . $user->username . '/news',
							'text' => elgg_echo('news'),
							'selected' => $selected == 'news' ? true : false
							),
							
				'blogs' => array(
							'href' => elgg_get_site_url() . $user->username . '/blogs',
							'text' => elgg_echo('blog'),
							'selected' => $selected == 'blogs' ? true : false
							),
							
				'archive' => array(
							'href' => elgg_get_site_url() . $user->username . '/archive',
							'text' => elgg_echo('archive'),
							'selected' => $selected == 'archive' ? true : false
							),
							
				'widgets' => array(
							'href' => elgg_get_site_url() . $user->username . '/widgets',
							'text' => elgg_echo('widgets'),
							'selected' => $selected == 'widgets' ? true : false
							)
				);

?>
<div class="channel-filter-menu">
	<ul>
		<?php foreach($items as $item): ?>
			<li>
				<a href="<?php echo $item['href'];?>" class="<?php echo $item['selected'] ? 'selected': null;?>"><?php echo $item['text'];?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>