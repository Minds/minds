<?php 

$type = $vars['type'];
$data = $vars['data'];

if($type == 'batch'){
?>
	<div class="archive-batch-3">
		<?php foreach($data as $image):?>
			
			<a href="<?= $image['href']?>" class="image-thumbnail lightbox-image batch-thumbnails">
				<img src="<?= $image['src']?>"/>
			</a>
			
		<?php endforeach; ?>
	</div>
<?php 
}
