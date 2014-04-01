<?php
/**
 * orientation menu view
 */

$steps = $vars['steps'];
$current = $vars['step'];

$site_url = elgg_get_site_url();
?>
<ul>
	<?php foreach($steps as $step){
		$class = "$step";
		if($step == $current)
			$class .= " active";
		
		echo "<li class=\"$class\"><a href=\"{$site_url}register/orientation/$step\" >". elgg_echo('orientation:'.$step) . "</a></li>";
	}?>
</ul>
