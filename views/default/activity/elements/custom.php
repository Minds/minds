<?php 

$type = $vars['type'];
$data = is_array($vars['data']) ? $vars['data'] : json_decode(json_encode($vars['data']), true);
$count = count($data);

if($type == 'batch'){
elgg_load_js('popup');
?>
    <?php if(!$vars['title']){?>
    <p>Uploaded <?= $count; ?> image<?php if ($count > 1) {echo 's'; } ?></a>
    <?php } ?>

    <p style="white-space:pre">
        <?= strip_tags($vars['title']) ?>
    </p>


    <div class="archive-batch archive-batch-<?=count($data)?>">
		<?php foreach($data as $image):
			$image = (array) $image; 
			if(strpos($image['href'], 'wall/attachment') !== FALSE)
				$image['href'] = str_replace('wall/attachment','archive/view/0', $image['href']);

		?>
				
			<a href="<?= $image['href']?>" class="image-thumbnail lightbox-image batch-thumbnails">
				<img src="<?= $image['src']?>"/>
			</a>
			
		<?php endforeach; ?>
	</div>
<?php 
}
