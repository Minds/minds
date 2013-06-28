<?php
/**
 * Elgg log rotator language pack.
 *
 * @package ElggLogRotate
 */

$german = array(
	'logrotate:period' => 'Wie oft sollen die Einträge im Systemlog archiviert werden?',

	'logrotate:weekly' => 'Einmal pro Woche',
	'logrotate:monthly' => 'Einmal pro Monat',
	'logrotate:yearly' => 'Einmal pro Jahr',

	'logrotate:logrotated' => "Die alten Einträge in der Logdatei wurden archiviert.\n",
	'logrotate:lognotrotated' => "Beim Archivieren der alten Einträge in der Logdatei ist ein Fehler aufgetreten.\n",

	'logrotate:delete' => 'Löschen von archivierten Logs älter als',

	'logrotate:week' => 'eine Woche',
	'logrotate:month' => 'einen Monat',
	'logrotate:year' => 'ein Jahr',

	'logrotate:logdeleted' => "Das Log wurde gelöscht.\n",
	'logrotate:lognotdeleted' => "Beim Löschen des Logs ist ein Fehler aufgetreten.\n",
);

add_translation("de", $german);