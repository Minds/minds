<?php
$user = $vars['user'];
$subtype = $vars['subtype'];
?>

<ul class="channel-content-filter">
	<li><a href="<?=elgg_get_site_url() . $user->username?>/archive" class="<?= $subtype == 'archive' ? 'active' : ''?>">All</a></li>
	<li><a  href="<?=elgg_get_site_url() . $user->username?>/archive?subtype=video" class="<?= $subtype == 'video' ? 'active' : ''?>">Videos</a></li>
	<li><a  href="<?=elgg_get_site_url() . $user->username?>/archive?subtype=album" class="<?= $subtype == 'albums' ? 'active' : ''?>">Albums</a></li>
</ul>