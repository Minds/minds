<?php
/**
 * @author VMLab
 * @link http://www.vmlab.it/
 */
	$italian = array(
	
		/**
		 * Menu items and titles
		 */
	
			'thewire' => "Bacheca",
			'thewire:user' => "Bacheca di %s",
			'thewire:posttitle' => "Messaggi in bacheca di %s: %s",
			'thewire:everyone' => "Tutti i messaggi in bacheca",
	
			'thewire:read' => "Messaggi in bacheca",
			
			'thewire:strapline' => "%s",
	
			'thewire:add' => "Pubblica in bacheca",
		    'thewire:text' => "Un appunto sulla bacheca",
			'thewire:reply' => "Rispondi",
			'thewire:via' => "via",
			'thewire:wired' => "Pubblicato in bacheca",
			'thewire:charleft' => "caratteri rimanenti",
			'item:object:thewire' => "Messaggi in bacheca",
			'thewire:notedeleted' => "appunto rimosso",
			'thewire:doing' => "Cosa stai facendo? Dillo a tutti sulla tua bacheca:",
			'thewire:newpost' => 'Nuovo messaggio in bacheca',
			'thewire:addpost' => 'Pubblica in bacheca',
			'thewire:by' => "Post in bacheca di %s",

	
        /**
	     * The wire river
	     **/
	        
	        //generic terms to use
	        'thewire:river:created' => "%s ha pubblicato",
	        
	        //these get inserted into the river links to take the user to the entity
	        'thewire:river:create' => "in bacheca.",
	        
	    /**
	     * Wire widget
	     **/
	     
	        'thewire:sitedesc' => 'Questo gadget mostra gli ultimi appunti sulla tua bacheca',
	        'thewire:yourdesc' => 'Questo gadget mostra gli ultimi appunti sulla tua bacheca',
	        'thewire:friendsdesc' => 'QUesto gadget mostrer&agrave; gli ultimi messaggi dei tuoi amici in bacheca',
	        'thewire:friends' => 'La bacheca dei tuoi amici',
	        'thewire:num' => 'Numero di elementi da visualizzare',
	        'thewire:moreposts' => 'Tutti i messaggi in bacheca',
	        
	
		/**
		 * Status messages
		 */
	
			'thewire:posted' => "Il tuo messaggio &egrave; stato correttamente pubblicato in bacheca.",
			'thewire:deleted' => "Il tuo messaggio &egrave; stato correttamente cancellato.",
	
		/**
		 * Error messages
		 */
	
			'thewire:blank' => "Spiacenti; devi scrivere qualcosa prima di poter salvare.",
			'thewire:notfound' => "Spiacenti; non abbiamo trovato il messaggio cercato.",
			'thewire:notdeleted' => "Spiacenti; non possiamo eliminare questo messaggio.",
	
	
		/**
		 * Settings
		 */
			'thewire:smsnumber' => "Your SMS number if different from your mobile number (mobile number must be set to public for the wire to be able to use it). All phone numbers must be in international format.",
			'thewire:channelsms' => "The number to send SMS messages to is <b>%s</b>",
			
	);
					
	add_translation("it",$italian);

?>