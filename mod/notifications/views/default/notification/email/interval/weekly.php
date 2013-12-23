<?php
/**
 * Weekly Template
 */

$entity = elgg_extract('entity', $vars);

$notifications = null;
$featured = minds_get_featured(null, 6);
$trending = null;
?>

<tr>
	<td>
		<h1>
			Hey Mark!
		</h1>
		<p>
			Here is your Minds weekly digest.
		</p>
	</td>
</tr>


<!-- FEATURED -->
<table>
	<tr>  
		<td> 
			<h2>Featured</h2>
		</td>
	</tr>
	<?php foreach($featured as $entity): ?>
		<tr>
			<td>
				<img src="<?php echo $entity->getIconURL();?>" width="200px" alt="<?php echo $entity->title;?>" style="margin:6px;"/>
			</td>
			<td>
				<h1 style="color:#333; font-size:18px; padding:0; margin:0;"><?php echo $entity->title; ?></h1>
				<h4 style="color:#666; padding:0; margin:0;">by <?php echo elgg_view('output/url', array('href'=>$entity->getOwnerEntity()->getURL(), 'text'=>$entity->getOwnerEntity()->name));?></h4>
				<p><?php echo $entity->excerpt ?: elgg_get_excerpt($entity->description); ?></p>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<!-- END FEATURED -->
