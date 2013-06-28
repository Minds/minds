<?php
	/**
	 * Elgg diagnostics language pack.
	 *
	 * @package ElggDiagnostics
	 */

	$french = array(
			'admin:develop_utilities:diagnostics' => "Diagnostic du système",
			'diagnostics' => "Diagnostics du système",
			'diagnostics:report' => "Rapport de Diagnostic",
			'diagnostics:description' => "Le rapport de diagnostic suivant est utile pour diagnostiquer tout problème avec Elgg, et devrait être inclus dans tout rapport d'erreur que vous rapportez.",
			'diagnostics:download' => "Télécharger le fichier '.txt'",
			'diagnostics:header' => "========================================================================
Rapport du diagnostic d'Elgg
Généré %s par %s
========================================================================

",
			'diagnostics:report:basic' => "
Elgg Révision %s, version %s

------------------------------------------------------------------------",
			'diagnostics:report:php' => "
PHP info :
%s
------------------------------------------------------------------------",
			'diagnostics:report:plugins' => "
Plugins installés et détails:

%s
------------------------------------------------------------------------",
			'diagnostics:report:md5' => "
Fichiers installés et somme de contrôles :

%s
------------------------------------------------------------------------",
			'diagnostics:report:globals' => "
Variables globales :

%s
------------------------------------------------------------------------",
);

add_translation("fr", $french);
