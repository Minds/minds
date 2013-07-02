<?php
	/**
	 * Elgg groups plugin language pack
	 *
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	$italian = array(

		/**
		 * Menu items and titles
		 */

			'groups' => "Gruppi",
			'groups:owned' => "Gruppi propri",
			'groups:yours' => "I tuoi gruppi",
			'groups:user' => "Gruppi di %s",
			'groups:all' => "Tutti i gruppi",
			'groups:new' => "Crea un nuovo gruppo",
			'groups:edit' => "Modifica gruppo",
			'groups:delete' => 'Cancella gruppo',
			'groups:membershiprequests' => 'Gestisci richieste di adesione',
			'groups:invitations' => 'Gruppi a cui sei invitato',

			'groups:icon' => 'Icona del gruppo (lascia vuoto per tenerlo invariato)',
			'groups:name' => 'Nome del gruppo',
			'groups:username' => 'Nome corto del gruppo (visualizzato negli URL, solo caratteri alfanumerici))',
			'groups:description' => 'Descrizione',
			'groups:briefdescription' => 'Breve descrizione',
			'groups:interests' => 'Tag',
			'groups:website' => 'Sito web',
			'groups:members' => 'Membri del gruppo',
			'groups:membership' => "Autorizzazioni dei membri del gruppo",
			'groups:access' => "Autorizzazioni di accesso",
			'groups:owner' => "Proprietario",
			'groups:widget:num_display' => 'Numero di gruppi da visualizzare',
			'groups:widget:membership' => 'Gruppi',
			'groups:widgets:description' => 'Visualizza alcuni dei gruppi di cui sei membro',
			'groups:noaccess' => 'Nessun accesso al gruppo',
			'groups:cantedit' => 'Non puoi modificare questo gruppo',
			'groups:saved' => 'Gruppo salvato',
			'groups:featured' => 'Gruppi in primo piano',
			'groups:makeunfeatured' => 'Non in primo piano',
			'groups:makefeatured' => 'Porta in primo piano',
			'groups:featuredon' => 'Hai portato questo gruppo in primo piano.',
			'groups:unfeature' => 'Il gruppo non &egrave; pi&ugrave; in primo piano',
			'groups:joinrequest' => 'Richiedi adesione',
			'groups:join' => 'Partecipa al gruppo',
			'groups:leave' => 'Abbandona il gruppo',
			'groups:invite' => 'Invita i tuoi contatti',
			'groups:inviteto' => "Invita i tuoi contatti su '%s'",
			'groups:nofriends' => "Non hai contatti che non sono stati invitati a questo gruppo.",
			'groups:viagroups' => "via gruppo",
			'groups:group' => "Gruppo",
			'groups:search:tags' => "tag",

			'groups:notfound' => "Gruppo non trovato",
			'groups:notfound:details' => "Il gruppo richiesto non esiste o non hai accesso ad esso",

			'groups:requests:none' => 'Non ci sono richieste di iscrizione in sospeso in questo momento.',

			'groups:invitations:none' => 'Non ci sono inviti in sospeso in questo momento.',

			'item:object:groupforumtopic' => "Argomenti del forum",

			'groupforumtopic:new' => "Aggiungi argomento",

			'groups:count' => "gruppi creati",
			'groups:open' => "gruppo aperto",
			'groups:closed' => "gruppo chiuso",
			'groups:member' => "membri",
			'groups:searchtag' => "Cerca tra i gruppi per tag",


			/*
			 * Access
			 */
			'groups:access:private' => 'Chiuso - Gli utenti devono essere invitati',
			'groups:access:public' => 'Aperto - Tutti gli utenti possono partecipare',
			'groups:closedgroup' => 'Questo &egrave; un gruppo privato. Per chiedere di partecipare, clicca su "Richiedi adesione".',
			'groups:visibility' => 'Chi pu&ograve; vedere questo gruppo?',

			/*
			Group tools
			*/
			'groups:enablepages' => 'Abilita pagine del gruppo',
			'groups:enableforum' => 'Abilita forum del gruppo',
			'groups:enablefiles' => 'Abilita file del gruppo',
			'groups:yes' => 'si',
			'groups:no' => 'no',

			'group:created' => 'Creato %s con %d post',
			'groups:lastupdated' => 'Ultimo aggiornamento %s di %s',
			'groups:pages' => 'Pagine del gruppo',
			'groups:files' => 'File del gruppo',

			/*
			Group forum strings
			*/

			'group:replies' => 'Risposte',
			'groups:forum' => 'Forum del gruppo',
			'groups:addtopic' => 'Aggiungi un argomento',
			'groups:forumlatest' => 'Ultime discussioni',
			'groups:latestdiscussion' => 'Ultime discussioni',
			'groups:newest' => 'Pi&ugrave; recenti',
			'groups:popular' => 'Pi&ugrave; viste',
			'groupspost:success' => 'Il tuo commento &egrave; stato pubblicato con successo',
			'groups:alldiscussion' => 'Ultime discussioni',
			'groups:edittopic' => 'Modifica l\'argomento',
			'groups:topicmessage' => 'Testo dell\'argomento',
			'groups:topicstatus' => 'Stato dell\'argomento',
			'groups:reply' => 'Pubblica un commento',
			'groups:topic' => 'Argomento',
			'groups:posts' => 'Risposte',
			'groups:lastperson' => 'Ultima da',
			'groups:when' => 'Quando',
			'grouptopic:notcreated' => 'Nessun argomento &egrave; stato creato.',
			'groups:topicopen' => 'Aperto',
			'groups:topicclosed' => 'Chiuso',
			'groups:topicresolved' => 'Risolto',
			'grouptopic:created' => 'Il tuo argomento &egrave; stato creato.',
			'groupstopic:deleted' => 'Il tuo argomento &egrave; stato creato.',
			'groups:topicsticky' => 'In evidenza',
			'groups:topicisclosed' => 'Questo argomento &egrave; chiuso.',
			'groups:topiccloseddesc' => 'Questo argomento &egrave; stato chiuso e non si accettano nuovi commenti.',
			'grouptopic:error' => 'Il tuo argomento non pu&ograve; essere creato. Per favore riprova o contatta l\'amministratore di sistema.',
			'groups:forumpost:edited' => "Post modificato con successo.",
			'groups:forumpost:error' => "Si &egrave; verificato un problema nel modificare il post.",
			'groups:privategroup' => 'Questo gruppo &egrave; privato, richiedi una adesione.',
			'groups:notitle' => 'Il gruppo deve avere un titolo',
			'groups:cantjoin' => 'Non puoi partecipare al gruppo',
			'groups:cantleave' => 'Non puoi abbandonare il gruppo',
			'groups:addedtogroup' => 'L\'utente &egrave; stato aggiunto al gruppo con successo',
			'groups:joinrequestnotmade' => 'La richiesta di partecipazione non pu&ograve; essere effettuata',
			'groups:joinrequestmade' => 'La richiesta di partecipazione al gruppo &egrave; avvenuta con successo',
			'groups:joined' => 'Hai aderito al gruppo con successo!',
			'groups:left' => 'Hai abbandonato il gruppo con successo!',
			'groups:notowner' => 'Spiacenti, non sei il proprietario di questo gruppo.',
			'groups:notmember' => 'Spiacenti, non sei membro di questo gruppo.',
			'groups:alreadymember' => 'Sei gi&agrave; un membro di questo gruppo!',
			'groups:userinvited' => 'L\'utente &egrave; stato invitato.',
			'groups:usernotinvited' => 'L\'utente non pu&ograve; essere invitato.',
			'groups:useralreadyinvited' => 'L\'utente &egrave; gi&agrave; stato invitato',
			'groups:updated' => "Ultimo commento",
			'groups:invite:subject' => "%s sei stato invitato a unirti a %s!",
			'groups:started' => "Iniziato da",
			'groups:joinrequest:remove:check' => 'Sei sicuro di voler annullare questa richiesta di adesione?',
			'groups:invite:remove:check' => 'Sei sicuro di voler annullare questo invito?',
			'groups:invite:body' => "Ciao %s,

%s ti ha invitato a partecipare al gruppo '%s'. Clicca sotto per vedere il tuo invito:

%s",

			'groups:welcome:subject' => "Benvenuto nel gruppo %s!",
			'groups:welcome:body' => "Ciao %s!

Sei ora un membro del gruppo  '%s'! Clicca qui sotto per iniziare a pubblicare!

%s",

			'groups:request:subject' => "%s ha richiesto di unirsi a %s",
			'groups:request:body' => "Ciao %s,

%s ha richiesto di unirsi al gruppo '%s', clicca qui sotto per vedere il suo profilo:

%s

o clicca qui sotto per vedere le richieste di adesione al gruppo:

%s",

			/*
				Forum river items
			*/

			'groups:river:member' => '&egrave; adesso membro di',
			'groupforum:river:updated' => '%s ha aggiornato',
			'groupforum:river:update' => 'questo argomento di discussione',
			'groupforum:river:created' => '%s ha creato',
			'groupforum:river:create' => 'un nuovo argomento di discussione intitolato',
			'groupforum:river:posted' => '%s ha pubblicato un nuovo commento',
			'groupforum:river:annotate:create' => 'su questo argomento di discussione',
			'groupforum:river:postedtopic' => '%s ha iniziato un nuovo argomento di discussione intitolato',
			'groups:river:member' => '%s &egrave; adesso membro di',
			'groups:river:togroup' => 'al gruppo',

			'groups:nowidgets' => 'Nessun gadget &egrave; stato definito per questo gruppo.',


			'groups:widgets:members:title' => 'Membri del gruppo',
			'groups:widgets:members:description' => 'Elenca i membri di un gruppo.',
			'groups:widgets:members:label:displaynum' => 'Elenca i membri di un gruppo.',
			'groups:widgets:members:label:pleaseedit' => 'Per favore configura questo gadget.',

			'groups:widgets:entities:title' => "Oggetti nel gruppo",
			'groups:widgets:entities:description' => "Elenca gli oggetti salvati in questo gruppo",
			'groups:widgets:entities:label:displaynum' => 'Elenca gli oggetti di un gruppo.',
			'groups:widgets:entities:label:pleaseedit' => 'Per favore configura questo gadget.',

			'groups:forumtopic:edited' => 'Argomento del forum modificato con successo.',

			'groups:allowhiddengroups' => 'Vuoi permettere gruppi privati (invisibili)?',

			/**
			 * Action messages
			 */
			'group:deleted' => 'Il gruppo e il suo contenuto sono stati rimossi',
			'group:notdeleted' => 'Il gruppo non pu&ograve; essere cancellato',

			'grouppost:deleted' => 'Post eliminato con successo',
			'grouppost:notdeleted' => 'Il post non pu&ograve; essere eliminato',
			'groupstopic:deleted' => 'Argomento cancellato',
			'groupstopic:notdeleted' => 'Argomento non cancellato',
			'grouptopic:blank' => 'Nessun argomento',
			'grouptopic:notfound' => 'Argomento non trovato',
			'grouppost:nopost' => 'Post vuoto',
			'groups:deletewarning' => "Sei sicuro di voler eliminare questo gruppo? Questa azione non pu&ograve; essere annullata!",

			'groups:invitekilled' => 'L\'invito &egrave; stato cancellato.',
			'groups:joinrequestkilled' => 'La richiesta di adesione &egrave; stata cancellata.',
	);

	add_translation("it",$italian);
?>
