<?php
/**
 * Install header
 */

$url = elgg_get_site_url()."_graphics/minds_washout_flat.png";

if (elgg_get_plugin_setting('logo_override', 'minds_themeconfig')) {
    $url = elgg_get_site_url() . 'themeicons/logo_topbar/' .elgg_get_plugin_setting('logo_override_ts', 'minds_themeconfig') . '.png';
}
?>
<img src="<?php echo $url; ?>" alt="Minds" />
<span class="experiment">Experimental</span>
