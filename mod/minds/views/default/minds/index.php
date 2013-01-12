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
    
    <div class='splash'>
		You are
		evolving
		the network
	</div>
   
    <div class="search">
            <form action="<?php echo elgg_get_site_url(); ?>search" method="get">
             
                    <input type="text" name="q" value=""  placeholder="Search the commons" />
                    <button type="submit" value="search" class="elgg-button elgg-button-submit">Search</button>
                    <input type="hidden" name="search" value="1" /></td>
    
            </form>
        
    </div>
  
   <?php 
   	$options = array('types' => 'object', 'subtypes' => $subtypes, 'metadata_name_value_pairs'=> array('name' => 'featured','value'=>true ),'limit' => 12);
	$entities = elgg_get_entities_from_metadata($options);
	$vars['entities'] = $entities;

	$content = elgg_view('minds/tiles',$vars);	
	
	echo $content;?>
   
   </div>
