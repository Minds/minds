<?php
/**
 * Elgg log rotator language pack.
 *
 * @package ElggLogRotate
 */

$french = array(
	'logrotate:period' => "A quelle fréquence souhaitez-vous archiver les logs du système ?",

	'logrotate:weekly' => "Une fois par semaine",
	'logrotate:monthly' => "Une fois par mois",
	'logrotate:yearly' => "Une fois par an",

	'logrotate:logrotated' => "Rotation du log effectuée\n",
	'logrotate:lognotrotated' => "Erreur lors de la rotation du log\n",
	
	'logrotate:delete' => "Supprimer les journaux archivés plus ancien qu'",

	'logrotate:week' => "une semaine",
	'logrotate:month' => "un mois",
	'logrotate:year' => "une année",
		
	'logrotate:logdeleted' => "Fichier journal supprimé (fichier log)",
	'logrotate:lognotdeleted' => "Erreur de suppression du journal (fichier log)",
);

add_translation("fr", $french);
