<?php
/**
 * Index View
 */
$img_src = elgg_get_site_url() == 'http://www.minds.com/' ? elgg_get_site_url().'mod/minds/graphics/minds_logo.png' : elgg_get_site_url().'mod/minds/graphics/minds_logo_io.png';
?>
<div class='minds_index'>
    <div class="logo">
        <img src="<?php echo $img_src;?>" width="200" height="90" />
    </div>
    
    <?php if(!elgg_is_logged_in()) {
 		echo "<div class='earlyAccess'>";
	    echo elgg_view_form('minds/front_register', array('action'=> '/register'));
    	echo "</div>";
   }
   ?>
   <div class='splash'>
		Free & Open Source
		Social Video
		Revolution
	</div>
   <?php 
   	$options = array('types' => 'object', 'subtypes' => $subtypes, 'metadata_name_value_pairs'=> array('name' => 'featured','value'=>true ),'limit' => 5);
	$entities = elgg_get_entities_from_metadata($options);
	$vars['entities'] = $entities;

	$content = elgg_view('minds/tiles',$vars);	
	
	echo $content;?>
   
   </div>
