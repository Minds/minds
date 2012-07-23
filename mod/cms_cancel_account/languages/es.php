<?php
/**
 * cms_cancel_account English language file
 */

$spanish = array(
	'cms_cancel_account:cancelaccount' => 'Cancelar cuenta',
	'cms_cancel_account:askreason' => 'Indique el motivo de la cancelación de su cuenta:',
	'cms_cancel_account:button:request' => 'Solicitar cancelación',
	'cms_cancel_account:invalidrequest' => 'Ya existe otra solicitud de cancelación pendiente para esta cuenta',
	'cms_cancel_account:successfulrequestsubject' => 'Solicitud de cancelación enviada',
	'cms_cancel_account:successfulrequestmessage' => '%s,
	
Su solicitud de cancelación ha sido enviada correctamente.
	
Tan pronto su cuenta haya sido cancelada recibirá una notificación por correo electrónico.',
	'admin:users:cancellations' => 'Solicitudes de cancelación',
	'cms_cancel_account:reason' => 'Motivo: ',
	'cms_cancel_account:check_all' => 'Todos',
	'cms_cancel_account:admin:delete' => 'Eliminar',
	'cms_cancel_account:confirm_delete_checked' => '¿Eliminar los usuarios seleccionados?',
	'cms_cancel_account:confirm_delete' => 'Eliminar a %s?',
	'cms_cancel_account:errors:unknown_users' => 'Usuarios desconocidos',

	'cms_cancel_account:messages:deleted_user' => 'Usuario eliminado.',
	'cms_cancel_account:messages:deleted_users' => 'Eliminados todos los usuarios seleccionados.',
	'cms_cancel_account:errors:could_not_delete_user' => 'No se pudo eliminar el usuario.',
	'cms_cancel_account:errors:could_not_delete_users' => 'No se pudieron eliminar todos los usuarios seleccionados.',
	'cms_cancel_account:admin:no_requests' => 'No hay solicitudes de cancelación.',

	'cms_cancel_account:mail:failedcancellationsubject' => 'Cancelación de cuenta incompleta',
	'cms_cancel_account:mail:failedcancellationmessage' => '%s,
Ha habido un problema durante la cancelación de su cuenta.

Por favor, póngase en contacto con el webmaster.',
	'cms_cancel_account:mail:successfulcancellationsubject' => 'Cancelación de cuenta completada con éxito',
	'cms_cancel_account:mail:successfulcancellationmessage' => '%s,
Su cuenta ha sido cancelada con éxito.

Gracias por haber formado parte de nuestra red social.

Si en cualquier momento quiere volver a registrarse, nos encontrará en %s.',

);

add_translation("es", $spanish);