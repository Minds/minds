<?php

    $entities = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'node',
	'limit' => 999
    ]);
?>
<div id="nodes-switcher" class="nodes-switcher" style="display:none;">
    <div class="nodes-list">
    <?php
    
	foreach ($entities as $entity) {
	    
	    echo elgg_view('minds_nodes/switcher/node', ['entity' => $entity]);
	    
	}
    
    ?>
    </div>
</div>