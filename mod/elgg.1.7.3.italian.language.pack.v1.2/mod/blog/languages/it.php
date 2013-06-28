<?php

	$italian = array(
	
		/**
		 * Menu items and titles
		 */
	
			'blog' => "Blog",
			'blogs' => "Blog",
			'blog:user' => "Blog di %s",
			'blog:user:friends' => "Blog degli amici di %s",
			'blog:your' => "Il tuo blog",
			'blog:posttitle' => "Blog di %s: %s",
			'blog:friends' => "Blog degli amici",
			'blog:yourfriends' => "Articoli recenti dei tuoi amici",
			'blog:everyone' => "Tutti i blog",
			'blog:newpost' => "Nuovo articolo del blog",
			'blog:via' => "via blog",
			'blog:read' => "Leggi blog",
	
			'blog:addpost' => "Aggiungi un articolo",
			'blog:editpost' => "Modifica articolo",
	
			'blog:text' => "Testo dell'articolo",
	
			'blog:strapline' => "%s",
			
			'item:object:blog' => 'Articoli del blog',
	
			'blog:never' => 'mai',
			'blog:preview' => 'Anteprima',
	
			'blog:draft:save' => 'Salva bozza',
			'blog:draft:saved' => 'Ultimo salvataggio',
			'blog:comments:allow' => 'Permetti commenti',
	
			'blog:preview:description' => 'Questa &egrave; una anteprima del tuo articolo. Non &egrave; ancora stato salvato',
			'blog:preview:description:link' => 'Per continuare a modificare il tuo articolo o salvarlo, clicca qui.',
	
			'blog:enableblog' => 'Abilita blog del gruppo',
	
			'blog:group' => 'Blog del gruppo',

		/**
		 * Blog widget
		 */
		'blog:widget:description' => 'Visualizza le ultime voci inserite nel tuo blog.',
		'blog:moreblogs' => 'Vedi tutti gli articoli',
		'blog:numbertodisplay' => 'Numero di articoli da visualizzare',
		
         /**
	     * Blog river
	     **/
	        
	        //generic terms to use
	        'blog:river:created' => "%s ha creato",
	        'blog:river:updated' => "%s ha aggiornato",
	        'blog:river:posted' => "%s ha pubblicato",
	        
	        //these get inserted into the river links to take the user to the entity
	        'blog:river:create' => "un nuovo articolo intitolato",
	        'blog:river:update' => "un articolo intitolato",
	        'blog:river:annotate' => "un commento su questo articolo",
			'blog:nogroup' => 'Questo gruppo non ha ancora nessun articolo',
	
		/**
		 * Status messages
		 */
	
			'blog:posted' => "Il tuo articolo &egrave; stato pubblicato con successo.",
			'blog:deleted' => "Il tuo articolo &egrave; stato rimosso con successo.",
	
		/**
		 * Error messages
		 */
	
			'blog:error' => 'Si &egrave; verificato un errore. Per favore riprova.',
			'blog:save:failure' => "Non &egrave; stato possibile pubblicare il tuo articolo. Per favore riprova.",
			'blog:blank' => "Spiacenti; devi inserire il testo e il titolo prima di pubblicare l'articolo.",
			'blog:notfound' => "Spiacenti; non abbiamo potuto trovare l'articolo.",
			'blog:notdeleted' => "Spiacenti;non abbiamo potuto rimuovere questo articolo.",
	
	);
					
	add_translation("it",$italian);

?>