<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

$translations = array(
	'minds:archive' => 'Archive',
	
	/**
	 * Navigation
	 */
	'minds:archive:upload' => 'Téléchargez',
	'minds:archive:all' => 'Tout le contenu',
	'minds:archive:featured' => 'teneur en vedette',
	'minds:archive:top' => 'Haut contenu',
	'minds:archive:mine' => 'Mon contenu',
	'minds:archive:network' => 'Le contenu de mon réseau',
	'minds:archive:owner' => '%s\'s contenu',
	'minds:archive:owner:network' => '%s\'s le contenu du réseau',
	
	'minds:archive:upload:videoaudio' => 'Video & Audio',
	
	'minds:archive:file:replace' => 'Remplacer le fichier',
	
	'minds:archive:download' => 'télécharger',
	
	'minds:archive:upload:videoaudio' => 'Téléchargez Video/Audio',
	'minds:archive:album:create' => 'Créer un album',
	'minds:archive:upload:others' => 'Télécharger des images, des fichiers + plus',

	'minds:archive:delete:success' => 'Le fichier a été supprimé de votre archive',
	'minds:archive:delete:error' => "Il y avait un problème. Nous ne pouvons pas supprimer ce fichier pour l'instant",

	
	//title of the menu, put whatever you want, for example 'Kaltura videos'
	'archive' => "Archives",
	
	 
	/*
	 * Archive Menus
	 */
	'archive:all' => 'Archives: Tous',
	'archive:owner' => 'Archives: %s',
	'archive:top' => 'Archives: Supérieur',
	'archive:network' => 'Archives: Réseau',
	
	'archive:upload:videoaudio' => 'Video & Audio',
	'archive:upload:others' => 'Images & Fichiers',
	
	/*
	 * Archive featured, sponsored& trending/popular
	 */
	'archive:popular:title' => 'Populaire',
	'archive:featured:title' => 'Sélection',
	'archive:featured:action' => 'Caractéristique',
	'archive:featured:un-action' => 'Un-fonction',
	'archive:morefromuser:title' => "plus d' %s",
	
	'archive:monetized:action' => 'Monétiser',
	'archive:monetized:un-action' => 'Un-monétiser',
	
	'archive:owner_tag' => 'par ',

	/*
	 * Other strings
	 */
	'archive:close' => 'Fermer',
);

add_translation("es", $translations);

?>
