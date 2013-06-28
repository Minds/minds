<?php
/**
 * Elgg developer tools Danish language file.
 *
 */

$danish = array(
	// menu
	'admin:developers' => 'Udviklere',
	'admin:developers:settings' => 'Udvikler indstillinger',
	'admin:developers:preview' => 'Tema eksempel',
	'admin:developers:inspect' => 'Inspicér',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Kontrollér dine indstillinger for udvikling og fejlfinding nedenfor. Nogle af disse indstillinger er også tilgængelige på andre admin sider.',
	'developers:label:simple_cache' => 'Brug simpel cache',
	'developers:help:simple_cache' => 'Fravælg fil cache, når du udvikler. Ellers vil dine ændringer (herunder CSS) blive ignoreret.',
	'developers:label:view_path_cache' => 'Brug view path cache',
	'developers:help:view_path_cache' => 'Slå dette fra, under udviklingen. Ellers vil nye views i din plugin ikke blive registreret.',
	'developers:label:debug_level' => "Sporings niveau",
	'developers:help:debug_level' => "Dette kontrollerer mængden af ​​loggede oplysninger. Se elgg_log() for flere oplysninger.",
	'developers:label:display_errors' => 'Vis fatale PHP fejl',
	'developers:help:display_errors' => "Som standard, udelader Elgg's .htaccess fil visning af fatale fejl.",
	'developers:label:screen_log' => "Log til skærmen",
	'developers:help:screen_log' => "Dette viser elgg_log() og elgg_dump() output på websiden.",
	'developers:label:show_strings' => "Vis rå oversættelses strenge",
	'developers:help:show_strings' => "Dette viser oversættelses strenge brugt af elgg_echo().",
	'developers:label:wrap_views' => "Wrap views",
	'developers:help:wrap_views' => "Dette wrapper næsten alle views med HTML kommentarer. Nyttigt til at finde et view, der opretter noget bestemt HTML.",
	'developers:label:log_events' => "Log events og plugin hooks",
	'developers:help:log_events' => "Skriver events og plugin hooks til loggen. Advarsel: Der er mange af disse per side.",

	'developers:debug:off' => 'Fra',
	'developers:debug:error' => 'Fejl',
	'developers:debug:warning' => 'Advarsel',
	'developers:debug:notice' => 'Bemærk',
	
	// inspection
	'developers:inspect:help' => 'Inspicér konfiguration af Elgg\'s  framework.',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' i %s",

	// theme preview
	'theme_preview:general' => 'Introduktion',
	'theme_preview:breakout' => 'Hop ud af iframe',
	'theme_preview:buttons' => 'Knapper',
	'theme_preview:components' => 'Komponenter',
	'theme_preview:forms' => 'Formularer',
	'theme_preview:grid' => 'Grid',
	'theme_preview:icons' => 'Ikoner',
	'theme_preview:modules' => 'Moduler',
	'theme_preview:navigation' => 'Navigation',
	'theme_preview:typography' => 'Typografi',

	// status messages
	'developers:settings:success' => 'Indstillinger gemt',
);

add_translation('da',$danish);

?>