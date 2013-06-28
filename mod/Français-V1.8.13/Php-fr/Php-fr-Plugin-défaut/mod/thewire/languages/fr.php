<?php
/**
 * The Wire English language file
 */

$french = array(

	/**
	 * Menu items and titles
	 */
	'thewire' => "Microblog",
	'thewire:everyone' => "Tous les messages du microblog",
	'thewire:user' => "Le microblog de %s",
	'thewire:friends' => "Messages des contacts sur le microblog",
	'thewire:reply' => "Répondre",
	'thewire:replying' => "Répondre à %s, qui a écrit",
	'thewire:thread' => "Flux",
	'thewire:charleft' => "caractères restant",
	'thewire:tags' => "Messages du microblog commentés par '% s' avec",
	'thewire:noposts' => "Pas encore de messages sur le microblog",
	'item:object:thewire' => "Messages du microblog",
	'thewire:update' => "Mise à jour",
	'thewire:by' => "Message sur le microblog par %s",
	
	'thewire:previous' => "Précédent",
	'thewire:hide' => "Masquer",
	'thewire:previous:help' => "Voir le message précédent",
	'thewire:hide:help' => "Masquer le message précédent",

	/**
	 * The wire river
	 */
	'river:create:object:thewire' => "%s a envoyé un message à %s",
	'thewire:wire' => "microblog",

	/**
	 * Wire widget
	 */
	'thewire:widget:desc' => "Affichez vos derniers messages du microblog",
	'thewire:num' => "Nombre de publications à afficher",
	'thewire:moreposts' => "Plus de messages du microblog",

	/**
	 * Status messages
	 */
	'thewire:posted' => "Votre message a bien été posté sur le microblog.",
	'thewire:deleted' => "Votre message a bien été supprimé du microblog.",
	'thewire:blank' => "Désolé, vous devez d'abord écrire un message avant de l'envoyer.",
	'thewire:notfound' => "Désolé, le message spécifié n'a pu être trouvé.",
	'thewire:notdeleted' => "Désolé, ce message n'a pu être effacé du microblog.",

	/**
	 * Notifications
	 */
	'thewire:notify:subject' => "Nouveau message sur le microblog",
	'thewire:notify:reply' => "%s a répondu à %s sur le microblog :",
	'thewire:notify:post' => "%s posté sur le microblog:",

);

add_translation("fr", $french);