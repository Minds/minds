<?php
/**
 * Tidypics server analysis
 */

function tp_readable_size($bytes) {
	if (strpos($bytes, 'M')) {
		return $bytes . 'B';
	}

	$size = $bytes / 1024;
	if ($size < 1024) {
		$size = number_format($size, 2);
		$size .= ' KB';
	} else {
		$size = $size / 1024;
		if ($size < 1024) {
			$size = number_format($size, 2);
			$size .= ' MB';
		} else {
			$size = $size / 1024;
			$size = number_format($size, 2);
			$size .= ' GB';
		}
	}
	return $size;
}

$disablefunc = explode(',', ini_get('disable_functions'));
$exec_avail = elgg_echo('tidypics:disabled');
if (is_callable('exec') && !in_array('exec',$disablefunc)) {
	$exec_avail = elgg_echo('tidypics:enabled');
}

ob_start();

?>
<table class="elgg-table-alt">
	<tr>
		<td><?php echo elgg_echo('tidypics:server_info:php_version'); ?></td>
		<td><?php echo phpversion(); ?></td>
		<td></td>
	</tr>
	<tr>
		<td>GD</td>
		<td><?php echo (extension_loaded('gd')) ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled'); ?></td>
		<td><?php echo elgg_echo('tidypics:server_info:gd_desc'); ?></td>
	</tr>
	<tr>
		<td>imagick</td>
		<td><?php echo (extension_loaded('imagick')) ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled'); ?></td>
		<td></td>
	</tr>
	<tr>
		<td>exec()</td>
		<td><?php echo $exec_avail; ?></td>
		<td><?php echo elgg_echo('tidypics:server_info:exec_desc'); ?></td>
	</tr>
	<tr>
		<td><?php echo elgg_echo('tidypics:server_info:memory_limit'); ?></td>
		<td><?php echo tp_readable_size(ini_get('memory_limit')); ?></td>
		<td><?php echo elgg_echo('tidypics:server_info:memory_limit_desc'); ?></td>
	</tr>
	<tr>
		<td><?php echo elgg_echo('tidypics:server_info:peak_usage'); ?></td>
		<td><?php if (function_exists('memory_get_peak_usage')) echo tp_readable_size(memory_get_peak_usage()); ?></td>
		<td><?php echo elgg_echo('tidypics:server_info:peak_usage_desc'); ?></td>
	</tr>
	<tr>
		<td><?php echo elgg_echo('tidypics:server_info:upload_max_filesize'); ?></td>
		<td><?php echo tp_readable_size(ini_get('upload_max_filesize')); ?></td>
		<td><?php echo elgg_echo('tidypics:server_info:upload_max_filesize_desc'); ?></td>
	</tr>
	<tr>
		<td><?php echo elgg_echo('tidypics:server_info:post_max_size'); ?></td>
		<td><?php echo tp_readable_size(ini_get('post_max_size')); ?></td>
		<td><?php echo elgg_echo('tidypics:server_info:post_max_size_desc'); ?></td>
	</tr>
	<tr>
		<td><?php echo elgg_echo('tidypics:server_info:max_input_time'); ?></td>
		<td><?php echo ini_get('max_input_time'); ?>s</td>
		<td><?php echo elgg_echo('tidypics:server_info:max_input_time_desc'); ?></td>
	</tr>
	<tr>
		<td><?php echo elgg_echo('tidypics:server_info:max_execution_time'); ?></td>
		<td><?php echo ini_get('max_execution_time'); ?> s</td>
		<td><?php echo elgg_echo('tidypics:server_info:max_execution_time_desc'); ?></td>
	</tr>
	<tr>
		<td>GD imagejpeg</td>
		<td><?php echo (is_callable('imagejpeg')) ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled'); ?></td>
		<td></td>
	</tr>
	<tr>
		<td>GD imagepng</td>
		<td><?php echo (is_callable('imagepng')) ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled'); ?></td>
		<td></td>
	</tr>
	<tr>
		<td>GD imagegif</td>
		<td><?php echo (is_callable('imagegif')) ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled'); ?></td>
		<td></td>
	</tr>
	<tr>
		<td>EXIF</td>
		<td><?php echo (is_callable('exif_read_data')) ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled'); ?></td>
		<td></td>
	</tr>
	<tr>
		<td><?php echo elgg_echo('tidypics:server_info:use_only_cookies'); ?></td>
		<td><?php echo (ini_get('session.use_only_cookies')) ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled'); ?></td>
		<td><?php echo elgg_echo('tidypics:server_info:use_only_cookies_desc'); ?></td>
	</tr>
</table>

<?php

$content = ob_get_clean();

echo elgg_view_module('inline', elgg_echo('tidypics:server_info'), $content);