<?php 
$user = elgg_get_logged_in_user_entity();
elgg_load_js('deck:js');
?>
<div class="blurb">
	Finally a free tool to bring all your social newsfeeds together in one place and interact with them seamlessly. 
</div>

<div class="orientation-table">
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			<span class="entypo">&#62221;</span> Facebook
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('output/url', array('text'=>'Connect', 'href'=>elgg_get_site_url().'authorize/facebook', 'class'=>' connect-network  elgg-button elgg-button-action')); ?>
		</div>
		<div class="orientation-table-cell label">
			<span class="entypo">&#62218;</span> Twitter
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('output/url', array('text'=>'Connect', 'href'=>elgg_get_site_url().'authorize/twitter', 'class'=>'connect-network  elgg-button elgg-button-action')); ?>
		</div>
	</div>
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			<span class="entypo">&#62230;</span>Tumblr
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('output/url', array('text'=>'Connect', 'href'=>elgg_get_site_url().'authorize/tumblr', 'class'=>'connect-network elgg-button elgg-button-action')); ?>
		</div>
		<div class="orientation-table-cell label">
			 <span class="entypo">&#62233;</span> Linkedin
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('output/url', array('text'=>'Connect', 'href'=>elgg_get_site_url().'authorize/linkedin', 'class'=>'connect-network  elgg-button elgg-button-action')); ?>
		</div>
	</div>
</div>