<?php
/**
 * Elgg diagnostics Spanish language pack.
 *
 * @package ElggDiagnostics
 */

$spanish = array(
	'admin:administer_utilities:diagnostics' => 'Diagn&oacute;sticos de sistema',
	'diagnostics' => 'Diagn&oacute;sticos',
	'diagnostics:report' => 'Reporte de Diagn&oacute;sticos',
	'diagnostics:description' => 'Los siguientes reportes de diagn&oacute;stico pueden ser &uacute;tiles para encontrar problemas en Elgg. Los desarrolladores de Elgg requieren que incluya estos resultados en los reportes de error.',
	'diagnostics:download' => 'Descargar',
// The following should not be translated because is the report that must be sended to the Elgg developers in case of bugs.
	'diagnostics:header' => '========================================================================
Elgg Diagnostic Report
Generated %s by %s
========================================================================

',
	'diagnostics:report:basic' => '
Elgg Release %s, version %s

------------------------------------------------------------------------',
	'diagnostics:report:php' => '
PHP info:
%s
------------------------------------------------------------------------',
	'diagnostics:report:plugins' => '
Installed plugins and details:

%s
------------------------------------------------------------------------',
	'diagnostics:report:md5' => '
Installed files and checksums:

%s
------------------------------------------------------------------------',
	'diagnostics:report:globals' => '
Global variables:

%s
-------------------------------------------------------------------------',
);

add_translation("es", $spanish);