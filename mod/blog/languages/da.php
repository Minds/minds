<?php
/**
 * Blog Danish language file.
 *
 */

$danish = array(
	'blog'  =>  'Blog', 
	'blog:blogs' =>  'Blogge',
	'blog:revisions' => 'Revision',
	'blog:archives' => 'Arkiver',
	'blog:blog' => 'Blog',
	'item:object:blog' => 'Blogs',
	 
	'blog:title:user_blogs' => '%s\'s blogge',
	'blog:title:all_blogs' => 'Alle blogge', 
	'blog:user:friends'  =>  '%ss venners blog',

	'blog:group' => 'Gruppe blog',	
	'blog:enableblog' => 'Aktiver gruppe blog',
	'blog:write' => 'Skriv et blogindlæg',	

	// Editing
	'blog:add' => 'Tilføj blogindlæg',
	'blog:edit' => 'Rediger blogindlæg',
	'blog:excerpt' => 'Uddrag',
	'blog:body' => 'Brødtekst',
	'blog:save_status' => 'Sidst gemt: ',
	'blog:never' => 'Aldrig',

	// Statuses
	'blog:status' => 'Status',
	'blog:status:draft' => 'Kladde',
	'blog:status:published' => 'Offentliggjort',
	'blog:status:unsaved_draft' => 'Ikke gemt kladde',

	'blog:revision' => 'Revision',
	'blog:auto_saved_revision' => 'Auto gemt revision',

	// messages
	'blog:message:saved' => 'Blogindlæg gemt.',
	'blog:error:cannot_save' => 'Kan ikke gemme blogindlæg.',
	'blog:error:cannot_write_to_container' => 'Utilstrækkelig adgang til at gemme bloggen til gruppe.',
	'blog:error:post_not_found' => 'Dette indlæg er blevet fjernet, er ugyldigt, eller du har ikke tilladelse til at se det.',
	'blog:messages:warning:draft' => 'Der er en ikke gemt kladde til dette indlæg!',
	'blog:edit_revision_notice' => '(Gammel version)',
	'blog:message:deleted_post' => 'Blogindlæg slettet.',
	'blog:error:cannot_delete_post' => 'Kan ikke slette blogindlæg.',
	'blog:none' => 'Ingen blogindlæg',
	'blog:error:missing:title' => 'Angiv en blog titel!',
	'blog:error:missing:description' => 'Indtast venligst brødteksten til ​​din blog!',
	'blog:error:cannot_edit_post' => 'Dette indlæg eksisterer måske ikke, eller du har måske ikke tilladelse til at redigere det.',
	'blog:error:revision_not_found' => 'Kan ikke finde denne revision.',

	// river
	'river:create:object:blog' => '%s har offentliggjort et blogindlæg %s',
	'river:comment:object:blog' => '%s har kommenteret et blogindlæg %s',

	// notifications
	'blog:newpost' => 'Et nyt blogindlæg',

	// widget
	'blog:widget:description' => 'Vis dit seneste blogindlæg',
	'blog:moreblogs' => 'Flere blogindlæg',
	'blog:numbertodisplay' => 'Antal af blogindlæg, der skal vises',
	'blog:noblogs' => 'Ingen blogindlæg'

);

add_translation('da',$danish);

?>