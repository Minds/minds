<?php
/*
 * Satheesh PM, BARC Mumbai
 * www.satheesh.anushaktinagar.net
 * 
 */

echo '<div align="justify">'.elgg_echo('Ads:information').'</div>';

echo '<div align="justify">'.elgg_echo('Ads:header').'  :  ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[showads_header]',
	'options_values' => array(
		'no' => elgg_echo('Ads:no'),
		'yes' => elgg_echo('Ads:yes')
	),
	'value' => $vars['entity']->showads_header,
        ));
echo '<br />'.elgg_echo('Ads:header:view').'  :  ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[view_ads_header]',
	'options_values' => array(
		'featured' => elgg_echo('Ads:featured'),
		'info' => elgg_echo('Ads:info'),
                'popup' => elgg_echo('Ads:popup'),
                'aside' => elgg_echo('Ads:aside')
	),
	'value' => $vars['entity']->view_ads_header,
        ));
echo '<br />'.elgg_echo('Ads:header:ads').'<br />';
echo elgg_view('input/plaintext', array(
        'name' => 'params[ads_header]',
        'value' => $vars['entity']->ads_header
        ));
echo '</div>';



echo '<div align="justify">'.elgg_echo('Ads:sidebar').'  :  ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[showads_sidebar]]',
	'options_values' => array(
		'no' => elgg_echo('Ads:no'),
		'yes' => elgg_echo('Ads:yes')
	),
	'value' => $vars['entity']->showads_sidebar,
        ));
echo '<br />'.elgg_echo('Ads:sidebar:view').'  :  ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[view_ads_sidebar]',
	'options_values' => array(
		'featured' => elgg_echo('Ads:featured'),
		'info' => elgg_echo('Ads:info'),
                'popup' => elgg_echo('Ads:popup'),
                'aside' => elgg_echo('Ads:aside')
	),
	'value' => $vars['entity']->view_ads_sidebar,
        ));
echo '<br />'.elgg_echo('Ads:sidebar:ads').'<br />';
echo elgg_view('input/plaintext', array(
        'name' => 'params[ads_sidebar]',
        'value' => $vars['entity']->ads_sidebar,
        ));
echo '</div>';


echo '<div align="justify">'.elgg_echo('Ads:footer').'  :  ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[showads_footer]]',
	'options_values' => array(
		'no' => elgg_echo('Ads:no'),
		'yes' => elgg_echo('Ads:yes')
	),
	'value' => $vars['entity']->showads_footer,
));
/*
echo '<br />'.elgg_echo('Ads:footer:view').'  :  ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[view_ads_footer]',
	'options_values' => array(
		'featured' => elgg_echo('Ads:featured'),
		'info' => elgg_echo('Ads:info'),
                'popup' => elgg_echo('Ads:popup'),
                'aside' => elgg_echo('Ads:aside')
	),
	'value' => $vars['entity']->view_ads_footer,
        ));
 */
echo '<br />'.elgg_echo('Ads:footer:ads').'<br />';
echo elgg_view('input/plaintext', array(
        'name' => 'params[ads_footer]',
        'value' => $vars['entity']->ads_footer,
        ));
echo '</div>';

echo '<div align="justify">'.elgg_echo('Ads:controls').'</div>';

echo '<div align="justify">'.elgg_echo('Ads:support').'</div>';

?>
