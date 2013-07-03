<?php
/**
 * Chat English language file.
 */

$spanish = array(
	'chat' => 'Charlar',
	'chat:chats' => 'Chats',
	'chat:view:all' => 'Ver todos los chats',
	'chat:chat' => 'Charlar',
	'item:object:chat' => 'Charlar',
	'chat:none' => 'No hay chats',
	'chat:more' => 'Ver más',

	'chat:title:user_chats' => '%s\'s chats',
	'chat:title:all_chats' => 'Todo el sitio web chats',
	'chat:title:friends' => 'Amigos \' chats',
	'chat:messages' => 'Mensajes de chat',
	'chat:members' => 'Agregar miembros',
	'chat:members:add' => 'Agregar miembros',
	'chat:leave' => 'Dejar',
	'chat:leave:confirm' => 'De verdad quieres dejar este chat?',
	'chat:members:more' => "+%s otros",
	'chat:unread_message' => '%s no leído',
	'chat:unread_messages' => '%s no leído', // Plurar

	'chat:group' => 'Grupo de chat',
	'chat:enablechat' => 'Activar grupo de chat',
	'chat:write' => 'Iniciar una conversación',

	// Editing
	'chat:add' => 'Iniciar una conversación',
	'chat:edit' => 'Editar chatear',
	'chat:members:manage' => 'Agregar / quitar miembros',
	'chat:delete:confirm' => 'Realmente desea eliminar este chat y todos los mensajes en el mismo?',
	'chat:title' => 'Chatear título',
	'chat:message' => 'Mensaje',

	// messages
	'chat:message:saved' => 'Chat guardada',
	'chat:message:deleted' => 'Chatear borrado',
	'chat:message:chat_message:saved' => 'Mensaje guardado',
	'chat:message:chat_message:deleted' => 'Mensaje borrado',
	'chat:message:members:saved' => 'Miembro añadió',
	'chat:message:members:saved:plurar' => '%s miembros agregaron',
	'chat:message:left' => 'Usted ha dejado el canal.',
	'chat:error:cannot_save' => 'No se puede empezar a chatear.',
	'chat:error:cannot_save_message' => 'Error al guardar el mensaje.',
	'chat:error:cannot_write_to_container' => 'Acceso insuficiente para iniciar un chat en grupo.',
	'chat:error:cannot_add_member' => 'No se pudo añadir el usuario %s para charlar.',
	'chat:error:cannot_delete' => 'No se puede eliminar el chat.',
	'chat:error:missing:title' => 'Introduce el título!',
	'chat:error:missing:members' => 'No hay miembros seleccionados!',
	'chat:error:cannot_edit_post' => 'Esta charla no exista o que no tenga permisos para editarlo.',
	'chat:error:cannot_leave' => 'No se ha podido salir de chat.',

	// river
	'river:create:object:chat' => '%s comenzó una charla %s',

	// notifications
	'chat:newpost' => 'Un nuevo mensaje de chat',
	'chat:notification' =>
'
%s comenzó un nuevo chat y añade como participante.

%s
%s

Únete a la conversación:
%s
',

	// widget
	'chat:widget:description' => 'Muestra tus últimos mensajes de chat',
	'chat:morechats' => 'Más chats',
	'chat:numbertodisplay' => 'Número de mensajes de chat para mostrar',
	'chat:nochats' => 'No hay mensajes de chat'
);

add_translation('es', $spanish);