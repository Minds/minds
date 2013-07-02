<?php

/**
 * Elgg invite Spanish language file
 * 
 * @package ElggInviteFriends
 */

$spanish = array(

	'friends:invite' => 'Invitar amigos',
	
	'invitefriends:registration_disabled' => 'El registro de nuevos usuarios ha sido deshabilitado y no puedes invitar amigos.',
	
	'invitefriends:introduction' => 'Para invitar a amigos para que se unan a esta red, ingresa a continuaci&oacute;n sus direcciones de email (una por linea):',
	'invitefriends:message' => 'Ingresa un mensaje para los que reciban la invitaci&oacute;n:',
	'invitefriends:subject' => 'Invitaci&oacute;n para unirse a %s',

	'invitefriends:success' => 'Se han enviado las invitaciones.',
	'invitefriends:invitations_sent' => 'Invitaciones enviadas: %s. Aqu&iacute; est&aacute;n los siguientes problemas:',
	'invitefriends:email_error' => 'La siguiente direcci&oacute;n de email no es v&aacute;lida: %s',
	'invitefriends:already_members' => 'La siguiente direcci&oacute;n ya est&aacute; registrada por %s',
	'invitefriends:noemails' => 'No se han ingresado direcciones de email.',
	
	'invitefriends:message:default' => '
Hola,

Quisiera invitarte a %s.',

	'invitefriends:email' => '
Has sido invitado a %s por %s. La siguiente incluye el siguiente mensaje:

%s

Para unirte, haz click en el siguiente enlace:

%s

Ser&aacute;s automaticamente agregado como amigo cuando crees tu cuenta.',
	
	);
					
add_translation("es", $spanish);