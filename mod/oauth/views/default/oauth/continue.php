<?php

echo '<p>' . sprintf(elgg_echo('oauth:authorize:continue'), $vars['consumer']->name) . '</p>';
if ($vars['verifier']) {
	echo '<p>' . elgg_echo('oauth:authorize:verifier') . '</p>';
	echo '<h2>' . $vars['verifier'] . '</h2>';
}

