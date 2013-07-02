<?php
/**
 * Blog French language file.
 * Elgg V1.8.1, traduction complétée par Jean-Baptiste Duclos
 */

$french = array(
	'blog' => "Blogs",
	'blog:blogs' => "Blogs",
	'blog:revisions' => "Révisions",
	'blog:archives' => "Archives",
	'blog:blog' => "Blog",
	'item:object:blog' => "Blogs",

	'blog:title:user_blogs' => "Blog de %s",
	'blog:title:all_blogs' => "Tous les blogs du site",
	'blog:title:friends' => "Blogs des contacts",

	'blog:group' => "Blog de groupe",
	'blog:enableblog' => "Activer le blog du groupe",
	'blog:write' => "Écrivez un article",

	// Editing
	'blog:add' => "Ajouter un article",
	'blog:edit' => "Modifier l'article",
	'blog:excerpt' => "Extrait",
	'blog:body' => "Corps de l'article",
	'blog:save_status' => "Dernier enregistrement :",
	'blog:never' => "jamais",

	// Statuses
	'blog:status' => "Statut",
	'blog:status:draft' => "Brouillon",
	'blog:status:published' => "Publié",
	'blog:status:unsaved_draft' => "Brouillon non enregistré",

	'blog:revision' => "Révision",
	'blog:auto_saved_revision' => "Révision enregistrée automatiquement",

	// messages
	'blog:message:saved' => "Article enregistré.",
	'blog:error:cannot_save' => ":Impossible d'enregistrer l'article.",
	'blog:error:cannot_write_to_container' => "Accès insuffisant pour enregistrer l'article pour ce groupe.",
	'blog:messages:warning:draft' => "Il y a un brouillon non enregistré pour cet article !",
	'blog:edit_revision_notice' => "(Ancienne version)",
	'blog:message:deleted_post' => "Article supprimé.",
	'blog:error:cannot_delete_post' => "Impossible de supprimer l'article.",
	'blog:none' => "Aucun article",
	'blog:error:missing:title' => "Vous devez donner un titre à votre article !",
	'blog:error:missing:description' => "Le corps de votre article est vide !",
	'blog:error:cannot_edit_post' => "Cet article peut ne pas exister ou vous n'ayez pas les autorisations pour le modifier.",
	'blog:error:revision_not_found' => "Impossible de trouvez cette révision.",

	// river
	'river:create:object:blog' => "%s a publié un article de blog %s",
	'river:comment:object:blog' => "%s a commenté le blog de %s",

	// notifications
	'blog:newpost' => "Nouvel envoie sur blog",
	'blog:notification' =>
"
%s a fait un nouvel envoie sur le blog.

%s
%s

Voir et commenter ce nouvel envoie sur le blog :
%s
",
	
	// widget
	'blog:widget:description' => "Ce widget affiche vos derniers articles de blog.",
	'blog:moreblogs' => "Plus d'articles du blog",
	'blog:numbertodisplay' => "Nombre d'articles du blog à afficher",
	'blog:noblogs' => "Aucun blog",
	
	// scraper
	'blog:minds:scraper' => 'RSS Outil de miroir',
	'blog:minds:scraper:create' => 'Créer un nouveau RSS Mirror',
	'blog:minds:scraper:menu' => 'Gérer Miroirs RSS',
	'blog:minds:scraper:name' => 'Nom',
	'blog:minds:scraper:url' => 'URL de flux rss',
);

add_translation("fr", $french);
