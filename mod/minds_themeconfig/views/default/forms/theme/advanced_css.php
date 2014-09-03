<?php
	elgg_load_css('codemirror');
	elgg_load_js('codemirror');
	elgg_load_js('codemirror-mode-css');
?>
<p><label> <?php echo elgg_echo('minds_themeconfig:custom_css');?>:</label><br />
   
    <textarea id="custom_css" name="custom_css" rows="50"><?php echo elgg_get_plugin_setting('custom_css', 'minds_themeconfig'); ?></textarea>
</p>

<?php echo elgg_view('input/button', array('value' => elgg_echo('preview'), 'id' => 'css-preview')); ?> <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
<hr />

<div id="preview" style="display: none;">
    <iframe style="width: 100%; height: 500px;"></iframe>
</div>

<script>
	
      var editor = CodeMirror.fromTextArea(document.getElementById("custom_css"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "text/x-scss"
      });
    

    $(document).ready(function(){
    	
    	function update(){
    		$("#custom").html(editor.getValue());
    	}
    	//update every so often
    	setInterval(update, 300);
    	
		$('#css-preview').click(function() {
			update();
		});
    });
</script>