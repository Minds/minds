<?php 

if(!elgg_is_logged_in()){
	$img_src = elgg_get_site_url() == 'http://www.minds.com/' ? elgg_get_site_url().'mod/minds/graphics/minds_logo.png' : elgg_get_site_url().'mod/minds/graphics/minds_logo_io.png';
?>
<div class="container">
	<img src="<?php echo $img_src; ?>" width="200" height="90" class="logo" />
</div>
<div class="container">
	<?php echo elgg_view_form('login');?>
</div>
<?php
} else {
forward('news');
}
