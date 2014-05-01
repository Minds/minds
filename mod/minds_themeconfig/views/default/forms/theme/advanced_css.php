
<p><label>
    <?php echo elgg_echo('minds_themeconfig:custom_css');?>:<br />
    <textarea id="custom_css" name="custom_css" rows="10"><?php echo elgg_get_plugin_setting('custom_css', 'minds_themeconfig') ? elgg_get_plugin_setting('custom_css', 'minds_themeconfig') : elgg_view('css'). "\n\n" .elgg_view('css/minds'); ?></textarea>
</label></p>
<?php echo elgg_view('input/button', array('value' => elgg_echo('preview'), 'id' => 'css-preview')); ?> <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
<hr />

<div id="preview" style="display: none;">
    <iframe style="width: 100%; height: 500px;" />
</div>

<script>
    $(document).ready(function(){
	$('#css-preview').click(function() {
	    $.post("<?php echo elgg_get_site_url(); ?>action/theme/advanced_css_preview",
	    {
		custom_css: $('#custom_css').val()
	    },
	    function(data, status){
		$('#preview iframe').attr('src', '<?php echo elgg_get_site_url(); ?>?preview=true');
		$('#preview').fadeIn();
	    });
	});
    });
    </script>