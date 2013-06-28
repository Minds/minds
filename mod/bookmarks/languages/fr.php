<?php
/**
 * Bookmarks English language file
 */

$french = array(

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "Signets",
	'bookmarks:add' => "Mettre quelque chose en signet",
	'bookmarks:edit' => "Modifier le signet",
	'bookmarks:owner' => "Les signets de %s",
	'bookmarks:friends' => "Signets des contacts",
	'bookmarks:everyone' => "Tous les signets du site",
	'bookmarks:this' => "Mettre en signet cette page",
	'bookmarks:this:group' => "Mettre en signet dans %s",
	'bookmarks:bookmarklet' => "Récupérer le 'bookmarklet'",
	'bookmarks:bookmarklet:group' => "Récupérer le 'bookmarklet' du groupe",
	'bookmarks:inbox' => "Boîte de réception des signets",
	'bookmarks:morebookmarks' => "",
	'bookmarks:more' => "Plus de signets",
	'bookmarks:with' => "Partager avec",
	'bookmarks:new' => "Un nouveau signet",
	'bookmarks:address' => "Adresse de la ressource à ajouter à vos signets",
	'bookmarks:none' => "Aucun signets",

	'bookmarks:notification' =>
"%s a ajouté un nouveau signet :

%s - %s
%s

Voir et commenter ce nouveau signets:
%s
",

	'bookmarks:delete:confirm' => "Etes-vous sûr(e) de vouloir supprimer cette ressource ?",

	'bookmarks:numbertodisplay' => "Nombre de signets à afficher",

	'bookmarks:shared' => "Mis en signet",
	'bookmarks:visit' => "Voir la ressource",
	'bookmarks:recent' => "Signets récents",

	'river:create:object:bookmarks' => "%s mis en signet %s",
	'river:comment:object:bookmarks' => "%s a commenté le signet %s",
	'bookmarks:river:annotate' => "a posté un commentaire sur ce signet",
	'bookmarks:river:item' => "un élément",

	'item:object:bookmarks' => "Eléments mis en signets",

	'bookmarks:group' => "Signets du groupe",
	'bookmarks:enablebookmarks' => "Activer les signets du groupe",
	'bookmarks:nogroup' => "Ce groupe n'a pas encore de signets",
	'bookmarks:more' => "Plus de signets",

	'bookmarks:no_title' => "Pas de titre",

	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Ce widget affiche vos derniers signets.",

	'bookmarks:bookmarklet:description' =>
			"Le bookmarklet vous permez de partager ce que vous trouvez sur le web avec vos contact, ou pour vous-même. Pour l'utiliser, glissez simplement le bouton ci-dessous dans votre barre de liens de votre navigateur.",

	'bookmarks:bookmarklet:descriptionie' =>
			"Si vous utilisez Internet Explorer, faites un clic droit sur le bouton et ajouter le dans vos favoris, puis votre barre de liens.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"Vous pouvez mettre en signet n'importe quelle page en cliquant sur le bookmarklet.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Votre élément a bien été mis en signet.",
	'bookmarks:delete:success' => "Votre signet a bien été supprimé.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Votre élément n'a pu être correctement mis en signet. Vérifiez que le titre et le lien soient correct et réessayez.",
	'bookmarks:save:invalid' => "L'addresse du signet est invalide et ne peut être sauvé.",
	'bookmarks:delete:failed' => "Votre signet n'a pu être supprimé. Merci de réessayer.",
);

add_translation("fr", $french);
