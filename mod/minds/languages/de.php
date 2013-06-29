<?php


$german = array(
	
	/* Minds renamings and overrides
	 */
	'more' => '&#57349;',
	
	'widgets:add' => 'Widgets',
	
	//login
	'login' => 'Eintreten',
	'logout' => 'Verlassen',
	'register' => 'Erstellen Sie einen Kanal',
	'register:early' => 'Fordern Early Access',
	
	'post' => 'Beitrag',
	
	//change friends to channels
	'access:friends:label' => "Kanäle",
	
	'friends' => "Netzwerk",
	'friends:yours' => "Kanäle, die Sie abonniert haben",
	'friends:owned' => "Kanäle %s's abonniert",
	'friend:add' => "Abonnieren",
	'friend:remove' => "Gezeichnet",

	'friends:add:successful' => "Sie haben erfolgreich zu %s abonniert.",
	'friends:add:failure' => "Wir konnten nicht an %s abonnieren.",

	'friends:remove:successful' => "Sie haben erfolgreich %s aus Ihrer Abonnements entfernt.",
	'friends:remove:failure' => "Wir konnten nicht entfernen %s aus Ihrer Abonnements.",

	'friends:none' => "Keine Kanäle noch.",
	'friends:none:you' => "Sie sind nicht auf irgendwelche Kanäle noch gezeichnet.",

	'friends:none:found' => "Keine Kanäle gefunden wurden.",

	'friends:of:none' => "Bisher hat noch niemand auf diesen Kanal abonniert doch.",
	'friends:of:none:you' => "Niemand hat Sie noch abonniert. Starten Sie das Hinzufügen von Inhalten und füllen Sie in Ihrem Profil, damit die Menschen finden!",

	'friends:of:owned' => "Personen, die %s abonniert haben",

	'friends:of' => "Abonnenten",
	'friends:collections' => "Kanal Sammlungen",
	'collections:add' => "Neue Kollektion",
	'friends:collections:add' => "Neuer Kanal Sammlung",
	'friends:addfriends' => "Wählen Sie Kanäle",
	'friends:collectionname' => "Sammlung Namen",
	'friends:collectionfriends' => "Kanäle in der Sammlung",
	'friends:collectionedit' => "Diese Sammlung bearbeiten",
	'friends:nocollections' => "Sie haben noch keine Sammlungen noch nicht.",
	'friends:collectiondeleted' => "Ihre Kollektion wurde gelöscht.",
	'friends:collectiondeletefailed' => "Wir waren nicht in der Lage, um die Sammlung zu löschen. Entweder Sie haben keine Berechtigung, oder ein anderes Problem aufgetreten ist.",
	'friends:collectionadded' => "Ihre Kollektion wurde erfolgreich erstellt",
	'friends:nocollectionname' => "Sie benötigen, um Ihre Sammlung einen Namen, bevor es erstellt werden können.",
	'friends:collections:members' => "Sammlung Mitglieder",
	'friends:collections:edit' => "Bearbeiten Sammlung",
	'friends:collections:edited' => "Gespeichert Sammlung",
	'friends:collection:edit_failed' => 'Konnte nicht gespeichert Sammlung.',
	
	'river:friend:user:default' => "%s abonniert %s",
	
	/**
 * Emails
 */
	'email:settings' => "E-Mail-Einstellungen",
	'email:address:label' => "Ihre E-Mail-Adresse",

	'email:save:success' => "Neue E-Mail-Adresse gespeichert.",
	'email:save:fail' => "Ihre neue E-Mail-Adresse konnte nicht gespeichert werden.",

	'friend:newfriend:subject' => "%s hat Ihnen abonnierten!",
	'friend:newfriend:body' => "%s hat Ihnen auf Minds abonniert!

Um ihren Kanal sehen, bitte hier klicken:

%s

Sie können nicht auf diese E-Mail antworten.",



	'email:resetpassword:subject' => "Passwort-Reset",
	'email:resetpassword:body' => "Hallo %s,

Ihr Passwort wurde zurückgesetzt: %s",


	'email:resetreq:subject' => "Fordern Sie für neue Passwort.",
	'email:resetreq:body' => "Hallo %s,

Jemand (aus der IP-Adresse %s), hat ein neues Passwort für ihr Konto beantragt.

Wenn Sie dies beantragt, auf den untenstehenden Link klicken. Andernfalls ignorieren Sie diese E-Mail.

%s
",
	
	//river menu
	'river:featured' => 'Vorgestellt',
	'river:trending' => 'Trending',
	'river:thumbs-up' => 'Daumen nach oben',
	'river:thumbs-down' => 'Daumen nach unten',
	
	//change activity to news
	'news' => 'Nachrichten', 
	'minds:riverdashboard:addwire' => 'Share your thoughts',
	'minds:riverdashboard:annoucement' => 'Ankündigung',
	'minds:riverdashboard:changeannoucement' => 'Ändern Sie die Ankündigung',
	
	//Minds Specific
	'minds:register:terms:failed' => 'Bitte akzeptieren Sie die Bedingungen, um sich zu registrieren',
	'minds:register:terms:read' => 'Ich akzeptiere die Bedingungen und Konditionen',
	'minds:regsiter:terms:link' => ' (lesen)',
	
	'minds:comments:commentcontent' => '%s: %s',
	'minds:comments:likebutton' => 'Wie',
    'minds:comments:unlikebutton' => 'im Gegensatz zu',
    'minds:comments:commentsbutton' => 'Kommentar',
    'minds:comments:sharebutton' => 'Teilen',
    'minds:comments:viewall' => 'Alle %s Kommentare',
    'minds:comments:remainder' => 'Sehen restlichen %s Kommentare',
    'minds:comments:nocomments' => 'Als Erster einen Kommentar',
    'minds:commenton' => 'Ihre Meinung zu %s',
    'minds:comments:valuecantbeblank' => 'Kommentar darf nicht leer sein',
    'minds:remind' => 'ReMind (repost)',
    'minds:remind:success' => 'Erfolgreich reMinded.',
    
	//river
	'river:remind:object:wall' => '%s reMinded %s\'s dachte',
	'river:remind:object:kaltura' => '%s reMinded %s\'s medien: %s',
	'river:remind:object:blog' => '%s reMinded %s\'s blog',
	'river:remind:api' => '%s reMinded %s',
	
	'river:feature:object:kaltura' => '%s\'s medien %s wurde vorgestellt',
	'river:feature:object:blog' => '%s\'s blog wurde vorgestellt',
	'river:feature:object:album' => '%s\'s album %s wurde vorgestellt',
	'river:feature:object:image' => '%s\'s bild %s wurde vorgestellt',
	'river:feature:object:tidypics_batch' => '%s\'s Bilder %s wurden vorgestellt',
	
	/* Quota 
	 */
	'minds:quota:statisitcs:title' => 'Ihre Nutzung',
	'minds:quota:statisitcs:storage' => 'Lagerung',
	'minds:quota:statisitcs:bandwidth' => 'Bandbreite',
	
	/**
	 * ONLINE USER STATUS
	 *
	 */
	'minds:online_status:online' => 'Online',
	
	/**
	 * Thoughts
	 */
	 'minds:thoughts' => 'Gedanken',
	
	/**
	 * Minds Universal upload form
	 */
	'minds:upload'=>'Hochladen',
	'minds:upload:file'=>'Datei',
	'minds:upload:nofile' => 'Es wurde keine Datei hochgeladen.',
	
	/* Licenses
	 */
	'minds:license:all' => "All licenses",
	'minds:license:label' => 'License <a href="' . elgg_get_site_url() . 'licenses" target="_blank"> (?) </a>',
	'minds:license:not-selected' => '-- Please select a license --',
	'minds:license:attribution-cc' => 'Attribution CC BY',
	'minds:license:attribution-sharealike-cc' => 'Attribution-ShareAlike BY-SA',
	'minds:license:attribution-noderivs-cc' => 'Attribution-NoDerivs CC BY-ND',
	'minds:license:attribution-noncommerical-cc' => 'Attribution-NonCommerical CC BY-NC',
	'minds:license:attribution-noncommercial-sharealike-cc' => 'Attribution-NonCommerical-ShareAlike CC BY-NC-SA',
	'minds:license:attribution-noncommercial-noderivs-cc' => 'Attribution-NonCommerical-NoDerivs CC BY-NC-ND',
	'minds:license:publicdomaincco' => 'Public Domain CCO "No Rights Reserved"',
	'minds:license:gnuv3' => 'GNU v3 General Public License',
	'minds:license:gnuv1.3' => 'GNU v1.3 Free Documentation License',
	'minds:license:gnu-lgpl' => 'GNU Lesser General Public License',
	'minds:license:gnu-affero' => 'GNU Affero General Public License',
	'minds:license:apache-v1' => 'Apache License, Version 1.0',
	'minds:license:apache-v1.1' => 'Apache License, Version 1.1',
	'minds:license:apache-v2' => 'Apache License, Version 2.0',
	'minds:license:mozillapublic' => 'Mozilla Public License',
	'minds:license:bsd' => 'BSD License',
	
	'categories' => 'Category',
	
	'blog:owner_more_posts' => 'More blogs from %s',
	'blog:featured' => 'Featured blogs',
	'readmore' => '→ read more',
	'minds:embed:youtube' => 'Youtube',

    
    
        'register:node' => 'Launch a social network',
        "register:node:testping" => 'Multisite node DNS Test',
);
		
add_translation("de", $german);