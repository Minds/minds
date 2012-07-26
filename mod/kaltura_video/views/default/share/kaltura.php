<?php 
	$widget = $vars['widget'];
	$content = '<h3>Share </h3><div class=\'embed\'><input type="text" class="input-text" value="' . $widget . '" />';
	
?>
<script> 
	function customFunc1 (entryid){ 
	
		$("#trigger_share_box").fancybox().trigger('click');
	
	} 
	
	function SelectAll(id){
		document.getElementById(id).focus();
		document.getElementById(id).select();
	}
</script>

<a href="#share_content" id="trigger_share_box" style="display:none;"/> </a>

<div style="display:none;">
	<div id="share_content" style='width:750px;'>
		<h3>Share</h3><br/>
      
    
          <b>Social</b>
          <br/>
       		<a href="http://www.facebook.com/sharer.php?u=<?php echo $_SERVER["SERVER_NAME"]. $_SERVER["REQUEST_URI"]; ?>" target="_blank"><img src="<?php echo elgg_get_site_url(); ?>mod/minds_social/graphics/facebook_big.png" /> </a>
        	<a href="https://twitter.com/share?url=http://<?php echo $_SERVER["SERVER_NAME"]. $_SERVER["REQUEST_URI"]; ?>&via=Minds" target="_blank"><img src="<?php echo elgg_get_site_url(); ?>mod/minds_social/graphics/twitter_big.png" /> </a>
        <br/>
        <b>Embed</b>
        <textarea id="embed" readonly="readonly" onClick="SelectAll('embed');"><?php echo $widget; ?> </textarea>
	</div>
</div>
