<?php
/**
 * Core Finnish Language
 *
 * @package Elgg.Core
 * @subpackage Languages.Finnish
 */

$finnish = array(
/**
 * Sites
 */

	'item:site' => 'Sivustot',

/**
 * Sessions
 */

	'login' => "Kirjaudu",
	'loginok' => "Olet kirjautunut sisään.",
	'loginerror' => "Kirjautuminen epäonnistui. Tämä saattaa johtua siitä, että et ole vahvistanut käyttäjätiliäsi, syöttämissäsi tiedoissa oli virheitä tai olet yrittänyt kirjautua liian monta kertaa väärin tiedoin. Vahvista tietojen oikeellisuus ja yritä uudelleen.",
	'login:empty' => "anna käyttäjänimi ja salasana.",
	'login:baduser' => "tiliäsi ei voitu ladata.",
	'auth:nopams' => "Sisäinen virhe. Käyttäjän autentikaatiota ei ole asennettu.",

	'logout' => "Kirjaudu ulos",
	'logoutok' => "Olet kirjautunut ulos.",
	'logouterror' => "Uloskirjautuminen epäonnistui. Yritä uudelleen.",

	'loggedinrequired' => "Tämän sivun näkyminen edellyttää että olet kirjautuneena sisään.",
	'adminrequired' => "Tämän sivun näkyminen edellyttää ylläpitäjän oikeuksia.",
	'membershiprequired' => "Sinun pitää olla ryhmän jäsen nähdäksesi tämän sivun.",

/**
 * Errors
 */
	'exception:title' => "Tervetuloa Elggiin.",

	'actionundefined' => "Haluttua toimenpidettä (%s) ei määritelty järjestelmässä.",
	'actionnotfound' => "Toimenpidetiedostoa %s ei löytynyt.",
	'actionloggedout' => "Valitettavasti tätä toimintoa ei voi suorittaa ellet ole kirjautuneena sisään.",
	'actionunauthorized' => 'Sinulla ei ole oikeuksia tämän toiminnon suorittamiseen',

	'InstallationException:SiteNotInstalled' => 'Pyyntöä ei voitu suorittaa. Tätä sivustoa ei ole asetettu tai database on ajettu alas.',
	'InstallationException:MissingLibrary' => 'Ei voitu ladata %s',
	'InstallationException:CannotLoadSettings' => 'Elgg ei voinut ladata asetustiedostoa. Se ei ole olemassa tai .',

	'SecurityException:Codeblock' => "Pääsy estetty, oikeuksia koodilohkoon vaaditaan",
	'DatabaseException:WrongCredentials' => "Elgg ei pysty luomaan tietokantayhteyttä annetuilla valtuuksilla.",
	'DatabaseException:NoConnect' => "Elgg ei pystynyt valitsemaan tietokantaa '%s'. Tarkista, että tietokanta on olemassa ja sinulla on pääsyoikeudet siihen.",
	'SecurityException:FunctionDenied' => "Pääsy oikeuksia vaativaan toimintoon '%s' on estetty.",
	'DatabaseException:DBSetupIssues' => "Ongelmia havaittu:",
	'DatabaseException:ScriptNotFound' => "Elgg ei löytänyt vaadittua tietokantaskriptiä %s.",
	'DatabaseException:InvalidQuery' => "Epäsopiva kysely",

	'IOException:FailedToLoadGUID' => "Uuden %s lataaminen GUID:%d epäonnistui",
	'InvalidParameterException:NonElggObject' => "Ei-ElggObjekti siirretään ElggObjektin koostajaan!",
	'InvalidParameterException:UnrecognisedValue' => "Tunnistamaton arvo siirretty.",

	'InvalidClassException:NotValidElggStar' => "GUID:%d ei ole kelvollinen %s",


	'PluginException:MisconfiguredPlugin' => "%s on virheellisesti konfiguroitu liitännäinen ja se poistettiin käytöstä. Etsi Elggin wikistä mahdollisia ongelmanaiheuttajia (http://docs.elgg.org/wiki/).",
	'PluginException:CannotStart' => '%s (guid: %s) ei voi käynnistyä.  Syy: %s',
	'PluginException:InvalidID' => "%s on vääränlainen liitännäisen ID.",
	'PluginException:InvalidPath' => "%s on vääränlainen liitännäisen polku.",
	'PluginException:InvalidManifest' => 'vääränlainen liitännäisen manifesti tiedosto %s',
	'PluginException:InvalidPlugin' => '%s ei ole käypä liitännäinen.',
	'PluginException:InvalidPlugin:Details' => '%s ei ole käypä liitännäinen: %s',

	'ElggPlugin:MissingID' => 'Liitännäisen ID puuttuu (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'ElggPluginPackage puuttuu liitännäiselle ID %s (guid %s)',

	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Tiedosto %s puuttuu paketista',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Vääränlainen riippuvuustyyppi "%s"',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Vääränlainen suoritus tyyppi "%s"',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Vääränlainen %s riippuvuus "%s" liitännäisessä %s.  Liitännäiset eivät voi kohdata tai ne vaativat jotain minkä heidän kuuluu toimittaa!',

	'ElggPlugin:Exception:CannotIncludeFile' => ' %s ei voi kuulua liitännäiselle %s (guid: %s) paikassa %s.Tarkista luvat!',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Ei voida avata kansiota views liitännäiselle %s (guid: %s) paikassa %s.Tarkista luvat!',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'Ei voida rekisteröidä kieliä liitännäiselle %s (guid: %s) paikassa %s.Tarkista luvat!',

	'ElggPlugin:Exception:NoID' => 'ei ID:tä liitännäiselle guid %s!',

	'PluginException:ParserError' => 'Virhe jäsennettäessä manifestia tällä API versiolla %s liitännäisessä %s.',
	'PluginException:NoAvailableParser' => 'Jäsentäjää manifesti API versiolle %s liitännäisessä %s ei löydy.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Vaadittu attribuutti '%s' puuttuu manifestista liitännäiselle %s.",

	'ElggPlugin:Dependencies:Requires' => 'Vaatii',
	'ElggPlugin:Dependencies:Suggests' => 'Ehdottaa',
	'ElggPlugin:Dependencies:Conflicts' => 'On ristiriidassa',
	'ElggPlugin:Dependencies:Conflicted' => 'Ristiriidassa',
	'ElggPlugin:Dependencies:Provides' => 'Toimittaa',
	'ElggPlugin:Dependencies:Priority' => 'Prioriteetti',

	'ElggPlugin:Dependencies:Elgg' => 'Elgg versio',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP jatke: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP ini asetukset: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Liitännäinen: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Jälkeen %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Ennen %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s ei ole asennettu',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Puuttuu',

	'InvalidParameterException:NonElggUser' => "Ei-ElggKäyttäjä siirretään ElggKäyttäjän koostajalle!",

	'InvalidParameterException:NonElggSite' => "Ei-ElggSivusto siirretään ElggSivuston koostajalle!",

	'InvalidParameterException:NonElggGroup' => "Ei-ElggRyhmä siirretään ElggRyhmän koostajalle!",

	'IOException:UnableToSaveNew' => "Uutta %s ei voida tallentaa",

	'InvalidParameterException:GUIDNotForExport' => "GUIDta ei ole määritelty viennin aikana, tätä ei pitäisi koskaan tapahtua.",
	'InvalidParameterException:NonArrayReturnValue' => "Entiteetin sarjoitusfunktio palautti parametrin arvona jonkin muun kuin taulukon",

	'ConfigurationException:NoCachePath' => "Välimuistin polkua ei ole määritelty!",
	'IOException:NotDirectory' => "%s ei ole hakemisto",

	'IOException:BaseEntitySaveFailed' => "Ei voitu tallentaa uuden objektin perusentiteetti-tietoa!",
	'InvalidParameterException:UnexpectedODDClass' => "tuonti() toimitti odottamattoman ODD-luokan",
	'InvalidParameterException:EntityTypeNotSet' => "Entiteetin tyyppi asetettava.",

	'ClassException:ClassnameNotClass' => "%s ei ole %s.",
	'ClassNotFoundException:MissingClass' => "Luokkaa '%s' ei löytynyt. Liitännäinen puuttuu?",
	'InstallationException:TypeNotSupported' => "Tyyppiä %s ei tueta. Tämä on osoitus virheestä asennuksessasi, johtuu todennäköisesti keskeneräisestä päivityksestä.",

	'ImportException:ImportFailed' => "Ei voitu tuoda osaa %d",
	'ImportException:ProblemSaving' => "Ongelma tallennettaessa %s",
	'ImportException:NoGUID' => "Uusi kokonaisuus luotu, mutta sillä ei ole GUIDia. Tätä ei pitäisi tapahtua.",

	'ImportException:GUIDNotFound' => "Entiteettiä '%d' ei löytynyt.",
	'ImportException:ProblemUpdatingMeta' => "'%d' entiteetin päivityksessä '%s' oli ongelmia",

	'ExportException:NoSuchEntity' => "Entiteettiä GUID:%d ei löytynyt",

	'ImportException:NoODDElements' => "OpenDD-osia ei löytynyt tuonnissa. Tuonti epäonnistui.",
	'ImportException:NotAllImported' => "Kaikkia osia ei tuotu.",

	'InvalidParameterException:UnrecognisedFileMode' => "Tunnistamaton tiedostomoodi '%s'",
	'InvalidParameterException:MissingOwner' => "Tiedostolta %s (tiedoston guid:%d) (omistajan guid:%d) puuttuu omistaja!",
	'IOException:CouldNotMake' => "%s ei voi tehdä",
	'IOException:MissingFileName' => "Tiedostolle täytyy määritellä nimi ennen kuin se avataan.",
	'ClassNotFoundException:NotFoundNotSavedWithFile' => "Tiedostovaraston luokkaa  %s tiedostolle %u ei voitu ladata.",
	'NotificationException:NoNotificationMethod'  =>  "Ilmoitusmetodia ei ole määritelty." , 
	'NotificationException:NoHandlerFound'  =>  "Käsittelijää '%s' ei löytynyt tai sitä ei voitu kutsua." , 
	'NotificationException:ErrorNotifyingGuid'  =>  "Virhe huomautettaessa %d" , 
	'NotificationException:NoEmailAddress'  =>  "GUID:%d sähköpostiosoitetta ei saatu." , 
	'NotificationException:MissingParameter'  =>  "Tarvittava parametri puuttuu, '%s'." , 

	'DatabaseException:WhereSetNonQuery'  =>  "Where-kokoelmasta puuttuu WhereQueryComponent" , 
	'DatabaseException:SelectFieldsMissing'  =>  "Tyylin valinnan haun kenttiä puuttuu" , 
	'DatabaseException:UnspecifiedQueryType'  =>  "Tuntemattomia tai tarkentamattomia hakutyyppejä." , 
	'DatabaseException:NoTablesSpecified'  =>  "Taulukkoa haulle ei määritelty." , 
	'DatabaseException:NoACL'  =>  "Pääsyrajoitustietoja ei välitetty haun mukana" , 

	'InvalidParameterException:NoEntityFound'  =>  "Entiteettiä ei löytynyt, sitä ei joko ole olemassa tai siihen ei ole pääsyä." ,
 
	'InvalidParameterException:GUIDNotFound'  =>  "GUID:%s ei löytynyt tai siihen ei ole pääsyä." , 
	'InvalidParameterException:IdNotExistForGUID'  =>  "'%s' ei ole olemassa guid:%d" , 
	'InvalidParameterException:CanNotExportType'  =>  "Ei ole mahdollista viedä '%s'" , 
	'InvalidParameterException:NoDataFound'  =>  "Mitään tietoa ei löytynyt." , 
	'InvalidParameterException:DoesNotBelong'  =>  "Ei kuulu entiteettiin." , 
	'InvalidParameterException:DoesNotBelongOrRefer'  =>  "Ei kuulu tai viittaa entiteettiin." , 
	'InvalidParameterException:MissingParameter'  =>  "Parametrejä puuttuu, GUID täytyy toimittaa." , 
	'InvalidParameterException:LibraryNotRegistered' => '%s ei ole rekisteröity kirjasto',

	'APIException:ApiResultUnknown' => "API-tulokset ovat tuntemattomia, tätä ei saisi koskaan tapahtua.",
	'ConfigurationException:NoSiteID' => "Yhtään sivun ID:tä ei ole määritelty.",
	'SecurityException:APIAccessDenied' => "Ylläpitäjä on estänyt pääsyn API-rajapintaan.",
	'SecurityException:NoAuthMethods' => "Yhtään autentikointitapaa ei löytynyt, jotta API-pyyntö voitaisiin varmentaa.",
	'SecurityException:UnexpectedOutputInGatekeeper' => 'Odottamaton lopputulos gatekeeper callissa. Ei suoriteta turvallisuus takia. Etsi http://docs.elgg.org/ lisää tietoa tästä.',
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "Metodia tai funktiota expose_method() ei voi kutsua",
	'InvalidParameterException:APIParametersArrayStructure' => "Parametrien taulukkomuoto on virheellinen eikä kutsuttavissa expose-metodilla '%s'",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Tunnistamaton http-metodi %s api-metodille '%s'",
	'APIException:MissingParameterInMethod' => "Puuttuva parametri %s metodissa %s",
	'APIException:ParameterNotArray' => "%s ei ole taulukko.",
	'APIException:UnrecognisedTypeCast' => "Tunnistamaton tyyppi %s muuttujalle '%s' metodissa '%s'.",
	'APIException:InvalidParameter' => "Virheellisiä parametrejä löytynyt '%s' metodissa '%s'.",
	'APIException:FunctionParseError' => "%s(%s) on jäsennysongelma.",
	'APIException:FunctionNoReturn' => "%s(%s) palautettiin ilman arvoa.",
	'APIException:APIAuthenticationFailed' => "Metodin kutsumisessa API-autentikointivirhe",
	'APIException:UserAuthenticationFailed' => "Metodin kutsumisessa käyttäjän autentikointivirhe",
	'SecurityException:AuthTokenExpired' => "Autentikointitoken puuttuu, ei kelpaa tai on vanhentunut.",
	'CallException:InvalidCallMethod' => "%s täytyy kutsua käyttäen '%s'.",
	'APIException:MethodCallNotImplemented' => "Metodikutsua '%s' ei ole implementoitu.",
	'APIException:FunctionDoesNotExist' => "Funktiota metodille '%s' ei voi kutsua",
	'APIException:AlgorithmNotSupported' => "Algoritmia '%s' ei tueta tai se on pois käytöstä.",
	'ConfigurationException:CacheDirNotSet' => "Välimuistihakemistoa 'cache_path' ei ole asetettu.",
	'APIException:NotGetOrPost' => "Pyyntömetodi täytyy olla GET tai POST",
	'APIException:MissingAPIKey' => "Puuttuva API-avain",
	'APIException:BadAPIKey' => "Virheellinen API-avain",
	'APIException:MissingHmac' => "Puuttuva X-Elgg-hmac header",
	'APIException:MissingHmacAlgo' => "Puuttuva X-Elgg-hmac-algo header",
	'APIException:MissingTime' => "Puuttuva X-Elgg-time header",
	'APIException:MissingNonce' => "Puuttuva X-Elgg-nonce header",
	'APIException:TemporalDrift' => "X-Elgg-aika on liian kaukana menneisyydessä tai tulevaisuudessa. Ajanjakso-virhe.",
'APIException:NoQueryString' => "Ei tietoa hakujonossa",
	'APIException:MissingPOSTHash' => "Puuttuva X-Elgg-posthash header",
	'APIException:MissingPOSTAlgo' => "Puuttuva X-Elgg-posthash_algo header",
	'APIException:MissingContentType' => "Lähetettävän datan tyyppi puuttuu",
	'SecurityException:InvalidPostHash' => "POST datan hash on virheellinen - Odotettu %s mutta saatu %s.",
	'SecurityException:DupePacket' => "Pakettiallekirjoitus on jo nähty.",
	'SecurityException:InvalidAPIKey' => "Virheellinen tai puuttuva API-avain.",
	'NotImplementedException:CallMethodNotImplemented' => "Kutsumetodia '%s' ei tueta.",

	'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC -metodikutsua '%s' ei implementoitu.",
	'InvalidParameterException:UnexpectedReturnFormat' => "Metodikutsu '%s' palautti odottamattoman tuloksen.",
	'CallException:NotRPCCall' => "Kutsu ei ole kelvollinen XML-RPC-kutsu",

	'PluginException:NoPluginName' => "Liitännäisen nimeä ei löydy",

	'SecurityException:authenticationfailed' => "Käyttäjää ei voitu autentikoida",

	'CronException:unknownperiod' => '%s ei ole tunnettu ajanjakso.',

	'SecurityException:deletedisablecurrentsite' => 'Sivustoa ei voi poistaa tai ottaa pois käytöstä kun tarkastelet sitä.',

	'RegistrationException:EmptyPassword' => 'Salasana kentät ei voi olla tyhjiä',
	'RegistrationException:PasswordMismatch' => 'Salasanojen täytyy täsmätä',
	'LoginException:BannedUser' => 'Sinulta on estetty pääsy tälle sivustolle etkä voi kirjautua sisään',
	'LoginException:UsernameFailure' => 'Emme voineet kirjata sinua sisään, tarkasta käyttäjänimi ja salasana.',
	'LoginException:PasswordFailure' => 'Emme voineet kirjata sinua sisään, tarkasta käyttäjänimi ja salasana.',
	'LoginException:AccountLocked' => 'Tilisi on lukittu koska olet yrittäny liian monta kertaa sisään.',

	'memcache:notinstalled' => 'PHP memcache -moduulia ei ole asennettu, asenna php5-memcache',
	'memcache:noservers' => 'Memcache-palvelimia ei ole määritelty, määrittele asetus kohdassa $CONFIG->memcache_servers variable',
	'memcache:versiontoolow' => 'Memcache vaatii vähintään version %s toimiakseen, ja käytössä on versio %s',
	'memcache:noaddserver' => 'Useiden palvelinten tuki on poistettu käytöstä, PECL memcache -kirjasto pitää päivittää',

	'deprecatedfunction' => '',
	'pageownerunavailable' => 'Varoitus: sivun omistaja %d ei saatavilla!',
	'viewfailure' => 'Sisäinen virhe näkymässä %s',
	'changebookmark' => 'Tarkista kirjanmerkkisi tälle sivulle',
/**
 * API
 */
	'system.api.list' => "Listaa kaikki mahdolliset API-kutsut järjestelmästä.",
	'auth.gettoken' => "API-kutsun avulla käyttäjä voi kirjautua sisään ja saada autentikointitokenin, jota voi käyttää myöhemmin tunnistautumiseen. Lähetä se parametrina auth_token",

/**
 * User details
 */

	'name' => "Näytettävä nimi",
	'email' => "Sähköpostiosoite",
	'username' => "Käyttäjänimi",
	'loginusername' => "Käyttäjänimi tai sähköpostiosoite",
	'password' => "Salasana",
	'passwordagain' => "Salasana (uudelleen)",
	'admin_option' => "Tee tästä käyttäjästä Valvoja?",

/**
 * Access
 */

	'PRIVATE' => "Yksityinen",
	'LOGGED_IN' => "Sisäänkirjautuneet käyttäjät",
	'PUBLIC' => "Julkinen",
	'access:friends:label' => "Ystävät",
	'access' => "Pääsy",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Kojelauta",
	'dashboard:nowidgets' => "Kojelautasi on henkilökohtainen työpöytäsi sivustolla. Napsauta 'Muokkaa sivua' lisätäksesi vimpaimia joiden avulla voit seurata toimintaasi sivustolla.",

	'widgets:add' => 'Lisää vimpaimia sivullesi',
	'widgets:add:description' => "Valitse ominaisuudet mitä haluat lisätä sivullesi raahaamalla ne <b>Vimpainkirjastosta</b> oikealle. Voit raahata vimpaimia mihin tahansa kolmesta palstasta alhaalla ja määritellä mihin kohtaan haluat ne sijoittaa.
Poistaaksesi vimpaimen, raahaa se takaisin <b>Vimpainkirjastoon</b>." ,

	'widgets:position:fixed' => '(Määritelty sijainti sivulla)',
	'widget:unavailable' => 'Olet jo lisännyt tämän vimpaimen',
	'widget:numbertodisplay' => 'vimpainten määrä, jotka näytetään',

	'widget:delete' => 'Poista %s',
	'widget:edit' => 'kustomoi tämä vimpain',

	'widgets' => "Vimpaimet",
	'widget' => "Vimpain",
	'item:object:widget' => "Vimpaimet",
	'widgets:save:success' => "Vimpaimesi tallennettiin onnistuneesti.",
	'widgets:save:failure' => "Vimpaimen tallennuksessa oli ongelmia. Yritä uudelleen.",
	'widgets:add:success' => "Vimpain tallennettiin onnistuneesti.",
	'widgets:add:failure' => "Vimpaimesi ei tallentunut. Yritä uudelleen.",
	'widgets:move:failure' => "Vimpaimen uutta sijaintia ei voitu tallentaa.",
	'widgets:remove:failure' => "Tätä vimpainta ei voida poistaa",

/**
 * Groups
 */

	'group' => "Ryhmä",
	'item:group' => "Ryhmät",

/**
 * Users
 */

	'user' => "Käyttäjä",
	'item:user' => "Käyttäjät",

/**
 * Friends
 */

	'friends' => "Ystävät",
	'friends:yours' => "Ystäväsi",
	'friends:owned' => "Käyttäjän %s ystävät",
	'friend:add' => "Lisää ystäväksi",
	'friend:remove' => "Poista ystävistä",

	'friends:add:successful' => "Olet lisännyt käyttäjän %s ystäväksesi.",
	'friends:add:failure' => "Käyttäjää %s ei voitu lisätä ystäväksi. Yritä uudelleen.",

	'friends:remove:successful' => "Olet poistanut käyttäjän %s ystävistäsi.",
	'friends:remove:failure' => "Käyttäjää %s ei voitu poistaa ystävistäsi. Yritä uudelleen.",

	'friends:none' => "Tämä käyttäjä ei ole lisännyt vielä ketään ystäväkseen.",
	'friends:none:you' => "Et ole lisännyt vielä ketään ystäväksesi. Tarkastele omien kiinnostuksen kohteittesi kautta muita henkilöitä joilla on samoja kiinnostuksen kohteita.",

	'friends:none:found' => "Ystäviä ei löydetty.",

	'friends:of:none' => "Kukaan ei ole vielä lisännyt tätä käyttäjää ystäväkseen.",
	'friends:of:none:you' => "Kukaan ei ole lisännyt sinua ystäväkseen vielä. Lisää sisältöä profiiliisi ja anna ihmisten löytää sinut!",

	'friends:of:owned' => "Käyttäjät, joiden ystävänä %s on",

	'friends:of' => "Kenen ystävänä",
	'friends:collections' => "Ystäväkokoelma",
	'collections:add' => "Uusi ystäväkokoelma",
	'friends:collections:add' => "Uusi ystäväkokoelma",
	'friends:addfriends' => "Valitse ystävät",
	'friends:collectionname' => "Kokoelman nimi",
	'friends:collectionfriends' => "Ystäviä kokoelmassa",
	'friends:collectionedit' => "Muokkaa tätä kokoelmaa",
	'friends:nocollections' => "Sinulla ei ole vielä yhtään ystäväkokoelmaa.",
	'friends:collectiondeleted' => "Ystäväkokoelma on poistettu.",
	'friends:collectiondeletefailed' => "Ystäväkokoelmaa ei voitu poistaa. Joko sinulla ei ole oikeuksia, tai sitten tapahtui odottamaton virhe.",
	'friends:collectionadded' => "Ystäväkokoelma luotiin onnistuneesti",
	'friends:nocollectionname' => "Sinun täytyy antaa ystäväkokoelmalle nimi ennen kuin se voidaan luoda.",
	'friends:collections:members' => "Kokoelman jäsenet",
	'friends:collections:edit' => "Muokkaa kokoelmaa",

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'Avatari',
	'avatar:create' => 'Luo avatarisi',
	'avatar:edit' => 'Muokkaa avataria',
	'avatar:preview' => 'Esikatsele',
	'avatar:upload' => 'Luo uusi avatari',
	'avatar:current' => 'Nykyinen avatar',
	'avatar:crop:title' => 'Avatarin rajaustyökalu',
	'avatar:upload:instructions' => "Avatarisi näkyy kaikkialla sivustolla. Voit vaihtaa sitä niin usein kuin haluat. (Hyväksytyt tiedostotyypit: GIF, JPG or PNG)",
	'avatar:create:instructions' => 'Klikkaa ja vedä neliötä ja rajaa haluamasi kohta kuvastasi sen avulla. Esikatseluversio kuvasta ilmestyy laatikkoon oikealle. Kun olet tyytyväinen kuvasi rajaukseen, klikkaa \'Luo avatarisi\'. Tämä rajattu versio näytetään kaikkialla sivustolla.',
	'avatar:upload:success' => 'Avatari lähetettiin',
	'avatar:upload:fail' => 'Avatarin lähetys epäonnistui',
	'avatar:resize:fail' => 'Avatarin koon muuttaminen epäonnistui',
	'avatar:crop:success' => 'Rajaus onnistui',
	'avatar:crop:fail' => 'Rajaus epäonnistui',

	'profile:edit' => 'Muokkaa profiilia',
	'profile:aboutme' => "Tietoa minusta",
	'profile:description' => "Tietoa minusta",
	'profile:briefdescription' => "Lyhyt esittely",
	'profile:location' => "Sijainti",
	'profile:skills' => "Taidot",
	'profile:interests' => "Mielenkiinnon kohteet",
	'profile:contactemail' => "Sähköpostiosoite",
	'profile:phone' => "Puhelin",
	'profile:mobile' => "Kännykkä",
	'profile:website' => "Kotisivu",
	'profile:twitter' => "Twitter käyttäjänimi",
	'profile:saved' => "Profiili tallennettiin.",

	'admin:appearance:profile_fields' => 'Muokkaa profiilikenttiä',
	'profile:edit:default' => 'Muokkaa profiilikenttiä',
	'profile:label' => "Profiilin otsikko",
	'profile:type' => "Profiilin tyyppi",
	'profile:editdefault:delete:fail' => 'Oletus profiilikentän poisto epäonnistui',
	'profile:editdefault:delete:success' => 'Oletus profiilikenttä poistettiin!',
	'profile:defaultprofile:reset' => 'Oletus järjestelmäprofiili resetoitiin',
	'profile:resetdefault' => 'Reset oletus profiili',
	'profile:explainchangefields' => "",
	'profile:editdefault:success' => 'Itemi lisättiin oletusprofiiliin',
	'profile:editdefault:fail' => 'Oletusprofiilia ei voitu tallentaa',

/**
 * Feeds
 */
	'feed:rss' => 'RSS syöte tälle sivulle',
/**
 * Links
 */
	'link:view' => 'näytä linkki',
	'link:view:all' => 'Näytä kaikki',


/**
 * River
 */
	'river' => "Joki",
	'river:friend:user:default' => "%s on nyt käyttäjän %s ystävä",
	'river:update:user:avatar' => '%s on nyt uusi avatari',
	'river:noaccess' => 'Sinulla ei ole lupaa nähdä tätä.',
	'river:posted:generic' => '%s lähetti viestin',
	'riveritem:single:user' => 'käyttäjä',
	'riveritem:plural:user' => 'joitain käyttäjiä',
	'river:ingroup' => 'ryhmässä %s',
	'river:none' => 'Ei toimintaa',

	'river:widget:title' => "Aktiivisuus",
	'river:widget:description' => "Näytä viimeisimmät tapahtumat",
	'river:widget:type' => "Aktiivisuuden tyyppi",
	'river:widgets:friends' => 'Ystävien tapahtumat',
	'river:widgets:all' => 'Kaikki sivuston tapahtumat',

/**
 * Notifications
 */
	'notifications:usersettings' => "Tiedotus asetukset",
	'notifications:methods' => "Valitse mitä tapoja haluat sallia käytettävän.",

	'notifications:usersettings:save:ok' => "Tiedotus asetukset tallennettiin.",
	'notifications:usersettings:save:fail' => "Ongelma asetuksien tallentamisessa.",

	'user.notification.get' => 'Palauta tiedotusasetukset valitulle käyttäjälle.',
	'user.notification.set' => 'Aseta oletus tiedotusasetukset valitulle käyttäjälle.',
/**
 * Search
 */

	'search' => "Etsi",
	'searchtitle' => "Etsi: %s",
	'users:searchtitle' => "Etsii käyttäjää: %s",
	'groups:searchtitle' => "Etsii ryhmiä: %s",
	'advancedsearchtitle' => "%s tulokset osuvat %s",
	'notfound' => "Tuloksia ei löytynyt.",
	'next' => "Seuraava",
	'previous' => "Edellinen",

	'viewtype:change' => "Vaihda listan tyyppiä",
	'viewtype:list' => "Listan näkymä",
	'viewtype:gallery' => "Galleria",

	'tag:search:startblurb' => "Kohdetta joiden tagit osuvat kohdalleen '%s':",

	'user:search:startblurb' => "Sopivia käyttäjiä '%s':",
	'user:search:finishblurb' => "Nähdäksesi lisää, klikkaa tästä.",

	'group:search:startblurb' => "Sopivia ryhmiä '%s':",
	'group:search:finishblurb' => "Nähdäksesi lisää, klikkaa tästä.",
	'search:go' => 'Etsi',
	'userpicker:only_friends' => 'Vain ystäviä',

/**
 * Account
 */

	'account' => "Tili",
	'settings' => "Asetukset",
	'tools' => "Työkalut",

	'register' => "Rekisteröi",
	'registerok' => "Olet rekisteröitynyt %s.",
	'registerbad' => "Tapahtui tunnistamaton virhe.",
	'registerdisabled' => "Valvoja on kieltänyt uudet rekisteröinnit",

	'registration:notemail' => 'Sähköpostiosoite jonka annoit ei kelpaa.',
	'registration:userexists' => 'Käyttäjänimi on jo olemassa',
	'registration:usernametooshort' => 'Käyttäjänimesi täytyy olla vähintään %u merkkiä pitkä.',
	'registration:passwordtooshort' => 'Salasanasi täytyy olla vähintään %u merkkiä pitkä.',
	'registration:dupeemail' => 'Tämä sähköpostiosoite on jo käytössä.',
	'registration:invalidchars' => 'Käyttäjänimessäsi on kiellettyjä merkkejä: %s.  kaikki nämä merkit ovat kiellettyjä: %s',
	'registration:emailnotvalid' => 'Sähköpostiosoite, jonka annoit ei käy tässä järjestelmässä',
	'registration:passwordnotvalid' => 'Salasana, jonka annoit ei käy tässä järjestelmässä',
	'registration:usernamenotvalid' => 'Käyttäjänimi jonka annoit ei käy tässä järjestelmässä',

	'adduser' => "Lisää käyttäjä",
	'adduser:ok' => "Olet onnistuneesti lisännyt uuden käyttäjän.",
	'adduser:bad' => "Uutta käyttäjää ei voitu luoda.",

	'user:set:name' => "Tilin nimi asetukset",
	'user:name:label' => "Minun näyttönimeni",
	'user:name:success' => "Nimesi vaihdettiin.",
	'user:name:fail' => "Nimeäsi ei voitu vaihtaa. Tarkista, että nimesi ei ole liian pitkä ja yritä sitten uudelleen.",

	'user:set:password' => "Tilin salasana",
	'user:current_password:label' => 'Nykyinen salasana',
	'user:password:label' => "Uusi salasanasi",
	'user:password2:label' => "uusi salasanasi uudelleen",
	'user:password:success' => "Salasana muutettu",
	'user:password:fail' => "salasanaa ei voitu muuttaa.",
	'user:password:fail:notsame' => "Toinen salasanoista on väärin kirjoitettu!",
	'user:password:fail:tooshort' => "Salasana on liian lyhyt!",
	'user:password:fail:incorrect_current_password' => 'nykyinen annettu salasana on väärin.',
	'user:resetpassword:unknown_user' => 'Pätemätön käyttäjä.',
	'user:resetpassword:reset_password_confirm' => 'Jos resetoit salasanasi, uusi salasanasi lähetetään sähköpostiisi.',

	'user:set:language' => "Kieli asetukset",
	'user:language:label' => "Sinun kielesi",
	'user:language:success' => "Kieliasetuksesi on päivitetty.",
	'user:language:fail' => "Kieliasetuksiasi ei voitu tallentaa.",

	'user:username:notfound' => 'Käyttäjänimeä %s ei löytynyt.',

	'user:password:lost' => 'Kadonnut salasana',
	'user:password:resetreq:success' => 'Uusi salasana lähetetty sähköpostiin',
	'user:password:resetreq:fail' => 'Ei voitu pyytä uutta salasanaa.',

	'user:password:text' => 'Pyytääksesi uutta salasanaa, anna käyttäjänimesi.',

	'user:persistent' => 'Muista minut',

	'walled_garden:welcome' => 'Tervetuloa',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Hallitse',
	'menu:page:header:configure' => 'Muokkaa',
	'menu:page:header:develop' => 'Kehitä',
	'menu:page:header:default' => 'Muuta',

	'admin:view_site' => 'Näytä sivusto',
	'admin:loggedin' => 'kirjautunut sisään nimellä %s',
	'admin:menu' => 'Valikko',

	'admin:configuration:success' => "Asetuksesi on tallennettu.",
	'admin:configuration:fail' => "Asetuksiasi ei voitu tallentaa.",

	'admin:unknown_section' => 'Valvojan Osio ei kelpaa.',

	'admin' => "Hallinta",
	'admin:description' => "Täällä voit hallita kaikkea.",

	'admin:statistics' => "Statistiikka",
	'admin:statistics:overview' => 'Yleiskatsaus',

	'admin:appearance' => 'Ulkoasu',
	'admin:utilities' => 'Palvelut',

	'admin:users' => "Käyttäjät",
	'admin:users:online' => 'Paikalla juuri nyt',
	'admin:users:newest' => 'Uusimmat',
	'admin:users:add' => 'Lisää uusi käyttäjä',
	'admin:users:description' => "Täällä voit hallita käyttäjäasetuksia.",
	'admin:users:adduser:label' => "Klikkaa tästä lisätäksesi uuden käyttäjän...",
	'admin:users:opt:linktext' => "Muokkaa käyttäjiä...",
	'admin:users:opt:description' => "Muokkaa käyttäjiä ja tilitietoja. ",
	'admin:users:find' => 'Etsi',

	'admin:settings' => 'Asetukset',
	'admin:settings:basic' => 'Perusasetukset',
	'admin:settings:advanced' => 'Edistyneet asetukset',
	'admin:site:description' => "Täällä voit muokata globaalit asetukset.",
	'admin:site:opt:linktext' => "Muokkaa sivustoa...",
	'admin:site:access:warning' => "Muutokset vaikuttavat vasta tuleviin tapahtumiin.",

	'admin:dashboard' => 'Kojelauta',
	'admin:widget:online_users' => 'Paikallaolevat käyttäjät',
	'admin:widget:online_users:help' => 'Listaa paikalla olevat käyttäjät',
	'admin:widget:new_users' => 'Uudet käyttäjät',
	'admin:widget:new_users:help' => 'Listaa uusimmat käyttäjät',
	'admin:widget:content_stats' => 'Sisällön statistiikka',
	'admin:widget:content_stats:help' => 'Seuraa käyttäjiesi luomaa sisältöä',
	'widget:content_stats:type' => 'Sisällön tyyppi',
	'widget:content_stats:number' => 'Numero',

	'admin:widget:admin_welcome' => 'Tervetuloa',
	'admin:widget:admin_welcome:help' => "Pieni esittely Elggin valvojan paneelista.",
	'admin:widget:admin_welcome:intro' =>
'Tervetuloa elggiin!',

	'admin:widget:admin_welcome:admin_overview' =>
"Päävalikko löytyy oikealta, se on jaettu kolmeen". " osaan:
	<dl>
		<dt>Administer</dt><dd>jokapäiväiset tehtävät, kuka on paikalla, statistiikka jne...</dd>
		<dt>Configure</dt><dd>muut tehtävät kuten sivuston nimeäminen ja liitännäisen aktivointi.</dd>
		<dt>Develop</dt><dd>Suunnittelijoille jotka tekevät liitännäisiä tai suunnittelevat teemoja. (Vaatii Suunnittelija Liitännäisen)</dd>
	</dl>
	",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Kiitoksia kun käytät Elggiä!',

	'admin:footer:faq' => 'Valvonta FAQ',
	'admin:footer:manual' => 'Valvojan manuaali',
	'admin:footer:community_forums' => 'Elgg yhteisön foorumit',
	'admin:footer:blog' => 'Elgg Blogi',

	'admin:plugins:category:all' => 'Kaikki liitännäiset',
	'admin:plugins:category:active' => 'Aktiiviset liitännäiset',
	'admin:plugins:category:inactive' => 'Poissa käytöstä olevat liitännäiset',
	'admin:plugins:category:admin' => 'Valvoja',
	'admin:plugins:category:bundled' => 'Paketti',
	'admin:plugins:category:content' => 'Sisältö',
	'admin:plugins:category:development' => 'Suunnittelu',
	'admin:plugins:category:enhancement' => 'Parannukset',
	'admin:plugins:category:api' => 'Palvelu/API',
	'admin:plugins:category:communication' => 'Kommunikaatio',
	'admin:plugins:category:security' => 'Turvallisuus ja Roskaposti',
	'admin:plugins:category:social' => 'Sosiaalinen',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Teemat',
	'admin:plugins:category:widget' => 'Vimpaimet',

	'admin:plugins:sort:priority' => 'Prioriteetti',
	'admin:plugins:sort:alpha' => 'Aakkosellinen',
	'admin:plugins:sort:date' => 'Uusin',

	'admin:plugins:markdown:unknown_plugin' => 'Tunnistamaton liitännäinen.',
	'admin:plugins:markdown:unknown_file' => 'Tunnistamaton tiedosto.',


	'admin:notices:could_not_delete' => 'Ei voitu poistaa tiedotetta.',

	'admin:options' => 'Valvojan valikko',


/**
 * Plugins
 */
	'plugins:settings:save:ok' => "Asetukset liitännäiselle %s tallennettiin.",
	'plugins:settings:save:fail' => "Ongelmia asetusten tallentamisessa liitännäiselle %s ",
	'plugins:usersettings:save:ok' => "Käyttäjän asetukset liitännäiselle %s tallennettiin.",
	'plugins:usersettings:save:fail' => "Käyttäjän asetusten tallentamisesa ongelmia liitännäiselle %s ",
	'item:object:plugin' => 'Liitännäiset',

	'admin:plugins' => "Liitännäiset",
	'admin:plugins:activate_all' => 'Aktivoi kaikki',
	'admin:plugins:deactivate_all' => 'Deaktivoi kaikki',
	'admin:plugins:activate' => 'Aktivoi',
	'admin:plugins:deactivate' => 'Deaktivoi',
	'admin:plugins:description' => "Täällä voit ottaa käyttöön tai poistaa käytöstä liitännäisiä.",
	'admin:plugins:opt:linktext' => "Muokkaa työkalut...",
	'admin:plugins:opt:description' => "Muokkaa työkalut jotka on asennettu tälle sivustolle. ",
	'admin:plugins:label:author' => "Tekijä",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Kategoriat',
	'admin:plugins:label:licence' => "Lisenssi",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:moreinfo' => 'lisää tietoa',
	'admin:plugins:label:version' => 'Versio',
	'admin:plugins:label:location' => 'Sijainti',
	'admin:plugins:label:dependencies' => 'Riippuvuudet',

	'admin:plugins:warning:elgg_version_unknown' => 'Tämä liitännäinen käyttää legacy manifesti tiedosto, se ei välttämättä toimi!',
	'admin:plugins:warning:unmet_dependencies' => 'Tällä liitännäisellä on riippuvuusongelmia.',
	'admin:plugins:warning:invalid' => '%s ei ole käypä Elgg liitännäinen.  Tarkista <a href="http://docs.elgg.org/Invalid_Plugin">the Elgg dokumentaatio</a> etsiäksesi vinkkejä.',
	'admin:plugins:cannot_activate' => 'ei voida aktivoida',

	'admin:plugins:set_priority:yes' => "Uudelleen järjestetty %s.",
	'admin:plugins:set_priority:no' => "Ei voitu järjestää uudelleen %s.",
	'admin:plugins:deactivate:yes' => "Deaktivoitu %s.",
	'admin:plugins:deactivate:no' => "Ei voitu deaktivoida %s.",
	'admin:plugins:activate:yes' => "Aktivoitu %s.",
	'admin:plugins:activate:no' => "Ei aktivoitu %s.",
	'admin:plugins:categories:all' => 'Kaikki kategoriat',
	'admin:plugins:plugin_website' => 'Liitännäisen kotisivut',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Versio %s',
	'admin:plugins:simple' => 'Yksinkertainen',
	'admin:plugins:advanced' => 'Edistynyt',
	'admin:plugin_settings' => 'Liitännäisen asetukset',
	'admin:plugins:simple_simple_fail' => 'Asetuksia ei voitu tallentaa.',
	'admin:plugins:simple_simple_success' => 'Asetukset tallennettu.',
	'admin:plugins:simple:cannot_activate' => 'Liitännäistä ei voitu aktivoida.',
	'admin:plugins:warning:unmet_dependencies_active' => 'Tämä liitännäinen on aktivoitu, mutta sillä on riippuvuusongelmia.',

	'admin:plugins:dependencies:type' => 'Tyyppi',
	'admin:plugins:dependencies:name' => 'Nimi',
	'admin:plugins:dependencies:expected_value' => 'Testattu arvo',
	'admin:plugins:dependencies:local_value' => 'Varsinainen arvo',
	'admin:plugins:dependencies:comment' => 'Kommentti',

	'admin:statistics:description' => "Täällä on sivustosi statistiikka.",
	'admin:statistics:opt:description' => "Näytä statistiikkatietoja sivustosi käyttäjistä ja sisällöstä.",
	'admin:statistics:opt:linktext' => "Näytä statistiikka...",
	'admin:statistics:label:basic' => "Perus statistiikka",
	'admin:statistics:label:numentities' => "Entititeetit sivustolla",
	'admin:statistics:label:numusers' => "Käyttäjien lukumäärä",
	'admin:statistics:label:numonline' => "Käyttäjiä paikalla",
	'admin:statistics:label:onlineusers' => "Käyttäjiä paikalla nyt",
	'admin:statistics:label:version' => "Elgg versio",
	'admin:statistics:label:version:release' => "Julkaisu",
	'admin:statistics:label:version:version' => "Versio",

	'admin:user:label:search' => "Etsi käyttäjiä:",
	'admin:user:label:searchbutton' => "Etsi",

	'admin:user:ban:no' => "Ei voi bannata käyttäjää",
	'admin:user:ban:yes' => "Käyttäjä bannattu.",
	'admin:user:self:ban:no' => "Et voi bannata itseäsi",
	'admin:user:unban:no' => "Käyttäjän banneja ei voi poistaa",
	'admin:user:unban:yes' => "Käyttäjä ei ole enää bannattu.",
	'admin:user:delete:no' => "Ei voida poistaa käyttäjää",
	'admin:user:delete:yes' => "Käyttäjä %s on poistettu",
	'admin:user:self:delete:no' => "Et voi poistaa itseäsi",

	'admin:user:resetpassword:yes' => "Salasana resetoitu, käyttäjälle ilmoitettu.",
	'admin:user:resetpassword:no' => "Salasanaa ei voitu resetoida.",

	'admin:user:makeadmin:yes' => "Käyttäjä on nyt Valvoja.",
	'admin:user:makeadmin:no' => "Käyttäjästä ei voitu tehdä valvojaa.",

	'admin:user:removeadmin:yes' => "Käyttäjä ei ole enää valvoja.",
	'admin:user:removeadmin:no' => "Valvojan oikeuksia ei voitu poistaa tältä käyttäjältä.",
	'admin:user:self:removeadmin:no' => "Et voi poistaa omia valvojan oikeuksiasi.",

	'admin:appearance:menu_items' => 'Valikon kohteet',
	'admin:menu_items:configure' => 'Muokkaa päävalikon kohteita',
	'admin:menu_items:description' => 'Valitse mitkä valikon kohteet ovat "featured links".',
	'admin:menu_items:hide_toolbar_entries' => 'Poista linkit elgg toolbar menusta?',
	'admin:menu_items:saved' => 'Valikon kohteet tallennettu.',
	'admin:add_menu_item' => 'Lisää oma valikon kohde',
	'admin:add_menu_item:description' => 'Täytä puuttuvat tiedot',

	'admin:appearance:default_widgets' => 'Oletus vimpaimet',
	'admin:default_widgets:unknown_type' => 'tuntematon vimpaintyyppi',
	'admin:default_widgets:instructions' => 'Lisää, poista, valitse sijainti ja muokkaa oletusvimpaimien muita asetuksia.'. '  Tämä vaikuttaa vain uusiin käyttäjiin.',

/**
 * User settings
 */
	'usersettings:description' => "Täällä voit muokata kaikkia henkilökohtaisia tietojasi ja asetuksiasi.",

	'usersettings:statistics' => "Statistiikkasi",
	'usersettings:statistics:opt:description' => "Näytä tietoa käyttäjistä ja objekteista tällä sivustolla.",
	'usersettings:statistics:opt:linktext' => "Tilin statistiikka",

	'usersettings:user' => "Asetukset",
	'usersettings:user:opt:description' => "Tämä sallii sinun muokata käyttäjäasetuksia.",
	'usersettings:user:opt:linktext' => "Muokkaa asetuksiasi",

	'usersettings:plugins' => "Työkalut",
	'usersettings:plugins:opt:description' => "Muokkaa asetuksia (jos mitään) aktiivisile työkaluillesi.",
	'usersettings:plugins:opt:linktext' => "Muokkaa työkalujasi",

	'usersettings:plugins:description' => "Täällä voit hallita liitännäisiäsi.",
	'usersettings:statistics:label:numentities' => "Sisältösi",

	'usersettings:statistics:yourdetails' => "Yksityiskohtasi",
	'usersettings:statistics:label:name' => "Kokonimi",
	'usersettings:statistics:label:email' => "Sähköposti",
	'usersettings:statistics:label:membersince' => "Jäsenenä",
	'usersettings:statistics:label:lastlogin' => "Viimeksi kirjautunut sisään",

/**
 * Activity river
 */
	'river:all' => 'Kaikki',
	'river:mine' => 'Minun',
	'river:friends' => 'Ystävät',
	'river:select' => 'Näytä %s',
	'river:comments:more' => '+%u lisää',
	'river:generic_comment' => 'kommentoi %s %s',

	'friends:widget:description' => "Näytä joitakin ystävistäsi.",
	'friends:num_display' => "Ystävien määrä, jotka näytetään",
	'friends:icon_size' => "Ikonin koko",
	'friends:tiny' => "tosi pieni",
	'friends:small' => "pieni",

/**
 * Generic action words
 */

	'save' => "Tallenna",
	'reset' => 'Resetoi',
	'publish' => "Julkaise",
	'cancel' => "Peruuta",
	'saving' => "Tallentaa ...",
	'update' => "Päivitä",
	'preview' => "Esikatsele",
	'edit' => "Muokkaa",
	'delete' => "Poista",
	'accept' => "Hyväksy",
	'load' => "Lataa",
	'upload' => "Lähetä",
	'ban' => "Ban",
	'unban' => "Unban",
	'banned' => "Banned",
	'enable' => "Ota käyttöön",
	'disable' => "Poista käytöstä",
	'request' => "Pyyntö",
	'complete' => "Käännetty",
	'open' => 'Avaa',
	'close' => 'Sulje',
	'reply' => "Vastaa",
	'more' => 'Lisää',
	'comments' => 'Kommentit',
	'import' => 'Tuo',
	'export' => 'Vie',
	'untitled' => 'Ei otsikkoa',
	'help' => 'Auta',
	'send' => 'Lähetä',
	'post' => 'Lähetä viesti',
	'submit' => 'Anna',
	'comment' => 'Kommentoi',
	'upgrade' => 'Päivitä',
	'sort' => 'Lajittele',
	'filter' => 'tarkenna',

	'site' => 'Sivusto',
	'activity' => 'Aktiivisuus',
	'members' => 'Jäsenet',

	'up' => 'Ylös',
	'down' => 'Alas',
	'top' => 'Yläosaan',
	'bottom' => 'Alaosaan',

	'more' => 'lisää',

	'invite' => "Kutsu",

	'resetpassword' => "Resetoi salasana",
	'makeadmin' => "Tee Valvojaksi",
	'removeadmin' => "Poista Valvoja",

	'option:yes' => "Kyllä",
	'option:no' => "Ei",

	'unknown' => 'Tuntematon',

	'active' => 'Aktiivinen',
	'total' => 'Yhteensä',

	'learnmore' => "Klikkaa tästä oppiaksesi lisää.",

	'content' => "sisältö",
	'content:latest' => 'Viimeisin toiminta',
	'content:latest:blurb' => 'Vaihtoehtoisesti, klikkaa tästä nähdäksesi sisältöä kaikkialta sivustolta.',

	'link:text' => 'näytä linkki',
/**
 * Generic questions
 */

	'question:areyousure' => 'Oletko varma?',

/**
 * Generic data words
 */

	'title' => "Otsikko",
	'description' => "Kuvaus",
	'tags' => "Tagit",
	'spotlight' => "Spotlight",
	'all' => "Kaikki",
	'mine' => "Minun",

	'by' => 'käyttäjältä',
	'none' => 'ei mitään',

	'annotations' => "Annotitaatiot",
	'relationships' => "Suhteet",
	'metadata' => "Metadata",
	'tagcloud' => "Tagi pilvi",
	'tagcloud:allsitetags' => "Kaikki sivuston tagit",

/**
 * Entity actions
 */
	'edit:this' => 'Muokkaa tätä',
	'delete:this' => 'Poista tämä',
	'comment:this' => 'Kommentoi tätä',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Oletko varma, että haluat poistaa tämän?",
	'fileexists' => "Tiedosto on jo lähetetty. korvataksesi sen,valitse se alapuolelta:",

/**
 * User add
 */

	'useradd:subject' => 'Käyttäjätili luotu',
	'useradd:body' => '
%s,

Käyttäjätili on luotu sinulle %s. Kirjautuaksesi sisään, käy täällä:

%s

Ja kirjaudu sisään näillä tiedoilla:

Käyttäjänimi: %s
Salasana: %s

Kun olet kirjautunut sisään, suosittelemme että vaihdat salasanasi heti.
',

/**
 * System messages
 **/

	'systemmessages:dismiss' => "klikkaa poistuaksesi",


/**
 * Import / export
 */
	'importsuccess' => "datan tuonti onnistui",
	'importfail' => "OpenDD datan tuonti epäonnistui.",

/**
 * Time
 */

	'friendlytime:justnow' => "juuri nyt",
	'friendlytime:minutes' => "%s minuuttia sitten",
	'friendlytime:minutes:singular' => "minuutti sitten",
	'friendlytime:hours' => "%s tuntia sitten",
	'friendlytime:hours:singular' => "tunti sitten",
	'friendlytime:days' => "%s päivää sitten",
	'friendlytime:days:singular' => "eilen",
	'friendlytime:date_format' => 'j F Y @ g:ia',

	'date:month:01' => 'Tammikuu %s',
	'date:month:02' => 'Helmikuu %s',
	'date:month:03' => 'Maaliskuu %s',
	'date:month:04' => 'Huuhtikuu %s',
	'date:month:05' => 'Toukokuu %s',
	'date:month:06' => 'Kesäkuu %s',
	'date:month:07' => 'Heinäkuu %s',
	'date:month:08' => 'Elokuu %s',
	'date:month:09' => 'Syyskuu %s',
	'date:month:10' => 'Lokakuu %s',
	'date:month:11' => 'Marraskuu %s',
	'date:month:12' => 'Joulukuu %s',


/**
 * System settings
 */

	'installation:sitename' => "Sivustosi nimi:",
	'installation:sitedescription' => "Lyhyt kuvaus (ei pakollinen):",
	'installation:wwwroot' => "Sivuston URL:",
	'installation:path' => "Koko polku Elgg asennukseen:",
	'installation:dataroot' => "Koko polku data kansioon:",
	'installation:dataroot:warning' => "Sinun pitää luoda tämä sijainti manuaalisesti. Sen pitäisi olla eri paikassa kuin Elgg asennuksesi.",
	'installation:sitepermissions' => "Oletus pääsyoikeudet:",
	'installation:language' => "Oletuskieli sivustollesi:",
	'installation:debug' => "Debug mode antaa lisää tietoa kun sivuston virheistä. se voi toisaalta hidastaa järjestelmääsi paljonkin, joten käytä sitä vain kun yrität etsiä virheen aiheuttajia:",
	'installation:debug:none' => 'Ota pois käytöstä debug mode (suositeltu)',
	'installation:debug:error' => 'Näytä vain kriittiset virheet',
	'installation:debug:warning' => 'Näytä virheet ja varoitukset',
	'installation:debug:notice' => 'Kirjaa lokiin kaikki virheet,varoitukset ja ilmoitukset',

	// Walled Garden support
	'installation:registration:description' => 'Käyttäjän rekisteröinti on oletuksena käytössä. Ota se pois käytöstä jos haluat, että käyttäjät eivät voi rekisteröityä sivustollesi.',
	'installation:registration:label' => 'Salli uusien jäsenien rekisteröinnit',
	'installation:walled_garden:description' => 'Käytä sivusto niin, että se toimii yksityisenä verkkona. Tämä ei salli kirjautumattomien käyttäjien nähdä mitään muuta kuin sivut jotka on erityisesti määrätty "Julkinen" merkinnällä.',
	'installation:walled_garden:label' => 'Rajoita sivut vain kirjautuneille käyttäjille',

	'installation:httpslogin' => "Ota tämä käyttöön jos haluat että käyttäjät autentikoidaan HTTPS:än kautta. sinun serverilläsi täytyy olla HTTPS käytössä jotta tämä toimisi.",
	'installation:httpslogin:label' => "Salli HTTPS kirjautuminen",
	'installation:view' => "Anna näkymä jonka sivustolle tuleva henkilö näkee ensimmäisenä (jos epäilyttää niin jätä tyhjäksi):",

	'installation:siteemail' => "Sivuston sähköpostiosoite (käytetään kun lähetetään järjestelmä viestejä):",

	'installation:disableapi' => "Elgg toimittaa API:n webbipalveluiden rakentamiseen jotta ulkoiset toimet voivat toimia sivuston kanssa hyvin.",
	'installation:disableapi:label' => "Käytä Elgg's web services API:a",

	'installation:allow_user_default_access:description' => "Jos merkattu, Yksittäiset käyttäjät voivat asettaa oman pääsyoikeutensa itse, ja se samalla ylitsekirjaa järjestelmän oman oletusarvon.",
	'installation:allow_user_default_access:label' => "Salli käyttäjien oletus pääsyoikeudet",

	'installation:simplecache:description' => "Simple cache lisää suorituskykyä  varastoimalla staatisen sisällön.",
	'installation:simplecache:label' => "Käytä simple cachea (suositeltu)",

	'installation:viewpathcache:description' => "view filepath cache vähentää liitännäisten latausaikoja.",
	'installation:viewpathcache:label' => "Käytä view filepath cachea (suositeltu)",

	'upgrading' => 'Päivittää...',
	'upgrade:db' => 'database on päivitetty.',
	'upgrade:core' => 'Elgg asennuksesi on päivitetty.',
	'upgrade:unable_to_upgrade' => 'Ei voitu päivittää.',
	'upgrade:unable_to_upgrade_info' =>
		'This installation cannot be upgraded because legacy views
		were detected in the Elgg core views directory. These views have been deprecated and need to be
		removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
		simply delete the views directory and replace it with the one from the latest
		package of Elgg downloaded from <a href="http://elgg.org">elgg.org</a>.<br /><br />

		If you need detailed instructions, please visit the <a href="http://docs.elgg.org/wiki/Upgrading_Elgg">
		Upgrading Elgg documentation</a>.  If you require assistance, please post to the
		<a href="http://community.elgg.org/pg/groups/discussion/">Community Support Forums</a>.',

	'update:twitter_api:deactivated' => 'Twitter API (previously Twitter Service) was deactivated during the upgrade. Please activate it manually if required.',
	'update:oauth_api:deactivated' => 'OAuth API (previously OAuth Lib) was deactivated during the upgrade.  Please activate it manually if required.',

	'deprecated:function' => '%s() was deprecated by %s()',

/**
 * Welcome
 */

	'welcome' => "Tervetuloa",
	'welcome:user' => 'Tervetuloa %s',

/**
 * Emails
 */
	'email:settings' => "Sähköposti asetukset",
	'email:address:label' => "Sähköpostiosoitteesi",

	'email:save:success' => "Uusi sähköpostiosoite tallennettu.",
	'email:save:fail' => "Sähköpostiosoitettasi ei voitu tallentaa.",

	'friend:newfriend:subject' => "%s on nyt ystäväsi!",
	'friend:newfriend:body' => "%s on nyt ystäväsi!

Nähdäksesi heidän profiilinsa klikkaa tästä:

%s

Et voi vastata tähän viestiin.",



	'email:resetpassword:subject' => "Salasana resetoitu!",
	'email:resetpassword:body' => "Hei %s,

Salasanasi on resetoitu: %s",


	'email:resetreq:subject' => "Pyydä uutta salasanaa.",
	'email:resetreq:body' => "Hei %s,

Joku (IP osoitteesta %s) on pyytänyt uutta salasanaa.

Jos sinä pyysit sitä, klikkaa alla olevaa linkkiä, muuten voit unohtaa tämän viestin.

%s
",

/**
 * user default access
 */

'default_access:settings' => "Oletuspääsyoikeutesi",
'default_access:label' => "Oletus pääsy",
'user:default_access:success' => "Asetukset tallennettiin.",
'user:default_access:failure' => "Asetuksia ei voitu tallentaa.",

/**
 * XML-RPC
 */
	'xmlrpc:noinputdata'	=>	"data häviksissä",

/**
 * Comments
 */

	'comments:count' => "%s kommenttia",

	'riveraction:annotation:generic_comment' => '%s kommentoi %s',

	'generic_comments:add' => "Jätä kommentti",
	'generic_comments:post' => "Lähetä kommentti",
	'generic_comments:text' => "Kommentti",
	'generic_comments:latest' => "Viimeisimmät kommentit",
	'generic_comment:posted' => "Kommenttisi lähetettiin.",
	'generic_comment:deleted' => "Kommenttisi poistettiin.",
	'generic_comment:blank' => "Sinun pitää kirjoittaa jotain, ennen kuin voimme tallentaa sen.",
	'generic_comment:notfound' => "Kohdetta ei löytynyt.",
	'generic_comment:notdeleted' => "Tätä kommenttia ei voitu poistaa.",
	'generic_comment:failure' => "Virhe tapahtui lisättäessä kommenttiasi. Yritä uudelleen.",
	'generic_comment:none' => 'Ei kommentteja',

	'generic_comment:email:subject' => 'Sinulle on uusi kommentti!',
	'generic_comment:email:body' => "Olet kommentoinut \"%s\" käyttäjältä %s. Siinä lukee:


%s


Vastataksesi tai nähdäksesi alkuperäisen viestin, klikkaa tästä:

%s

katsoaksesi %s's profiilin, klikkaa tästä:

%s

Et voi vastata tähän viestiin.",

/**
 * Entities
 */
	'byline' => '%s',
	'entity:default:strapline' => 'Luonut %s käyttäjä %s',
	'entity:default:missingsupport:popup' => 'Tätä ei voida näyttää oikein. Voi olla että pluginin puuttuminen aiheuttaa tämän.',

	'entity:delete:success' => 'Entiteetti %s on poistettu',
	'entity:delete:fail' => 'Entiteettiä %s ei voitu poistaa',


/**
 * Action gatekeeper
 */
	'actiongatekeeper:missingfields' => 'Puuttuu __token tai __ts kentät',
	'actiongatekeeper:tokeninvalid' => "Löysimme virheen (token mismatch).Sivusto taisi ehtiä vanheta, yritä uudelleen.",
	'actiongatekeeper:timeerror' => 'Sivusto jolle yrität päästä vanheni. päivitä sivu ja yritä uudelleen.',
	'actiongatekeeper:pluginprevents' => 'liitännäinen on estänyt tämän lähettämisen.',


/**
 * Word blacklists
 */
	'word:blacklist' => 'and, the, then, but, she, his, her, him, one, not, also, about, now, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Tagit',
	'tags:site_cloud' => 'Sivuston Tagi Pilvi',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Ei voi ottaa yhteyttä %s. Voit kokea ongelmia tallennettaessa tätä.',
	'js:security:token_refreshed' => ' %s voitiin taas yhdistää!',

/**
 * Languages according to ISO 639-1
 */

	 'aa'  =>  "Afar" , 
	 'ab'  =>  "Abkhazian" , 
	 'af'  =>  "Afrikaani" , 
	 'am'  =>  "Amharic" , 
	 'ar'  =>  "Arabia" , 
	 'as'  =>  "Assemese" , 
	 'ay'  =>  "Aymara" , 
	 'az'  =>  "Azerbaijan" , 
	 'ba'  =>  "Bashkir" , 
	 'be'  =>  "Valkovenäjä" , 
	 'bg'  =>  "Bulgaria" , 
	 'bh'  =>  "Bihari" , 
	 'bi'  =>  "Bislama" , 
	 'bn'  =>  "Bengali" , 
	 'bo'  =>  "Tiibetti" , 
	 'br'  =>  "Bretoni" , 
	 'ca'  =>  "Katalaani" , 
	 'co'  =>  "Korsika" , 
	 'cs'  =>  "Tsekki" , 
	 'cy'  =>  "Welsh" , 
	 'da'  =>  "Tanska" , 
	 'de'  =>  "Saksa" , 
	 'dz'  =>  "Bhutan" , 
	 'el'  =>  "Kreikka" , 
	 'en'  =>  "Englanti" , 
	 'eo'  =>  "Esperanto" , 
	 'es'  =>  "Espanja" , 
	 'et'  =>  "Eesti" , 
	 'eu'  =>  "Baski" , 
	 'fa'  =>  "Persia" , 
	 'fi'  =>  "Suomi" , 
	 'fj'  =>  "Fiji" , 
	 'fo'  =>  "Faeroese" , 
	 'fr'  =>  "Ranska" , 
	 'fy'  =>  "Friisi" , 
	 'ga'  =>  "Irlanti" , 
	 'gd'  =>  "Gaelic" , 
	 'gl'  =>  "Galician" , 
	 'gn'  =>  "Guarani" , 
	 'gu'  =>  "Gujarati" , 
	 'he'  =>  "Hebrea" , 
	 'ha'  =>  "Hausa" , 
	 'hi'  =>  "Hindi" , 
	 'hr'  =>  "Kroatia" , 
	 'hu'  =>  "Unkari" , 
	 'hy'  =>  "Armenia" , 
	 'ia'  =>  "Interlingua" , 
	 'id'  =>  "Indonesia" , 
	 'ie'  =>  "Interlingue" , 
	 'ik'  =>  "Inupiak" , 
	 'is'  =>  "Islanti" , 
	 'it'  =>  "Italia" , 
	 'iu'  =>  "Inuktitut" , 
	 'iw'  =>  "Hebrea" , 
	 'ja'  =>  "Japani" , 
	 'ji'  =>  "Jiddish" , 
	 'jw'  =>  "Jaava" , 
	 'ka'  =>  "Georgia" , 
	 'kk'  =>  "Kazakstan" , 
	 'kl'  =>  "Grönlanti" , 
	 'km'  =>  "Kambodia" , 
	 'kn'  =>  "Kanada" , 
	 'ko'  =>  "Korea" , 
	 'ks'  =>  "Kashmiri" , 
	 'ku'  =>  "Kurdi" , 
	 'ky'  =>  "Kirghiz" , 
	 'la'  =>  "Latina" , 
	 'ln'  =>  "Lingala" , 
	 'lo'  =>  "Laothia" , 
	 'lt'  =>  "Liettua" , 
	 'lv'  =>  "Latvia" , 
	 'mg'  =>  "Malagasy" , 
	 'mi'  =>  "Maori" , 
	 'mk'  =>  "Makedonia" , 
	 'ml'  =>  "Malayalam" , 
	 'mn'  =>  "Mongolia" , 
	 'mo'  =>  "Moldavia" , 
	 'mr'  =>  "Marathi" , 
	 'ms'  =>  "Malaja" , 
	 'mt'  =>  "Malta" , 
	 'my'  =>  "Burma" , 
	 'na'  =>  "Nauru" , 
	 'ne'  =>  "Nepali" , 
	 'nl'  =>  "Hollanti" , 
	 'no'  =>  "Norja" , 
	 'oc'  =>  "Occitan" , 
	 'om'  =>  "Oromo" , 
	 'or'  =>  "Oriya" , 
	 'pa'  =>  "Punjabi" , 
	 'pl'  =>  "Puola" , 
	 'ps'  =>  "Pashto" , 
	 'pt'  =>  "Portugali" , 
	 'qu'  =>  "Quechua" , 
	 'rm'  =>  "Reto-Romaani" , 
	 'rn'  =>  "Kirundi" , 
	 'ro'  =>  "Romania" , 
	 'ru'  =>  "Venäjä" , 
	 'rw'  =>  "Kinyarwanda" , 
	 'sa'  =>  "Sanskriitti" , 
	 'sd'  =>  "Sindhi" , 
	 'sg'  =>  "Sangro" , 
	 'sh'  =>  "Serbo-Kroaatti" , 
	 'si'  =>  "Singhalese" , 
	 'sk'  =>  "Slovakia" , 
	 'sl'  =>  "Slovenia" , 
	 'sm'  =>  "Samoa" , 
	 'sn'  =>  "Shona" , 
	 'so'  =>  "Somali" , 
	 'sq'  =>  "Albania" , 
	 'sr'  =>  "Serbia" , 
	 'ss'  =>  "Siswati" , 
	 'st'  =>  "Sesotho" , 
	 'su'  =>  "Sundanese" , 
	 'sv'  =>  "Ruotsi" , 
	 'sw'  =>  "Swahili" , 
	 'ta'  =>  "Tamili" , 
	 'te'  =>  "Tegulu" , 
	 'tg'  =>  "Tajik" , 
	 'th'  =>  "Thai" , 
	 'ti'  =>  "Tigrinya" , 
	 'tk'  =>  "Turkmen" , 
	 'tl'  =>  "Tagalog" , 
	 'tn'  =>  "Setswana" , 
	 'to'  =>  "Tonga" , 
	 'tr'  =>  "Turkki" , 
	 'ts'  =>  "Tsonga" , 
	 'tt'  =>  "Tataari" , 
	 'tw'  =>  "Twi" , 
	 'ug'  =>  "Uigur" , 
	 'uk'  =>  "Ukraina" , 
	 'ur'  =>  "Urdu" , 
	 'uz'  =>  "Uzbek" , 
	 'vi'  =>  "Vietnami" , 
	 'vo'  =>  "Volapuk" , 
	 'wo'  =>  "Wolof" , 
	 'xh'  =>  "Xhosa" , 
	 'yi'  =>  "Jiddish" , 
	 'yo'  =>  "Yoruba" , 
	 'za'  =>  "Zuang" , 
	 'zh'  =>  "Kiina" , 
	 'zu'  =>  "Zulu" , 


);

add_translation("fi",$finnish);

?>
