<?php
/**
 * Bookmarks German language file
 */

$german = array(

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "Lesezeichen",
	'bookmarks:add' => "Lesezeichen hinzufügen",
	'bookmarks:edit' => "Lesezeichen editieren",
	'bookmarks:owner' => "Lesezeichen von %s",
	'bookmarks:friends' => "Lesezeichen Deiner Freunde",
	'bookmarks:everyone' => "Alle Lesezeichen der Community",
	'bookmarks:this' => "Lesezeichen für diese Seite hinzufügen",
	'bookmarks:this:group' => "Lesezeichen in %s setzen",
	'bookmarks:bookmarklet' => "Bookmarklet zum Browser hinzufügen",
	'bookmarks:bookmarklet:group' => "Gruppen-Bookmarklet zum Browser hinzufügen",
	'bookmarks:inbox' => "Lesezeichen-Inbox",
	'bookmarks:morebookmarks' => "Weitere Lesezeichen",
	'bookmarks:more' => "Mehr",
	'bookmarks:with' => "Teile das Lesezeichen mit",
	'bookmarks:new' => "Ein neues Lesezeichen",
	'bookmarks:via' => "via Lesezeichen",
	'bookmarks:address' => "Zieladresse des Lesezeichens",
	'bookmarks:none' => 'Noch keine Lesezeichen vorhanden.',

        'bookmarks:notification' =>
'%s hat ein neues Lesezeichen erstellt:

%s - %s
%s

Schau Dir das neue Lesezeichen an und schreibe einen Kommentar:
%s
',

	'bookmarks:delete:confirm' => "Bist Du sicher, dass Du dieses Lesezeichen löschen willst?",

	'bookmarks:numbertodisplay' => 'Anzahl der anzuzeigenden Lesezeichen-Einträge.',

	'bookmarks:shared' => "Lesezeichen gesetzt",
	'bookmarks:visit' => "Gehe zu dieser Seite",
	'bookmarks:recent' => "Neuesten Lesezeichen",

	'river:create:object:bookmarks' => '%s hat das Lesezeichen %s hinzugefügt.',
	'river:comment:object:bookmarks' => '%s kommentierte das Lesezeichen %s',
	'bookmarks:river:annotate' => 'einen Kommentar zum Lesezeichen',
	'bookmarks:river:item' => 'einen Eintrag',

	'item:object:bookmarks' => 'Lesezeichen',

	'bookmarks:group' => 'Gruppen-Lesezeichen',
	'bookmarks:enablebookmarks' => 'Gruppen-Lesezeichen aktivieren',
	'bookmarks:nogroup' => 'Diese Gruppe hat noch keine Lesezeichen.',
	'bookmarks:more' => 'Weitere Lesezeichen',

	'bookmarks:no_title' => 'Kein Titel',

	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Dieses Widget zeigt Deine neuesten Lesezeichen an.",

	'bookmarks:bookmarklet:description' =>
			"Das Lesezeichen-Bookmarklet ermöglicht es Dir, alle interessanten Web-Addressen mit Deinen Freunden zu teilen oder auch nur für Dich selbst ein Lesezeichen zu setzen. Um das Bookmarklet zu verwenden, ziehe den folgenden Button einfach in die Lesezeichen-Leiste Deines Browsers:",

	'bookmarks:bookmarklet:descriptionie' =>
			"Wenn Du den Internet Explorer verwendest, klicke mit der rechten Maustaste auf das Bookmarklet-Icon, wähle 'Zu Favoriten hinzufügen' und dann die Lesezeichen-Leiste.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"Du kannst dann ein Lesezeichen für eine Seite setzen, indem Du auf das Bookmarklet in der Lesezeichen-Leiste des Browsers klickst.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Für den Eintrag wurde ein Lesezeichen gesetzt.",
	'bookmarks:delete:success' => "Das Lesezeichen wurde gelöscht.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Das Lesezeichen konnte nicht gespeichert werden. Bitte gebe einen Titel und eine Zieladresse an und versuche es noch einmal.",
	'bookmarks:save:invalid' => "Die Adresse des Lesezeichens ist ungültig und kann nicht gespeichert werden.",
	'bookmarks:delete:failed' => "Das Lesezeichen konnte nicht gelöscht werden. Versuche es bitte noch einmal.",
);

add_translation('de', $german);