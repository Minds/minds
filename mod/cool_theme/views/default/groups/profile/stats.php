<?php 

$group = $vars['entity'];
$owner = $group->getOwnerEntity();

?>
<dl class="elgg-profile">
	<dt><?php echo elgg_echo("groups:owner"); ?></dt>
	<dd>
		<?php
			echo elgg_view('output/url', array(
				'text' => $owner->name,
				'value' => $owner->getURL(),
			));
		?>
	</dd>
	<dt><?php echo elgg_echo('groups:members'); ?></dt>
	<dd><?php echo $group->getMembers(0, 0, TRUE); ?></dd>
</dl>