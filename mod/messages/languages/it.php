<?php
/**
 * @author VMLab
 * @link http://www.vmlab.it/
 */
	$italian = array(
	
		/**
		 * Menu items and titles
		 */
	
			'messages' => "Messaggi",
            'messages:back' => "torna ai messaggi",
			'messages:user' => "Posta in arrivo",
			'messages:sentMessages' => "Posta inviata",
			'messages:posttitle' => "Messaggio di %s: %s",
			'messages:inbox' => "In arrivo",
			'messages:send' => "Invia un messaggio",
			'messages:sent' => "Posta inviata",
			'messages:message' => "Messaggio",
			'messages:title' => "Titolo",
			'messages:to' => "A",
            'messages:from' => "Da",
			'messages:fly' => "Invia",
			'messages:replying' => "Messaggio di risposta a",
			'messages:inbox' => "In arrivo",
			'messages:sendmessage' => "Invia un messaggio",
			'messages:compose' => "Componi un messaggio",
			'messages:sentmessages' => "Posta inviata",
			'messages:recent' => "Messaggi recenti",
            'messages:original' => "Messaggio originale",
            'messages:yours' => "Il tuo messaggio",
            'messages:answer' => "Rispondi",
			'messages:toggle' => 'Seleziona tutti',
			'messages:markread' => 'Segna come gi&agrave; letto',
			
			'messages:new' => 'Nuovo messaggio',
	
			'notification:method:site' => 'Sito',
	
			'messages:error' => 'Si &egrave; verificato un errore nel salvare il tuo messaggio. Per favore riprova.',
	
			'item:object:messages' => 'Messaggi',
	
		/**
		 * Status messages
		 */
	
			'messages:posted' => "Il tuo messaggio &egrave; stato inviato con successo.",
			'messages:deleted' => "Il tuo messaggio &egrave; stato cancellato con successo.",
			'messages:markedread' => "Il tuo messaggio &egrave; stato segnato come letto.",
	
		/**
		 * Email messages
		 */
	
			'messages:email:subject' => 'Hai un nuovo messaggio!',
			'messages:email:body' => "Hai un nuovo messaggio da %s. Ha scritto:

			
%s


Per vedere i tuoi messaggi, clicca qui:

	%s

Per mandare un messaggio a %s, clicca qui:

	%s

Non puoi rispondere a questa email.",
	
		/**
		 * Error messages
		 */
	
			'messages:blank' => "Spiacenti; devi scrivere qualcosa nel messaggio prima di poter salvare.",
			'messages:notfound' => "Spiacenti; non abbiamo potuto trovare il messaggio.",
			'messages:notdeleted' => "Spiacenti; non abbiamo potuto cancellare il messaggio.",
			'messages:nopermission' => "Non hai i permessi per modificare il messaggio.",
			'messages:nomessages' => "Non ci sono messaggi da visualizzare.",
			'messages:user:nonexist' => "Non siamo riusciti a trovare il destinatario nel database degli utenti.",
			'messages:user:blank' => "Non hai selezionato nessuno a cui inviare il messaggio.",
	
	);
					
	add_translation("it",$italian);

?>