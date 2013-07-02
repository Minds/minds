<?php
/**
 * Elgg log rotator Spanish language pack.
 *
 * @package ElggLogRotate
 */

$spanish = array(
	'logrotate:period' => '&iquest;Qu&eacute; frecuencioa deseas para rotar los registros?',

	'logrotate:weekly' => 'Semanal',
	'logrotate:monthly' => 'Mensual',
	'logrotate:yearly' => 'Anual',

	'logrotate:logrotated' => "Registro rotado\n",
	'logrotate:lognotrotated' => "Error al rotar los registros\n",
	
	'logrotate:delete' => 'Borrar los registros archivados anteriores a',

	'logrotate:week' => 'una semana',
	'logrotate:month' => 'un mes',
	'logrotate:year' => 'un año',
		
	'logrotate:logdeleted' => "Registro borrado\n",
	'logrotate:lognotdeleted' => "Error al borrar los registros\n",
);

add_translation("es", $spanish);
