<?php

$user = elgg_extract('user', $vars, elgg_get_logged_in_user_entity());

$icon_url = elgg_format_url($user->getIconURL('medium'));
$icon = elgg_view('output/img', array(
	'src' => $icon_url,
	'alt' => $user->name,
	'title' => $user->name,
));
$overview = elgg_view('user/overview', array('entity' => $user));

global $CONFIG;
$banner = elgg_view('output/img', array(
	'src'=>$CONFIG->cdn_url. "$user->username/banner/",
	'class'=>'hovercard-banner-img'
));
?>
<div class="minds-hovercard" <?php if(isset($vars['show'])){ ?> style="display:block;" <?php } ?>'>
	
	<div class="hovercard-banner">
		<?=$banner ?>
		<div class="top-arrow"><?= $banner ?></div>
		<div class="gradient"></div>
	</div>
	
	<a href="<?= $user->getUrl() ?>">
		<div class="hovercard-container">
			<div class="hovercard-icon">
				<?= $icon ?>
			</div>
			<h3><?=$user->name?></h3>
		</div>
	</a>
	<div class="overview">
	<?= $overview ?>
	</div>
	<?= elgg_view_menu('hovercard', array('entity'=>$user))?>
</div>
