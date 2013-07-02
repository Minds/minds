<?php
/**
 * Elgg developer tools English language file.
 *
 */

$french = array(
	// menu
	'admin:develop_tools' => "Outils",
	'admin:develop_tools:preview' => "Bac à sable thème",
	'admin:develop_tools:inspect' => "Inspecter",
	'admin:develop_tools:unit_tests' => "Tests unitaires",
	'admin:developers' => "Les développeurs",
	'admin:developers:settings' => "Paramètres développeurs",

	// settings
	'elgg_dev_tools:settings:explanation' => "Pour vos dévelopements et déboguage, contrôlez les paramètres ci-dessous. Certains de ces paramètres sont aussi disponibles sur d'autres pages adminitrations.",
	'developers:label:simple_cache' => "Utiliser le cache simple",
	'developers:help:simple_cache' => "Désactiver le fichier cache lors du développement. Autrement, les changements que vous verrez (y compris les css) seront ignorés.",
	'developers:label:view_path_cache' => "Utiliser voir le chemin du cache",
	'developers:help:view_path_cache' => "Désactivez cette option lors du développement. Autrement, les nouveaux aspects de vos plugins ne seront pas enregistrés.",
	'developers:label:debug_level' => "Niveau de suivi des traces",
	'developers:help:debug_level' => "Contrôle la quantité d'informations enregistrées. Voir elgg_log() pour plus d'informations.",
	'developers:label:display_errors' => "Affichage des erreurs PHP fatales",
	'developers:help:display_errors' => "Par défaut, le fichier .htaccess d'Elgg supprime l'affichage des erreurs fatales.",
	'developers:label:screen_log' => "Journal à l'écran",
	'developers:help:screen_log' => "Affiche les sorties elgg_log() et elgg_dump() sur la page web.",
	'developers:label:show_strings' => "Montrer les chaines de traduction Brutes",
	'developers:help:show_strings' => "Affiche les chaines de traduction utilisées par elgg_echo().",
	'developers:label:wrap_views' => "Vues contractées",
	'developers:help:wrap_views' => "Cela associe toutes les vues avec des commentaires HTML. Utile pour trouver la vue créant un fichier HTML particulier. (traduction à revoir, chaine originale : This wraps almost every view with HTML comments. Useful for finding the view creating particular HTML.)",
	'developers:label:log_events' => "Journaux des évènements et interceptions plugins.",
	'developers:help:log_events' => "Ecrire les évènements et les interceptions plugins dans le journal. Attention : il y a en beaucoup par page.",

	'developers:debug:off' => "Arrêt",
	'developers:debug:error' => "Erreur",
	'developers:debug:warning' => "Avertissement",
	'developers:debug:notice' => "Avis",

	// inspection
	'developers:inspect:help' => "Inspecter la configuration système d'Elgg.",

	// event logging
	'developers:event_log_msg' => "%s : '%s, %s' dans %s",

	// theme preview
	'theme_preview:general' => "Introduction",
	'theme_preview:breakout' => "Sortir de l'iframe",
	'theme_preview:buttons' => "Boutons",
	'theme_preview:components' => "Composants",
	'theme_preview:forms' => "Formulaires",
	'theme_preview:grid' => "Grille",
	'theme_preview:icons' => "Icônes",
	'theme_preview:modules' => "Modules",
	'theme_preview:navigation' => "Navigation",
	'theme_preview:typography' => "Typographie",
	'theme_preview:miscellaneous' => "Divers",
  
	// unit tests
	'developers:unit_tests:description' => "Elgg a des tests unitaires et d'intégration pour détecter des bugs dans les classes et fonctions de son coeur.",
	'developers:unit_tests:warning' => "Attention : Ne pas exécuter ces tests sur un site en Production. Ils peuvent corrompre votre base de données.",
	'developers:unit_tests:run' => "Exécuter",

	// status messages
	'developers:settings:success' => "Paramètres sauvegardés",
);

add_translation("fr", $french);