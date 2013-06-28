<?php
/**
 * Pages languages
 *
 * @package ElggPages
 */

$german = array(

	/**
	 * Menu items and titles
	 */

	'pages' => "Coop-Seiten",
	'pages:owner' => "Coop-Seiten von %s",
	'pages:friends' => "Coop-Seiten von Freunden",
	'pages:all' => "Alle Coop-Seiten",
	'pages:add' => "Eine Coop-Seiten hinzufügen",

	'pages:group' => "Gruppen-Coop-Seiten",
	'groups:enablepages' => 'Gruppen-Coop-Seiten aktivieren',

	'pages:edit' => "Diese Coop-Seite bearbeiten",
	'pages:delete' => "Diese Coop-Seite löschen",
	'pages:history' => "Bearbeitungsverlauf",
	'pages:view' => "Coop-Seite anzeigen",
	'pages:revision' => "Revision",

	'pages:navigation' => "Navigation",
        'pages:new' => "Eine neue Coop-Seite",
        'pages:notification' =>
'%s hat eine neue Coop-Seite erstellt:

%s
%s

Schau Dir die neue Coop-Seite an und schreibe einen Kommentar:
%s
',
	'item:object:page_top' => 'Haupt-Coop-Seiten',
	'item:object:page' => 'Coop-Seiten',
	'pages:nogroup' => 'Diese Gruppe hat noch keine Coop-Seiten.',
	'pages:more' => 'Weitere Coop-Seiten',
	'pages:none' => 'Es wurden noch keine Coop-Seiten erstellt.',

	/**
	* River
	**/

	'river:create:object:page' => '%s hat die Coop-Seite %s hinzugefügt.',
	'river:create:object:page_top' => '%s hat die Coop-Seite %s hinzugefügt.',
	'river:update:object:page' => '%s aktualisierte die Coop-Seite %s.',
	'river:update:object:page_top' => '%s aktualisierte die Coop-Seite %s.',
	'river:comment:object:page' => '%s schrieb einen Kommentar zur Coop-Seite %s.',
	'river:comment:object:page_top' => '%s schrieb einen Kommentar zur Coop-Seite %s.',

	/**
	 * Form fields
	 */

	'pages:title' => 'Titel der Coop-Seite',
	'pages:description' => 'Seitentext',
	'pages:tags' => 'Tags',
	'pages:access_id' => 'Zugangslevel',
	'pages:write_access_id' => 'Schreibberechtigung',

	/**
	 * Status and error messages
	 */
	'pages:noaccess' => 'Keine Zugangsberechtigung für diese Coop-Seite.',
	'pages:cantedit' => 'Du kannst diese Coop-Seite nicht bearbeiten.',
	'pages:saved' => 'Die Coop-Seite wurde gespeichert.',
	'pages:notsaved' => 'Die Coop-Seite konnte nicht gespeichert werden.',
	'pages:error:no_title' => 'Du mußt einen Titel für diese Coop-Seite eingeben.',
	'pages:delete:success' => 'Die Coop-Seite wurde gelöscht.',
	'pages:delete:failure' => 'Die Coop-Seite konnte nicht gelöscht werden.',

	/**
	 * Page
	 */
	'pages:strapline' => 'Zuletzt aktualisiert am %s von %s',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revision erzeugt am %s von %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Anzahl der anzuzeigenden Coop-Seiten',
	'pages:widget:description' => "Dies ist eine Liste Deiner Coop-Seiten.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Coop-Seite anzeigen",
	'pages:label:edit' => "Coop-Seite bearbeiten",
	'pages:label:history' => "Bearbeitungsverlauf der Coop-Seite",

	/**
	 * Sidebar items
	 */
	'pages:sidebar:this' => "Diese Coop-Seite",
	'pages:sidebar:children' => "Unter-Coop-Seiten",
	'pages:sidebar:parent' => "Übergeordnete Coop-Seite",

	'pages:newchild' => "Eine Unter-Coop-Seite erstellen",
	'pages:backtoparent' => "Zurück zu '%s'",
);

add_translation("de", $german);