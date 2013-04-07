<?php
/**
 * Minds Tools Setting - for adsense ID ATM
 */
 ?>
<p>Want a share of the revenue? Paste your ad block below (we recomend 200px x 200px). We'll give you one block on each of your blogs.</p>
<?php echo elgg_view('input/plaintext', array('name'=>'params[adblock]', 'value'=>elgg_get_plugin_user_setting('adblock', elgg_get_page_owner_guid(), 'minds'))); 

?>
