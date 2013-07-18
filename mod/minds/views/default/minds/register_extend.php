<?php
/**
 * Index View
 */
?>
<div>
<label>
	<input type="checkbox" name="tcs" value="true">
	<?php echo elgg_echo('minds:register:terms:read')."<a href=\"{$vars['url']}terms\">".elgg_echo('minds:regsiter:terms:link')."</a>" ?>
	<input type="checkbox" name="terms" value="false">
</label></div>
