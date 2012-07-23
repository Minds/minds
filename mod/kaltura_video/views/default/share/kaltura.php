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
      
        <?php 
			$pubid = elgg_get_plugin_setting('profileID','addthis_share');
			if($pubid){

		?>
          <b>Social</b>
        <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
            <a class="addthis_button_preferred_1"></a>
            <a class="addthis_button_preferred_2"></a>
            <a class="addthis_button_preferred_3"></a>
            <a class="addthis_button_preferred_4"></a>
            <a class="addthis_button_compact"></a>
            <a class="addthis_counter addthis_bubble_style"></a>
            </div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=<?php echo $pubid; ?>"></script>
            <!-- AddThis Button END -->
		<?php } ?>
        <br/>
        <b>Embed</b>
        <textarea id="embed" readonly="readonly" onClick="SelectAll('embed');"><?php echo $widget; ?> </textarea>
	</div>
</div>
