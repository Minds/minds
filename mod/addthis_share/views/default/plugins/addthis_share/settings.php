<div>
	<?php
	echo'<p>Enter AddThis profile ID:<br><em>You can get this from http://www.addthis.com (login to AddThis and find under Settings > Profiles)</em></p>';
	
	echo elgg_view('input/text', array(
	'name' => 'params[profileID]',
	'value' => $vars['entity']->profileID,
));

	?>
</div>


<div>
    <?php
    echo '<p>How do you like to display the set of share buttons?</p>';

    echo elgg_view('input/dropdown', array(
        'name' => 'params[alignposition]',
		'options_values' => array(
						'left' => elgg_echo('Align Left'),
						'right' => elgg_echo('Align Right')),
        'value' => $vars['entity']->alignposition,
    ));

    ?>
</div>

<div>
    <?php
    echo '<p>Which style of share buttons do you want to use?</p>';

    echo elgg_view('input/dropdown', array(
        'name' => 'params[buttonstyle]',
		'options_values' => array(
						'standard' => elgg_echo('Standard Buttons Style'),
						'small' => elgg_echo('Small Share Buttons Style'),
						'big' => elgg_echo('Big Share Buttons Style')),
        'value' => $vars['entity']->buttonstyle,
    ));
	
		$styleimg_url = elgg_get_site_url() . "/mod/addthis_share/_graphics/style.jpg";
		echo '<br /><br /><p><img src="'.$styleimg_url .'" alt="Button Styles" width="202" height="115" /></p>';

    ?>
</div>

<div>
	<?php
	echo '<p>This plugin is provided by <a href="http://www.colourscripts.com" target="_blank">ColourScript</a>. For more plugins, web applications or customisation needs, please contact us.</p>';
	?>
</div>	