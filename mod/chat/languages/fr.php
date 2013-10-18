<?php
/**
 * Chat English language file.
 */

$french = array(
	'chat' => "T'Chat",
	'chat:chats' => 'Bavarder',
	'chat:view:all' => 'Voir tous les chats',
	'chat:chat' => "T'Chat",
	'item:object:chat' => "T'Chat",
	'chat:none' => 'Pas de chats',
	'chat:more' => 'Voir plus',

	'chat:title:user_chats' => '%s\'s Bavarder',
	'chat:title:all_chats' => 'Tous les chats du site',
	'chat:title:friends' => 'Amis\' Bavarder',
	'chat:messages' => "T'Chat messages",
	'chat:members' => 'Ajouter des membres',
	'chat:members:add' => 'Ajouter des membres',
	'chat:leave' => 'Laisser',
	'chat:leave:confirm' => 'Voulez-vous vraiment quitter ce chat?',
	'chat:members:more' => "+%s autres",
	'chat:unread_message' => '%s non lus',
	'chat:unread_messages' => '%s non lus', // Plurar

	'chat:group' => 'Discussion de groupe',
	'chat:enablechat' => 'Activer le chat en groupe',
	'chat:write' => 'Démarrer une conversation',

	// Editing
	'chat:add' => 'Démarrer une conversation',
	'chat:edit' => 'Modifier le chat',
	'chat:members:manage' => 'Ajouter / supprimer des membres',
	'chat:delete:confirm' => 'Voulez-vous vraiment supprimer ce chat et tous les messages dans le?',
	'chat:title' => "T'Chat titre",
	'chat:message' => 'Un message',

	// messages
	'chat:message:saved' => 'Chat enregistrés',
	'chat:message:deleted' => "T'Chat supprimé",
	'chat:message:chat_message:saved' => 'Message enregistré',
	'chat:message:chat_message:deleted' => 'Message supprimé',
	'chat:message:members:saved' => 'Ajouté membre',
	'chat:message:members:saved:plurar' => '%s membres ajoutés',
	'chat:message:left' => 'Vous avez quitté le chat.',
	'chat:error:cannot_save' => 'Impossible de démarrer le chat.',
	'chat:error:cannot_save_message' => "Impossible d'enregistrer un message.",
	'chat:error:cannot_write_to_container' => "L'accès insuffisant à démarrer une conversation en groupe.",
	'chat:error:cannot_add_member' => "Impossible d'ajouter l'utilisateur %s pour discuter.",
	'chat:error:cannot_delete' => 'Impossible de supprimer le chat.',
	'chat:error:missing:title' => "S'il vous plaît entrer le titre!",
	'chat:error:missing:members' => 'Pas de membres choisis!',
	'chat:error:cannot_edit_post' => 'Ce chat peut ne pas exister ou vous ne disposez pas des autorisations pour le modifier.',
	'chat:error:cannot_leave' => 'Impossible de laisser le chat.',

	// river
	'river:create:object:chat' => '%s commencé une conversation %s',

	// notifications
	'chat:newpost' => 'Un nouveau poste de chat',
	'chat:notification' =>
'
%s commencé un nouveau chat et vous ajouté en tant que participant.

%s
%s

Rejoindre le chat:
%s
',

	// widget
	'chat:widget:description' => 'Afficher vos derniers messages de chat',
	'chat:morechats' => 'plus chats',
	'chat:numbertodisplay' => 'Nombre de messages de chat pour afficher',
	'chat:nochats' => 'Pas de messages de chat'
);

add_translation('fr', $french);