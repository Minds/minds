<?php

    $entities = $vars['entities'];
?>
<div id="nodes-switcher" class="nodes-switcher" style="display:none;">
    
    <div class="nodes-list">
	<ul>
    <?php
    
	foreach ($entities as $entity) {
	    
	    echo "<li>";
	    echo elgg_view('minds_nodes/switcher/node', ['entity' => $entity]);
	    echo "</li>";
	}
    
    ?>
	</ul>
    </div>
    <div class="launcher">
	<a class ="elgg-button elgg-button-action" href="<?= elgg_get_site_url(); ?>nodes/launch">+ Launch a node</a>
    </div>
</div>