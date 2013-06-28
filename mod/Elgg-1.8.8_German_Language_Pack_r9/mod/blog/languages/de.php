<?php
/**
 * Blog German language file.
 *
 */

$german = array(
	'blog' => 'Blogs',
	'blog:blogs' => 'Blogs',
	'blog:revisions' => 'Revisionen',
	'blog:archives' => 'Ältere Blogs',
	'blog:blog' => 'Blog',
	'item:object:blog' => 'Blogs',

	'blog:title:user_blogs' => 'Blogs von %s',
	'blog:title:all_blogs' => 'Alle Blogs der Community',
	'blog:title:friends' => 'Blogs Deiner Freunde',

	'blog:group' => 'Gruppen-Blog',
	'blog:enableblog' => 'Gruppen-Blog aktivieren',
	'blog:write' => 'Einen Blog-Eintrag verfassen',

	// Editing
	'blog:add' => 'Blog-Eintrag verfassen',
	'blog:edit' => 'Blog-Eintrag editieren',
	'blog:excerpt' => 'Auszug',
	'blog:body' => 'Blogtext',
	'blog:save_status' => 'Zuletzt gespeichert: ',
	'blog:never' => 'Nie',

	// Statuses
	'blog:status' => 'Status',
	'blog:status:draft' => 'Entwurf',
	'blog:status:published' => 'Veröffentlicht',
	'blog:status:unsaved_draft' => 'Nicht-gespeicherter Entwurf',

	'blog:revision' => 'Revision',
	'blog:auto_saved_revision' => 'Automatisch gespeicherte Revision',

	// messages
	'blog:message:saved' => 'Dein Blog-Eintrag wurde gespeichert.',
	'blog:error:cannot_save' => 'Dein Blog-Eintrag konnte nicht gespeichert werden.',
	'blog:error:cannot_write_to_container' => 'Keine ausreichenden Zugriffsrechte zum Speichern des Blog-Eintrags im Gruppenblog vorhanden.',
	'blog:messages:warning:draft' => 'Die Entwurfsversion dieses Eintrags wurde nocht nicht gespeichert!',
	'blog:edit_revision_notice' => '(Alte Revision)',
	'blog:message:deleted_post' => 'Dein Blog-Eintrag wurde gelöscht.',
	'blog:error:cannot_delete_post' => 'Der Blog-Eintrag konnte nicht gelöscht werden.',
	'blog:none' => 'Keine Blog-Einträge vorhanden.',
	'blog:error:missing:title' => 'Bitte einen Titel für Deinen Blog-Eintrag angeben!',
	'blog:error:missing:description' => 'Bitte gebe den Text Deines Blog-Eintrages ein!',
	'blog:error:cannot_edit_post' => 'Dieser Blog-Eintrag scheint nicht vorhanden zu sein oder Du hast möglicherweise nicht die notwendigen Zugriffrechte, um ihn zu editieren.',
	'blog:error:revision_not_found' => 'Diese Revision ist nicht verfügbar.',

	// river
	'river:create:object:blog' => '%s veröffentlichte den Blog-Eintrag %s',
	'river:comment:object:blog' => '%s kommentierte den Blog-Eintrag %s',

	// notifications
	'blog:newpost' => 'Ein neuer Blog-Eintrag',
        'blog:notification' =>
'
%s hat einen neuen Blog-Eintrag erstellt.

%s
%s

View and comment on the new blog post:
%s
',

	// widget
	'blog:widget:description' => 'Dieses Widget zeigt Deine neuesten Blogs an.',
	'blog:moreblogs' => 'Weitere Blog-Einträge',
	'blog:numbertodisplay' => 'Anzahl der anzuzeigenden Blog-Einträge',
	'blog:noblogs' => 'Keine Blog-Einträge vorhanden.'
);

add_translation('de', $german);