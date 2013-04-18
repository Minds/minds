<?php 
	$widget = $vars['widget'];
	$content = '<h3>Share </h3><div class=\'embed\'><input type="text" class="input-text" value="' . $widget . '" />';
	elgg_load_js('lightbox');
	elgg_load_css('lightbox');
?>
<script> 
	function customFunc1 (entryid){ 
	
		$("#trigger_share_box").fancybox().trigger('click');
		console.log('testing');
	} 
	
	function SelectAll(id){
		document.getElementById(id).focus();
		document.getElementById(id).select();
	}
</script>
<a href="#share_content" id="trigger_share_box" style="display:none;" class="elgg-lightbox"/> </a>

<div style="display:none;">
	<div id="share_content" style='width:750px;'>
		<h3>Share</h3>
          <?php echo elgg_view('minds_social/social_footer');?>
        <b>Embed</b>
        <textarea id="embed" readonly="readonly" onClick="SelectAll('embed');"><?php echo $widget; ?> </textarea>
	</div>
</div>
