<?php
/**
 * View for carousels
 * 
 */
elgg_load_js('carouFredSel');

$entities = elgg_extract('entities', $vars);
$id =  elgg_extract('id',$vars, rand());

?>
<script>
$(document).ready(function() {
	
	// Using custom configuration
	$("#<?php echo $id;?>").carouFredSel({
		items				: 1,
		direction			: "left",
		swipe				: true,
		infinite			: true,
		circular			: true,
		pagination			: "#<?php echo $id;?>_pag",
		prev	: { 
			button  : "#<?php echo $id;?>_prev",
			key		: "left",
		},
		next	: { 
			button  : "#<?php echo $id;?>_next",
			key		: "right",
		},
		scroll : {
			items			: 3,
			duration		: <?php echo rand(1000,1500);?>,							
			pauseOnHover	: true
		}					
	});	
});
</script>
<div id="hz_carousel">
	<div id="<?php echo $id;?>">
		<?php 
		
		foreach($entities as $entity){
			
			if($entity->getSubtype() == 'kaltura_video'){
				$icon = elgg_view('output/img', array(
						'src' => kaltura_get_thumnail($entity->kaltura_video_id, 300, 185, 100),
						'title' => $entity->title,
						'alt' => $entity->title,
				));
				$title = $entity->title;
			} elseif($entity->getSubtype() == 'image') {
				$icon = elgg_view('output/img', array(
						'src' => $entity->getIconURL('large'),
						'title' => $entity->title,
						'alt' => $entity->title,
				));
				$title = $entity->getTitle();
			} elseif($entity->getSubtype() == 'file') {
				continue;
			} elseif($entity->getSubtype() == 'blog') {
				$icon = elgg_view('output/img', array('src'=>minds_fetch_image($entity->description), 'class'=>'rich-image'));
				$title = $entity->title; 
			}
			
			$owner = $entity->getOwnerEntity();
			
			echo '<div class="thumbnail-tile ">';
				
				echo '<div class="hover"> <div class="inner">';	
					echo '<div class="title">' . elgg_view('output/url', array('href'=>$entity->getURL(), 'text' =>$title)) . '</div>';
					echo '<div class="owner">' . elgg_echo('archive:owner_tag') . elgg_view('output/url', array('href'=>$owner->getURL(), 'text'=> $owner->name)) . '</div>'; 
					echo elgg_view_menu('thumbs', array('entity'=>$entity));
				echo '</div></div>';
				
				echo elgg_view('output/url', array(
						'text' => $icon,
						'href' => $entity->getURL(),
					));
			
			echo '</div>';
			
		}
	?>
	</div>
	<div class="pagination" id="<?php echo $id;?>_pag"></div>
</div>
