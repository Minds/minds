<?php 

$type = $vars['type'];
$data = is_array($vars['data']) ? $vars['data'] : json_decode(json_encode($vars['data']), true);

if($type == 'batch'){
elgg_load_js('popup');
?>
	<div class="archive-batch archive-batch-<?=count($data)?>">
		<?php foreach($data as $image):
			$image = (array) $image;
			?>
				
			<a href="<?= $image['href']?>" class="image-thumbnail lightbox-image batch-thumbnails">
				<img src="<?= $image['src']?>"/>
			</a>
			
		<?php endforeach; ?>
	</div>
<?php 
}
