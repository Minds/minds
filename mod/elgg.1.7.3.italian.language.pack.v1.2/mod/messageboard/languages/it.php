<?php
/**
 * @author VMLab
 * @link http://www.vmlab.it/
 */
	$italian = array(
	
		/**
		 * Menu items and titles
		 */
	
			'messageboard:board' => "Lavagna",
			'messageboard:messageboard' => "lavagna",
			'messageboard:viewall' => "Vedi tutto",
			'messageboard:postit' => "Pubblica",
			'messageboard:history:title' => "Cronologia",
			'messageboard:none' => "Non c'&egrave; niente su questa lavagna ancora",
			'messageboard:num_display' => "Numero di messaggi da visualizzare",
			'messageboard:desc' => "Questa &egrave; una lavagna. Puoi posizionarla sul tuo profilo cos&igrave; i tuoi amici possono lasciarti dei messaggi",
	
			'messageboard:user' => "Lavagna di %s",
	
			'messageboard:replyon' => 'rispondi sulla',
			'messageboard:history' => "cronologia",
	
         /**
	     * Message board widget river
	     **/
	        
	        'messageboard:river:annotate' => "%s ha avuto un nuovo commento sulla sua lavagna.",
	        'messageboard:river:create' => "%s ha aggiunto il gadget lavagna.",
	        'messageboard:river:update' => "%s ha aggiornato la sua lavagna.",
	        'messageboard:river:added' => "%s ha scritto sulla lavagna di",
		    'messageboard:river:messageboard' => "lavagna",

			
		/**
		 * Status messages
		 */
	
			'messageboard:posted' => "Hai scritto correttamente sulla lavagna.",
			'messageboard:deleted' => "Hai rimosso il messaggio con successo.",
	
		/**
		 * Email messages
		 */
	
			'messageboard:email:subject' => 'Hai un nuovo commento sulla lavagna!',
			'messageboard:email:body' => "Hai un nuovo commento da %s. Ha scritto:

			
%s


Per vedere i commenti sulla tua lavagna, clicca qui:

	%s

Per vedere il profilo di %s, clicca qui:

	%s

Non puoi rispondere a questa email.",
	
		/**
		 * Error messages
		 */
	
			'messageboard:blank' => "Spiacenti; devi scrivere qualcosa prima di poter salvare.",
			'messageboard:notfound' => "Spiacenti; non abbiamo potuto trovare l'elemento cercato.",
			'messageboard:notdeleted' => "Spiacenti; non abbiamo potuto cancellare il messaggio.",
			'messageboard:somethingwentwrong' => "Qualcosa &egrave; andato storto durante il tentativo di salvare il messaggio, accertarsi che effettivamente hai inviato un messaggio.",
	     
			'messageboard:failure' => "Si &egrave; verificato un errore imprevisto aggiungendo il tuo messaggio. Per favore riprova.",
	
	);
					
	add_translation("it",$italian);

?>