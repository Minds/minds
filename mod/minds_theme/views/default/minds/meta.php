<?php

	$entity = $vars['entity'];
		
	if($entity){
	
	$tags = implode(',', $entity->tags);
?>

<meta name="description" content="<?php echo $entity->excerpt ? $entity->excerpt : $entity->description; ?>" />

<meta name="keywords" content="<?php echo $tags; ?>"/>

<?php } ?>