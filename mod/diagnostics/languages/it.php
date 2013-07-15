<?php
/**
 * @author VMLab
 * @link http://www.vmlab.it/
 */

	$italian = array(

			'diagnostics' => 'Diagnostica di sistema',
			'diagnostics:unittester' => 'Unit&agrave; di test',

			'diagnostics:description' => 'Il seguente rapporto di diagnostica &egrave; utile per diagnosticare eventuali problemi con il sistema, e dovrebbe essere applicata su qualsiasi segnalazioni di bug sui file.',
			'diagnostics:unittester:description' => 'I seguenti sono test diagnostici che sono registrati dai plugin e possono essere effettuati al fine di eseguire il debug di parti del sistema.',

			'diagnostics:unittester:description' => 'Questa unit&agrave; di test controlla Elgg Core per verificare eventuali API danneggiate.',
			'diagnostics:unittester:debug' => 'Il sito deve essere in modalit&agrave; di debug per eseguire le unit&agrave; di test.',
			'diagnostics:unittester:warning' => 'Attenzione: Questi test possono lasciare oggetti di debug nel database. Non utilizzare su un sito di produzione!',

			'diagnostics:test:executetest' => 'Esegui test',
			'diagnostics:test:executeall' => 'Esegui tutto',
			'diagnostics:unittester:notests' => 'Spiacenti, al momento non sono installate unit&agrave; di test.',
			'diagnostics:unittester:testnotfound' => 'Spiacenti, la relazione non pu&ograve; essere generata perch&egrave; questo test non &egrave; stato trovato',

			'diagnostics:unittester:testresult:nottestclass' => 'Non riuscito - Il risultato non &egrave; un test della classe',
			'diagnostics:unittester:testresult:fail' => 'Non riuscito',
			'diagnostics:unittester:testresult:success' => 'Eseguito',

			'diagnostics:unittest:example' => 'Esempio di unit&agrave; di test, disponibile solo in modalit&agrave; di debug.',

			'diagnostics:unittester:report' => 'Relazione del test per %s',

			'diagnostics:download' => 'Scarica .txt',


			'diagnostics:header' => '========================================================================
Elgg Diagnostic Report
Genereto: %s da %s
========================================================================

',
			'diagnostics:report:basic' => '
Elgg Release %s, versione %s

------------------------------------------------------------------------',
			'diagnostics:report:php' => '
PHP info:
%s
------------------------------------------------------------------------',
			'diagnostics:report:plugins' => '
Plugin installati e dettagli:

%s
------------------------------------------------------------------------',
			'diagnostics:report:md5' => '
File installati e checksums:

%s
------------------------------------------------------------------------',
			'diagnostics:report:globals' => '
Variabili globali:

%s
------------------------------------------------------------------------',

	);

	add_translation("it",$italian);
?>