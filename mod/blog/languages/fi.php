<?php
/**
 * Blog Finnish language file.
 *
 */

$finnish = array(
	'blog' => 'Blogit',
	'blog:blogs' => 'Blogit',
	'blog:revisions' => 'Revisionit',
	'blog:archives' => 'Arkistot',
	'blog:blog' => 'Blogi',
	'item:object:blog' => 'Blogit',

	'blog:title:user_blogs' => '%s blogit',
	'blog:title:all_blogs' => 'Kaikki sivuston blogit',
	'blog:title:friends' => 'Ystävien\' blogit',

	'blog:group' => 'Ryhmäblogit',
	'blog:enableblog' => 'Salli Ryhmäblogit',
	'blog:write' => 'Kirjoita blogi',

	// Editing
	'blog:add' => 'Lisää blogiviesti',
	'blog:edit' => 'Muokkaa blogiviestiä',
	'blog:excerpt' => 'Poiminto',
	'blog:body' => 'Runko',
	'blog:save_status' => 'Viimeinen tallennettu: ',
	'blog:never' => 'Ei koskaan',

	// Statuses
	'blog:status' => 'Status',
	'blog:status:draft' => 'Luonnos',
	'blog:status:published' => 'Julkaistu',
	'blog:status:unsaved_draft' => 'Tallentamaton luonnos',

	'blog:revision' => 'Revisioni',
	'blog:auto_saved_revision' => 'Automaattisesti tallennettu Revisioni',

	// messages
	'blog:message:saved' => 'Blogiviesti tallennettu.',
	'blog:error:cannot_save' => 'Blogiviestiä ei voi tallentaa.',
	'blog:error:cannot_write_to_container' => 'Ei tarpeeksi oikeuksia tallentaaksesi ryhmäblogiin.',
	'blog:error:post_not_found' => 'Tämä viesti on poistettu, ei kelpaa, tai sinulla ei ole lupaa nähdä sitä.',
	'blog:messages:warning:draft' => 'Tästä viestistä on tallentamaton luonnos!',
	'blog:edit_revision_notice' => '(Vanha versio)',
	'blog:message:deleted_post' => 'Blogiviesti poistettu.',
	'blog:error:cannot_delete_post' => 'Blogiviestiä ei voida poistaa.',
	'blog:none' => 'Ei blogiviestiä',
	'blog:error:missing:title' => 'Anna blogille otsikko!',
	'blog:error:missing:description' => 'Kirjoita blogillesi runko!',
	'blog:error:cannot_edit_post' => 'Tätä viestiä ei ole olemassa tai sinulla ei ole lupaa muokata sitä.',
	'blog:error:revision_not_found' => 'Ei voi löytää tätä revisionia.',

	// river
	'river:create:object:blog' => '%s julkaisi blogiviestin %s',
	'river:comment:object:blog' => '%s kommentoi blogissa %s',

	// notifications
	'blog:newpost' => 'Uusi blogiviesti',

	// widget
	'blog:widget:description' => 'Näytä viimeisimmät blogiviestisi',
	'blog:moreblogs' => 'Lisää blogiviestejä',
	'blog:numbertodisplay' => 'Blogiviestien määrä, jotka näytetään',
	'blog:noblogs' => 'Ei blogiviestejä'
);

add_translation('fi', $finnish);
