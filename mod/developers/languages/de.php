<?php
/**
 * Elgg developer tools German language file.
 *
 */

$german = array(
	// menu
	'admin:develop_tools' => 'Entwickler-Werkzeuge',
        'admin:develop_tools:preview' => 'Theming-Sandbox',
        'admin:develop_tools:inspect' => 'Prüfen',
        'admin:develop_tools:unit_tests' => 'Modultests',
        'admin:developers' => 'Entwickler',
        'admin:developers:settings' => 'Einstellungen',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Prüfe die untenstehenden Entwicklungs- und Debug-Einstellungen. Einige dieser Einstellungen sind auch auf anderen Admin-Seiten verfügbar.',
	'developers:label:simple_cache' => 'Simple-Cache aktivieren',
	'developers:help:simple_cache' => 'Deaktiviere den Simple-Cache während Entwicklungstests. Andernfalls werden Code-Änderungen an Views (inklusive CSS und Javascript) nicht unmittelbar sichtbar sein.',
        'developers:label:system_cache' => 'Systemcache aktivieren',
        'developers:help:system_cache' => 'Deaktiviere den Systemcache während Entwicklungstests. Andernfalls werden neue Views in Deinen Plugins nicht unmittelbar registriert werden.',
	'developers:label:debug_level' => "Fehlerprotokoll-Level",
	'developers:help:debug_level' => "Diese Einstellung legt fest, welche Details protokolliert werden. Siehe elgg_log() für mehr Informationen.",
	'developers:label:display_errors' => 'Fatal PHP Errors anzeigen',
	'developers:help:display_errors' => "Standardmäßig unterdrückt die .htaccess-Datei von Elgg die Anzeige von Fatal Errors.",
	'developers:label:screen_log' => "Protokolleinträge auf dem Bildschirm ausgeben",
	'developers:help:screen_log' => "Diese Einstellung legt fest, ob die Ausgaben von elgg_log() und elgg_dump() im Browserfenster ausgegeben werden.",
	'developers:label:show_strings' => "Sprach-Strings im Rohformat anzeigen",
	'developers:help:show_strings' => "Diese Einstellung legt fest, ob die von elgg_echo() verwendeten Sprach-Strings angezeigt werden.",
	'developers:label:wrap_views' => "Views einkapseln",
	'developers:help:wrap_views' => "Diese Einstellung aktiviert die Einkapselung fast aller Views in HTML-Kommentare. Dies kann hilfreich sein, um den erzeugten HTML-Code einer View zuzuordnen.",
	'developers:label:log_events' => "Events und Plugin Hooks protokollieren",
	'developers:help:log_events' => "Einträge für Events und Plugin Hooks ins Log schreiben. Warnung: es gibt sehr viele davon bei jedem Seitenaufruf.",

	'developers:debug:off' => 'Aus',
	'developers:debug:error' => 'Fehler',
	'developers:debug:warning' => 'Warnung',
	'developers:debug:notice' => 'Anmerkung',

	// inspection
	'developers:inspect:help' => 'Konfiguration des Elgg-Frameworks prüfen.',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' in %s",

	// theme preview
	'theme_preview:general' => 'Einführung',
	'theme_preview:breakout' => 'Theme-Preview in ganzen Browserfenster anzeigen',
	'theme_preview:buttons' => 'Knöpfe',
	'theme_preview:components' => 'Komponenten',
	'theme_preview:forms' => 'Forms',
	'theme_preview:grid' => 'Grid',
	'theme_preview:icons' => 'Icons',
	'theme_preview:modules' => 'Module',
	'theme_preview:navigation' => 'Navigation',
	'theme_preview:typography' => 'Typographie',

        // unit tests
        'developers:unit_tests:description' => 'Elgg enthält Modultests und Integrationstests, um mögliche Fehler in seinen Klassen und Funktionen zu finden.',
        'developers:unit_tests:warning' => 'Warnung: Führe diese Tests niemals auf Deiner Hauptinstallation aus. Sie können Deine Datenbank beschädigen!',
        'developers:unit_tests:run' => 'Ausführen',

	// status messages
	'developers:settings:success' => 'Einstellungen gespeichert',
);

add_translation('de', $german);