<?php
/**
 * @author VMLab
 * @link http://www.vmlab.it/
 */

$italian = array(

/**
 * Sites
 */

	'item:site' => 'Site',

/**
 * Sessions
 */

	'login' => "Entra",
	'loginok' => "Benvenuto su Elgg.",
        'loginerror'  =>  "Non possiamo farti entrare. Questo pu&ograve; accadere perch&egrave; non hai ancora convalidato il tuo account, o i dati che hai fornito non sono corretti. Assicurati che i tuoi dati siano corretti e per favore riprova." ,
	'logout' => "Esci",
	'logoutok' => "Arrivederci!",
	'logouterror' => "Si &egrave; verificato un errore mentre cercavi di uscire, per favore riprova.",

	'loggedinrequired' => "Devi effettuare l'accesso per vedere questa pagina.",
	'adminrequired' => "Devi essere amministratore per vedere questa pagina.",
	'membershiprequired' => "Devi essere un membro del gruppo per vedere questa pagina.",


/**
 * Errors
 */
	'exception:title' => "Benvenuto su Elgg.",

	'InstallationException:CantCreateSite' => "Non siamo riusciti a creare un sito di default con queste credenziali Nome: %s, Url: %s.",

	'actionundefined' => "L'operazione richesta (%s) non &egrave; definita nel sistema.",
	'actionloggedout' => "Attenzione, non puoi compiere questa operazione senza autenticarti.",

	'SecurityException:Codeblock' => "Accesso negato per l'esecuzione di codice.",
	'DatabaseException:WrongCredentials' => "Elgg non ha potuto connettersi al database con le credenziali fornite.",
	'DatabaseException:NoConnect' => "Elgg non ha potuto selezionare il database '%s', per favore controlla se il database &egrave; stato creato e se si ha accesso ad esso.",
	'SecurityException:FunctionDenied' => "Accesso negato alla funzione privilegiata '%s'.",
	'DatabaseException:DBSetupIssues' => "Si sono verificati alcuni problemi: ",
	'DatabaseException:ScriptNotFound' => "Elgg non ha potuto trovare lo script del database %s.",

	'IOException:FailedToLoadGUID' => "Non &egrave; stato possibile caricare %s da GUID:%d",
	'InvalidParameterException:NonElggObject' => "Hai passato un tipo di oggetto errato al costruttore (Object)!",
	'InvalidParameterException:UnrecognisedValue' => "Hai passato un valore sconosciuto al costruttore.",

	'InvalidClassException:NotValidElggStar' => "GUID:%d non &egrave; un %s valido",

	'PluginException:MisconfiguredPlugin' => "%s &egrave; un plugin non configurato e non &egrave; stato abilitato.",

	'InvalidParameterException:NonElggUser' => "Hai passato un tipo di oggetto errato al costruttore (User)!",

	'InvalidParameterException:NonElggSite' => "Hai passato un tipo di oggetto errato al costruttore (Site)!",

	'InvalidParameterException:NonElggGroup' => "Hai passato un tipo di oggetto errato al costruttore (Group)!",

	'IOException:UnableToSaveNew' => "Non siamo riusciti a salvare il nuovo %s",

	'InvalidParameterException:GUIDNotForExport' => "Il GUID non &egrave; stato specificato durante l'esportazione, questo non dovrebbe accadere mai.",
	'InvalidParameterException:NonArrayReturnValue' => "L'entita di serializzazione ha ritornato un valore non corretto (non-array)",

	'ConfigurationException:NoCachePath' => "Il percorso della cache non &egrave; impostato!",
	'IOException:NotDirectory' => "%s non &egrave; una directory.",

	'IOException:BaseEntitySaveFailed' => "Impossibile salvare le nuove impostazioni di base dell'oggetto!",
	'InvalidParameterException:UnexpectedODDClass' => "import() ha passato una classe ODD inaspettatamente",
	'InvalidParameterException:EntityTypeNotSet' => "Il tipo dell'Entit&agrave; deve essere impostato.",

	'ClassException:ClassnameNotClass' => "%s non &egrave; un %s.",
	'ClassNotFoundException:MissingClass' => "La Classe '%s' non &egrave; stata trovata, manca il plugin?",
	'InstallationException:TypeNotSupported' => "Il tipo %s non &egrave; supportato. Questo sta ad indicare un errore nella tua installazione, probabilmente causato da un upgrade incompleto.",

	'ImportException:ImportFailed' => "Non &egrave; possibile importare l'elemento %d",
	'ImportException:ProblemSaving' => "Si &egrave; verificato un problema nel salvataggio %s",
	'ImportException:NoGUID' => "Una nuova entit&agrave; &egrave; stata creata ma non ha un GUID, questo non dovrebbe accadere.",

	'ImportException:GUIDNotFound' => "L'entit&agrave; '%d' non pu&ograve; essere trovata.",
	'ImportException:ProblemUpdatingMeta' => "Si &egrave; verificato un problema con l'aggiornamento '%s' nell'entit&agrave; '%d'",

	'ExportException:NoSuchEntity' => "Nessuna entit&agrave; simile a GUID:%d",

	'ImportException:NoODDElements' => "Nessun elemento OpenDD trovato nei dati importati, importazione fallita.",
	'ImportException:NotAllImported' => "Non tutti gli elementi sono stati importati.",

	'InvalidParameterException:UnrecognisedFileMode' => "Modalit&agrave; file non riconosciuta '%s'",
	'InvalidParameterException:MissingOwner' => "File %s (file guid:%d) (guid proprietario:%d) manca un proprietario!",
	'IOException:CouldNotMake' => "Non si pu&ograve; eseguire %s",
	'IOException:MissingFileName' => "Devi specificare un nome prima di aprire il file.",
	'ClassNotFoundException:NotFoundNotSavedWithFile' => "La cartella per il salvataggio dei File non &egrave; stata trovata o la classe non &egrave; stata salvata con un file!",
	'NotificationException:NoNotificationMethod' => "Nessun metodo di notifica &egrave; stato specificato.",
	'NotificationException:NoHandlerFound' => "Nessun gestore trovato per '%s' o non &egrave; possibile richiamarlo.",
	'NotificationException:ErrorNotifyingGuid' => "Si &egrave; presentato un errore nel notificare %d",
	'NotificationException:NoEmailAddress' => "Non &egrave; stato possibile ottenere l'indirizzo email per GUID:%d",
	'NotificationException:MissingParameter' => "Manca un parametro richiesto, '%s'",

	'DatabaseException:WhereSetNonQuery' => "L'impostazione non contiene un WhereQueryComponent",
	'DatabaseException:SelectFieldsMissing' => "Campi mancanti nella query per selezionare lo stile.",
	'DatabaseException:UnspecifiedQueryType' => "Tipo di query sconosciuto o non riconosciuto.",
	'DatabaseException:NoTablesSpecified' => "Nessuna tabella specificata per la query.",
	'DatabaseException:NoACL' => "Non &egrave; stato fornito nessun controllo di accesso per la query",

	'InvalidParameterException:NoEntityFound' => "Nessuna entit&agrave; trovata, potrebbe non esistere o potresti non avere i diritti di accesso.",

	'InvalidParameterException:GUIDNotFound' => "GUID:%s non pu&ograve; essere trovata, o potresti non avere i diritti di accesso.",
	'InvalidParameterException:IdNotExistForGUID' => "Spiacenti, '%s' non esiste per guid:%d",
	'InvalidParameterException:CanNotExportType' => "Spiacenti, non riesco ad esportare '%s'",
	'InvalidParameterException:NoDataFound' => "Non &egrave; stato trovato nessun dato.",
	'InvalidParameterException:DoesNotBelong' => "Non appartengono all'entit&agrave;.",
	'InvalidParameterException:DoesNotBelongOrRefer' => "Non appartengono all'entit&agrave; o non esistono riferimenti ad essa.",
	'InvalidParameterException:MissingParameter' => "Parametro mancante, devi fornire un GUID.",

	'APIException:ApiResultUnknown' => "Il risultato dell'API &egrave; di un tipo sconosciuto, questo non dovrebbe mai accadere.",
	'ConfigurationException:NoSiteID' => "L'ID del sito non &egrave; stato specificato.",
	'SecurityException:APIAccessDenied' => "Spiacenti, l'accesso a questa API &egrave; stato disabilitato dall'amministratore.",
	'SecurityException:NoAuthMethods' => "Non &egrave; stato trovato nessun metodo di autenticazione per risolvere questa richiesta API.",
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "Metodo o funzione non configurata nella chiamata a expose_method()",
	'InvalidParameterException:APIParametersArrayStructure' => "La struttura array dei parametri non &egrave; corretta per chiamare il metodo '%s'",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Metodo http non riconosciuto %s per il metodo API '%s'",
	'APIException:MissingParameterInMethod' => "Parametro mancante %s nel metodo %s",
	'APIException:ParameterNotArray' => "%s non sembra essere un array.",
	'APIException:UnrecognisedTypeCast' => "Tipo sconosciuto nel cast %s per la variabile '%s' nel metodo '%s'",
	'APIException:InvalidParameter' => "Trovato parametro non valido '%s' nel metodo '%s'.",
	'APIException:FunctionParseError' => "%s(%s) ha un errore di parsing.",
	'APIException:FunctionNoReturn' => "%s(%s) non ha restituito nessun valore.",
	'APIException:APIAuthenticationFailed' => "Il metodo chiamato ha fallito l'autenticazione API",
	'APIException:UserAuthenticationFailed' => "Il metodo chiamato ha fallito l'autenticazione Utente",
	'SecurityException:AuthTokenExpired' => "Dati di autenticazione mancanti, non validi o scaduti.",
	'CallException:InvalidCallMethod' => "%s deve essere chiamato usando '%s'",
	'APIException:MethodCallNotImplemented' => "Il metodo chiamato '%s' non &egrave; stato implementato.",
	'APIException:FunctionDoesNotExist' => "La funzione o il metodo '%s' non &egrave; disponibile",
	'APIException:AlgorithmNotSupported' => "L'algoritmo '%s' non &egrave; supportato o &egrave; stato disabilitato.",
	'ConfigurationException:CacheDirNotSet' => "Il percorso della Cache 'cache_path' non &egrave; impostato.",
	'APIException:NotGetOrPost' => "La richiesta deve essere di tipo GET o POST",
	'APIException:MissingAPIKey' => "Manca la API key",
	'APIException:BadAPIKey' => "API key errata",
	'APIException:MissingHmac' => "Manca il gestore X-Elgg-hmac",
	'APIException:MissingHmacAlgo' => "Manca il gestore X-Elgg-hmac-algo",
	'APIException:MissingTime' => "Manca il gestore X-Elgg-time",
	'APIException:MissingNonce' => "Manca il gestore X-Elgg-nonce",
	'APIException:TemporalDrift' => "X-Elgg-time &egrave; troppo lontano nel passato o nel futuro. Epoch fail.",
	'APIException:NoQueryString' => "Non sono disponibili dati nella stringa di ricerca",
	'APIException:MissingPOSTHash' => "Manca il gestore X-Elgg-posthash",
	'APIException:MissingPOSTAlgo' => "Manca il gestore X-Elgg-posthash_algo",
	'APIException:MissingContentType' => "Manca il tipo di contenuto per i dati del post",
	'SecurityException:InvalidPostHash' => "POST hash dei dati non valido - Aspettato %s ma ricevuto %s.",
	'SecurityException:DupePacket' => "Tipo di firma gi&agrave; visto.",
	'SecurityException:InvalidAPIKey' => "API Key mancante o non valido.",
	'NotImplementedException:CallMethodNotImplemented' => "La chiamata al metodo '%s' &egrave; al momento non supportata.",

	'NotImplementedException:XMLRPCMethodNotImplemented' => "La chiamata al metodo XML-RPC '%s' non &egrave; implementata.",
	'InvalidParameterException:UnexpectedReturnFormat' => "La chiamata al metodo '%s' ha restituito un valore inaspettato.",
	'CallException:NotRPCCall' => "La chiamata XML-RPC sembra non essere un tipo valido",

	'PluginException:NoPluginName' => "Il nome del plugin non pu&ograve; essere trovato",

	'ConfigurationException:BadDatabaseVersion' => "Il database installato non soddisfa i requisiti di base per installare Elgg. Si prega di consultare la documentazione.",
	'ConfigurationException:BadPHPVersion' => "Devi avere la versione 5.2 o superiore di PHP per installare Elgg.",
	'configurationwarning:phpversion' => "Devi avere la versione 5.2 o superiore di PHP per installare Elgg, puoi installarlo anche con la versione 5.1.6 ma alcune caratteristiche potrebbero non funzionare. Fai attenzione nell'installarlo.",


	'InstallationException:DatarootNotWritable' => "La tua cartella dei dati %s non &egrave; scrivibile.",
	'InstallationException:DatarootUnderPath' => "La tua cartella dei dati %s deve essere esterna al percorso di installazione di Elgg.",
	'InstallationException:DatarootBlank' => "Non hai specificato una cartela per i dati.",

	'SecurityException:authenticationfailed' => "L'utente potrebbe non essere autenticato.",

	'CronException:unknownperiod' => '%s non &egrave; un periodo riconosciuto.',

	'SecurityException:deletedisablecurrentsite' => 'Non &egrave; possibile eliminare o disabilitare il sito che si sta visualizzando!',

	'memcache:notinstalled' => 'Il modulo PHP memcache non &egrave; installato, devi installare php5-memcache',
	'memcache:noservers' => 'Nessun server memcache definito, si prega di configurare la variabile $CONFIG->memcache_servers',
	'memcache:versiontoolow' => 'Memcache ha bisogno almeno della versione %s per essere eseguito, tu stai eseguendo la versione %s',
	'memcache:noaddserver' => 'Il supporto per i server multipli &egrave; disabilitato, devi aggiornare la tualibreria PECL memcache.',

	'deprecatedfunction' => 'Attenzione: questo codice usa una funzione superata \'%s\' e non &egrave; compatibile con questa versione di Elgg.',

	'pageownerunavailable' => 'Attenzione: la pagina proprietaria %d non &egrave; accessibile!',
/**
 * API
 */
	'system.api.list' => "Elenca tutte le chiamate API disponibili sul sistema.",
	'auth.gettoken' => "Questa chiamata API permette all'utente di ottenere un token di autenticazione che pu&ograve; essere usato per le future autenticazioni API. Passarlo come parametro auth_token",

/**
 * User details
 */

	'name' => "Nome completo",
	'email' => "Indirizzo Email",
	'username' => "Username",
	'password' => "Password",
	'passwordagain' => "Password (di nuovo per verifica)",
	'admin_option' => "Rendere questo utente amministratore?",

/**
 * Access
 */

	'PRIVATE' => "Privato",
	'LOGGED_IN' => "Utenti autenticati",
	'PUBLIC' => "Pubblico",
	'access:friends:label' => "Amici",
	'access' => "Accesso",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Bacheca",
	'dashboard:configure' => "Modifica pagina",
	'dashboard:nowidgets' => "La <b>Bacheca</b> &egrave; il tuo punto di accesso alla piattaforma. Clicca su 'Modifica pagina' per aggiungere i <b>Gadget</b>, per tenere traccia dei contenuti e delle attivit&agrave; all'interno di Elgg.",

	'widgets:add' => 'Aggiungi Gadget alla tua pagina',
	'widgets:add:description' => "Scegli le caratterisctiche che vuoi aggiungere alla tua pagina trascinandoli dalla <b>Galleria dei Gadget</b> sulla destra, in qualcuna delle tre aree per i <b>Gadget</b> qui sotto, e posizionali dove vuoi che vengano visualizzati.

Per rimuovere un <b>Gadget</b> trascinalo nella <b>Galleria dei Gadget</b>.",
	'widgets:position:fixed' => '(Posizione fissa nella pagina)',

	'widgets' => "Gadgets",
	'widget' => "Gadget",
	'item:object:widget' => "Gadget",
	'layout:customise' => "Personalizza disposizione",
	'widgets:gallery' => "Galleria dei Gadget",
	'widgets:leftcolumn' => "Gadget di sinistra",
	'widgets:fixed' => "Posizione fissa",
	'widgets:middlecolumn' => "Gadget centrali",
	'widgets:rightcolumn' => "Gadget di destra",
	'widgets:profilebox' => "Profilo",
	'widgets:panel:save:success' => "I tuoi gadget sono stati salvati correttamente.",
	'widgets:panel:save:failure' => "Si &egrave; verificato un probleba nel salvare i tuoi gadget. Per favore riprova.",
	'widgets:save:success' => "Il gadget &egrave; stato salvato correttamente.",
	'widgets:save:failure' => "Non abbiamo potuto salvare il tuo gadget. Per favore riprova.",
	'widgets:handlernotfound' => 'Questo gadget &egrave; danneggiato o &egrave; stato disabilitato da Elgg.',

/**
 * Groups
 */

	'group' => "Gruppo",
	'item:group' => "Gruppi",

/**
 * Users
 */

	'user' => "Utenti",
	'item:user' => "Utenti",

/**
 * Friends
 */

	'friends' => "Amici",
	'friends:yours' => "I tuoi amici",
	'friends:owned' => "Amici di %s",
	'friend:add' => "Aggiungi agli amici",
	'friend:remove' => "Rimuovi dagli amici",

	'friends:add:successful' => "Hai aggiunto correttamente %s ai tuoi amici.",
	'friends:add:failure' => "Non abbiamo potuto aggiungere %s ai tuoi amici. Per favore riprova.",

	'friends:remove:successful' => "Hai rimosso correttamente %s dai tuoi amici.",
	'friends:remove:failure' => "Non abbiamo potuto rimuovere %s dai tuoi amici. Per favore riprova.",

	'friends:none' => "Questo utente non ha ancora aggiunto nessuno come amico.",
	'friends:none:you' => "Non hai ancora aggiunto nessuno come amico! Cerca i tuoi interessi per iniziare a trovare i tuoi amici.",

	'friends:none:found' => "Nessun amico &egrave; stato trovato.",

	'friends:of:none' => "Nessuno ha ancora aggiunto questo utente come amico.",
	'friends:of:none:you' => "Nessuno ti ha ancora aggiunto come amico. Inizia a inserire contenuti e completa il tuo profilo per permettere agli altri utenti di trovarti!",

	'friends:of:owned' => "Utenti che hanno aggiunto %s come amico",

	'friends:num_display' => "Numero di amici da visualizzare",
	'friends:icon_size' => "Dimensione icone",
	'friends:tiny' => "piccolo",
	'friends:small' => "medio",
	'friends:of' => "Richieste di amicizia",
	'friends:collections' => "Liste di amici",
	'friends:collections:add' => "Nuova lista di amici",
	'friends:addfriends' => "Aggiungi amici",
	'friends:collectionname' => "Nome del gruppo",
	'friends:collectionfriends' => "Amici nella  lista",
	'friends:collectionedit' => "Modifica questa lista",
	'friends:nocollections' => "Non hai ancora nessuna lista.",
	'friends:collectiondeleted' => "La tua lista &egrave; stato cancellato.",
	'friends:collectiondeletefailed' => "Non siamo stati in grado di eliminare la lista. O non hai il permesso, o si &egrave; verificato qualche altro problema.",
	'friends:collectionadded' => "La tua lista &egrave; stata creata correttamente",
	'friends:nocollectionname' => "Devi dare un nome alla tua lista prima che possa essere creata.",
	'friends:collections:members' => "Membri della lista",
	'friends:collections:edit' => "Modifica lista",

	'friends:river:created' => "%s ha aggiunto il gadget Amici.",
	'friends:river:updated' => "%s hanno aggiornato il loro gadget Amici.",
	'friends:river:delete' => "%s hanno rimosso il loro gadget Amici.",
	'friends:river:add' => "%s &egrave; adesso amico di ",

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

/**
 * Feeds
 */
	'feed:rss' => 'Sottoscrivi i feed',
	'feed:odd' => 'Sindacato OpenDD',

/**
 * links
 **/

	'link:view' => 'vedi link',


/**
 * River
 */
	'river' => "River",
	'river:relationship:friend' => ' &egrave; adesso amico di ',
	'river:noaccess' => 'Spiacenti, non hai i permessi per visualizzare questa risorsa.',
	'river:posted:generic' => '%s ha pubblicato',
	'riveritem:single:user' => 'un utente',
	'riveritem:plural:user' => 'pi&ugrave; utenti',

/**
 * Plugins
 */
	'plugins:settings:save:ok' => "Le impostazioni per il plugin %s sono state salvate correttamente.",
	'plugins:settings:save:fail' => "Si &egrave; verificato un problema nel salvare le impostazioni per il plugin %s.",
	'plugins:usersettings:save:ok' => "Le impostazioni utente per il plugin %s sono state salvate correttamente.",
	'plugins:usersettings:save:fail' => "Si &egrave; verificato un problema nel salvare le impostazioni utente per il plugin %s.",
	'admin:plugins:label:version' => "Versione",
	'item:object:plugin' => 'Preferenze di configurazione per i plugin',

/**
 * Notifications
 */
	'notifications:usersettings' => "Impostazioni delle notifiche",
	'notifications:methods' => "Per favore specifica quale metodo vuoi permettere.",

	'notifications:usersettings:save:ok' => "Le tue impostazioni di notifica sono state correttamente salvate.",
	'notifications:usersettings:save:fail' => "Si &egrave; verificato un problema nel salvare le tue impostazioni di notifica.",

	'user.notification.get' => 'Restituisce le impostazioni di notifica per un dato utente.',
	'user.notification.set' => 'Configura le impostazioni di notifica per un dato utente.',
/**
 * Search
 */

	'search' => "Cerca",
	'searchtitle' => "Cerca: %s",
	'users:searchtitle' => "Cerca tra gli utenti: %s",
	'groups:searchtitle' => "Cerca tra i gruppi: %s",
	'advancedsearchtitle' => "%s con i risultati corrispondenti %s",
	'notfound' => "Nessun risultato trovato.",
	'next' => "Avanti",
	'previous' => "Indietro",

	'viewtype:change' => "Cambia il tipo di lista",
	'viewtype:list' => "Elenco",
	'viewtype:gallery' => "Galleria",

	'tag:search:startblurb' => "Elementi con tag corrispondenti '%s':",

	'user:search:startblurb' => "Utenti corrispondenti '%s':",
	'user:search:finishblurb' => "Per vedere tutto, clicca qui.",

	'group:search:startblurb' => "Gruppi corrispondenti '%s':",
	'group:search:finishblurb' => "Per vedere tutto, clicca qui.",
	'search:go' => 'Vai',
	'userpicker:only_friends' => 'Solo amici',

/**
 * Account
 */

	'account' => "Account",
	'settings' => "Impostazioni",
	'tools' => "Strumenti",
	'tools:yours' => "I tuoi strumenti",

	'register' => "Registrati",
	'registerok' => "Complimenti, ti sei registrato con successo come %s.",
	'registerbad' => "Spiacenti, la tua registrazione non &egrave; andata a buon fine. L'username che hai scelto &egrave; gi&agrave; esistente, la tua password non corrisponde, il tuo username o la tua password potrebbero essere troppo corti.",
	'registerdisabled' => "La registrazione &egrave; stata disabilitata dall'amministratore",

	'firstadminlogininstructions' => 'Elgg &egrave; stato correttamente installato ed &egrave; stato creato il tuo account da amministratore. Ora &egrave; possibile configurare il sito ulteriormente abilitando o disabilitando i plugin installati.',

	'registration:notemail' => 'La email che hai fornito non sembra essere un indirizzo email valido.',
	'registration:userexists' => 'Questo username &egrave; gi&agrave; esistente',
	'registration:usernametooshort' => 'Il tuo username deve avere un minimo di 4 caratteri.',
	'registration:passwordtooshort' => 'La tua password deve avere un minimo di 4 caratteri.',
	'registration:dupeemail' => 'Questo indirizzo email &egrave; gi&agrave; registrato.',
	'registration:invalidchars' => 'Il tuo username contiene caratteri non validi.',
	'registration:emailnotvalid' => 'La email che hai inserito non &egrave; valida per il nostro sistema',
	'registration:passwordnotvalid' => 'La password che hai inserito non &egrave; valida per il nostro sistema',
	'registration:usernamenotvalid' => 'Questo username non &egrave; valido per il nostro sistema',

	'adduser' => "Aggiungi utente",
	'adduser:ok' => "Hai aggiunto un nuovo utente con successo.",
	'adduser:bad' => "Non &egrave; stato possibie creare il nuovo utente.",

	'item:object:reported_content' => "Voci segnalate",

	'user:set:name' => "Impostazioni nome account",
	'user:name:label' => "Il tuo nome",
	'user:name:success' => "Hai cambiato il tuo nome con successo.",
	'user:name:fail' => "Non &egrave; stato possibile modificare il tuo nome.  Per favore assicurati che il tuo nome non sia troppo lungo e prova di nuovo.",

	'user:set:password' => "Impostazioni password account",
	'user:password:label' => "La tua nuova password",
	'user:password2:label' => "La tua nuova password di nuovo",
	'user:password:success' => "Password modificata",
	'user:password:fail' => "Non &egrave; stato possibile modificare la tua password.",
	'user:password:fail:notsame' => "Le due password non corrispondono!",
	'user:password:fail:tooshort' => "La password &egrave; troppo corta!",
	'user:resetpassword:unknown_user' => 'Utente non riconosciuto.',
	'user:resetpassword:reset_password_confirm' => 'Resettando la password, una nuova verr&agrave; inviata nel tuo indirizzo email.',

	'user:set:language' => "Impostazioni della lingua",
	'user:language:label' => "La tua lingua",
	'user:language:success' => "La tua lingua &egrave; stata aggiornata.",
	'user:language:fail' => "Non &egrave; stato possibile modificare la tua lingua.",

	'user:username:notfound' => 'Username %s non trovato.',

	'user:password:lost' => 'Hai dimenticato la password',
	'user:password:resetreq:success' => 'Hai chiesto una nuova password, &egrave; stata inviata nel tuo indirizzo email',
	'user:password:resetreq:fail' => 'Non &egrave; stato possibile richiedere una nuova password.',

	'user:password:text' => 'Per generare una nuova password scrivi qui sotto il tuo username. Ti invieremo nella tua email il link di attivazione. Cliccando sul link ti verr&agrave; inviata la nuova password',

	'user:persistent' => 'Ricordami',
/**
 * Administration
 */

	'admin:configuration:success' => "Le tue impostazioni sono state salvate.",
	'admin:configuration:fail' => "Non &egrave; stato possibile salvare le tue impostazioni.",

	'admin' => "Amministrazione",
	'admin:description' => "Il pannello di amministrazione permette di controllare tutti gli aspetti del sistema, dalla gestione degli utenti ai comportamenti dei plugin. Scegli una delle seguenti opzioni per iniziare.",

	'admin:user' => "Utenti",
	'admin:user:description' => "Questo pannello di amministrazione permette di controllare le impostazioni utente per il tuo sito. Scegli una delle seguenti opzioni per iniziare.",
	'admin:user:adduser:label' => "Clicca qui per aggiungere un nuovo utente...",
	'admin:user:opt:linktext' => "Configura utenti...",
	'admin:user:opt:description' => "Configura utenti e informazioni di account. ",

	'admin:site' => "Sito",
	'admin:site:description' => "Questo pannello di amministrazione permette di controllare le impostazioni globali per il tuo sito. Scegli una delle seguenti opzioni per iniziare.",
	'admin:site:opt:linktext' => "Configura sito...",
	'admin:site:opt:description' => "Configura il sito e le impostazioni. ",
	'admin:site:access:warning' => "Le modifiche delle impostazioni di accesso avranno effetto solo per le autorizzazioni e per i contenuti creati in futuro.",

	'admin:plugins' => "Strumenti",
	'admin:plugins:description' => "Questo pannello di amministrazione permette di controllare e configurare gli strumenti installati sul tuo sito.",
	'admin:plugins:opt:linktext' => "Configura strumenti...",
	'admin:plugins:opt:description' => "Configura gli strumenti installati sul sito. ",
	'admin:plugins:label:author' => "Autore",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:licence' => "Licenza",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:moreinfo' => 'dettagli',
	'admin:plugins:label:version' => 'Versione',
	'admin:plugins:warning:elggversionunknown' => 'Attenzione:  Questo plugin non specifica una versione compatibile di Elgg.',
	'admin:plugins:warning:elggtoolow' => 'Attenzione: Questo plugin richiede una versione successiva di Elgg!',
	'admin:plugins:reorder:yes' => "Plugin %s riordinato con successo.",
	'admin:plugins:reorder:no' => "Plugin %s potrebbe non essere riordinato.",
	'admin:plugins:disable:yes' => "Plugin %s disabilitato con successo.",
	'admin:plugins:disable:no' => "Plugin %s potrebbe non essere disabilitato.",
	'admin:plugins:enable:yes' => "Plugin %s attivato con successo.",
	'admin:plugins:enable:no' => "Plugin %s potrebbe non essere abilitato.",

	'admin:statistics' => "Statistiche",
	'admin:statistics:description' => "Questa &egrave; una panoramica delle statistiche sul tuo sito.",
	'admin:statistics:opt:description' => "Visualizza le informazioni e le statistiche sugli utenti e gli oggetti del tuo sito.",
	'admin:statistics:opt:linktext' => "Visualizza statistiche...",
	'admin:statistics:label:basic' => "Statistiche di base del sito",
	'admin:statistics:label:numentities' => "Attivit&agrave; del sito",
	'admin:statistics:label:numusers' => "Numero di utenti",
	'admin:statistics:label:numonline' => "Numero di utenti online",
	'admin:statistics:label:onlineusers' => "Utenti online",
	'admin:statistics:label:version' => "Versione di Elgg",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Versione",

	'admin:user:label:search' => "Trova utenti:",
	'admin:user:label:searchbutton' => "Cerca",

	'admin:user:ban:no' => "Non puoi bannare l'utente",
	'admin:user:ban:yes' => "Utente bannato.",
	'admin:user:unban:no' => "Non puoi riabilitare l'utente.",
	'admin:user:unban:yes' => "Utente riabilitato.",
	'admin:user:delete:no' => "Non puoi cancellare l'utente",
	'admin:user:delete:yes' => "Utente cancellato",

	'admin:user:resetpassword:yes' => "Password resettata, notificato all'utente.",
	'admin:user:resetpassword:no' => "La password non pu&ograve; essere resettata.",

	'admin:user:makeadmin:yes' => "L'utente &egrave; adesso amministratore.",
	'admin:user:makeadmin:no' => "Non &egrave; possibile rendere l'utente amministratore.",

	'admin:user:removeadmin:yes' => "Questo utente non &egrave; pi&ugrave; un amministratore.",
	'admin:user:removeadmin:no' => "Non siamo riusciti a rimuovere i privilegi di amministratore di questo utente.",

/**
 * User settings
 */
	'usersettings:description' => "Il pannello impostazioni utente ti permette di controllare tutte le tue impostazioni personali, dalla gestione degli utenti al comportartamento dei plugin. Scegli una delle seguenti opzioni per iniziare.",

	'usersettings:statistics' => "Le tue statistiche",
	'usersettings:statistics:opt:description' => "Visualizza le statistiche sugli utenti e gli oggetti del tuo sito.",
	'usersettings:statistics:opt:linktext' => "Statistiche Account",

	'usersettings:user' => "Le tue impostazioni",
	'usersettings:user:opt:description' => "Consente di controllare le impostazioni utente.",
	'usersettings:user:opt:linktext' => "Modifica le tue impostazioni",

	'usersettings:plugins' => "Strumenti",
	'usersettings:plugins:opt:description' => "Configura le impostazioni (se necessario) per i vostri strumenti.",
	'usersettings:plugins:opt:linktext' => "Configura i tuoi strumenti",

	'usersettings:plugins:description' => "Questo pannello permette di controllare e configurare le impostazioni personali e gli strumenti installati dal vostro amministratore di sistema.",
	'usersettings:statistics:label:numentities' => "Le tue attivit&agrave;",

	'usersettings:statistics:yourdetails' => "I tuoi dettagli",
	'usersettings:statistics:label:name' => "Nome completo",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:membersince' => "Membro da",
	'usersettings:statistics:label:lastlogin' => "Ultimo accesso",



/**
 * Generic action words
 */

	'save' => "Salva",
	'publish' => "Pubblica",
	'cancel' => "Annulla",
	'saving' => "Salvataggio ...",
	'update' => "Aggiorna",
	'edit' => "Modifica",
	'delete' => "Cancella",
	'accept' => "Accetta",
	'load' => "Carica",
	'upload' => "Upload",
	'ban' => "Banna",
	'unban' => "Togli ban",
	'enable' => "Abilita",
	'disable' => "Disabilita",
	'request' => "Richiesta",
	'complete' => "Completo",
	'open' => 'Apri',
	'close' => 'Chiudi',
	'reply' => "Rispondi",
	'more' => 'Dettagli',
	'comments' => 'Commenti',
	'import' => 'Importa',
	'export' => 'Esporta',
	'untitled' => 'Nessun oggetto',
	'help' => 'Aiuto',
	'send' => 'Invia',
	'post' => 'Pubblica',
	'submit' => 'Invia',
	'site' => 'Sito',

	'up' => 'Su',
	'down' => 'Gi&ugrave;',
	'top' => 'Alto',
	'bottom' => 'Basso',

	'invite' => "Invita",

	'resetpassword' => "Reset password",
	'makeadmin' => "Rendi amministratore",
	'removeadmin' => "Rimuovi da amministratore",

	'option:yes' => "Si",
	'option:no' => "No",

	'unknown' => 'Sconosciuto',

	'active' => 'Attivo',
	'total' => 'Totale',

	'learnmore' => "Clicca qui per saperne di pi&ugrave;.",

	'content' => "contenuto",
	'content:latest' => 'Attivit&agrave; recenti',
	'content:latest:blurb' => 'In alternativa, fare clic qui per visualizzare il contenuto piu recente di tutto il sito.',

	'link:text' => 'vedi link',

	'enableall' => 'Abilita tutto',
	'disableall' => 'Disabilita tutto',

/**
 * Generic questions
 */

	'question:areyousure' => 'Sei sicuro?',

/**
 * Generic data words
 */

	'title' => "Titolo",
	'description' => "Descrizione",
	'tags' => "Tag",
	'spotlight' => "In primo piano",
	'all' => "Tutto",

	'by' => 'da',

	'annotations' => "Annotazioni",
	'relationships' => "Relazioni",
	'metadata' => "Metadata",

/**
 * Input / output strings
 */

	'deleteconfirm' => "Sei sicuro di voler cancellare questa voce?",
	'fileexists' => "Un file &egrave; gi&agrave; stato caricato. Per sostituirlo, selezionare sotto:",

/**
 * User add
 */

	'useradd:subject' => 'Account utente creato',
	'useradd:body' => '
%s,

Un account utente &egrave; stato creato per te %s. Per accedere, visita:

%s

I tuoi dati di accesso sono:

Username: %s
Password: %s

Grazie, ci vediamo presto!
',

/**
 * System messages
 **/

	'systemmessages:dismiss' => "chiudi",


/**
 * Import / export
 */
	'importsuccess' => "Importazione dati riuscita",
	'importfail' => "Importazione dati fallita.",

/**
 * Time
 */

	'friendlytime:justnow' => "adesso",
	'friendlytime:minutes' => "%s minuti fa",
	'friendlytime:minutes:singular' => "un minuto fa",
	'friendlytime:hours' => "%s ore fa",
	'friendlytime:hours:singular' => "un ora fa",
	'friendlytime:days' => "%s giorni fa",
	'friendlytime:days:singular' => "ieri",
	'friendlytime:date_format' => 'j F Y @ g:ia',

	'date:month:01' => 'Gennaio %s',
	'date:month:02' => 'Febbraio %s',
	'date:month:03' => 'Marzo %s',
	'date:month:04' => 'Aprile %s',
	'date:month:05' => 'Maggio %s',
	'date:month:06' => 'Giugno %s',
	'date:month:07' => 'Luglio %s',
	'date:month:08' => 'Agosto %s',
	'date:month:09' => 'Settembre %s',
	'date:month:10' => 'Ottobre %s',
	'date:month:11' => 'Novembre %s',
	'date:month:12' => 'Dicembre %s',


/**
 * Installation and system settings
 */

	'installation:error:htaccess' => "Elgg richiede un file chiamato .htaccess che deve essere nella directory principale della tua installazione. Abbiamo cercato di crearlo per te, ma Elgg non ha i permessi di scrittura nella directory.

Creare il file &egrave; semplicissimo. Copiare il contenuto della casella di testo qui di seguito in un editor di testo e salvarlo come .htaccess

",
	'installation:error:settings' => "Elgg potrebbe non trovare il file delle impostazioni. La maggior parte delle impostazioni di Elgg  verranno configurate automaticamente, ma abbiamo bisogno dei dati di accesso del database mysql. Per favore fai questo:

1. Rinomina il file che trovi in /engine/settings.example.php in settings.php

2. Aprilo con un editor di testo ed inserisci i tuoi dati del database MySQL . Se non li conosci, chiedi al tuo amministratore di sistema o di assistenza tecnica per farti aiutare

In alternativa, puoi inserire le impostazioni del database sotto. Elgg prover&agrave; a fare questo per te ...",

	'installation:error:db:title' => "Errore nelle impostazioni del database",
	'installation:error:db:text' => "Controllare le impostazioni del database di nuovo, Elgg non &egrave; riuscito a collegarsi e ad accedere al database.",
	'installation:error:configuration' => "Una volta corretti eventuali problemi di configurazione, premere aggiorna per riprovare.",

	'installation' => "Installazione",
	'installation:success' => "Database di Elgg installato con successo.",
	'installation:configuration:success' => "Le tue impostazioni di configurazione iniziale sono state salvate. Ora registra il tuo primo utente, questo sar&agrave; un amministratore di sistema.",

	'installation:settings' => "Impostazioni di sistema",
	'installation:settings:description' => "Ora che il database di Elgg &egrave; installato e funzionante, abbiamo bisogno che inserisci alcune informazioni. Abbiamo cercato di indovinarle, ma per maggiore sicurezza <b> verifica questi dettagli.</b>",

	'installation:settings:dbwizard:prompt' => "Inserisci le impostazioni del tuo database e premi salva:",
	'installation:settings:dbwizard:label:user' => "Database user",
	'installation:settings:dbwizard:label:pass' => "Database password",
	'installation:settings:dbwizard:label:dbname' => "Nome database",
	'installation:settings:dbwizard:label:host' => "Database host (normalmente 'localhost')",
	'installation:settings:dbwizard:label:prefix' => "Prefisso delle tabelle (normalmente 'elgg_')",

	'installation:settings:dbwizard:savefail' => "Non siamo stati in grado di salvare il nuovo settings.php. Si prega di salvare il seguente file come engine/settings.php utilizzando un editor di testo.",

	'installation:sitename' => "Il nome del tuo sito (es \"Il Mio Social Network\"):",
	'installation:sitedescription' => "Una breve descrizione del tuo sito (opzionale)",
	'installation:wwwroot' => "L'URL del sito, seguito da uno slash:",
	'installation:path' => "Il percorso completo del sito sul tuo host, seguito da uno slash:",
	'installation:dataroot' => "Il percorso completo della cartella in cui verranno caricati i file di dati, seguito da uno slash:",
	'installation:dataroot:warning' => "Devi creare questa cartella manualmente. Deve trovarsi in una cartella diversa da quella in cui hai installato Elgg.",
	'installation:sitepermissions' => "Le autorizzazioni di accesso predefinite:",
	'installation:language' => "La lingua di default per il tuo sito",
	'installation:debug' => "Il debug mode fornisce informazioni supplementari che possono essere utilizzate per diagnosticare guasti, tuttavia pu&ograve; rallentare il vostro sistema. Deve essere utilizzato solo se si verificano problemi:",
	'installation:debug:none' => 'Disabilita debug mode (raccomandato)',
	'installation:debug:error' => 'Mostra solo errori critici',
	'installation:debug:warning' => 'Mostra errori e avvertimenti',
	'installation:debug:notice' => 'Traccia tutti gli errori, avvertimenti e notifiche',
	'installation:httpslogin' => "Abilita questo utente ad avere accesso tramite HTTPS. Avrai bisogno di avere https abilitato sul server.",
	'installation:httpslogin:label' => "Abilita l'accesso HTTPS",
	'installation:view' => "Inserisci la vista principale che sar&agrave; utilizzata come impostazione predefinita per il tuo sito o lascia questo spazio vuoto per la visualizzazione predefinita (in caso di dubbio, lascia default)::",

	'installation:siteemail' => "Indirizzo email del sito (usato quando verranno inviate delle email)",

	'installation:disableapi' => "La RESTful &egrave; una API flessibile ed estensibile che consente alle applicazioni di interfaccia di utilizzare determinate funzioni di Elgg da remoto.",
	'installation:disableapi:label' => "Abilita la RESTful API",

	'installation:allow_user_default_access:description' => "Se selezionato, i singoli utenti sono autorizzati a fissare i loro livelli di accesso di default e possono accedere al sistema oltre il livello di accesso predefinito.",
	'installation:allow_user_default_access:label' => "Consentire agli utenti l'accesso predefinito",

	'installation:simplecache:description' => "La simple cache aumenta le performance con caching statico del contenuto, compresi alcuni file CSS e JavaScript. Normalmente si desidera questo.",
	'installation:simplecache:label' => "Usa simple cache (raccomandato)",

	'installation:viewpathcache:description' => "La view filepath cache diminuisce il tempo di caricamento dei plugin tramite caching delle impostazioni delle loro viste.",
	'installation:viewpathcache:label' => "Usa view filepath cache (raccomandato)",

	'upgrading' => 'Aggiornamento...',
	'upgrade:db' => 'Il tuo database &egrave; stato aggiornato.',
	'upgrade:core' => 'La tua installazine di Elgg &egrave; stata aggiornata.',

/**
 * Welcome
 */

	'welcome' => "Benvenuto",
	'welcome:user' => 'Benvenuto %s',
	'welcome_message' => "Benvenuto nell'installazione di Elgg.",

/**
 * Emails
 */
	'email:settings' => "Impostazioni email",
	'email:address:label' => "Il tuo indirizzo email",

	'email:save:success' => "Il nuovo indirizzo email &egrave; stato salvato, &egrave; richiesta una verifica.",
	'email:save:fail' => "Si &egrave; verificato un problema nel salvare il tuo nuovo indirizzo email.",

	'friend:newfriend:subject' => "%s ti ha aggiunto tra i suoi amici!",
	'friend:newfriend:body' => "%s ti ha aggiunto tra i suoi amici!

Per vedere il profilo, clicca qui:

%s

Non puoi rispondere a questa email.",



	'email:resetpassword:subject' => "Password resettata!",
	'email:resetpassword:body' => "Ciao %s,

La tua password &egrave; stata resettata, quella nuova &egrave;: %s",


	'email:resetreq:subject' => "Richiesta di una nuova password.",
	'email:resetreq:body' => "Ciao %s,

Qualcuno (da questo indirizzo IP %s) ha richiesto una nuova password per questo account.

Se sei stato tu clicca sul link sotto, altrimenti ignora questa email.

%s
",

/**
 * user default access
 */

'default_access:settings' => "Il tuo livello di accesso predefinito",
'default_access:label' => "Accesso predefinito",
'user:default_access:success' => "Il tuo nuovo livello di accesso predefinito &egrave; stato salvato correttamente.",
'user:default_access:failure' => "Si &egrave; verificato un problema nel salvare il tuo nuovo livello di accesso predefinito.",

/**
 * XML-RPC
 */
	'xmlrpc:noinputdata'	=>	"Mancano dati in ingresso",

/**
 * Comments
 */

	'comments:count' => "%s commenti",

	'riveraction:annotation:generic_comment' => '%s commento su %s',

	'generic_comments:add' => "Aggiungi un commento",
	'generic_comments:text' => "Commento",
	'generic_comment:posted' => "Commento inviato con successo.",
	'generic_comment:deleted' => "Il tuo commento &egrave; stato eliminato.",
	'generic_comment:blank' => "Spiacenti, devi scrivere qualcosa nel commento prima di salvare.",
	'generic_comment:notfound' => "Spiacenti, non siamo riusciti a trovare l'oggetto specificato.",
	'generic_comment:notdeleted' => "Spiacenti, non abbiamo potuto eliminare questo commento.",
	'generic_comment:failure' => "Un errore imprevisto si &egrave; verificato mentre aggiungievamo il tuo commento. Per favore riprova.",

	'generic_comment:email:subject' => 'Hai un nuovo commento!',
	'generic_comment:email:body' => "Hai un nuovo commento su \"%s\" da %s. Il commento dice:


%s


Per rispondere o vedere il commento originale, clicca qui:

%s

Per vedere il profilo di %s, clicca qui:

%s

Non puoi rispondere a questa email.",

/**
 * Entities
 */
	'entity:default:strapline' => 'Creato %s da %s',
	'entity:default:missingsupport:popup' => 'Questo oggetto non pu&ograve; essere visualizzato correttamente. Cio pu&ograve; essere dovuto al fatto che richiede il supporto fornito da un plugin che non &egrave; piu installato.',

	'entity:delete:success' => 'Oggetto %s rimosso',
	'entity:delete:fail' => 'Errore durante la rimozione di %s',


/**
 * Action gatekeeper
 */
	'actiongatekeeper:missingfields' => 'Manca un campo __token o __ts',
	'actiongatekeeper:tokeninvalid' => "Si &egrave; verificato un errore (di disallineamento). Questo probabilmente significa che la pagina che si stava utilizzando &egrave; scaduta. Per favore riprova.",
	'actiongatekeeper:timeerror' => 'La pagina che si stava utilizzando &egrave; scaduta. Per favore aggiorna e riprova.',
	'actiongatekeeper:pluginprevents' => 'Una estensione ha impedito che questo form possa essere inviato.',

/**
 * Word blacklists
 */
	'word:blacklist' => 'e, la, allora, ma, lei, la sua, lei, lui, uno, e non, anche, su, ora, di conseguenza, tuttavia, ancora, allo stesso modo, altrimenti, quindi, per converso, invece, di conseguenza, inoltre, tuttavia, invece, nel frattempo, di conseguenza, questa, sembra, che cosa, chi, di cui, chiunque.',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Tags',

/**
 * Languages according to ISO 639-1
 */
	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "Arabic",
	"as" => "Assamese",
	"ay" => "Aymara",
	"az" => "Azerbaijani",
	"ba" => "Bashkir",
	"be" => "Byelorussian",
	"bg" => "Bulgarian",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengali; Bangla",
	"bo" => "Tibetan",
	"br" => "Breton",
	"ca" => "Catalan",
	"co" => "Corsican",
	"cs" => "Czech",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "German",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Spanish",
	"et" => "Estonian",
	"eu" => "Basque",
	"fa" => "Persian",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "French",
	"fy" => "Frisian",
	"ga" => "Irish",
	"gd" => "Scots / Gaelic",
	"gl" => "Galician",
	"gn" => "Guarani",
	"gu" => "Gujarati",
	"he" => "Hebrew",
	"ha" => "Hausa",
	"hi" => "Hindi",
	"hr" => "Croatian",
	"hu" => "Hungarian",
	"hy" => "Armenian",
	"ia" => "Interlingua",
	"id" => "Indonesian",
	"ie" => "Interlingue",
	"ik" => "Inupiak",
	//"in" => "Indonesian",
	"is" => "Icelandic",
	"it" => "Italian",
	"iu" => "Inuktitut",
	"iw" => "Hebrew (obsolete)",
	"ja" => "Japanese",
	"ji" => "Yiddish (obsolete)",
	"jw" => "Javanese",
	"ka" => "Georgian",
	"kk" => "Kazakh",
	"kl" => "Greenlandic",
	"km" => "Cambodian",
	"kn" => "Kannada",
	"ko" => "Korean",
	"ks" => "Kashmiri",
	"ku" => "Kurdish",
	"ky" => "Kirghiz",
	"la" => "Latin",
	"ln" => "Lingala",
	"lo" => "Laothian",
	"lt" => "Lithuanian",
	"lv" => "Latvian/Lettish",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Macedonian",
	"ml" => "Malayalam",
	"mn" => "Mongolian",
	"mo" => "Moldavian",
	"mr" => "Marathi",
	"ms" => "Malay",
	"mt" => "Maltese",
	"my" => "Burmese",
	"na" => "Nauru",
	"ne" => "Nepali",
	"nl" => "Dutch",
	"no" => "Norwegian",
	"oc" => "Occitan",
	"om" => "(Afan) Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polish",
	"ps" => "Pashto / Pushto",
	"pt" => "Portuguese",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ru" => "Russian",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sangro",
	"sh" => "Serbo-Croatian",
	"si" => "Singhalese",
	"sk" => "Slovak",
	"sl" => "Slovenian",
	"sm" => "Samoan",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanian",
	"sr" => "Serbian",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sundanese",
	"sv" => "Swedish",
	"sw" => "Swahili",
	"ta" => "Tamil",
	"te" => "Tegulu",
	"tg" => "Tajik",
	"th" => "Thai",
	"ti" => "Tigrinya",
	"tk" => "Turkmen",
	"tl" => "Tagalog",
	"tn" => "Setswana",
	"to" => "Tonga",
	"tr" => "Turkish",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Uigur",
	"uk" => "Ukrainian",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamese",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zuang",
	"zh" => "Chinese",
	"zu" => "Zulu",
);

add_translation("it",$italian);
