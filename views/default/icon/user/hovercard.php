<?php
$user = elgg_extract('user', $vars, elgg_get_logged_in_user_entity());

$icon_url = elgg_format_url($user->getIconURL('medium'));
$icon = elgg_view('output/img', array(
	'src' => $icon_url,
	'alt' => $user->name,
	'title' => $user->name,
));
$overview = elgg_view('user/overview', array('entity' => $user));

$banner = elgg_view('output/img', array(
	'src'=>elgg_get_site_url() . "$user->username/banner",
	'class'=>'hovercard-banner-img'
));
?>
<div class="minds-hovercard">
	
	<div class="hovercard-banner">
		<?=$banner ?>
		<div class="top-arrow"><?= $banner ?></div>
		<div class="gradient"></div>
	</div>
	
	<div class="hovercard-container">
		<div class="hovercard-icon">
			<?= $icon ?>
		</div>
		<h3><?=$user->name?></h3>
	</div>
	<?= $overview ?>
	<?= elgg_view_menu('hovercard', array('entity'=>$user)) ?>
</div>
