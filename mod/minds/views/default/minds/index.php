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
    <div class="search">
            <form action="<?php echo elgg_get_site_url(); ?>search" method="get">
             
                    <input type="text" name="q" value=""  placeholder="<?php echo elgg_echo('search');?>" />
                    <button type="submit" value="search" class="elgg-button elgg-button-submit">Search</button>
                    <input type="hidden" name="search" value="1" /></td>
    
            </form>
        
    </div>
    <div class='splash'>
		Free &
		Open Source
		Everything
	</div>
  	<div class='featured_wall'>
	   <?php 
	    /**
		 * Video carousel
		 */
	   	$options = array('types' => 'object', 'subtypes' => array('kaltura_video'), 'metadata_name_value_pairs'=> array('name' => 'featured','value'=>true ),'limit' => 12);
		$videos = elgg_get_entities_from_metadata($options);
		echo '<h3>Videos</h3>';
		echo elgg_view('output/carousel', array('id'=>'videos','entities'=>$videos));
		
		/**
		 * Blog carousel
		 */
		elgg_load_library('elgg:blog');
		$blogs = blog_get_featured(10);
		echo '<h3>Blogs</h3>';
		echo elgg_view('output/carousel', array('id'=>'blogs','entities'=>$blogs));
		
		/**
		 * Images
		 */
		//elgg_load_library('elgg:blog');
		$images = minds_get_featured('image',10);
		echo '<h3>Images</h3>';
		echo elgg_view('output/carousel', array('id'=>'images','entities'=>$images));
		
		?>
   </div>
</div>