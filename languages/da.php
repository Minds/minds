<?php
/**
* Core Danish Language
*
* @package Elgg.Core
* @subpackage Languages.Danish
* @version Id: da.php 2011.08.15
* @source file is Copyright (c) 2008-2010 Curverider Ltd
* @modified and translated by Elggzone
* @link http://www.perjensen-online.dk
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
*
* This file is part of the Danish language package for Elgg 1.8
* Copyright (c) 2010-2011 Elggzone
*
* The package is free software; you can redistribute it and/or modify it under the terms of the GNU
* General Public License as published by the Free Software Foundation, version 2 of the License.

* The Danish language package is distributed in the hope that it will be useful, but WITHOUT ANY
* WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
* A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along with this language
* package. If not, see <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>.
*
*/

$danish = array( 
	/**
	* Sites
	*/
	
	'item:site'  =>  "Sider",
	
/**
* Sessions
*/
	
	'login'  =>  "Log ind", 
	'loginok'  =>  "Du er blevet logget ind.", 
	'loginerror'  =>  "Vi kunne ikke logge dig ind. Tjek venligst dine legitimationsoplysninger og prøv igen.",
	'login:empty' => "Du skal angive brugernavn og adgangskode.",
	'login:baduser' => "Din brugerkonto kunne ikke indlæses.",
	'auth:nopams' => "Intern fejl. Ingen bruger godkendelsesmetode installeret.",
		
	'logout'  =>  "Log ud", 
	'logoutok'  =>  "Du er blevet logget ud.", 
	'logouterror'  =>  "Vi kunne ikke logge dig ud. Prøv venligst igen.",
	
	'loggedinrequired' => "Du skal være logget ind for at se denne side.",
	'adminrequired' => "Du skal være administrator for at se denne side.",
	'membershiprequired' => "Du skal være medlem af gruppen for at se siden.",

	
/**
* Errors
*/ 
	'exception:title'  =>  "Fatal fejl.",
	
	'actionundefined'  =>  "Den forespurgte handling (%s) var ikke defineret i systemet.",
	'actionnotfound' => "Filen til %s blev ikke fundet.", 	  
	'actionloggedout'  =>  "Beklager, du kan ikke udføre denne handling, når du er logget ud.",
	'actionunauthorized' => 'Du har ikke tilladelse til at udføre denne handling.',
	
	'InstallationException:SiteNotInstalled' => 'Ude af stand til at håndtere denne anmodning. Dette site '
		. ' er ikke konfigureret eller databasen er nede..',
	'InstallationException:MissingLibrary' => 'Kunne ikke indlæse %s',
	'InstallationException:CannotLoadSettings' => "Elgg Kunne ikke indlæse filen 'settings'. Enten eksisterer den ikke, eller der er problemer med filtilladelser.",
	
	'SecurityException:Codeblock'  =>  "Adgang nægtet til at eksekvere privilegeret kodeblok", 
	'DatabaseException:WrongCredentials'  =>  "Elgg kunne ikke forbinde til databasen ved hjælp af de givne legitimationsoplysninger. Kontrollér filen 'settings'.", 
	'DatabaseException:NoConnect'  =>  "Elgg kunne ikke finde databasen '%s'. Kontroller at databasen er oprettet og at du har adgang til den.", 
	'SecurityException:FunctionDenied'  =>  "Adgang til priviligeret funktion '%s' nægtet.", 
	'DatabaseException:DBSetupIssues'  =>  "Der var følgende problemer: ", 
	'DatabaseException:ScriptNotFound'  =>  "Elgg kunne ikke finde det efterspurgte database script på %s.",
	'DatabaseException:InvalidQuery' => "Ugyldig forespørgsel", 
	
	'IOException:FailedToLoadGUID' => "Failed to load new %s from GUID:%d", 
	'InvalidParameterException:NonElggObject'  =>  "Passing a non-ElggObject to an ElggObject constructor!", 
	'InvalidParameterException:UnrecognisedValue'  =>  "Unrecognised value passed to constuctor.",
	  
	'InvalidClassException:NotValidElggStar' => "GUID:%d is not a valid %s",
	 
	'PluginException:MisconfiguredPlugin'  =>  "% s (GUID:% s) er en fejlkonfigureret plugin. Den er blevet deaktiveret. Tjek venligst i Elgg wiki for mulige årsager (http://docs.elgg.org/wiki/).",
	'PluginException:CannotStart' => '%s (guid: %s) kan ikke starte.  Årsag: %s',
	'PluginException:InvalidID' => "%s er et ugyldigt plugin ID.",
	'PluginException:InvalidPath' => "%s er en ugyldig plugin sti.",
	'PluginException:InvalidManifest' => 'Ugyldig manifestfil til plugin %s',
	'PluginException:InvalidPlugin' => '%s er ikke en gyldig plugin.',
	'PluginException:InvalidPlugin:Details' => '%s er ikke en gyldig plugin: %s',
	
	'ElggPlugin:MissingID' => 'Mangler plugin ID (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Mangler ElggPluginPackage til plugin ID %s (guid %s)',

	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Mangler fil %s i pakken',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Invalid dependency type "%s"',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Invalid provides type "%s"',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Invalid %s dependency "%s" in plugin %s.  Plugins cannot conflict with or require something they provide!',

	'ElggPlugin:Exception:CannotIncludeFile' => 'Cannot include %s for plugin %s (guid: %s) at %s.  Check permissions!',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Cannot open views dir for plugin %s (guid: %s) at %s.  Check permissions!',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'Cannot register languages for plugin %s (guid: %s) at %s.  Check permissions!',
	'ElggPlugin:Exception:NoID' => 'No ID for plugin guid %s!',
	
	'PluginException:ParserError' => 'Error parsing manifest with API version %s in plugin %s.',
	'PluginException:NoAvailableParser' => 'Cannot find a parser for manifest API version %s in plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Missing required '%s' attribute in manifest for plugin %s.",
	
	'ElggPlugin:Dependencies:Requires' => 'Kræver',
	'ElggPlugin:Dependencies:Suggests' => 'Foreslår',
	'ElggPlugin:Dependencies:Conflicts' => 'Konflikter',
	'ElggPlugin:Dependencies:Conflicted' => 'Konfliktede',
	'ElggPlugin:Dependencies:Provides' => 'Yder',
	'ElggPlugin:Dependencies:Priority' => 'Prioritet',

	'ElggPlugin:Dependencies:Elgg' => 'Elgg version',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP udvidelse: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP ini setting: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Efter %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Før %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s er ikke installeret',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Mangler',
	
	'InvalidParameterException:NonElggUser' => "Passing a non-ElggUser to an ElggUser constructor!",

	'InvalidParameterException:NonElggSite' => "Passing a non-ElggSite to an ElggSite constructor!",

	'InvalidParameterException:NonElggGroup' => "Passing a non-ElggGroup to an ElggGroup constructor!",
	 
	'IOException:UnableToSaveNew'  =>  "Ikke i stand til at gemme ny %s",
	 
	'InvalidParameterException:GUIDNotForExport' => "GUID has not been specified during export, this should never happen.",
	'InvalidParameterException:NonArrayReturnValue' => "Entity serialisation function passed a non-array returnvalue parameter",

	'ConfigurationException:NoCachePath' => "Cache path set to nothing!",
	'IOException:NotDirectory' => "%s is not a directory.",
	 
	'IOException:BaseEntitySaveFailed' => "Unable to save new object's base entity information!",
	'InvalidParameterException:UnexpectedODDClass' => "import() passed an unexpected ODD class",
	'InvalidParameterException:EntityTypeNotSet' => "Entity type must be set.",
	 
	'ClassException:ClassnameNotClass'  =>  "%s er ikke en %s.", 
	'ClassNotFoundException:MissingClass'  =>  "Klassen '%s' blev ikke fundet, mangler der et plugin?", 
	'InstallationException:TypeNotSupported'  =>  "Type %s er ikke understøttet. Det indikerer en fejl ved din installation, sandsynligvis forårsaget af en ufuldstændig opdatering.",
	 
	'ImportException:ImportFailed'  =>  "Kunne ikke importere elementet %d",
	'ImportException:ProblemSaving'  =>  "Der var et problem med at gemme %s", 
	'ImportException:NoGUID' => "New entity created but has no GUID, this should not happen.",

	'ImportException:GUIDNotFound' => "Entity '%d' could not be found.",
	'ImportException:ProblemUpdatingMeta' => "There was a problem updating '%s' on entity '%d'",

	'ExportException:NoSuchEntity' => "No such entity GUID:%d",
	 
	'ImportException:NoODDElements' => "No OpenDD elements found in import data, import failed.", 
	'ImportException:NotAllImported'  =>  "Ikke alle elementer blev importeret.",
	
	'InvalidParameterException:UnrecognisedFileMode' => "Unrecognised file mode '%s'",
	'InvalidParameterException:MissingOwner' => "File %s (file guid:%d) (owner guid:%d) is missing an owner!",
	'IOException:CouldNotMake' => "Could not make %s",
	'IOException:MissingFileName' => "You must specify a name before opening a file.",
	'ClassNotFoundException:NotFoundNotSavedWithFile' => "Unable to load filestore class %s for file %u",
	'NotificationException:NoNotificationMethod' => "No notification method specified.",
	'NotificationException:NoHandlerFound' => "No handler found for '%s' or it was not callable.",
	'NotificationException:ErrorNotifyingGuid' => "There was an error while notifying %d",
	'NotificationException:NoEmailAddress' => "Could not get the email address for GUID:%d",
	'NotificationException:MissingParameter' => "Missing a required parameter, '%s'",
	
	'DatabaseException:WhereSetNonQuery' => "Where set contains non WhereQueryComponent",
	'DatabaseException:SelectFieldsMissing' => "Fields missing on a select style query",
	'DatabaseException:UnspecifiedQueryType' => "Unrecognised or unspecified query type.",
	'DatabaseException:NoTablesSpecified' => "No tables specified for query.",
	'DatabaseException:NoACL' => "No access control was provided on query", 
	
	'InvalidParameterException:NoEntityFound' => "No entity found, it either doesn't exist or you don't have access to it.",
	
	'InvalidParameterException:GUIDNotFound' => "GUID:%s could not be found, or you can not access it.",
	'InvalidParameterException:IdNotExistForGUID' => "Sorry, '%s' does not exist for guid:%d",
	'InvalidParameterException:CanNotExportType' => "Sorry, I don't know how to export '%s'",
	'InvalidParameterException:NoDataFound' => "Could not find any data.",
	'InvalidParameterException:DoesNotBelong' => "Does not belong to entity.",
	'InvalidParameterException:DoesNotBelongOrRefer' => "Does not belong to entity or refer to entity.",
	'InvalidParameterException:MissingParameter' => "Missing parameter, you need to provide a GUID.",
	'InvalidParameterException:LibraryNotRegistered' => '%s is not a registered library',
	
	'APIException:ApiResultUnknown' => "API Result is of an unknown type, this should never happen.",
	'ConfigurationException:NoSiteID' => "No site ID has been specified.",
	'SecurityException:APIAccessDenied' => "Sorry, API access has been disabled by the administrator.",
	'SecurityException:NoAuthMethods' => "No authentication methods were found that could authenticate this API request.",	
	'SecurityException:UnexpectedOutputInGatekeeper' => 'Unexpected output in gatekeeper call. Halting execution for security. Search http://docs.elgg.org/ for more information.',
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "Method or function not set in call in expose_method()",
	'InvalidParameterException:APIParametersArrayStructure' => "Parameters array structure is incorrect for call to expose method '%s'", 
	'InvalidParameterException:UnrecognisedHttpMethod' => "Unrecognised http method %s for api method '%s'",
	'APIException:MissingParameterInMethod' => "Missing parameter %s in method %s",
	'APIException:ParameterNotArray' => "%s does not appear to be an array.",
	'APIException:UnrecognisedTypeCast' => "Unrecognised type in cast %s for variable '%s' in method '%s'",
	'APIException:InvalidParameter' => "Invalid parameter found for '%s' in method '%s'.",
	'APIException:FunctionParseError' => "%s(%s) has a parsing error.",
	'APIException:FunctionNoReturn' => "%s(%s) returned no value.",
	'APIException:APIAuthenticationFailed' => "Method call failed the API Authentication",
	'APIException:UserAuthenticationFailed' => "Method call failed the User Authentication",
	'SecurityException:AuthTokenExpired' => "Authentication token either missing, invalid or expired.",
	'CallException:InvalidCallMethod' => "%s must be called using '%s'",
	'APIException:MethodCallNotImplemented' => "Method call '%s' has not been implemented.",
	'APIException:FunctionDoesNotExist' => "Function for method '%s' is not callable",
	'APIException:AlgorithmNotSupported' => "Algorithm '%s' is not supported or has been disabled.",
	'ConfigurationException:CacheDirNotSet' => "Cache directory 'cache_path' not set.",
	'APIException:NotGetOrPost' => "Request method must be GET or POST",
	'APIException:MissingAPIKey' => "Missing API key",
	'APIException:BadAPIKey' => "Bad API key",
	'APIException:MissingHmac' => "Missing X-Elgg-hmac header",
	'APIException:MissingHmacAlgo' => "Missing X-Elgg-hmac-algo header",
	'APIException:MissingTime' => "Missing X-Elgg-time header",
	'APIException:MissingNonce' => "Missing X-Elgg-nonce header",
	'APIException:TemporalDrift' => "X-Elgg-time is too far in the past or future. Epoch fail.",
	'APIException:NoQueryString' => "No data on the query string",
	'APIException:MissingPOSTHash' => "Missing X-Elgg-posthash header",
	'APIException:MissingPOSTAlgo' => "Missing X-Elgg-posthash_algo header",
	'APIException:MissingContentType' => "Missing content type for post data",
	'SecurityException:InvalidPostHash' => "POST data hash is invalid - Expected %s but got %s.",
	'SecurityException:DupePacket' => "Packet signature already seen.",
	'SecurityException:InvalidAPIKey' => "Invalid or missing API Key.",
	'NotImplementedException:CallMethodNotImplemented' => "Call method '%s' is currently not supported.", 
	
	'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC method call '%s' not implemented.",
	'InvalidParameterException:UnexpectedReturnFormat' => "Call to method '%s' returned an unexpected result.",
	'CallException:NotRPCCall' => "Call does not appear to be a valid XML-RPC call",
	
	'PluginException:NoPluginName'  =>  "Plugin navnet kunne ikke findes",

	'SecurityException:authenticationfailed' => "Bruger kunne ikke autoriseres",

	'CronException:unknownperiod' => '%s er ikke en genkendt periode.',

	'SecurityException:deletedisablecurrentsite' => 'Du kan ikke slette eller deaktivere den side, du er på!',
	
	'RegistrationException:EmptyPassword' => "Felterne 'adgangskode' må ikke være tomme",
	'RegistrationException:PasswordMismatch' => 'Adgangskoderne skal være ens',
	'LoginException:BannedUser' => 'Du er blevet udelukket fra dette websted, og kan ikke logge ind',
	'LoginException:UsernameFailure' => 'Vi kunne ikke logge dig ind. Tjek venligst dit brugernavn og adgangskode.',
	'LoginException:PasswordFailure' => 'Vi kunne ikke logge dig ind. Tjek venligst dit brugernavn og adgangskode.',
	'LoginException:AccountLocked' => 'Din konto er blevet spærret på grund af for mange log ind fejl.',

	'memcache:notinstalled' => 'PHP memcache module not installed, you must install php5-memcache',
	'memcache:noservers' => 'No memcache servers defined, please populate the $CONFIG->memcache_servers variable',
	'memcache:versiontoolow' => 'Memcache needs at least version %s to run, you are running %s',
	'memcache:noaddserver' => 'Multiple server support disabled, you may need to upgrade your PECL memcache library',
		
	'deprecatedfunction' => 'Warning: This code uses the deprecated function \'%s\' and is not compatible with this version of Elgg',
	
	'pageownerunavailable' => 'Warning: The page owner %d is not accessible!',
	'viewfailure' => 'There was an internal failure in the view %s',
	'changebookmark' => 'Vær venlig at ændre dit bogmærke til denne side',
	/**
	* API
	*/	
	'system.api.list' => "List all available API calls on the system.",
	'auth.gettoken' => "This API call lets a user obtain a user authentication token which can be used for authenticating future API calls. Pass it as the parameter auth_token",
		
	/**
	* User details
	*/
		
	'name'  =>  "Fulde navn", 
	'email'  =>  "E-mail adresse", 
	'username'  =>  "Brugernavn", 
	'loginusername' => "Brugernavn eller e-mail",
	'password'  =>  "Adgangskode", 
	'passwordagain'  =>  "Adgangskode (igen for verifikation)",
	'admin_option'  =>  "Skal denne bruger være administrator?",
	
	/**
	* Access
	*/
	
	'PRIVATE'  =>  "Privat", 
	'LOGGED_IN'  =>  "Brugere der er logget ind", 
	'PUBLIC'  =>  "Offentlig",
	'access:friends:label' => "Venner",
	'access' => "Adgang", 
	
	/**
	* Dashboard and widgets
	*/
	
	'dashboard'  =>  "Instrumentpanel", 
	'dashboard:nowidgets'  =>  "Med dit instrumentpanel kan du holde øje med aktivitet og indhold der betyder noget for dig.",
	
	'widgets:add'  =>  "Tilføj widgets", 
	'widgets:add:description'  =>  "Klik på en widget knap herunder for at tilføje den til siden.",
	'widgets:position:fixed' => '(Fast placering på siden)',
	'widget:unavailable' => 'Du har allerede tilføjet denne widget',
	'widget:numbertodisplay' => 'Antal enmer der skal vises',

	'widget:delete' => 'Fjern %s',
	'widget:edit' => 'Tilpas denne widget',
		
	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'widgets:save:success' => "Din widget blev gemt.",
	'widgets:save:failure' => "Vi kunne ikke gemme din widget. Prøv venligst igen.",
	'widgets:add:success' => "Din widget blev tilføjet.",
	'widgets:add:failure' => "Vi kunne ikke tilføje din widget.",
	'widgets:move:failure' => "Vi kunne ikke gemme den nye widget position.",
	'widgets:remove:failure' => "Kunne ikke fjerne denne widget",
	
	/**
	* Groups
	*/
	
	'group'  =>  "Gruppe", 
	'item:group'  =>  "Grupper",
	
	/**
	* Users
	*/
	
	'user'  =>  "Bruger", 
	'item:user'  =>  "Brugere", 
	
	/**
	* Friends
	*/
	
	'friends'  =>  "Venner", 
	'friends:yours'  =>  "Dine venner", 
	'friends:owned'  =>  "%s's venner", 
	'friend:add'  =>  "Tilføj ven",
	'friend:remove'  =>  "Fjern ven",
	
	'friends:add:successful'  =>  "Du har føjet %s til dine venner.", 
	'friends:add:failure'  =>  "Vi kunne ikke tilføje %s som en ven. Prøv venligst igen.",
	
	'friends:remove:successful'  =>  "Du har fjernet %s fra dine venner.", 
	'friends:remove:failure'  =>  "Vi kunne ikke fjerne %s fra dine venner. Prøv venligst igen.",
	
	'friends:none'  =>  "Denne bruger har ikke tilføjet nogen venner endnu.", 
	'friends:none:you'  =>  "Du har ikke tilføjet nogen venner endnu!",
	 
	'friends:none:found'  =>  "Ingen venner fundet.", 
	
	'friends:of:none'  =>  "Ingen har tilføjet denne bruger som ven endnu.", 
	'friends:of:none:you'  =>  "Ingen har tilføjet dig som ven endnu. Begynd at tilføje indhold og udfylde din profil for at lade folk finde dig!",
	
	'friends:of:owned'  =>  "Folk der har føjet %s til deres venner",

	'friends:of' => "Venner med",	
	'friends:collections'  =>  "Venneliste", 
	'collections:add' => "Ny liste", 
	'friends:collections:add'  =>  "Ny venneliste",
	'friends:addfriends'  =>  "Tilføj venner", 
	'friends:collectionname'  =>  "Listens navn", 
	'friends:collectionfriends'  =>  "Venner i listen", 
	'friends:collectionedit'  =>  "Rediger denne liste", 
	'friends:nocollections'  =>  "Du har endnu ikke nogen lister!", 
	'friends:collectiondeleted'  =>  "Din liste er blevet slettet!", 
	'friends:collectiondeletefailed'  =>  "Vi kunne ikke slette listen. Enter har du ikke tilladelse, eller også skete der en anden fejl.", 
	'friends:collectionadded'  =>  "Din liste er blevet oprettet", 
	'friends:nocollectionname'  =>  "Du er nødt til at give din liste et navn, før den kan oprettes.", 
	'friends:collections:members' => "Venneliste",
	'friends:collections:edit' => "Rediger liste",
	
	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZÆØÅ',

	'avatar' => 'Avatar',
	'avatar:create' => 'Opret din avatar',
	'avatar:edit' => 'Rediger avatar',
	'avatar:preview' => 'Forhåndsvisning',
	'avatar:upload' => 'Upload en ny avatar',
	'avatar:current' => 'Nuværende avatar',
	'avatar:crop:title' => 'Avatar beskæringsværktøj',
	'avatar:upload:instructions' => "Din avatar vises på hele sitet. Du kan ændre den så tit du har lyst. (Filformater der accepteres: GIF, JPG eller PNG)",
	'avatar:create:instructions' => 'Klik og træk en firkant nedenfor for at vise, hvordan du ønsker din avatar beskåret. Et eksempel vises i boksen til højre. Når du er tilfreds med forhåndsvisningen, klikker du på \'Opret din avatar \'. Denne redigerede version vil blive anvendt på hele sitet som din avatar.',
	'avatar:upload:success' => 'Avatar uploaded',
	'avatar:upload:fail' => 'Avatar upload fejlede',
	'avatar:resize:fail' => 'Ændring af størrelsen på din avatar mislykkedes',
	'avatar:crop:success' => 'Beskæring af avataren lykkedes',
	'avatar:crop:fail' => 'Beskæring af avataren fejlede',	

	'profile:edit' => "Rediger profil",
	'profile:aboutme' => "Om mig",
	'profile:description' => "Om mig",
	'profile:briefdescription' => "Kort beskrivelse",
	'profile:location' => "Sted",
	'profile:skills' => "Færdigheder",
	'profile:interests' => "Interesser",
	'profile:contactemail' => "Kontakt mail",
	'profile:phone' => "Telefon",
	'profile:mobile' => "Mobil",
	'profile:website' => "Website",
	'profile:twitter' => "Twitter brugernavn",
	'profile:saved' => "Din profil blev gemt.",

	'admin:appearance:profile_fields' => 'Rediger profil felter',		
	'profile:edit:default' => 'Rediger profil felter',
	'profile:label' => "Navn",
	'profile:type' => "Profiltype",
	'profile:editdefault:delete:fail' => 'Standardprofil element kunne ikke slettes',
	'profile:editdefault:delete:success' => 'Standardprofil element slettet!',
	'profile:defaultprofile:reset' => 'Standard profil genoprettet',
	'profile:resetdefault' => 'Genopret standard profil',
	'profile:explainchangefields' => "Du kan erstatte de eksisterende felter i profilen med dine egne ved hjælp af formularen nedenfor. \n\n Giv det nye profilfelt en navn, for eksempel, 'Favorit hold', vælg derefter felt type (f.eks tekst, url, tags), og klik på knappen 'Tilføj'. For at ændre rækkefølgen af felterne træk i markeringen ved siden af navnet. For at redigere et feltnavn - klik på navnet for at gøre teksten redigerbar. \n\n Du kan altid vende tilbage til standardindstillingerne for profilen, men du vil miste alle data, der allerede er tilføjet i de brugerdefinerede felter på profil sider.",
	'profile:editdefault:success' => 'Element føjet til standard profilen',
	'profile:editdefault:fail' => 'Standard profilen kunne ikke gemmes',


/**
 * Feeds
 */
	'feed:rss' => 'RSS feed for denne side',
/**
 * Links
 */
	'link:view' => 'se link',
	'link:view:all' => 'Se alle',


/**
 * River
 */
	'river' => "River",
	'river:friend:user:default' => "%s er nu ven med %s",
	'river:update:user:avatar' => '%s har oprettet en ny avatar',
	'river:noaccess' => 'Du har ikke tilladelse til at se dette element.',
	'river:posted:generic' => '%s postede',
	'riveritem:single:user' => 'en bruger',
	'riveritem:plural:user' => 'nogle grugere',
	'river:ingroup' => 'i gruppen %s',
	'river:none' => 'Ingen aktivitet',

	'river:widget:title' => "Aktivitet",
	'river:widget:description' => "Vis seneste aktivitet",
	'river:widget:type' => "Type af aktivitet",
	'river:widgets:friends' => 'Venners aktivitet',
	'river:widgets:all' => 'Al aktivitet',
	
/**
 * Notifications
 */
	'notifications:usersettings' => "Indstillinger for beskeder",
	'notifications:methods' => "Angiv hvilke metoder du vil tillade.",

	'notifications:usersettings:save:ok' => "Dine indstillinger for beskeder er gemt.",
	'notifications:usersettings:save:fail' => "Der opstop et problem med at gemme dine indstillinger for beskeder.",

	'user.notification.get' => 'Vis indstillinger for beskeder for en given bruger.',
	'user.notification.set' => 'Angiv indstillinger for beskeder for en given bruger.',
/**
 * Search
 */
 	
	'search' => "Søg",
	'searchtitle' => "Søg: %s",
	'users:searchtitle' => "Søgning efter brugere: %s",
	'groups:searchtitle' => "Søgning efter grupper: %s",
	'advancedsearchtitle' => "%s med resultater der matcher %s",
	'notfound' => "Ingen resultater fundet.",
	'next' => "Næste",
	'previous' => "Forrige",

	'viewtype:change' => "Skift listetype",
	'viewtype:list' => "Listevisning",
	'viewtype:gallery' => "Galleri",

	'tag:search:startblurb' => "Elementer med tags matchende '%s':",

	'user:search:startblurb' => "Brugere matchende '%s':",
	'user:search:finishblurb' => "Klik her for at se mere.",

	'group:search:startblurb' => "Grupper, der matcher '%s':",
	'group:search:finishblurb' => "Klik her for at se mere.",
	'search:go' => 'Go',
	'userpicker:only_friends' => 'Kun venner',
	
/**
 * Account
 */

	'account' => "Konto",
	'settings' => "Indstillinger",
	'tools' => "Værktøjer",

	'register' => "Registrer",
	'registerok' => "Du er nu tilmeldt %s.",
	'registerbad' => "Din registrering mislykkedes på grund af en ukendt fejl.",
	'registerdisabled' => "Registrering er blevet deaktiveret af systemadministratoren",

	'registration:notemail' => 'Den e-mail adresse, du angav, synes ikke at være en gyldig e-mail adresse.',
	'registration:userexists' => 'Brugernavnet er allerede i brug',
	'registration:usernametooshort' => 'Dit brugernavn skal være mindst på %u tegn.',
	'registration:passwordtooshort' => 'Din adgangskode skal være på mindst %u tegn.',
	'registration:dupeemail' => 'Denne e-mail adresse er allerede registreret.',
	'registration:invalidchars' => 'Beklager, dit brugernavn indeholder følgende ugyldige tegn: %s.  Alle disse tegn er ugyldige: %s',
	
	'registration:emailnotvalid' => 'Beklager, den e-mail adresse, du indtastede er ugyldig på dette system',
	'registration:passwordnotvalid' => 'Beklager, den adgangskode, du indtastede er ugyldig på dette system',
	'registration:usernamenotvalid' => 'Beklager, det brugernavn, du indtastede, er ugyldigt på dette system',		
	
	'adduser' => "Tilføj bruger",
	'adduser:ok' => "Du har nu tilføjet en ny bruger.",
	'adduser:bad' => "Den nye bruger kunne ikke oprettes.",

	'user:set:name' => "Indstillinger for kontonavn",
	'user:name:label' => "Mit brugernavn",
	'user:name:success' => "Dit navn er ændret.",
	'user:name:fail' => "Kunne ikke ændre dit navn i systemet. Sørg for, at dit navn ikke er for langt og prøv igen.",

	'user:set:password' => "Konto adgangskode",
	'user:current_password:label' => 'Nuværende adgangskode',
	'user:password:label' => "Din nye adgangskode",
	'user:password2:label' => "Din nye adgangskode igen",
	'user:password:success' => "Adgangskode ændret",
	'user:password:fail' => "Kunne ikke ændre din adgangskode.",
	'user:password:fail:notsame' => "De to adgangskoder er ikke ens!",
	'user:password:fail:tooshort' => "Adgangskoden er for kort!",
	'user:password:fail:incorrect_current_password' => 'Den nuværende indtastede adgangskode er forkert.',
	'user:resetpassword:unknown_user' => 'Ugyldig bruger.',
	'user:resetpassword:reset_password_confirm' => 'Nulstilling af din adgangskode vil sende en e-mail med en ny adgangskode til din registrerede e-mail adresse.',
	'user:set:language' => "Sprogindstillinger",
	'user:language:label' => "Dit sprog",
	'user:language:success' => "Dine sprogindstillinger er blevet opdateret.",
	'user:language:fail' => "Dine sprogindstillinger kunne ikke gemmes.",	

	'user:username:notfound' => 'Brugernavn %s ikke fundet.',

	'user:password:lost' => 'Mistet adgangskode',
	'user:password:resetreq:success' => 'Vellykket anmodet om en ny adgangskode, e-mail sendt',
	'user:password:resetreq:fail' => 'Kunne ikke anmode om en ny adgangskode.',

	'user:password:text' => 'For at anmode om et nyt password, så indtast dit brugernavn nedenfor og klik på knappen Anmod.',

	'user:persistent' => 'Husk mig',

	'walled_garden:welcome' => 'Velkommen til',
	
/**
 * Administration
 */
	'menu:page:header:administer' => 'Administrer',
	'menu:page:header:configure' => 'Konfigurer',
	'menu:page:header:develop' => 'Udvikle',
	'menu:page:header:default' => 'Andet',

	'admin:view_site' => 'Se siden',
	'admin:loggedin' => 'Logged ind som %s',
	'admin:menu' => 'Menu',

	'admin:configuration:success' => "Dine indstillinger er blevet gemt.",
	'admin:configuration:fail' => "Dine indstillinger kunne ikke gemmes.",

	'admin:unknown_section' => 'Ugyldig adminsektion.',

	'admin' => "Administration",
	'admin:description' => "Administrationspanelet giver dig mulighed for at styre alle aspekter af systemet, fra brugerhåndtering til hvordan plugins skal fungere. Vælg en indstilling nedenfor for at komme i gang.",

	'admin:statistics' => "Statistik",
	'admin:statistics:overview' => 'Oversigt',

	'admin:appearance' => 'Udseende',
	'admin:utilities' => 'Hjælpeprogrammer',

	'admin:users' => "Brugere",
	'admin:users:online' => 'Online i øjeblikket',
	'admin:users:newest' => 'Nyeste',
	'admin:users:add' => 'Tilføj ny bruger',
	'admin:users:description' => "Dette administrationspanel giver dig mulighed for at kontrollere brugernes indstillinger. Vælg herunder for at komme i gang.",
	'admin:users:adduser:label' => "Klik her for at tilføje en ny bruger...",
	'admin:users:opt:linktext' => "Konfigurer brugere...",
	'admin:users:opt:description' => "Konfigurer brugere og kontooplysninger. ",
	'admin:users:find' => 'Find',

	'admin:settings' => 'Indstillinger',
	'admin:settings:basic' => 'Grundlæggende indstillinger',
	'admin:settings:advanced' => 'Avancerede indstillinger',
	'admin:site:description' => "Dette administrationspanel giver dig mulighed for at kontrollere de globale indstillinger for dit websted. Vælg en indstilling nedenfor for at komme i gang.",
	'admin:site:opt:linktext' => "Konfigurer siden...",
	'admin:site:access:warning' => "Ændring af adgangstilladelser påvirker kun tilladelser for indhold oprettet fremover.",

	'admin:dashboard' => 'Dashboard',
	'admin:widget:online_users' => 'Online brugere',
	'admin:widget:online_users:help' => 'Viser de brugere, der er på sitet i øjeblikket',
	'admin:widget:new_users' => 'Nye brugere',
	'admin:widget:new_users:help' => 'Viser de nyeste brugere',
	'admin:widget:content_stats' => 'Indholdsstatistik',
	'admin:widget:content_stats:help' => 'Hold styr på indhold oprettet af brugerne',
	'widget:content_stats:type' => 'Indholdstype',
	'widget:content_stats:number' => 'Nummer',

	'admin:widget:admin_welcome' => 'Velkommen',
	'admin:widget:admin_welcome:help' => "En kort introduktion til Elggs admininistrationspanel",
	'admin:widget:admin_welcome:intro' =>
'Velkommen til Elgg! Lige nu kigger du på administrationsdelens instrumentpanel. Det er nyttigt til give overblik over, hvad der sker på sitet.',
	'admin:widget:admin_welcome:admin_overview' =>
"Navigation til administrationspanelet er i menuen til højre. Den er organiseret i"
. " tre dele:
	<dl>
		<dt>Administrer</dt><dd>Dagligdags opgaver som kontrol af rapporteret indhold, hvem der er online og visning af statistik.</dd>
		<dt>Konfigurer</dt><dd>Lejlighedsvise opgaver som at indtaste navnet på hjemmesiden eller aktivere et plugin.</dd>
		<dt>Udvikle</dt><dd>For udviklere, der er ved at kode plugins eller designe temaer. (Kræver et udvikler-plugin.)</dd>
	</dl>
	",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Sørg for at tjekke de ressourcer, der er til rådighed via link i footer og tak fordi du anvender Elgg!',

	'admin:footer:faq' => 'Administration FAQ',
	'admin:footer:manual' => 'Administration Manual',
	'admin:footer:community_forums' => 'Elgg Community Forums',
	'admin:footer:blog' => 'Elgg Blog',

	'admin:plugins:category:all' => 'Alle plugins',
	'admin:plugins:category:active' => 'Aktive plugins',
	'admin:plugins:category:inactive' => 'Inaktive plugins',
	'admin:plugins:category:admin' => 'Admin',
	'admin:plugins:category:bundled' => 'Medfølgende',
	'admin:plugins:category:content' => 'Indhold',
	'admin:plugins:category:development' => 'Udvikling',
	'admin:plugins:category:enhancement' => 'Forbedringer',
	'admin:plugins:category:api' => 'Service/API',
	'admin:plugins:category:communication' => 'kommunikation',
	'admin:plugins:category:security' => 'Sikkerhed og Spam',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Temaer',
	'admin:plugins:category:widget' => 'Widgets',

	'admin:plugins:sort:priority' => 'Prioritet',
	'admin:plugins:sort:alpha' => 'Alfabetisk',
	'admin:plugins:sort:date' => 'Nyeste',

	'admin:plugins:markdown:unknown_plugin' => 'Ukendt plugin.',
	'admin:plugins:markdown:unknown_file' => 'Ukendt fil.',


	'admin:notices:could_not_delete' => 'Kunne ikke slette besked.',

	'admin:options' => 'Admin muligheder',

	
/**
 * Plugins
 */
	'plugins:settings:save:ok' => "Indstillinger for %s plugin er blevet gemt.",
	'plugins:settings:save:fail' => "Der var problemer med at gemme indstillinger for %s plugin.",
	'plugins:usersettings:save:ok' => "Bruger indstillinger for %s plugin er blevet gemt.",
	'plugins:usersettings:save:fail' => "Der var problemer med at gemme bruger indstillinger for %s plugin.",
	'item:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Aktiver alle',
	'admin:plugins:deactivate_all' => 'Deaktiver alle',
	'admin:plugins:activate' => 'Aktiver',
	'admin:plugins:deactivate' => 'Deaktiver',
	'admin:plugins:description' => "Dette administrationspanel giver dig mulighed for at styre og konfigurere værktøjer installeret på dit websted.",
	'admin:plugins:opt:linktext' => "Konfigurer værktøjer...",
	'admin:plugins:opt:description' => "Konfigurer værktøjer installeret på sitet. ",
	'admin:plugins:label:author' => "Author",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Kategorier',
	'admin:plugins:label:licence' => "Licens",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:moreinfo' => 'mere info',
	'admin:plugins:label:version' => 'Version',
	'admin:plugins:label:location' => 'Placering',
	'admin:plugins:label:dependencies' => 'Afhængigheder',
	
	'admin:plugins:warning:elgg_version_unknown' => 'Dette plugin bruger en ugyldig manifest fil og angiver ikke en kompatibel Elgg version. Det vil sandsynligvis ikke fungere!',
	'admin:plugins:warning:unmet_dependencies' => 'Dette plugin har udækkede afhængigheder og kan ikke aktiveres. Tjek afhængigheder under mere info.',
	'admin:plugins:warning:invalid' => '%s er ikke et gyldigt Elgg plugin.  Tjek <a href="http://docs.elgg.org/Invalid_Plugin">the Elgg documentation</a> for tip til fejlfinding.',
	'admin:plugins:cannot_activate' => 'kan ikke aktivere',

	'admin:plugins:set_priority:yes' => "Flyttede %s.",
	'admin:plugins:set_priority:no' => "Kunne ikke flytte %s.",
	'admin:plugins:deactivate:yes' => "Deaktiverede %s.",
	'admin:plugins:deactivate:no' => "Kunne ikke deaktivere %s.",
	'admin:plugins:activate:yes' => "Aktiverede %s.",
	'admin:plugins:activate:no' => "Kunne ikkeaktivere %s.",
	'admin:plugins:categories:all' => 'Alle kategorier',
	'admin:plugins:plugin_website' => 'Plugin website',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Version %s',
	'admin:plugins:simple' => 'Enkel',
	'admin:plugins:advanced' => 'Avanceret',
	'admin:plugin_settings' => 'Plugin Indstillinger',
	'admin:plugins:simple_simple_fail' => 'Kunne ikke gemme indstillinger.',
	'admin:plugins:simple_simple_success' => 'Indstillinger gemt.',
	'admin:plugins:simple:cannot_activate' => 'Kan ikke aktivere dette plugin. Kontroller den avancerede plugin administration for mere information.',
	'admin:plugins:warning:unmet_dependencies_active' => 'Dette plugin er aktiv, men der er udækkede afhængigheder. Du kan støde på problemer. Se "mere information" nedenfor for yderligere oplysninger.',

	'admin:plugins:dependencies:type' => 'Type',
	'admin:plugins:dependencies:name' => 'Navn',
	'admin:plugins:dependencies:expected_value' => 'Testet værdi',
	'admin:plugins:dependencies:local_value' => 'Faktisk værdi',
	'admin:plugins:dependencies:comment' => 'Kommentar',

	'admin:statistics:description' => "Dette er en oversigt over statistik på dit websted. Hvis du har brug for mere detaljerede statistikker, er en professionel administrations funktion tilgængelig.",
	'admin:statistics:opt:description' => "Vis statistiske oplysninger om brugere og objekter på dit websted.",
	'admin:statistics:opt:linktext' => "Vis statistik...",
	'admin:statistics:label:basic' => "Grundlæggende side statistik",
	'admin:statistics:label:numentities' => "Enheder på siden",
	'admin:statistics:label:numusers' => "Antal brugere",
	'admin:statistics:label:numonline' => "Antal brugere online",
	'admin:statistics:label:onlineusers' => "Brugere online nu",
	'admin:statistics:label:version' => "Elgg version",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Version",

	'admin:user:label:search' => "Find brugere:",
	'admin:user:label:searchbutton' => "Søg",

	'admin:user:ban:no' => "Kan ikke udelukke brugeren",
	'admin:user:ban:yes' => "Bruger udelukket.",
	'admin:user:self:ban:no' => "Du kan ikke udelukke dig selv",
	'admin:user:unban:no' => "Kan ikke annullere udelukkelse af bruger",
	'admin:user:unban:yes' => "Brugers udelukkelse annulleret.",
	'admin:user:delete:no' => "Kan ikke slette bruger",
	'admin:user:delete:yes' => "Brugeren %s er blevet slettet",
	'admin:user:self:delete:no' => "Du kan ikke slette dig selv",

	'admin:user:resetpassword:yes' => "Nulstilling af adgangskode, brugeren underrettet",
	'admin:user:resetpassword:no' => "Adgangskoden kunne ikke nulstilles.",

	'admin:user:makeadmin:yes' => "Bruger er nu administrator.",
	'admin:user:makeadmin:no' => "Vi kunne ikke gøre denne bruger til administrator.",

	'admin:user:removeadmin:yes' => "Brugeren er ikke længere administrator.",
	'admin:user:removeadmin:no' => "Vi kunne ikke fjerne administratorrettigheder fra denne bruger.",
	'admin:user:self:removeadmin:no' => "Du kan ikke fjerne dine egne administratorrettigheder.",

	'admin:appearance:menu_items' => 'Menupunkter',
	'admin:menu_items:configure' => 'Konfigurer hovedmenupunkter',
	'admin:menu_items:description' => 'Vælg, hvilke menupunkter du vil vise som hyperlinks. Ubrugte emner vil blive tilføjet under "Flere".',
	'admin:menu_items:hide_toolbar_entries' => 'Fjern links fra værktøjslinie menuen?',
	'admin:menu_items:saved' => 'Menupunkter gemt.',
	'admin:add_menu_item' => 'Opret et tilpasset menupunkt',
	'admin:add_menu_item:description' => 'Udfyld Navn og URL for at tilføje brugerdefinerede elementer til din navigationsmenu.',

	'admin:appearance:default_widgets' => 'Standard Widgets',
	'admin:default_widgets:unknown_type' => 'Ukendt widget type',
	'admin:default_widgets:instructions' => 'Tilføj, fjern, placér, og konfigurer standard widgets til den valgte widget side.'
		. '  Disse ændringer vil kun gælde for nye brugere på sitet.',
		
/**
 * User settings
 */
	'usersettings:description'  =>  "Bruger indstillingspanelet gør dig i stand til at kontrollere alle dine personlige indstilinger, fra brugerstrying til hvordan dine plugins skal fungere. Vælg en mulighed herunder for at begynde.",

	'usersettings:statistics'  =>  "Dine statistikker", 
	'usersettings:statistics:opt:description'  =>  "Vis statistisk information om brugere og objekter på din side.", 
	'usersettings:statistics:opt:linktext'  =>  "Konto statistikker",

	'usersettings:user'  =>  "Dine indstillinger", 
	'usersettings:user:opt:description'  =>  "Dette gør dig i stand til at styre dine brugerinstillinger.", 
	'usersettings:user:opt:linktext'  =>  "Rediger dine indstillinger", 

	'usersettings:plugins'  =>  "Værktøjer", 
	'usersettings:plugins:opt:description'  =>  "Konfigurerer indstillinger (hvis nogen) for dine aktive værktøjer.", 
	'usersettings:plugins:opt:linktext'  =>  "Konfigurer dine værktøjer",

	'usersettings:plugins:description'  =>  "Dette panel gør dig i stand til at styre og opsætte personlige indstillinger for de værktøjer, der er blevet installeret af systemadministratoren.", 
	'usersettings:statistics:label:numentities'  =>  "Dit indhold",

	'usersettings:statistics:yourdetails'  =>  "Dine detaljer", 
	'usersettings:statistics:label:name'  =>  "Fulde navn", 
	'usersettings:statistics:label:email'  =>  "E-mail", 
	'usersettings:statistics:label:membersince'  =>  "Medlem siden", 
	'usersettings:statistics:label:lastlogin'  =>  "Sidst logget ind",		
		
/**
 * Activity river
 */
	'river:all' => 'Al aktivitet',
	'river:mine' => 'Min aktivitet',
	'river:friends' => 'Venners aktivitet',
	'river:select' => 'Vis %s',
	'river:comments:more' => '+%u mere',
	'river:generic_comment' => 'kommenterede %s %s',

	'friends:widget:description' => "Vis nogle af dine venner.",
	'friends:num_display' => "Antal venner der skal vises",
	'friends:icon_size' => "Ikon størrelse",
	'friends:tiny' => "bittesmå",
	'friends:small' => "små",	
	
	/**
	* Generic action words
	*/
	'save'  =>  "Gem",
	'reset' => 'Reset',
	'publish' => "Offentliggør", 
	'cancel'  =>  "Annuller", 
	'saving'  =>  "Gemmer ...", 
	'update'  =>  "Opdater",
	'preview' => "Eksempel", 
	'edit'  =>  "Rediger", 
	'delete'  =>  "Slet",
	'accept' => "Accepter", 
	'load'  =>  "Hent", 
	'upload'  =>  "Tilføj", 
	'ban'  =>  "Udeluk", 
	'unban'  =>  "Ophæv udelukkelse",
	'banned' => "Udelukket",
	'enable'  =>  "Aktiver", 
	'disable'  =>  "Deaktiver", 
	'request'  =>  "Anmod", 
	'complete'  =>  "Færdig", 
	'open'  =>  "Åbn", 
	'close'  =>  "Luk", 
	'reply'  =>  "Svar",
	'more' => 'Mere',
	'comments' => 'Kommentarer',
	'import' => 'Importer',
	'export' => 'Eksporter',
	'untitled' => 'Untitled',
	'help' => 'Hjælp',
	'send' => 'Send',
	'post' => 'Post',
	'submit' => 'Send',
	'comment' => 'Kommentar',
	'upgrade' => 'Opgrader',
	'sort' => 'Sortér',
	'filter' => 'Filter',

	'site' => 'Side',
	'activity' => 'Aktivitet',
	'members' => 'Medlemmer',
	
	'up' => 'Op',
	'down' => 'Ned',
	'top' => 'Top',
	'bottom' => 'Bund',

	'more' => 'mere',
		
	'invite'  =>  "Invitér",
	
	'resetpassword'  =>  "Nulstil adgangskode", 
	'makeadmin'  =>  "Gør til administrator", 
	'removeadmin' => "Fjern admin",
	
	'option:yes'  =>  "Ja", 
	'option:no'  =>  "Nej",
	
	'unknown'  =>  "Ukendt",
	
	'active'  =>  "Aktiv", 
	'total'  =>  "Total",
	
	'learnmore'  =>  "Klik her for at lære mere.", 
	
	'content'  =>  "indhold", 
	'content:latest'  =>  "Seneste aktivitet", 
	'content:latest:blurb'  =>  "Alternativt, klik her for at se det seneste indhold fra hele siden.", 
	
	'link:text'  =>  "se link",
	/**
	* Generic questions
	*/
	
	'question:areyousure' => 'Er du sikker?',
	
	/**
	* Generic data words
	*/
	
	'title'  =>  "Titel", 
	'description'  =>  "Beskrivelse", 
	'tags'  =>  "Tags", 
	'spotlight'  =>  "Spotlight", 
	'all'  =>  "Alle",
	'mine' => "Mine",
		
	'by'  =>  "af", 
	'none' => 'ingen',
		
	'annotations'  =>  "Notater", 
	'relationships'  =>  "Forhold", 
	'metadata'  =>  "Metadata", 
	'tagcloud' => "Tag cloud",
	'tagcloud:allsitetags' => "Alle tags",
	
/**
 * Entity actions
 */
	'edit:this' => 'Rediger dette',
	'delete:this' => 'Slet dette',
	'comment:this' => 'Kommenter dette',
		
	/**
	* Input / output strings
	*/
	
	'deleteconfirm'  =>  "Er du sikker på, at du vil slette dette?", 
	'fileexists'  =>  "En fil er allerede blevet uploaded. Vælg den for at erstatte den:", 
	
	/**
	* User add
	*/
	
	'useradd:subject' => 'Brugerkonto oprettet',
	'useradd:body' => '
%s,
	
En brugerkonto er blevet oprettet til dig på %s. For at logge ind, besøg:

%s

Du kan logge ind med disse bruger oplysninger:

Brugernavn: %s
Adgangskode: %s

Vi anbefaler, at du ændrer din adgangskode, når du har logget ind.
',
	
/**
* System messages
**/
	
	'systemmessages:dismiss'  =>  "klik for at lukke",
	
/**
* Import / export
*/
	
	'importsuccess'  =>  "Data import lykkedes", 
	'importfail'  =>  "OpenDD data import mislykkedes",
	
	/**
	* Time
	*/
	
	'friendlytime:justnow' => "lige nu", 
	'friendlytime:minutes' => "%s minutter siden", 
	'friendlytime:minutes:singular' => "et minut siden", 
	'friendlytime:hours' => "%s timer siden", 
	'friendlytime:hours:singular' => "en time siden", 
	'friendlytime:days' => "%s dage siden", 
	'friendlytime:days:singular' => "i går",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	
	'date:month:01' => 'Januar %s',
	'date:month:02' => 'Februar %s',
	'date:month:03' => 'Marts %s',
	'date:month:04' => 'April %s',
	'date:month:05' => 'Maj %s',
	'date:month:06' => 'Juni %s',
	'date:month:07' => 'Juli %s',
	'date:month:08' => 'August %s',
	'date:month:09' => 'September %s',
	'date:month:10' => 'Oktober %s',
	'date:month:11' => 'November %s',
	'date:month:12' => 'December %s',


/**
 * System settings
 */
   
	'installation:sitename'  =>  "Din sides navn (f.eks. \"Mit sociale netværk\"):", 
	'installation:sitedescription'  =>  "Kort beskrivelse af din side (Valgfrit)", 
	'installation:wwwroot'  =>  "Sidens URL, efterfulgt af en skråstreg:", 
	'installation:path'  =>  "Den fulde sti til din sides rod på din disk, efterfulgt af en skråstreg:", 
	'installation:dataroot'  =>  "Den fulde sti til den folder, hvor tilføjede filer bliver gemt, efterfulgt af en skråstreg:", 
	'installation:dataroot:warning'  =>  "Du skal oprette denne folder manuelt. Den bør være placeret i en anden folder end din Elgg installation.",
	'installation:sitepermissions' => "Standard adgangstilladelser:", 
	'installation:language'  =>  "Standardsproget for din side:", 
	'installation:debug'  =>  "Debug mode giver ekstra information, der kan bruges til at diagnosticere fejl, men den gør dit system langsommere og bør kun bruges, hvis du har problemer.",	  
	'installation:debug:none' => 'Fravælg debug mode (anbefalet)',
	'installation:debug:error' => 'Vis kun kritiske fejl',
	'installation:debug:warning' => 'Vis fejl og advarsler',
	'installation:debug:notice' => 'Log alle fejl, advarsler og bemærkninger',
	
	// Walled Garden support
	'installation:registration:description' => 'Bruger registrering er aktiveret som standard. Slå dette fra, hvis du ikke ønsker, at nye brugere skal kunne registrere på egen hånd.',
	'installation:registration:label' => 'Tillad nye brugere at registrere sig',
	'installation:walled_garden:description' => 'Aktiver stedet til at køre som et privat netværk. Dette vil ikke tillade brugere, der ikke er logget ind, at se andre sider end dem, der specifikt er markeret som offentlige.',
	'installation:walled_garden:label' => 'Begræns sider til registrerede brugere',

	'installation:httpslogin' => "Aktiver dette hvis du vil have bruger log ind håndteret af HTTPS. Du skal have https aktiveret på din server for at dette fungerer.",
	'installation:httpslogin:label' => "Aktiver HTTPS log ind",
	'installation:view' => "Vælg det udseende, der skal bruges som standard for din side eller lad være med at vælge for at bruge standard udseende (er du i tvivl, så brug standard):",
	
	'installation:siteemail'  =>  "Sidens e-mail adresse (bruges når der sendes system e-mails)",

	'installation:disableapi'  =>  "RESTful API er en fleksibel og udvidelig grænseflade, der tillader programmer at tilgå visse Elgg funktioner udefra.", 
	'installation:disableapi:label'  =>  "Aktiver Elgg's web service API",
	
	'installation:allow_user_default_access:description' => "Hvis tilvalgt vil individuelle brugere have tilladelse til at definere deres eget adgangsniveau, som kan overskrive systemets standard adgangsniveau.",
	'installation:allow_user_default_access:label' => "Tillad bruger standard adgang.",	

	'installation:simplecache:description' => "Simple cache øger præstationen ved at cache statisk indhold inklusive nogle CSS og JavaScript filer. Normalt vil du have dette slået til.",
	'installation:simplecache:label' => "Brug simple cache (anbefalet)",
	
	'installation:viewpathcache:description' => "View filepath cache nedsætter loadtiden på plugins ved at cache placeringen af deres  visninger.",
	'installation:viewpathcache:label' => "Brug view filepath cache (anbefalet)",
	
	'upgrading' => 'Opgraderer...',
	'upgrade:db' => 'Din database blev opgraderet.',
	'upgrade:core' => 'Din elgg installation blev opgraderet.',
	'upgrade:unable_to_upgrade' => 'Kunne ikke opgradere.',
	'upgrade:unable_to_upgrade_info' =>
		'This installation cannot be upgraded because legacy views
		were detected in the Elgg core views directory. These views have been deprecated and need to be
		removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
		simply delete the views directory and replace it with the one from the latest
		package of Elgg downloaded from <a href="http://elgg.org">elgg.org</a>.<br /><br />

		If you need detailed instructions, please visit the <a href="http://docs.elgg.org/wiki/Upgrading_Elgg">
		Upgrading Elgg documentation</a>.  If you require assistance, please post to the
		<a href="http://community.elgg.org/pg/groups/discussion/">Community Support Forums</a>.',

	'update:twitter_api:deactivated' => 'Twitter API (tidligere Twitter Service) blev deaktiveret under opgraderingen. Aktiver den venligst manuelt, hvis det kræves.',
	'update:oauth_api:deactivated' => 'OAuth API (previously OAuth Lib) blev deaktiveret under opgraderingen. Aktiver den venligst manuelt, hvis det kræves.',

	'deprecated:function' => '%s() was deprecated by %s()',
			
/**
 * Welcome
 */

	'welcome' => "Velkommen",
	'welcome:user' => 'Velkommen %s',
	
/**
* Emails
*/ 	
	'email:settings'  =>  "E-mail", 
	'email:address:label'  =>  "Din e-mail adresse",
	
	'email:save:success'  =>  "Ny e-mail adresse gemt, anmodning om verifikation afsendt.", 
	'email:save:fail'  =>  "Din nye e-mail adresse kunne ikke gemmes.", 
	
	'friend:newfriend:subject'  =>  "%s har gjort dig til ven!", 
	'friend:newfriend:body'  =>  "%s har gjort dig til ven!
	
For at se deres personlige profil, klik her;

%s

Du kan ikke besvare via denne mail.",

	'email:resetpassword:subject'  =>  "Adgangskode ændret!", 
	'email:resetpassword:body'  =>  "Hej %s,
	
Dit password er blevet ændret til: %s",
 

	'email:resetreq:subject'  =>  "Anmodning om en ny adgangskode.", 
	'email:resetreq:body'  =>  "Hej %s,
	
Nogen (fra IP adressen %s) har anmodet om en ny adgangskode til deres konto.

Hvis det var dig, der sendte anmodningen så klik på linket nedenfor ellers ignorer denne e-mail.

%s
",

/**
* user default access
*/
	
	'default_access:settings' => "Dit standard adgangsniveau",
	'default_access:label' => "Standard adgang",
	'user:default_access:success' => "Dit nye standard adgangsniveau er gemt.",
	'user:default_access:failure' => "Dit nye standard adgangsniveau kunne ikke gemmes.",	

/**
 * XML-RPC
 */
	'xmlrpc:noinputdata'	=>	"Input data mangler",
		
	/**
	* Comments
	*/
	
	'comments:count'  =>  "%s kommntarer",
	
	'riveraction:annotation:generic_comment'  =>  "%s har kommenteret %s",
	
	'generic_comments:add'  =>  "Tilføj kommentar",
	'generic_comments:post' => "Send kommentar", 
	'generic_comments:text'  =>  "Kommentar",
	'generic_comments:latest' => "Seneste kommentarer", 
	'generic_comment:posted'  =>  "Din kommentar er blevet tilføjet.", 
	'generic_comment:deleted'  =>  "Din kommentar er blevet slettet.", 
	'generic_comment:blank'  =>  "Beklager, men du er nødt til at skrive noget i din kommentar for at vi kan gemme den.", 
	'generic_comment:notfound'  =>  "Beklager, vi kunne ikke finde det specifikke objekt.", 
	'generic_comment:notdeleted'  =>  "Beklager, vi kunne ikke slette denne kommentar.", 
	'generic_comment:failure'  =>  "En uforudset fejl skete ved tilføjelsen af din kommentar. Prøv venligst igen.", 
	'generic_comment:none' => 'Ingen kommentarer',
		
	'generic_comment:email:subject'  =>  "Du har en ny kommentar!", 
	'generic_comment:email:body'  =>  "Du har en ny kommentar til din \"%s\" fra %s. Der står:
	
		
%s


Klik her for at svare eller se det oprindelige emne:

%s

Klik her for at se %s's profil:

%s

Du kan ikke svare via denne mail.",

/**
* Entities
*/
	'byline' => 'Af %s',	
	'entity:default:strapline'  =>  "Oprettet %s af %s", 
	'entity:default:missingsupport:popup'  =>  "Denne enhed kan ikke vises korrekt. Dette kan være fordi det kræver undersøttelse fra et plugin, der ikke længere er installeret.",
	
	'entity:delete:success'  =>  "Enheden %s er blevet slettet", 
	'entity:delete:fail'  =>  "Enheden %s kunne ikke slettes", 

/**
* Action gatekeeper
*/
	
	'actiongatekeeper:missingfields'  =>  "Form mangler __token eller __ts felter", 
	'actiongatekeeper:tokeninvalid'  =>  "Vi stødte på en fejl (token mismatch). Det betyder formegentlig, at siden du brugte er udløbet. Prøv venligst igen.", 
	'actiongatekeeper:timeerror'  =>  "Siden du brugte er udløbet. Genopfrisk siden og prøv igen.", 
	'actiongatekeeper:pluginprevents'  =>  "En udvidelse har forhindret denne form i at blive indsendt.", 

	/**
	* Word blacklists
	*/
	
	'word:blacklist'  =>  "og, den, det, da, men hun, han, hendes, hans, en, et, ikke, også, om, nu, dermed, således, til, stadig, ligesom, derimod, derfor, omvendt, tværtimod, hellere, følge, yderligere, alligevel, imens, derefter, denne, dette, synes, hvem, hvad, hvor, hvornår, hvordan, hvorfor, hvorledes, hvormed",
	
	/**
	* Tag labels
	*/
	
	'tag_names:tags' => 'Tags',	  
	'tags:site_cloud' => 'Site Tag Cloud',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Kan ikke kontakte %s. Du kan muligvis opleve problemer med at gemme indhold.',
	'js:security:token_refreshed' => 'Forbindelse med %s genoprettet!',
			
	/**
	* Languages according to ISO 639-1
	*/	
	'aa'  =>  "Afar", 
	'ab'  =>  "Abkhasisk", 
	'af'  =>  "Afrikaans", 
	'am'  =>  "Amharisk", 
	'ar'  =>  "Arabisk", 
	'as'  =>  "Assamesisk", 
	'ay'  =>  "Aymaransk", 
	'az'  =>  "Azerbadjansk", 
	'ba'  =>  "Bashkir", 
	'be'  =>  "Hviderussisk", 
	'bg'  =>  "Bulgarsk", 
	'bh'  =>  "Bihari", 
	'bi'  =>  "Bislama", 
	'bn'  =>  "Bengalsk", 
	'bo'  =>  "Tibetansk", 
	'br'  =>  "Breton", 
	'ca'  =>  "Catalansk", 
	'co'  =>  "Corsicansk", 
	'cs'  =>  "Tjekkisk", 
	'cy'  =>  "Walisisk", 
	'da'  =>  "Dansk", 
	'de'  =>  "Tysk", 
	'dz'  =>  "Bhutani", 
	'el'  =>  "Græsk", 
	'en'  =>  "Engelsk", 
	'eo'  =>  "Esperanto", 
	'es'  =>  "Spansk", 
	'et'  =>  "Estisk", 
	'eu'  =>  "Baskisk", 
	'fa'  =>  "Persisk", 
	'fi'  =>  "Finsk", 
	'fj'  =>  "Fiji", 
	'fo'  =>  "Faeroese", 
	'fr'  =>  "Fransk", 
	'fy'  =>  "Frisian", 
	'ga'  =>  "Irsk", 
	'gd'  =>  "Skotsk / Gælisk", 
	'gl'  =>  "Galician", 
	'gn'  =>  "Guaranisk", 
	'gu'  =>  "Gujarati", 
	'he'  =>  "Hebraisk", 
	'ha'  =>  "Hausa", 
	'hi'  =>  "Hindi", 
	'hr'  =>  "Croatisk", 
	'hu'  =>  "Ungarsk", 
	'hy'  =>  "Armensk", 
	'ia'  =>  "Interlingua", 
	'id'  =>  "Indonesisk", 
	'ie'  =>  "Interlingue", 
	'ik'  =>  "Inupiak",
	//"in" => "Indonesian", 
	'is'  =>  "Islandsk", 
	'it'  =>  "Italiensk", 
	'iu'  =>  "Inuktitut", 
	'iw'  =>  "Hebraisk (forældet)", 
	'ja'  =>  "Japansk", 
	'ji'  =>  "Yiddish (obsolete)", 
	'jw'  =>  "Javanesisk", 
	'ka'  =>  "Georgisk", 
	'kk'  =>  "Kazakh", 
	'kl'  =>  "Grønlandsk", 
	'km'  =>  "Khmer", 
	'kn'  =>  "Kannada", 
	'ko'  =>  "Koreansk", 
	'ks'  =>  "Kashmiri", 
	'ku'  =>  "Kurdisk", 
	'ky'  =>  "Kirghizisk", 
	'la'  =>  "Latin", 
	'ln'  =>  "Lingala", 
	'lo'  =>  "Laothian", 
	'lt'  =>  "Lithaunsk", 
	'lv'  =>  "Lettisk", 
	'mg'  =>  "Malagasy", 
	'mi'  =>  "Maori", 
	'mk'  =>  "Makedonsk", 
	'ml'  =>  "Malaysisk", 
	'mn'  =>  "Mongolsk", 
	'mo'  =>  "Moldovisk", 
	'mr'  =>  "Marathi", 
	'ms'  =>  "Malajisk", 
	'mt'  =>  "Maltesisk", 
	'my'  =>  "Burmesisk", 
	'na'  =>  "Naurisk", 
	'ne'  =>  "Nepalesisk", 
	'nl'  =>  "Hollandsk", 
	'no'  =>  "Norsk", 
	'oc'  =>  "Occitansk",
	'om'  =>  "Oromo", 
	'or'  =>  "Orija", 
	'pa'  =>  "Punjab", 
	'pl'  =>  "Polsk", 
	'ps'  =>  "Afghansk", 
	'pt'  =>  "Portuguisisk", 
	'qu'  =>  "Quechua", 
	'rm'  =>  "Retroromansk", 
	'rn'  =>  "Kirundi", 
	'ro'  =>  "Rumænsk", 
	'ru'  =>  "Russisk", 
	'rw'  =>  "Kinyarwanda", 
	'sa'  =>  "Sanskrit", 
	'sd'  =>  "Sindhi", 
	'sg'  =>  "Sangro", 
	'sh'  =>  "Serbokroatisk", 
	'si'  =>  "Singalesisk", 
	'sk'  =>  "Slovakisk", 
	'sl'  =>  "Slovensk", 
	'sm'  =>  "Samoansk",
	'sn'  =>  "Shona", 
	'so'  =>  "Somalisk", 
	'sq'  =>  "Albansk", 
	'sr'  =>  "Serbisk", 
	'ss'  =>  "Siswati", 
	'st'  =>  "Sesotho", 
	'su'  =>  "Sundanesisk", 
	'sv'  =>  "Svensk", 
	'sw'  =>  "Swahili", 
	'ta'  =>  "Tamilsk",
	'te'  =>  "Tegulu", 
	'tg'  =>  "Tajik", 
	'th'  =>  "Thai", 
	'ti'  =>  "Tigrinya", 
	'tk'  =>  "Turkmen", 
	'tl'  =>  "Tagalog", 
	'tn'  =>  "Setswana", 
	'to'  =>  "Tonga", 
	'tr'  =>  "Tyrkisk", 
	'ts'  =>  "Tsonga",
	'tt'  =>  "Tatar", 
	'tw'  =>  "Twi", 
	'ug'  =>  "Uigur", 
	'uk'  =>  "Ukrainsk", 
	'ur'  =>  "Urdu", 
	'uz'  =>  "Uzbek", 
	'vi'  =>  "Vietnamesisk", 
	'vo'  =>  "Volapuk", 
	'wo'  =>  "Wolof", 
	'xh'  =>  "Xhosa", 
	//"y" => "Yiddish",
	"yi"  => "Yiddish",
	'yo'  =>  "Yoruba", 
	'za'  =>  "Zuang", 
	'zh'  =>  "Kinesisk", 
	'zu'  =>  "Zulu"
);

add_translation("da",$danish);

?>