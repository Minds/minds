<?php 
$user = elgg_get_logged_in_user_entity();
?>
<div class="blurb">
	Want to import videos from your Youtube channel? Or maybe an RSS feed from another website? The Minds RSS app autogenerates blogs from RSS feeds allowing you to rapidly build a content supply.
</div>

<div class="orientation-table">
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			Import name
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'name', 'placeholder'=>'eg. My youtube feed')); ?>
		</div>
	</div>
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			Url
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'url', 'placeholder'=>'eg. https://gdata.youtube.com/feeds/base/users/CHANNELNAME/uploads (for youtube!)')); ?>
		</div>
	</div>
</div>
