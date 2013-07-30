<?php
/**
 * Elgg diagnostics T&#252;rk&#231;e dil paketi.
 *
 * @package ElggDiagnostics
 */

$turkish = array(
	'admin:administer_utilities:diagnostics' => 'Sistem Tan&#305;lama',
	'diagnostics' => 'Sistem tan&#305;lamalar&#305;',
	'diagnostics:report' => 'tan&#305;lama raporu',
	'diagnostics:description' => 'A&#351;a&#287;&#305;daki tan&#305;lama raporu, Elgg hatalar&#305;n&#305; tan&#305;lamak i&#231;in faydal&#305; olabilir. Elgg geli&#351;tiricileri bunu hata raporuna eklemenizi isteyebilir.',
	'diagnostics:download' => '&#304;ndir',
	'diagnostics:header' => '========================================================================
Elgg Tan&#305;lama Raporu
%s %s taraf&#305;ndan olu&#351;turuldu
========================================================================

',
	'diagnostics:report:basic' => '
Elgg S&#252;r&#252;m %s, versiyon %s

------------------------------------------------------------------------',
	'diagnostics:report:php' => '
PHP bilgisi:
%s
------------------------------------------------------------------------',
	'diagnostics:report:plugins' => '
Kurulan eklentiler ve ayr&#305;nt&#305;lar&#305;:

%s
------------------------------------------------------------------------',
	'diagnostics:report:md5' => '
Kurulan dosyalar ve sa&#287;lamalar&#305;:

%s
------------------------------------------------------------------------',
	'diagnostics:report:globals' => '
Global de&#287;i&#351;kenler:

%s
------------------------------------------------------------------------',
);

add_translation("tr", $turkish);
