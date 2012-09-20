<?php
/**
 * Request a withdrawel
 *
 * @package Pay
 */

elgg_load_library('elgg:pay');

$content = elgg_view_form('pay/withdraw');


echo $content;
//echo elgg_view_page($title, $body);
