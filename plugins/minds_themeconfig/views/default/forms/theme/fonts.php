<?php
global $CONFIG;
foreach ($CONFIG->theme_fonts as $element => $code) {
    ?>
    <div class="font-selection font-selection-<?php echo $element; ?>">
        <h2 style="text-transform:capitalize;"><?php echo $element; ?></h2>
        <div id="sample_<?php echo $code; ?>" class="font-sample">
    	<<?php echo $code; ?> style="<?php 
	    $f = elgg_get_plugin_setting('font::' . $code, 'minds_themeconfig');
	    $s = elgg_get_plugin_setting('font_size::' . $code, 'minds_themeconfig');
	    $c = elgg_get_plugin_setting('font_colour::' . $code, 'minds_themeconfig');
	
	    if ($f) echo "font-family: " . addslashes ($f) ."; " ;
	    if ($s) echo "font-size: {$s}pt;";
	    if ($c) echo "color: #{$c};";
	    
	?>" class="sample">Pack my box with five dozen liquor jugs.</<?php echo $code; ?>>
        </div>

        <p><label>
		<?php echo elgg_echo('minds_themeconfig:font:' . $element); ?>:<br />
		<?php echo elgg_view('input/fontpicker', array('data-sample' => "sample_$code", 'id' => "font_$code", 'name' => "font_$code", 'class' => 'font-picker',  'value' => elgg_get_plugin_setting('font::' . $code, 'minds_themeconfig'))); ?>
    	</label></p>

        <p><label>
		<?php echo elgg_echo('minds_themeconfig:font_size:' . $element); ?> (pt):<br />
		<?php echo elgg_view('input/fontsize', array('data-sample' => "sample_$code", 'id' => "font_size_$code",  'name' => "font_size_$code", 'class' => 'font-size', 'value' => elgg_get_plugin_setting('font_size::' . $code, 'minds_themeconfig'))); ?>
    	</label></p>
	
	<p><label>
		<?php echo elgg_echo('minds_themeconfig:font_colour:' . $element); ?>:<br />
		<?php echo elgg_view('input/colourpicker', array('data-sample' => "sample_$code", 'id' => "font_colour_$code",  'name' => "font_colour_$code", 'class' => 'font-colour', 'value' => elgg_get_plugin_setting('font_colour::' . $code, 'minds_themeconfig'))); ?>
    	</label></p>
    </div>


    <?php
}
?>

<?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>

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
