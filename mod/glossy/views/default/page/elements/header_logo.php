<?php
/**
 * Elgg header logo
 * The logo to display in elgg-header.
 */

$site = elgg_get_site_entity();
$site_name = $site->name;
?>

<h1>
<center>
	<a class="elgg-heading-site" href="<?php echo elgg_get_site_url(); ?>"> <img src="<?php echo elgg_get_site_url(); ?>mod/glossy/images/header.jpg"></a>
	</center>
</h1>
