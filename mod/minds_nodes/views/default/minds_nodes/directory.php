<?php
$nodes = $vars['nodes'];

?>

<ul class="nodes-directory-list">

	<?php foreach($nodes as $node):?>
		<li class="node" id="<?= $node->guid ?>">
			<img src="//<?= $node->domain ?>/themeicons/logo_main/<?= time() ?>.png" class="node_logo" onerror="this.src='<?=elgg_get_site_url()?>_graphics/minds_2.png'"/>
			<h3><?= $node->domain ?></h3>
		</li>
	<?php endforeach; ?>

</ul>
