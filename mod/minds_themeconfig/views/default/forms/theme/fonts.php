<?php
global $CONFIG;

foreach ($CONFIG->theme_fonts as $element => $code) {
    ?>
    <div class="font-selection font-selection-<?php echo $element; ?>">
        <h2 style="text-transform:capitalize;"><?php echo $element; ?></h2>
        <div id="sample_<?php echo $code; ?>" class="font-sample">
    	<<?php echo $code; ?> class="sample">Pack my box with five dozen liquor jugs.</<?php echo $code; ?>>
        </div>

        <p><label>
		<?php echo elgg_echo('minds_themeconfig:font:' . $element); ?>:<br />
		<?php echo elgg_view('input/fontpicker', array('data-sample' => "sample_$code", 'id' => "font_$code", 'class' => 'font-picker', 'name' => 'font[' . $code . ']', 'value' => elgg_get_plugin_setting('font::' . $code, 'minds_themeconfig'))); ?>
    	</label></p>

        <p><label>
		<?php echo elgg_echo('minds_themeconfig:font_size:' . $element); ?> (pt):<br />
		<?php echo elgg_view('input/fontsize', array('data-sample' => "sample_$code", 'id' => "font_size_$code", 'class' => 'font-size', 'name' => 'font_size[' . $code . ']', 'value' => elgg_get_plugin_setting('font_size::' . $code, 'minds_themeconfig'))); ?>
    	</label></p>
    </div>


    <?php
}
?>

<script>
    $(document).ready(function() {
	$('.font-picker').change(function() {
	    var sample = $(this).attr('data-sample');
	    var value = $(this).val();
	    
	    
	    if (value != "") {
		$('#'+sample + " .sample").css("font-family", value);
	    } else {
		$('#'+sample + " .sample").css("font-family", "");
	    }
});

	$('.font-size').change(function() {
	    var sample = $(this).attr('data-sample');
	    var value = $(this).val();
	    
	     if (value != "") {
		$('#'+sample + " .sample").css("font-size", value+"pt");
	    } else {
		$('#'+sample + " .sample").css("font-size", "");
	    }
	});
    });
</script>