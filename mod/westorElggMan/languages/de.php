<?php

	$german = array(
	'ElggMan_' => "ElggMan",
	'ElggMan_SMS_Sitename' => "ElggMan", // 11 Characters for sender while sending automatic SMS from your Website
	'ElggMan:displayname' => "Anzeigename",
	'ElggMan:name' => "Nutzername",
	'ElggMan:email' => "E-Mail",
	'ElggMan:mobile' => "Handy",
//	'ElggMan_:known_since' => "Bekannt seit",
	'ElggMan_:friend_status' => "Status",
	'ElggMan_:friend_friend' => "Freund",
	'ElggMan_:intro' => "Manage deine Freunde, externe Kontakte und Gruppen",
	'ElggMan_:loading' => "Laden.... bitte warten!",
	'ElggMan_:messages:new' => "NEU",

// settings
	'ElggMan_:Info' => "Community Nutzer sind registrierte Nutzer dieser Community. Jeder User kann externe Kontakte verwalten, diese sind privat und nur für diesen Nutzer sichtbar.<br>
Hinweis: Community Nutzer können Nachrichten per E-Mail und / oder über ihre Community Inbox empfangen. Externe Kontakte besitzen keine Inbox.
",

	'ElggMan_:adminOnlyOption' => "Erlaube nur Admins die Verwneung dieses Plugins",
	'ElggMan_:FullMail' => "Sende die gesamte Nachricht zu Community Nutzern und lege eine Kopie in der Inbox der Nutzer ab. (Standard Elgg Verhalten)",
	'ElggMan_:NotifyOnly' => "Sende nur eine kurze Mitteilung über eine neu in der Inbox eingegangene Mail an Community Nutzer. (Der Nutzer muss sich anmelden, umd die gesamte Nachricht zu lesen.)",
	'ElggMan_:NoMessage' => "Sende keine Mail an den Nutzer, lege die Nachricht nur in die Post-Eingangsbox des Nutzers.",
	'ElggMan_:NoInbox' => "Sende die gesamte Nachricht an den Nutzer, speichere keine Kopie in der Eingangsbox des Nutzers.",
	'ElggMan_:CopyOutboxOption' => "Speichere eine Kopie der Nachricht in der Ausgangsbox des Absenders. (Standard Elgg Verhalten ist 'Ja'. Bei großem Mailaufkommen, z.B. Massen-E-Mail Versand, kann man diesen Schalter auf 'Nein' stellen.)",

	'ElggMan_:UseCronOption' => "Verzögerter Nachrichtenversand. Standard Verhalten ist 'Nein'. Allerdings kann das Senden einer größeren Zahl von Nachrichten schnell zu Abbrüchen durch Script Timeouts oder zu langen Antwortzeiten führen. Deswegen wird empfohlen, die Nachrichten zeitlich verzögert durch das Sytem senden zu lassen. Dabei muss cron richtig konfiguriert sein.<br>Eine Zeile in einem cron Script für die minütliche Auführung dieses Hintergrundtasks sieht zum Beispiel so aus:<br>
*/1 * * * * lynx --dump http://localhost/pg/cron/minute/ > /dev/null<br>
<br>In diesem Beispiel ist neben Cron auch Lynx installiert. Für weitere Information zum Systemprogramm cron schauen Sie bitte in Ihr Linux Manual. Für Windows benutzen Sie bitte eine ähnliche Software, die Jobs zeitgesteuert ausführen kann.",

	'ElggMan_:AllowSendToAllOption' => "Der Nutzer darf Nachrichten zu allen Nutzern senden. (Nein: Nur zu seinen Freunden.)",

	'ElggMan_:FriendsToRiverOption' => "Füge Freundschaftsanfragen zum Fluss (the river) hinzu.",

	'ElggMan_:varColumnsAdmin' => "Zeige diese Spalten für Administratoren:",
	'ElggMan_:varColumnsUser' => "Zeige diese Spalten normalen Nutzern:",

	// variable
	'ElggMan_:cUserName' => "Username",
	'ElggMan_:cEmail' => "e-Mail",
	'ElggMan_:cMobile' => "Mobilnummer",
	'ElggMan_:cSince' => "Bekannt seit",
	'ElggMan_:cLastAction' => "Letzte Aktion",
	'ElggMan_:cLocation' => "Ort",

// backend
	'ElggMan_:sessionError' => "Deine Sitzung scheint abgelaufen oder ungültig. Bitte melde dich erneut an!",
	'ElggMan_:adminError' => "Für dies Aktion musst du Administrator sein.",

	'ElggMan_:usersDeletedSuccess' => "Nutzer erfolgreich gelöscht.",
	'ElggMan_:usersDeletedFailed' => "Nicht alle Nutzer konnten gelöscht werden.",

	'ElggMan_:massagesDeletedFailed' => "Nicht alle Nachrichten konnten gelöscht werden.",
	'ElggMan_:messagesSaved' => "Deine Nachrichten für %s Empfänger wurden zum Senden gespeichert.",
	'ElggMan_:noMessageTxt' => "Sorry, aber es gibt keinen Nachrichtentext zum Senden.",
	'ElggMan_:noSubject' => '[kein Betreff]',


	'ElggMan_:newMessageNotification' => 'Du hast eine Nachricht in deiner Eingangsbox.',

	'ElggMan_:sms:test:left' => "Kostenloser Test der SMS Funktionalitäten. Du hast noch %s Nachrichten übrig.",

	'ElggMan_:sms:noNumber' => "Keine Handynummer für die folgenden Empfänger gefunden: ",

	'ElggMan_:sms:noNumber2' => "Sie müssen die Nummer Ihres Handys eingeben!",
	'ElggMan_:sms:noMoreVerifyRetry' => "Sie haben keine Versuche mehr, den Code einzugeben, bitte starten Sie den Prozess neu!",

	'ElggMan_:password:problem' => "Problem mit nicht pasendem Passwort",

	// frontend

	'ElggMan:welcome'  =>  "Willkommen ",
	'ElggMan:tab:user'  =>  "Nutzer",
	'ElggMan:tab:groups'  =>  "Gruppen",
  'ElggMan:tab:objects'  =>  "Elgg-Entities",
	'ElggMan:tab:settings'  =>  "Einstellungen",

	'ElggMan:search'  =>  "Suchen:",
	'ElggMan:contextAdvice'  =>  "Um Nutzerdaten zu ändern, und für weitere Optionen klicke mit der rechten Maustaste auf eine Tabellenzeile.",
// friends
	'ElggMan:friends'  =>  "Meine Freunde",
	'ElggMan:friends:add' => "Füge eine Person zu deiner Freundesliste hinzu",
	'ElggMan:friends:addAdvice' => "Um eine Freundschaftsanfrage zu stellen, benutze die Liste der aktivierten Nutzer im Dropdown.",
	'ElggMan:friends:addShort' => "Freund hinzufügen",
	'ElggMan:friends:addTo' => "Sende Freundschaftsanfrage",
	'ElggMan:friends:delete' => "Entferne eine Person von der Freundschaftsliste",
	'ElggMan:friends:delShort' => "Entferne Freund",
	'ElggMan:friends:dialog:add:advice' => "Wähle einen oder mehr Nutzer aus, um eine Freundschaftsanfrage zu stellen!",
	'ElggMan:friends:dialog:remove:advice' => "Wähle einen Freund aus, um ihn von deiner Freundesliste zu entfernen!",
	'ElggMan:friends:dialog:add' => "Willst du wirklich eine Freundschaftsanfrage an die ausgewählten Nutzer senden?",
	'ElggMan:friends:dialog:remove' => "Willst du die ausgewähletn Nutzer wirklich von deiner Freundesliste entfernen?",
	'ElggMan:friends:incoming' => "Eingehende Anfragen",
	'ElggMan:friends:outgoing' => "Ausgehende Anfragen",

	'ElggMan_:friends:FR:reason_sent' => "Anfrage schon gesendet?",
	'ElggMan_:friends:FR:reason_friend' => "Nutzer ist schon ein Freund",
	'ElggMan_:friends:FR:reason_self' => "Du kannst dich nicht selbst hinzufügen",

	'ElggMan_:friend_request:new' => "Neue Freundschaftsanfrage",

	'ElggMan_:FR:newfriend:subject' => "%s möchte dein Freund sein!",
	'ElggMan_:FR:newfriend:body' => "%s möchte dein Freund sein!
Aber er oder sie wartet auf dein Zustimmung. Bitte logge dich ain, um die Anfrage zu beantworten.
Du kannst die wartenden Anfragen hier bearbeiten:
%s

Hinweis: Du kannst auf diese Mail nicht antworten.",

	'ElggMan_:sms:senderNumber' => "Deine Absendernummer ist : ",
	'ElggMan_:sms:senderNotKnown' => "[unbekannt]",
	'ElggMan_:sms:verify:sendCode' => "Bitte gib diesen Code ein, um deine Nummer zu verifizieren: ",
	'ElggMan_:sms:verify:sendCodeResult' => "Dein Freischaltcode wurde per SMS an %s versendet.",
	'ElggMan_:sms:verify:codeMismatch' => "Der Code, den du eigegeben hast, entspricht nicht dem Code, der dir gesendet wurde. Du hast %s weitere Versuche.",
	'ElggMan_:sms:verify:sendCodeVerified' => "Code verifiziert, alles ok. Du kannst nun die SMS Funktionalitäten verwenden.",

	'ElggMan_:sms:problems' => "Problems sending message.",

	// frontend

	'ElggMan:acceppt:FR' => "Freundschaftsanfrage akzeptieren",
	'ElggMan:reject:FR' => "Freundschaftsanfrage ablehnen",
	'ElggMan:send:FR' => "Sende Freundschaftanfrage",
	'ElggMan:delete:FR' => "Lösche meine Freundschaftsanfrage",


// external contacts
	'ElggMan:contacts'  =>  "Externe Kontakte",
	'ElggMan:contacts:delete' => "Lösche eine externe Person von deiner Kontaktliste",
	'ElggMan:contacts:dialog:delete' => "Möchtest du wirklich die ausgewählten Kontakte entfernen?",
	'ElggMan:contacts:dialog:delete:advice' => "Wähle Kontakte zum Löschen aus!",
	'ElggMan:contacts:add' => "Füge eine externe Person zu deiner Kontaktliste hinzu",
	'ElggMan:contacts:modify' => "Ändere Kontaktdaten",

// users
	'ElggMan:users'  =>  "Alle aktivierten Nutzer",
	'ElggMan:users:add' => "Füge einen neuen User zur Community hinzu",
	'ElggMan:users:delete' => "Lösche einen Nutzer und seinen gesamten Inhalt.",
	'ElggMan:users:dialog:delete' => "Möchtest du die ausgewählten Nutzer wirklich löschen?",
	'ElggMan:users:dialog:delete:advice' => "Bitte wähle Nutzer zum Löschen aus!",
// users Online
	'ElggMan:usersOnline'  =>  "Nutzer Online",

	'ElggMan:notActivatedUsers'  =>  "Nicht aktivierte Nutzer",
	'ElggMan:activateUser'  =>  "Aktiviere Nutzer",
	'ElggMan:deactivateUser'  =>  "Deaktiviere Nutzer",
	'ElggMan:activateUser:dialog' => "Möchtest du den ausgewählten Nuitzer wirklich aktivieren?",
	'ElggMan:deactivateUser:dialog' => "Möchtest du wirklich den ausgewählten Nutzer und seine gesamten Inhalte deaktivieren?",

	'ElggMan:blockedUsers'  =>  "Verbannte Nutzer",
	'ElggMan:blockUser'  =>  "Verbanne Nutzer",
	'ElggMan:unblockUser'  =>  "Verbannung aufheben",
	'ElggMan:blockUser:dialog' => "Möchtest du ausgewählte Nutzer wirklich verbannen?",
	'ElggMan:unblockUser:dialog' => "Möchtest du bei ausgewähltem Nutzer die Verbannung aufheben?",

	'ElggMan:resetPassword'  =>  "Passwort zurück setzen",

	'ElggMan:makeAdmin'  =>  "Mache zum Administrator",
	'ElggMan:removeAdmin'  =>  "Entferne Admin-Rechte",

	'ElggMan:editUser'  =>  "Bearbeite Nutzer",
	'ElggMan:editContact'  =>  "Bearbeite Kontakt",
	'ElggMan:showProfile'  =>  "Zeige Profil",

	'ElggMan:groups'  =>  "Gruppen",

	'ElggMan:mark_all' => "Wähle alle",

	'ElggMan:contact_invite' => "kontaktieren/einladen",
	'ElggMan:manage' => "verwalten",

	'ElggMan:selectedUser' => "Wähle Empfänger:",

	'ElggMan:delete' => "Löschen",
	'ElggMan:add' => "Hinzufügen",

  'ElggMan:ok' => "OK",
	'ElggMan:cancel' => "Abbrechen",
	'ElggMan:save' => "Speichern",
  'ElggMan:done' => "Fertig",
  'ElggMan:ERROR' => "Sorry, ein Fehler ist aufgetreten.",
  'ElggMan:WARNING' => "Warnung, bitte beachten!",
  'ElggMan:INFO' => "Bitte beachten!",
  'ElggMan:CONFIRMATION' => "Wirklich?",
	
	'ElggMan:import' => "Importiere Kontakte",

	'ElggMan:messages:show' => 'Nachrichtencenter',


	'ElggMan:email:label' => 'Sende E-Mail',
	'ElggMan:email:label:descr' => 'Sende eine E-Mail an die folgenden Empfänger:',
	'ElggMan:email:label:subject' => 'Betreff:',
  'ElggMan:email:schedule' => "E-Mail planen",
  'ElggMan:email:now' => "E-Mail jetzt senden",	
	
	'ElggMan:email:save_as' => 'Speichern als',
	'ElggMan:email:load_from' => 'Laden von',

	'ElggMan:alert:email:select' => 'Bitte wähle mindestens einen Kontakt mit E-Mail Adresse aus!',
	'ElggMan:email:newdraft' => '[neuer Entwurf]',
	'ElggMan:email:deldraft' => 'Entwurf löschen',
	'ElggMan:alert:draft:name' => '',
	'ElggMan:alert:draft:delete' => '',

// messages
	'ElggMan:messages:delete' => "Löschen",
  'ElggMan:messages:maximize' => "Maximieren",
	'ElggMan:messages:dialog:delete' => "Ausgewählte Nachrichten wirklich löschen?",
	'ElggMan:messages:dialog:delete:advice' => "Bitte wähle Nachrichten zum Löschen aus!",

	'ElggMan:messages:recipient' => "Empfänger",
	'ElggMan:messages:sender' => "Sender",
	'ElggMan:messages:datetime' => "Datum und Zeit",
	'ElggMan:messages:subject' => "Betreff",
	'ElggMan:messages:state' => "Status",
	'ElggMan:messages:search' => "Suche in Nachrichten oder Betreff",
	'ElggMan:messages:re' => "Re: ",

	'ElggMan:messages:outbox' => "Ausgangsbox",
	'ElggMan:messages:inbox' => "Eingangsbox",
	'ElggMan:messages:answer' => "Antworten",

	'ElggMan:email:help' => '',

	'ElggMan:email:draft' => 'Benutze Entwürfe',

// Groups
	'ElggMan:groups:other' => 'Andere Gruppem',
	'ElggMan:groups:my' => 'Ich bin ein Mitglied von',
	'ElggMan:groups:member' => 'Gruppenmitglieder',
	'ElggMan:groups:help' => "Bitte ziehe mit der Maus (Drag And Drop) die Gruppen, an denen du teilnehmen möchtest oder die du verlassen willst, in die entsprechenden Felder.<br>Für die mit <strong>einem * markierten</strong> Gruppen bist du der Eigentümer. <span style='color : grey'>[Gruppen in grau]</span> sind geschlossene Gruppen.",
	'ElggMan:groups:error:owner' => "Du bist der Eigentümer der Gruppe. Du kannst die Gruppe nicht verlassen, bis du jemand anderem die Eigentümerschaft übertragen hast.",
	'ElggMan:groups:error:closed' => "Dies ist eine geschlossene Gruppe. Du musst beim Eigentümer die Mitgliedschaft beantragen.",

// SMS
	'ElggMan:sms' => "SMS",
	'ElggMan:sms:label' => "SMS senden",
	'ElggMan:sms:label:descr' => "Sende SMS folgende Empfänger:",

	'ElggMan:alert:sms:select' => 'Bitte wähle mindestens einen Eintrag mit einer Handynummer!',



	'ElggMan:sms:confirm' => "Absender bestätigen",
	'ElggMan:sms:history' => "SMS Historie",
	'ElggMan:sms:balance' => "Konto aufladen",
	'ElggMan:sms:char' => "Zeichen",
	'ElggMan:sms:chars' => "Zeichen",
	'ElggMan:sms:sendnow' => "SMS jetzt senden",
	'ElggMan:sms:schedule' => "SMS planen",
	'ElggMan:sms:enternumber' => "Keine Nutzer ausgewählt. Bitte Nutzer vorher wählen oder persönliche Kontakte hinzufügen.",

	'ElggMan:sms:confirm:helpHeaderH' => "Bitte verifiziere deine Handynummer.",
	'ElggMan:sms:confirm:helpHeader' => "Um sicher zu gehen, dass du die Person bist, die mit deinem Absender Nachrichten senden darf, musst du deine Nummer hier verifizieren.",

	'ElggMan:sms:confirm:helpNumberH' => "Gib deine Handynummer ein.",
	'ElggMan:sms:confirm:helpNumber' => "Bitte gib in das folgende Feld deine Handynummer ein. Beginne mit einem Plus und für den Code für deine Land (Deutschland: +49). Die Null der Netzvorwahl wird weg gelassen. Gültige Nummern sind zum Beispiel: +49 173 123 4 5678",
	'ElggMan:sms:confirm:start' => "Sende die Nummer",

	'ElggMan:sms:confirm:helpCodeH' => "Gib den Validierungscode ein.",
	'ElggMan:sms:confirm:helpCode' => "Bitte überprüfe dein Handy. Innheralb von wenigen Minuten solltest du einen Validierungscode als SMS auf deinem Handy empfangen. Diesen Code gib bitte hier ein.",
	'ElggMan:sms:confirm:verify' => "Sende den Code",


	'ElggMan:sms:sender:restart' => "Validierung neu starten",
	'ElggMan:sms:sender:ready' => "Fertig",

	// settings
	'ElggMan:save:reload' => "Theme speichern und neu laden",
	'ElggMan:theme:modern' => "Modern Theme",
	'ElggMan:theme:dark' => "Dark Theme",
	'ElggMan:theme:cs24' => "Silverblue Theme",
	'ElggMan:helpTableColumns' => 'Dies ist die Nutzertabelle. Bitte benutzen Sie die Maus in der Kopfzeile, um die Spalten auf die gewünschte Breite zu ziehen.',
	'ElggMan:rb:view:admin' => "Admin Ansicht",
	'ElggMan:rb:view:user' => "User Ansicht",

	);

	add_translation("de",$german);

?>