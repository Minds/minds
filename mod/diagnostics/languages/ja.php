<?php
/**
 * Elgg diagnostics language pack.
 *
 * @package ElggDiagnostics
 * @author Curverider Ltd
 * @link http://elgg.com/
 * @version 1.8.3
 * @update 2012-1-30
 */

$japanese = array(
	'admin:administer_utilities:diagnostics' => 'システム診断',
	'diagnostics' => 'システム診断',
	'diagnostics:report' => '診断報告書',
	'diagnostics:description' => 'Elggの障害の解析に有効なレポートです。開発者はバグレートを送る際にこれを添付することをお勧めします。',
	'diagnostics:download' => 'Download',
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

add_translation("ja",$japanese);
