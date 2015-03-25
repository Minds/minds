<?php
$remind = (array) $vars['remind'];
//$entity = Minds\Core\entities::build(new minds\entities\entity($remind), false);
$entity = new Minds\entities\activity($remind);

if(isset($remind['thumbnail_src']))
	$entity->thumbnail_src = $remind['thumbnail_src'];
//else
//	$entity->thumbnail_src = $entity->getIconUrl();
?>
<div class="activity-remind">
	<?= elgg_view_entity($entity,array('entity'=>$entity, 'comments'=>false, 'menu'=>false)) ?>
</div>
