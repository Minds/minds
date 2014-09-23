<?php 

$type = $vars['type'];
$data = $vars['data'];

if($type == 'batch'){
?>
	<div class="archive-batch archive-batch-<?=count($data)?>">
		<?php foreach($data as $image):?>
			
			<a href="<?= $image['href']?>" class="image-thumbnail lightbox-image batch-thumbnails">
				<img src="<?= $image['src']?>"/>
			</a>
			
		<?php endforeach; ?>
	</div>
<?php 
}
