<?php
/**
* Elgg diagnostics Danish language file
*/
	 
	 

$danish = array(

	'admin:utilities:diagnostics' => 'System diagnostik',
	'diagnostics' => 'System diagnostik',
	'diagnostics:report' => 'Diagnostik rapport',
	'diagnostics:unittester' => 'Enhedstest',
	
	'diagnostics:description' => 'Den følgende diagnostik rapport er anvendelig til at diagnosticere ethvert problem med Elgg, og skal vedhæftes alle bugrapporter.',
	'diagnostics:unittester:description' => 'Følgende er diagnostik tests, som er registreret af plugins og kan udføres for at debugge dele af Elgg frameworket.',
	
	'diagnostics:unittester:description' => 'Enhedstest kontrollerer Elgg Core for defekte eller buggy APIs.',
	'diagnostics:unittester:debug' => 'Siden skal være i debug mode for at køre enhedstest.',
	'diagnostics:unittester:warning' => 'Advarsel: Disse tests kan efterlade debugging objekter i din database. BRUG DEM IKKE PÅ EN FUNGERENDE ONLINE SIDE!',
	
	'diagnostics:test:executetest' => 'Udfør test',
	'diagnostics:test:executeall' => 'Udfør alle',
	'diagnostics:unittester:notests' => 'Beklager, der er ingen enhedstest moduler installeret.',
	'diagnostics:unittester:testnotfound' => 'Beklager, rapporten kunne ikke genereres, fordi testen ikke blev fundet',
	
	'diagnostics:unittester:testresult:nottestclass' => 'FEJL - Resultatet er ikke en test class',
	'diagnostics:unittester:testresult:fail' => 'FEJL',
	'diagnostics:unittester:testresult:success' => 'SUCCES',
	
	'diagnostics:unittest:example' => 'Eksempel på enhedstest er kun tilgængelig i debug mode.',
	
	'diagnostics:unittester:report' => 'Test rapport for %s',
	
	'diagnostics:download' => 'Download .txt',
	
	
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
------------------------------------------------------------------------',

);

add_translation("da",$danish);

?>