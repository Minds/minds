<?php
/**
 * Chat English language file.
 */

$german = array(
	'chat' => 'Schnacken',
	'chat:chats' => 'Chats',
	'chat:view:all' => 'Alle Chats',
	'chat:chat' => 'Schnacken',
	'item:object:chat' => 'Schnacken',
	'chat:none' => 'Keine Chats',
	'chat:more' => 'Mehr',

	'chat:title:user_chats' => '%s\'s chats',
	'chat:title:all_chats' => 'Alle Website-Chats',
	'chat:title:friends' => 'Freunde\' chats',
	'chat:messages' => 'Chat-Nachrichten',
	'chat:members' => 'hinzufügen von Mitgliedern',
	'chat:members:add' => 'Hinzufügen von Mitgliedern',
	'chat:leave' => 'Verlassen',
	'chat:leave:confirm' => 'Wollen Sie wirklich auf diesen Chat verlassen?',
	'chat:members:more' => "+%s andere",
	'chat:unread_message' => '%s ungelesenen',
	'chat:unread_messages' => '%s ungelesenen', // Plurar

	'chat:group' => 'Gruppen-Chat',
	'chat:enablechat' => 'Aktivieren Gruppenchat',
	'chat:write' => 'Chat beginnen',

	// Editing
	'chat:add' => 'Chat beginnen',
	'chat:edit' => 'Bearbeiten chat',
	'chat:members:manage' => 'Hinzufügen / Entfernen von Mitgliedern',
	'chat:delete:confirm' => 'Wollen Sie wirklich auf diesen Chat und alle Nachrichten in sie zu entfernen?',
	'chat:title' => 'Chat Titel',
	'chat:message' => 'Nachricht',

	// messages
	'chat:message:saved' => 'Chat gespeichert',
	'chat:message:deleted' => 'Chat gelöscht',
	'chat:message:chat_message:saved' => 'Nachricht gespeichert',
	'chat:message:chat_message:deleted' => 'Nachricht gelöscht',
	'chat:message:members:saved' => 'Mitglied hinzugefügt',
	'chat:message:members:saved:plurar' => '%s Mitglieder aufgenommen',
	'chat:message:left' => 'Sie haben den Chat verlassen.',
	'chat:error:cannot_save' => 'Kann nicht starten Chat.',
	'chat:error:cannot_save_message' => 'Nachricht konnte nicht speichern.',
	'chat:error:cannot_write_to_container' => 'Unzureichender Zugang zu einen Chat in der Gruppe.',
	'chat:error:cannot_add_member' => 'Fehler beim Benutzer %s hinzufügen zu plaudern.',
	'chat:error:cannot_delete' => 'Kann nicht gelöscht Chat.',
	'chat:error:missing:title' => 'Bitte Titel eingeben!',
	'chat:error:missing:members' => 'Keine Mitglieder ausgewählt!',
	'chat:error:cannot_edit_post' => 'Dieser Chat ist möglicherweise nicht vorhanden oder Sie haben keine Berechtigung zum Bearbeiten.',
	'chat:error:cannot_leave' => 'Fehler beim Chat verlassen.',

	// river
	'river:create:object:chat' => '%s begann eine chat %s',

	// notifications
	'chat:newpost' => 'Ein neuer Chat-Beitrag',
	'chat:notification' =>
'
%s Begann eine neue Chat und hat Sie als Teilnehmer.

%s
%s

Registriert den Chat:
%s
',

	// widget
	'chat:widget:description' => 'Zeigen Sie Ihre neuesten Chatnachrichten',
	'chat:morechats' => 'Weitere Chats',
	'chat:numbertodisplay' => 'Anzahl der Chat-Nachrichten vorhanden',
	'chat:nochats' => 'Keine Chat-Nachrichten'
);

add_translation('de', $german);