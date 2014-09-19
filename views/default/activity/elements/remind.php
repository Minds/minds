<?php
$remind = $vars['remind'];
$entity = minds\core\entities::build(new minds\entities\entity($remind));

?>
<div class="activity-remind">
	<?= elgg_view_entity($entity,array('entity'=>$entity, 'comments'=>false, 'menu'=>false)) ?>
</div>