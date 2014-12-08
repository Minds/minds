<?php

    $entities = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'node',
	'limit' => 999,
	'owner_guid' => elgg_get_logged_in_user_guid()
    ]); 
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
</div>