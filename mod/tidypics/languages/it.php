<?php

/**
 * Italian language pack
 */

$italian = array(
			
		// Menu items and titles			 
			'image' => "Immagine",
			'images' => "Immagini",
			'caption' => "Caption",		
			'photos' => "Foto",
			'album' => "Album fotografico",
			'albums' => "Album fotografici",
			'album:yours' => "Il tuo Album fotografico",
			'album:yours:friends' => "Album Foto degli amici",
			'album:user' => "Album foto di %s",
			'album:friends' => "Album foto degli amici di %s",
			'album:all' => "Tutti gli album di foto",
			'album:group' => "Gruppi di album",
			'item:object:image' => "Foto",
			'item:object:album' => "Album",
			'tidypics:settings:maxfilesize' => "Dimensione massima dei file in kilo bytes (KB):",
			'tidypics:enablephotos' => 'Attiva Group Photo Albums',
			'tidypics:editprops' => 'Modifica le proprietà della immagine',
			'tidypics:upgrade' => 'Aggiorna',
			'tidypics:sort' => 'Ordina il %s album',

		//Settings
			'tidypics:settings:server:analysis' => 'Esegui Analisi del Server',
	
		//actions
			'album:create' => "Crea un nuovo album",
			'album:add' => "Aggiungi un foto Album",
			'album:addpix' => "Aggiungi foto all'album",
			'album:edit' => "Modifica album",
			'album:delete' => "Cancella album",
			'album:sort' => "Ordina album",
			'image:edit' => "Modifica immagine",
			'image:delete' => "Cancella immagine",
			'image:download' => "Download immagine",
		
		//forms
			'album:title' => "Titolo",
			'album:desc' => "Descrizione",
			'album:tags' => "Tags",
			'album:cover' => "Copertina dell'album?",
			'album:cover:yes' => "Si",
			'image:access:note' => "(view access is inherited from the album)",
			'tidypics:uploader:choose' => "Scegli le Foto",
			'tidypics:uploader:upload' => "Carica le Foto",
			'tidypics:uploader:describe' => "Descrivi le Foto",
			'tidypics:uploader:basic' => 'Puoi caricare fino a 10 Foto alla volta (%s MB massimo per foto)',
			'tidypics:sort:instruct' => 'Ordina i Foto Album trascinando le immagini. Poi clicca il bottone salva.',

		//views
			'image:total' => "immagini nell'album:",
			'image:by' => "immagini aggiunte da",
			'album:by' => "Album creato da",
			'album:created:on' => "Creato",
			'image:none' => "Non sono ancora state aggiunte immagini.",
			'image:back' => "Indietro",
			'image:next' => "Avanti",
		
		//widgets
			'album:widget' => "Album Fotografici",
			'album:more' => "Vedi tutti gli albums",
			'album:widget:description' => "Visualizza il tuo ultimo album fotografico",
			'album:display:number' => "Numero di album da mostrare",
			'album:num_albums' => "Numero di album da mostrare",
			
		//  river
			'image:river:created' => "%s uploaded %s %s",
			'image:river:created:multiple' => "%s added %u photos to album %s",
			'image:river:item' => "una immagine",
			'image:river:annotate' => "a comment on the image",
			'image:river:tagged' => "%s tagged %s in the photo %s",
			'image:river:tagged:unknown' => "%s tagged %s in a photo",
			'album:river:created' => "%s created a new photo album: ",
			'album:river:item' => "an album",
			'album:river:annotate' => "un commento nel foto album",
			
		// notifications
			'tidypics:newalbum' => 'Nuovo album di foto',
			
				
		//  Status messages
			'image:saved' => "Immagine salvata.",
			'images:saved' => "Tutte le immagini sono state salvate.",
			'image:deleted' => "Immagine cancellata.",			
			'image:delete:confirm' => "Sei sicuro di volerla cancellare?",
			'images:edited' => "Immagini modificate.",
			'album:edited' => "Album fotografico aggiornato.",
			'album:saved' => "Album fotografico salvato.",
			'album:deleted' => "L'album � stato cancellato.",	
			'album:delete:confirm' => "Sei sicuro di voler cancellare questo album?",
			'album:created' => "Nuovo Album creato.",
			'tidypics:status:processing' => "Attendere ....",
			'tidypics:album:sorted' => "L'album %s è stato ordinato",
				
		//Error messages
			'image:none' => "Non ci sono immagini.",
			'image:uploadfailed' => "il file non � stato caricato:",
			'image:deletefailed' => "L'immagine non puo essere cancellata in questo momento.",
			'image:downloadfailed' => "Spiacente; questa immagine non � attualmente disponibile.",
			'image:notimage' => "Sono accettate immagini jpg, gif o png delle dimensioni entro i limiti.",
			'images:notedited' => "Non tutte le immagini sono state caricate",
			'album:none' => "Nessun album � stato ancora creato.",
			'album:uploadfailed' => "Spiacente; non � possibile salvare l'album.",
			'album:deletefailed' => "L'album non può essere cancellato.",
			'album:blank' => "Dai a quest'album un titolo e una descrizione."
);
					
add_translation("it", $italian);
