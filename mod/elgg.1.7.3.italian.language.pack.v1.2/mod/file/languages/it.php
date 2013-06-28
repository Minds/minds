<?php
	/**
	 * Elgg file plugin language pack
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	$italian = array(
	
		/**
		 * Menu items and titles
		 */
	
			'file' => "File",
			'files' => "File",
			'file:yours' => "I tuoi file",
			'file:yours:friends' => "File degli amici",
			'file:user' => "File di %s",
			'file:friends' => "File degli amici di %s",
			'file:all' => "Tutti i file",
			'file:edit' => "Modifica file",
			'file:more' => "Vedi tutti i file",
			'file:list' => "elenco",
			'file:group' => "File del gruppo",
			'file:gallery' => "galleria",
			'file:gallery_list' => "Galleria o elenco",
			'file:num_files' => "Numero di file da visualizzare",
			'file:user:gallery'=>'Guarda la galleria: %s', 
	        'file:via' => 'via file',
			'file:upload' => "Carica un file",
			'file:replace' => 'Sostituisci il contenuto del file (lascia vuoto per non cambiare il file)',
	
			'file:newupload' => 'Carica nuovo file',
			
			'file:file' => "File",
			'file:title' => "Titolo",
			'file:desc' => "Descrizione",
			'file:tags' => "Tag",
	
			'file:types' => "Tipi di file caricati",
	
			'file:type:all' => "Tutti i file",
			'file:type:video' => "Video",
			'file:type:document' => "Documenti",
			'file:type:audio' => "Audio",
			'file:type:image' => "Immagini",
			'file:type:general' => "Altro",
	
			'file:user:type:video' => "Video di %s",
			'file:user:type:document' => "Documenti di %s",
			'file:user:type:audio' => "Audio di %s",
			'file:user:type:image' => "Immagini di %s",
			'file:user:type:general' => "Tutti i file di%s",
	
			'file:friends:type:video' => "Video dei tuoi amici",
			'file:friends:type:document' => "Documenti dei tuoi amici",
			'file:friends:type:audio' => "Audio dei tuoi amici",
			'file:friends:type:image' => "Immagini dei tuoi amici",
			'file:friends:type:general' => "Tutti i file dei tuoi amici",
	
			'file:widget' => "File gadget",
			'file:widget:description' => "Visualizza i tuoi file pi&ugrave; recenti",
	
			'file:download' => "Scarica questo file",
	
			'file:delete:confirm' => "Sei sicuro di voler cancellare questo file?",
			
			'file:tagcloud' => "Tag",
	
			'file:display:number' => "Numero di file da visualizzare",
	
			'file:river:created' => "%s ha caricato",
			'file:river:item' => "un file",
			'file:river:annotate' => "un commento su questo file",

			'item:object:file' => 'File',
			
	    /**
		 * Embed media
		 **/
		 
		    'file:embed' => "Incorpora file multimediale",
		    'file:embedall' => "Tutto",
	
		/**
		 * Status messages
		 */
	
			'file:saved' => "Il tuo file &egrave; stato salvato con successo.",
			'file:deleted' => "Il tuo file &egrave; stato cancellato con successo.",
	
		/**
		 * Error messages
		 */
	
			'file:none' => "Nessun file caricato.",
			'file:uploadfailed' => "Spiacenti; non siamo riusciti a salvare il tuo file.",
			'file:downloadfailed' => "Spiacenti; questo file non &egrave; disponibile al momento.",
			'file:deletefailed' => "Il tuo file non pu&ograve; essere rimosso in questo momento.",
			'file:noaccess' => "Non hai il permesso di modificare questo file",
			'file:cannotload' => "Si &egrave; verificato un errore durante il caricamento del file",
			'file:nofile' => "Devi selezionare un file",
			'file:nogroup' => 'Questo gruppo non ha ancora nessun file',
	);
					
	add_translation("it",$italian);
?>