<?php
	/**
	 * Elgg groups plugin language pack
	 *
	 * @package ElggGroups
	 */

	$german = array(

	/**
	 * Menu items and titles
	 */

	'groups' => "Gruppen",
	'groups:owned' => "Von mir gegründete Gruppen",
        'groups:owned:user' => 'Gruppen gegründet von %s',
	'groups:yours' => "Meine Gruppen",
	'groups:user' => "Gruppen von %s",
	'groups:all' => "Alle Gruppen",
	'groups:add' => "Starte eine neue Gruppe",
	'groups:edit' => "Bearbeite Gruppen-Einstellungen",
	'groups:delete' => 'Lösche Gruppe',
	'groups:membershiprequests' => 'Verwalte Beitritts-Anfragen',
	'groups:invitations' => 'Einladungen zum Gruppenbeitritt',

	'groups:icon' => 'Gruppen-Icon (leer lassen, um nicht zu ändern)',
	'groups:name' => 'Gruppenname',
	'groups:username' => 'Kurzname der Gruppe (angezeigt in URLs, nur alphanumerische Zeichen)',
	'groups:description' => 'Beschreibung',
	'groups:briefdescription' => 'Kurzbeschreibung',
	'groups:interests' => 'Tags',
	'groups:website' => 'Webseite',
	'groups:members' => 'Gruppen-Mitglieder',
        'groups:members:title' => 'Mitglieder von %s',
        'groups:members:more' => "Alle Mitglieder auflisten",
	'groups:membership' => "Beschränkung des Gruppenbeitritts",
	'groups:access' => "Zugangslevel",
	'groups:owner' => "Gründer",
	'groups:widget:num_display' => 'Anzahl der anzuzeigenden Gruppen',
	'groups:widget:membership' => 'Gruppen-Mitgliedschaft',
	'groups:widgets:description' => 'Auflistung der Gruppen, in denen Du Mitglied bist, in Deinem Profil',
	'groups:noaccess' => 'Zugang zur Gruppe verweigert',
        'groups:permissions:error' => 'Du hast keine Berechtigung für diese Aktion.',
        'groups:ingroup' => 'in der Gruppe',
	'groups:cantedit' => 'Du kannst die Gruppen-Einstellungen nicht bearbeiten',
	'groups:saved' => 'Gruppe angelegt',
	'groups:featured' => 'Besondere Gruppen',
	'groups:makeunfeatured' => 'Aus "Besondere Gruppen" entfernen',
	'groups:makefeatured' => 'Zu "Besondere Gruppen" hinzufügen',
        'groups:featuredon' => '%s ist nun eine "Besondere Gruppe".',
        'groups:unfeatured' => '%s wurde aus der Liste der "Besonderen Gruppen" entfernt.',
        'groups:featured_error' => 'Ungültige Gruppe.',
	'groups:joinrequest' => 'Gruppenbeitritt beantragen',
	'groups:join' => 'Gruppe beitreten',
	'groups:leave' => 'Gruppe verlassen',
	'groups:invite' => 'Freunde einladen',
        'groups:invite:title' => 'Lade Deine Freunde ein, dieser Gruppe beizutreten',
	'groups:inviteto' => "Freunde zur Gruppe '%s' einladen",
	'groups:nofriends' => "Alle Deine Freunde wurden bereits zu dieser Gruppe eingeladen.",
        'groups:nofriendsatall' => 'Du hast leider noch keine Freunde, die Du einladen könntest!',
	'groups:viagroups' => "via Gruppen",
	'groups:group' => "Gruppe",
	'groups:search:tags' => "Tag",
        'groups:search:title' => "Suche nach Gruppen mit dem Tag '%s'",
        'groups:search:none' => "Es wurden keine passenden Gruppen gefunden.",
        'groups:search_in_group' => "In dieser Gruppe suchen",
        'groups:acl' => "Gruppe: %s",

        'discussion:notification:topic:subject' => 'Neuer Gruppen-Diskussionsbeitrag',
        'groups:notification' =>
'%s hat einen neuen Eintrag im Diskussionsforum von %s geschrieben:

%s
%s

Schau Dir den neuen Diskussionsbeitrag an und schreibe einen Kommentar:
%s
',
        'discussion:notification:reply:body' =>
'%s hat auf den Diskusstionsbeitrag %s in der Gruppe %s geantwortet:

%s

Schau Dir den neuen Diskussionsbeitrag an und schreibe einen Kommentar:
%s
',

        'groups:activity' => "Letzte Aktivitäten der Gruppe",
        'groups:enableactivity' => 'Anzeige von "Letzte Aktivitäten der Gruppe" aktivieren',
        'groups:activity:none' => "In dieser Gruppe gibt es noch keine Aktivitäten.",

	'groups:notfound' => "Gruppe nicht gefunden",
	'groups:notfound:details' => "Die angeforderte Gruppe existiert entweder nicht oder Du hast keine Zugangsberechtigung",

	'groups:requests:none' => 'Derzeit gibt es keine ausstehenden Anfragen für einen Beitritt zu dieser Gruppe.',

	'groups:invitations:none' => 'Derzeit gibt es keine unbeantworteten Einladungen zum Beitreten in diese Gruppe.',

	'item:object:groupforumtopic' => "Diskussionsthemen",

	'groupforumtopic:new' => "Neuen Eintrag im Diskussionsforum hinzufügen",

	'groups:count' => "Verfügbare Gruppen",
	'groups:open' => "Öffentliche Gruppe",
	'groups:closed' => "Nicht-öffentliche Gruppe",
	'groups:member' => "Mitglieder",
	'groups:searchtag' => "Suche nach Gruppen via Tags",

        'groups:more' => 'Weitere Gruppen',
        'groups:none' => 'Keine Gruppen',


	/*
	 * Access
	 */
	'groups:access:private' => 'Nicht-öffentliche Gruppe - Gruppenbeitritt ist nur mit Einladung möglich',
	'groups:access:public' => 'Öffentliche Gruppe - jeder Benutzer kann der Gruppe beitreten',
        'groups:access:group' => 'Nur für Gruppenmitglieder',
	'groups:closedgroup' => 'Diese Gruppe ist nicht-öffentlich.',
	'groups:closedgroup:request' => 'Um dieser Gruppe beitreten zu dürfen, wähle bitte den Menueintrag "Gruppenbeitritt beantragen".',
	'groups:visibility' => 'Wer kann diese Gruppe sehen?',

	/*
	Group tools
	*/
	'groups:enableforum' => 'Gruppen-Diskussionsforum aktivieren',
	'groups:yes' => 'ja',
	'groups:no' => 'nein',
	'groups:lastupdated' => '%s zuletzt aktualisiert durch %s',
	'groups:lastcomment' => '%s zuletzt kommentiert durch %s',

	/*
	Group discussion
	*/
        'discussion' => 'Diskussion',
        'discussion:add' => 'Neuen Eintrag im Diskussionsforum hinzufügen',
        'discussion:latest' => 'Neuester Diskussionsbeitrag',
        'discussion:group' => 'Gruppen-Diskussion',
        'discussion:none' => 'Es gibt noch keine Diskussionsbeiträge.',
        'discussion:reply:title' => 'Antwort von %s',

        'discussion:topic:created' => 'Der Diskussionsbeitrag wurde hinzugefügt.',
        'discussion:topic:updated' => 'Der Diskussionsbeitrag wurde aktualisiert.',
        'discussion:topic:deleted' => 'Der Diskussionsbeitrag wurde gelöscht.',

        'discussion:topic:notfound' => 'Der gewünschte Diskussionsbeitrag wurde leider nicht gefunden.',
        'discussion:error:notsaved' => 'Der Diskussionsbeitrag konnte nicht gespeichert werden.',
        'discussion:error:missing' => 'Es müssen sowohl der Titel als auch das Textfeld ausgefüllt werden.',
        'discussion:error:permissions' => 'Du hast keine Berechtigung für diese Aktion.',
        'discussion:error:notdeleted' => 'Der Diskussionsbeitrag konnte nicht gelöscht werden.',

        'discussion:reply:deleted' => 'Die Antwort im Diskussionsbeitrag wurde gelöscht.',
        'discussion:reply:error:notdeleted' => 'Die Antwort im Diskussionsbeitrag konnte nicht gelöscht werden.',

        'reply:this' => 'Antwort schreiben',



	'group:replies' => 'Antworten',
        'groups:forum:created' => '%s hinzugefügt mit %d Kommentaren',
        'groups:forum:created:single' => '%s hinzugefügt mit %d Antwort',
        'groups:forum' => 'Diskussion',
	'groups:addtopic' => 'Einen Diskussionsbeitrag hinzufügen',
	'groups:forumlatest' => 'Neueste Diskussionsbeiträge',
	'groups:latestdiscussion' => 'Neueste Diskussionsbeiträge',
	'groups:newest' => 'Neueste',
	'groups:popular' => 'Beliebt',
	'groupspost:success' => 'Deine Antwort wurde gespeichert',
	'groups:alldiscussion' => 'Neueste Diskussionsbeiträge',
	'groups:edittopic' => 'Diskussionsbeitrag bearbeiten',
	'groups:topicmessage' => 'Textinhalt des Diskussionsbeitrags',
	'groups:topicstatus' => 'Status des Beitrags',
	'groups:reply' => 'Einen Kommentar schreiben',
	'groups:topic' => 'Beitrag',
	'groups:posts' => 'Einträge',
	'groups:lastperson' => 'Letzte Person',
	'groups:when' => 'Wann',
	'grouptopic:notcreated' => 'Es wurden noch keine Diskussionsbeiträge erstellt.',
	'groups:topicopen' => 'Offen',
	'groups:topicclosed' => 'Geschlossen',
	'groups:topicresolved' => 'Aufgelöst',
	'grouptopic:created' => 'Dein Diskussionsbeitrag wurde gespeichert.',
	'groupstopic:deleted' => 'Der Beitrag wurde gelöscht.',
	'groups:topicsticky' => 'Sticky',
	'groups:topicisclosed' => 'Dieser Diskussionsbeitrag ist geschlossen.',
	'groups:topiccloseddesc' => 'Dieser Diskussionsbeitrag ist geschlossen und es können keine weiteren Kommentare hinzugefügt werden.',
	'grouptopic:error' => 'Dein Diskussionsbeitrag konnte nicht gespeichert werden. Bitte versuche es noche einmal oder wende Dich an einen Administrator.',
	'groups:forumpost:edited' => "Die Änderungen an dem Diskussionsbeitrag wurden gespeichert.",
	'groups:forumpost:error' => "Beim Speichern der Änderungen an dem Diskussionsbeitrag ist ein Fehler aufgetreten.",
	'groups:privategroup' => 'Dies ist eine nicht-öffentliche Gruppe. Der Beitritt zur Gruppe ist nur auf Anfrage möglich.',
	'groups:notitle' => 'Gruppen müssen einen Titel haben.',
	'groups:cantjoin' => 'Du kannst dieser Gruppe nicht beitreten.',
	'groups:cantleave' => 'Das Verlassen der Gruppe ist fehlgeschlagen.',
        'groups:removeuser' => 'Aus der Gruppe entfernen',
        'groups:cantremove' => 'Der Benutzer konnte nicht aus der Gruppe entfernt werden.',
        'groups:removed' => '%s wurde aus der Gruppe entfernt.',
	'groups:addedtogroup' => 'Der Benutzer wurde als Mitglied der Gruppe hinzugefügt.',
	'groups:joinrequestnotmade' => 'Die Anfrage zum Beitritt zur Gruppe ist fehlgeschlagen.',
	'groups:joinrequestmade' => 'Die Anfrage zum Beitritt zur Gruppe wurde gesendet.',
	'groups:joined' => 'Du bist der Gruppe beigetreten!',
	'groups:left' => 'Du hast die Gruppe verlassen.',
	'groups:notowner' => 'Entschuldigung, aber Du bist nicht der Gründer dieser Gruppe.',
	'groups:notmember' => 'Entschuldigung, aber Du bist kein Mitglied dieser Gruppe.',
	'groups:alreadymember' => 'Du bist bereits ein Mitglied dieser Gruppe!',
	'groups:userinvited' => 'Der Benutzer wurde eingeladen.',
	'groups:usernotinvited' => 'Die Einladung an den Benutzer konnte nicht gesendet werden.',
	'groups:useralreadyinvited' => 'Dieser Benutzer wurde bereits eingeladen.',
	'groups:invite:subject' => "Hallo %s, Du wurdest eingeladen, der Gruppe %s beizutreten!",
        'groups:updated' => "Letze Antwort von %s %s",
        'groups:started' => "Gestartet von %s",
	'groups:joinrequest:remove:check' => 'Bist Du sicher, dass Du diese Anfrage zum Gruppenbeitritt löschen willst?',
	'groups:invite:remove:check' => 'Bist Du sicher, dass Du diese Einladung zum Gruppenbeitritt löschen willst?',
	'groups:invite:body' => "Hallo %s,

%s hat Dich eingeladen, der Gruppe '%s' beizutreten. Folge dem Link um Deine ausstehenden Einladungen zum Beitreten in Gruppen zu sehen:

%s",

	'groups:welcome:subject' => "Willkommen in der Gruppe %s!",
	'groups:welcome:body' => "Hallo %s!

Du bist nun ein Mitglied der Gruppe '%s'! Folge dem Link um einen Beitrag in der Gruppe zu schreiben!

%s",

	'groups:request:subject' => "%s hat beantragt, der Gruppe %s beitreten zu dürfen",
	'groups:request:body' => "Hallo %s,

%s hat beantragt, der Gruppe %s beitreten zu dürfen. Folge dem Link um ihr/sein Profil zu sehen:

%s

oder folge dem nächsten Link, um die ausstehenden Anfragen zum Gruppenbeitritt zu sehen:

%s",

	/*
		Forum river items
	*/

        'river:create:group:default' => '%s hat die Gruppe %s gegründet.',
        'river:join:group:default' => '%s ist der Gruppe %s beigetreten.',
        'river:create:object:groupforumtopic' => '%s schrieb einen neuen Diskussionsbeitrag %s',
        'river:reply:object:groupforumtopic' => '%s schrieb einen Kommentar zum Diskussionsbeitrag %s',

	'groups:nowidgets' => 'Für diese Gruppe wurden keine Widgets aktiviert.',


	'groups:widgets:members:title' => 'Mitglieder der Gruppen',
	'groups:widgets:members:description' => 'Zeige die Mitglieder einer Gruppe an.',
	'groups:widgets:members:label:displaynum' => 'Zeige die Mitglieder einer Gruppe an.',
	'groups:widgets:members:label:pleaseedit' => 'Bitte konfiguriere dieses Widget.',

	'groups:widgets:entities:title' => "Objekte in der Gruppe",
	'groups:widgets:entities:description' => "Zeige die Objekte an, die in dieser Gruppe gespeichert sind.",
	'groups:widgets:entities:label:displaynum' => 'Zeige die Objekte einer Gruppe an.',
	'groups:widgets:entities:label:pleaseedit' => 'Bitte konfiguriere dieses Widget.',

	'groups:forumtopic:edited' => 'Diskussionsbeitrag wurde aktualisiert.',

	'groups:allowhiddengroups' => 'Möchtest Du private (versteckte) Gruppen zulassen?',

	/**
	 * Action messages
	 */
	'group:deleted' => 'Die Gruppe und der Inhalt der Gruppe wurde gelöscht.',
	'group:notdeleted' => 'Die Gruppe konnte nicht gelöscht werden.',

        'group:notfound' => 'Die Gruppe wurde nicht gefunden.',
	'grouppost:deleted' => 'Der Gruppenbeitrag wurde gelöscht.',
	'grouppost:notdeleted' => 'Der Gruppenbeitrag konnte nicht gelöscht werden.',
	'groupstopic:deleted' => 'Beitrag wurde gelöscht.',
	'groupstopic:notdeleted' => 'Beitrag konnte nicht gelöscht werden.',
	'grouptopic:blank' => 'Kein Titel eingegeben oder leerer Textinhalt.',
	'grouptopic:notfound' => 'Dieser Beitrag konnte nicht gefunden werden.',
	'grouppost:nopost' => 'Leerer Beitrag.',
	'groups:deletewarning' => "Bist Du sicher, dass Du diese Gruppe löschen willst? Dies kann nicht rückgängig gemacht werden!",

	'groups:invitekilled' => 'Die Einladung wurde gelöscht.',
	'groups:joinrequestkilled' => 'Der Antrag zum Gruppenbeitritt wurde gelöscht.',

        // ecml
        'groups:ecml:discussion' => 'Gruppen-Diskussionen',
        'groups:ecml:groupprofile' => 'Gruppen-Profile',

	);

	add_translation("de",$german);