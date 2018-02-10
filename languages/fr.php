<?php
/**
 * Core French Language
 *
 * @package Elgg.Core
 * @subpackage Languages.French
 */

$french = array(
/**
 * Sites
 */
	'item:site' => "Sites",

/**
 * Sessions
 */
	'login' => "Connexion",
	'loginok' => "Vous êtes désormais connecté(e).",
	'loginerror' => "Nous n'avons pas pu vous identifier. Assurez-vous que les informations que vous avez entrées sont correctes et réessayez.",
	'login:empty' => "Nom d'utilisateur et mot de passe sont requis.",
	'login:baduser' => "Impossible de charger votre compte d'utilisateur.",
	'auth:nopams' => "Erreur interne. Aucune méthode d'authentification des utilisateurs installés.",

	'logout' => "Déconnexion",
	'logoutok' => "Vous avez été déconnecté(e).",
	'logouterror' => "Nous n'avons pas pu vous déconnecter. Essayez de nouveau.",

	'loggedinrequired' => "Vous devez être connecté pour voir cette page.",
	'adminrequired' => "Vous devez être administrateur pour voir cette page.",
	'membershiprequired' => "Vous devez être membre de ce groupe pour voir cette page.",


/**
 * Errors
 */
	'exception:title' => "Erreur Irrécupérable.",

	'actionundefined' => "L'action demandée (%s) n'est pas définie par le système.",
	'actionnotfound' => "Le fichier d'action pour %s n'a pas été trouvé.",
	'actionloggedout' => "Désolé, vous ne pouvez pas effectuer cette action sans être connecté.",
	'actionunauthorized' => "Vous n'êtes pas autorisé à effectuer cette action",

	'InstallationException:SiteNotInstalled' => "Impossible de traiter cette requête.
  Ce site n'est pas configuré ou la base de données est en panne.",
	'InstallationException:MissingLibrary' => "Impossible de charger %s",
	'InstallationException:CannotLoadSettings' => "Elgg n'a pas pu charger le fichier de paramètres. Il n'existe pas ou il y a un problème de d'autorisations.",

	'SecurityException:Codeblock' => "Accès non autorisé pour la création de bloc de code.",
	'DatabaseException:WrongCredentials' => "Elgg n'a pas pu se connecter à la base de données avec les informations données. Vérifiez les paramètres.",
	'DatabaseException:NoConnect' => "Elgg n'a pas pu sélectionner la base de données '%s', merci de vérifier que la base de données est bien créée et que vous y avez accès.",
	'SecurityException:FunctionDenied' => "L'accès à la fonction privilégiée '%s' n'est pas autorisé.",
	'DatabaseException:DBSetupIssues' => "Il y a eu plusieurs problèmes :",
	'DatabaseException:ScriptNotFound' => "Elgg n'a pas pu trouver le script de la base de données a %s.",
	'DatabaseException:InvalidQuery' => "Requête non valide",

	'IOException:FailedToLoadGUID' => "Echec du chargement du nouveau %s avec le GUID:%d",
	'InvalidParameterException:NonElggObject' => "Passage d'un objet de type non-Elgg vers un constructeur d'objet Elgg !",
	'InvalidParameterException:UnrecognisedValue' => "Valeur non reconnue passés au constructeur.",

	'InvalidClassException:NotValidElggStar' => "GUID: %d n'est pas valide %s",

	'PluginException:MisconfiguredPlugin' => "%s (GUID: %s) est un plugin non configuré. Il a été désactivé. Veuillez chercher dans le wiki d'Elgg pour connaître les cause possibles (http://docs.elgg.org/wiki/).",
	'PluginException:CannotStart' => "%s (GUID: %s) ne peut pas démarrer. Raison : %s",
	'PluginException:InvalidID' => "%s est un ID de plugin invalide.",
	'PluginException:InvalidPath' => "%s est un chemin invalide pour le plugin.",
	'PluginException:InvalidManifest' => "Fichier manifest.xml invalide pour le plugin %s",
	'PluginException:InvalidPlugin' => "%s n'est pas un plugin valide.",
	'PluginException:InvalidPlugin:Details' => "%s n'est pas valide. plugin : %s",
	'PluginException:NullInstantiated' => "Il peut pas y avoir aucun Plugins d'Elgg. Vous devez passer un GUID, un plugin ID, ou un chemin complet.",

	'ElggPlugin:MissingID' => "Manque l'ID du plugin (GUID %s)",
	'ElggPlugin:NoPluginPackagePackage' => "Manque le paquet d'Elgg 'ElggPluginPackage' du plugin ID %s (GUID %s)",

	'ElggPluginPackage:InvalidPlugin:MissingFile' => "Manque le fichier %s dans le paquet",
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => "Type '%s' des dépendances invalide",
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => "Type '%s' invalide",
	'ElggPluginPackage:InvalidPlugin:CircularDep' =>"%s invalide dans dépendance '%s', dans le plugin %s. Les plugins peuvent pas être en conflit avec, ou avoir besoin de quelque chose, qu'ils contiennent !",
	'ElggPlugin:Exception:CannotIncludeFile' => "Impossible d'inclure %s pour le plugin %s (GUID : %s) ici %s. Vérifiez les autorisations !",
	'ElggPlugin:Exception:CannotRegisterViews' => "Impossible d'ouvrir la vue dir pour le plugin %s (GUID : %s) ici %s. Vérifiez les autorisations !",
	'ElggPlugin:Exception:CannotRegisterLanguages' => "Impossible d'enregistrer les langues pour le plugin %s (GUID : %s) sur %s. Vérifiez les autorisations !",
	'ElggPlugin:Exception:CannotRegisterClasses' => "Impossible d'enregistrer les classes pour le plugin %s (GUID : %s) ici %s. Vérifiez les autorisations !",
	'ElggPlugin:Exception:NoID' => "Aucun ID pour le plugin guid %s !",

	'PluginException:ParserError' => "Erreur de syntaxe du fichier manifest.xml avec la version %s de l'API du plugin %s.",
	'PluginException:NoAvailableParser' => "Analyseur syntaxique du fichier manifest.xml introuvable pour l'API version %s du plugin %s.",
	'PluginException:ParserErrorMissingRequiredAttribute' => "L'attribut nécessaire '%s' manque dans le fichier manifest.xml pour le plugin %s.",

	'ElggPlugin:Dependencies:Requires' => "Requis",
	'ElggPlugin:Dependencies:Suggests' => "Suggestion",
	'ElggPlugin:Dependencies:Conflicts' => "Conflits",
	'ElggPlugin:Dependencies:Conflicted' => "En conflit",
	'ElggPlugin:Dependencies:Provides' => "Fournit",
	'ElggPlugin:Dependencies:Priority' => "Priorité",

	'ElggPlugin:Dependencies:Elgg' => "version d'Elgg",
	'ElggPlugin:Dependencies:PhpExtension' => "extension PHP: %s",
	'ElggPlugin:Dependencies:PhpIni' => "PHP ini: %s",
	'ElggPlugin:Dependencies:Plugin' => "Plugin: %s",
	'ElggPlugin:Dependencies:Priority:After' => "Après %s",
	'ElggPlugin:Dependencies:Priority:Before' => "Avant %s",
	'ElggPlugin:Dependencies:Priority:Uninstalled' => "%s n'est pas installé",
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => "Manquant",

	'ElggPlugin:InvalidAndDeactivated' => '%s est un plugin invalide et a été désactivé.',

	'InvalidParameterException:NonElggUser' => "Passage d'un utilisateur de type non-Elgg vers un constructeur d'utilisateur Elgg !",

	'InvalidParameterException:NonElggSite' => "Passage d'un site non-Elgg vers un constructeur de site Elgg !",

	'InvalidParameterException:NonElggGroup' => "Passage d'un groupe non-Elgg vers un constructeur de groupe Elgg !",

	'IOException:UnableToSaveNew' => "Impossible de sauvegarder le nouveau %s",

	'InvalidParameterException:GUIDNotForExport' => "GUID non spécifié durant l'export, ceci ne devrait pas se produire.",
	'InvalidParameterException:NonArrayReturnValue' => "La fonction de sérialisation de l'entité a retourné une valeur dont le type n'est pas un tableau",

	'ConfigurationException:NoCachePath' => "Le chemin du cache est vide !",
	'IOException:NotDirectory' => "%s n'est pas un répertoire.",

	'IOException:BaseEntitySaveFailed' => "Impossibilité de sauver les informations de base du nouvel objet !",
	'InvalidParameterException:UnexpectedODDClass' => "import() a passé un argument qui n'est pas du type ODD class",
	'InvalidParameterException:EntityTypeNotSet' => "Le type d'entité doit être renseigné.",

	'ClassException:ClassnameNotClass' => "%s n'est pas %s.",
	'ClassNotFoundException:MissingClass' => "La classe '%s' n'a pas été trouvée, le plugin serait-il manquant ?",
	'InstallationException:TypeNotSupported' => "Le type %s n'est pas supporté. Il y a une erreur dans votre installation, le plus souvent causé par une mise à jour non-complète.",

	'ImportException:ImportFailed' => "Impossible d'importer l'élément %d",
	'ImportException:ProblemSaving' => "Une erreur est survenue en sauvant %s",
	'ImportException:NoGUID' => "La nouvelle entité a été créée mais n'a pas de GUID, ceci ne devrait pas se produire.",

	'ImportException:GUIDNotFound' => "L'entité '%d' n'a pas été trouvée.",
	'ImportException:ProblemUpdatingMeta' => "Il y a eu un problème lors de la mise à jour de '%s' pour l'entité '%d'",

	'ExportException:NoSuchEntity' => "Il n'y a pas d'entité telle que GUID:%d",

	'ImportException:NoODDElements' => "Aucun élément OpenDD n'a été trouvé dans les données importées, l'importation a échoué.",
	'ImportException:NotAllImported' => "Tous les éléments n'ont pas été importés.",
	'InvalidParameterException:UnrecognisedFileMode' => "Mode de fichier non-reconnu : '%s'",
	'InvalidParameterException:MissingOwner' => "Tous les fichiers doivent avoir un propriétaire",
	'IOException:CouldNotMake' => "Impossible de faire %s",
	'IOException:MissingFileName' => "Vous devez spécifier un nom avant d'ouvrir un fichier.",
	'ClassNotFoundException:NotFoundNotSavedWithFile' => "Fichiers stockés non trouvés ou classes non sauvegardées avec le fichier !",
	'NotificationException:NoNotificationMethod' => "Aucune méthode de notification spécifiée.",
	'NotificationException:NoHandlerFound' => "Aucune fonction trouvée pour '%s' ou elle ne peut être appelée.",
	'NotificationException:ErrorNotifyingGuid' => "Une erreur s'est produite lors de la notification %d",
	'NotificationException:NoEmailAddress' => "Impossible de trouver une adresse mail pour GUID:%d",
	'NotificationException:MissingParameter' => "Un argument obligatoire a été omis, '%s'",
	'DatabaseException:WhereSetNonQuery' => "La requête where ne contient pas de WhereQueryComponent",
	'DatabaseException:SelectFieldsMissing' => "Des champs sont manquants sur la requête de sélection.",
	'DatabaseException:UnspecifiedQueryType' => "Type de requête non-reconnue ou non-spécifiée.",
	'DatabaseException:NoTablesSpecified' => "Aucune table spécifiée pour la requête.",
	'DatabaseException:NoACL' => "Pas de liste d'accès fourni pour la requête",
	'InvalidParameterException:NoEntityFound' => "Aucune entité trouvée, soit elle est inexistante, soit vous n'y avez pas accès.",
	'InvalidParameterException:GUIDNotFound' => "GUID : %s n'a pas été trouvé ou vous n'y avez pas accès.",
	'InvalidParameterException:IdNotExistForGUID' => "Désolé, '%s' n'existe pas pour GUID : %d",
	'InvalidParameterException:CanNotExportType' => "Désolé, je ne sais pas comment exporter '%s'",
	'InvalidParameterException:NoDataFound' => "Aucune donnée trouvée.",
	'InvalidParameterException:DoesNotBelong' => "N'appartient pas à l'entité.",
	'InvalidParameterException:DoesNotBelongOrRefer' => "N'appartient pas ou aucune référence à l'entité.",
	'InvalidParameterException:MissingParameter' => "Paramètre manquant, il faut fournir un GUID.",
	'InvalidParameterException:LibraryNotRegistered' => "%s n'est pas une bibliothèque enregistré",
	'InvalidParameterException:LibraryNotFound' => "Impossible de lire la bibliothèque %s à partir de %s",
	'APIException:ApiResultUnknown' => "Les résultats de l'API sont de type inconnu, ceci ne devrait pas se produire.",
	'ConfigurationException:NoSiteID' => "L'identifiant du site n'a pas été spécifié.",
	'SecurityException:APIAccessDenied' => "Désolé, l'accès API a été désactivée par l'administrateur.",
	'SecurityException:NoAuthMethods' => "Aucune méthode d'authentification n'a été trouvée pour cette requête API.",
	'SecurityException:ForwardFailedToRedirect' => "La Redirection ne peut aboutir à cause des entêtes déjà envoyées. Arrêt de l'exécution par sécurité. Pour plus d'informations rechercher sur http://docs.elgg.org/ .",
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "Méthode ou fonction non définie dans expose_method()",
	'InvalidParameterException:APIParametersArrayStructure' => "Le paramètre de structure 'array' est incorrect pour appeller to expose method '%s'",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Méthode HTTP %s pour la methode API '%s' non reconnue",
	'APIException:MissingParameterInMethod' => "Argument %s manquant pour la méthode %s",
	'APIException:ParameterNotArray' => "%s n'est semble t-il pas un tableau.",
	'APIException:UnrecognisedTypeCast' => "Type %s non reconnu pour la variable '%s' pour la fonction '%s'",
	'APIException:InvalidParameter' => "Paramètre invalide pour '%s' pour la fonction '%s'.",
	'APIException:FunctionParseError' => "%s(%s) a une erreur d'analyse.",
	'APIException:FunctionNoReturn' => "%s(%s) ne retourne aucune valeur.",
	'APIException:APIAuthenticationFailed' => "Echec d'authentification d'API par l'appel de méthode",
	'APIException:UserAuthenticationFailed' => "Echec d'authentification d'utilisateur par l'appel de méthode",
	'SecurityException:AuthTokenExpired' => "Le jeton d'authentification est manquant, invalide ou expiré.",
	'CallException:InvalidCallMethod' => "%s doit être appelé en utilisant '%s'",
	'APIException:MethodCallNotImplemented' => "L'appel à la méthode '%s' n'a pas été implémenté.",
	'APIException:FunctionDoesNotExist' => "La fonction pour la methode '%s' n'est pas appellable",
	'APIException:AlgorithmNotSupported' => "L'algorithme '%s' n'est pas supporté ou a été désactivé.",
	'ConfigurationException:CacheDirNotSet' => "Le répertoire de cache 'cache_path' n'a pas été renseigné.",
	'APIException:NotGetOrPost' => "La méthode de requête doit être GET ou POST",
	'APIException:MissingAPIKey' => "Clé API manquante",
	'APIException:BadAPIKey' => "Mauvaise clé API",
	'APIException:MissingHmac' => "X-Elgg-hmac manquant dans l'entête",
	'APIException:MissingHmacAlgo' => "X-Elgg-hmac-algo manquant dans l'entête",
	'APIException:MissingTime' => "X-Elgg-time manquant dans l'entête",
	'APIException:MissingNonce' => "X-Elgg-nonce manquant dans l'entête",
	'APIException:TemporalDrift' => "X-Elgg-time est trop éloigné dans le temps. Epoch a échoué.",
	'APIException:NoQueryString' => "Aucune valeur dans la requête",
	'APIException:MissingPOSTHash' => "X-Elgg-posthash manquant dans l'entête",
	'APIException:MissingPOSTAlgo' => "X-Elgg-posthash_algo manquant dans l'entête",
	'APIException:MissingContentType' => "Le content-type est manquant pour les données postées",
	'SecurityException:InvalidPostHash' => "La signature des données POST est invalide.%s attendu mais %s reçu.",
	'SecurityException:DupePacket' => "La signature du paquet a déjà été envoyée.",
	'SecurityException:InvalidAPIKey' => "Clé API invalide ou non-reconnue.",
	'NotImplementedException:CallMethodNotImplemented' => "La méthode '%s' n'est pas supportée actuellement.",

	'NotImplementedException:XMLRPCMethodNotImplemented' => "L'appel à la méthode XML-RPC '%s' n'a pas été implémentée.",
	'InvalidParameterException:UnexpectedReturnFormat' => "L'appel à la méthode '%s' a retourné un résultat inattendu.",
	'CallException:NotRPCCall' => "L'appel ne semble pas être un appel XML-RPC valide",
	'PluginException:NoPluginName' => "Le nom du plugin n'a pas pu être trouvé",
	'SecurityException:authenticationfailed' => "Impossible d'authentifier l'utilisateur",
	'CronException:unknownperiod' => "%s n'est pas une période valide.",
	'SecurityException:deletedisablecurrentsite' => "Impossible de supprimer ou désactiver le site en cours !",
	'RegistrationException:EmptyPassword' => "Les champs du mot de passe ne peut pas être vide",
	'RegistrationException:PasswordMismatch' => "Les mots de passe doivent correspondre",
	'LoginException:BannedUser' => "Vous avez été banni de ce site et ne pouvez plus vous connecter",
	'LoginException:UsernameFailure' => "Nous n'avons pas pu vous connecter! Vérifiez votre nom d'utilisateur.",
	'LoginException:PasswordFailure' => "Nous n'avons pas pu vous connecter! Vérifiez votre nom d'utilisateur et votre mot de passe.",
	'LoginException:AccountLocked' => "Votre compte a été verrouillé suite à un trop grand nombre d'échecs de connexion.",
	'LoginException:ChangePasswordFailure' => "Echec lors de la vérification du mot de passe initial.",
	'memcache:notinstalled' => "Le module PHP memcache n'est pas installé. Vous devez installer php5-memcache",
	'memcache:noservers' => "Pas de serveur memcache défini, veuillez renseigner la variable",
	'memcache:versiontoolow' => "Memcache nécessite au minimum la version %s pour fonctionner, vous avez la version %s",
	'memcache:noaddserver' => "Le support de serveurs multiples est désactivé, vous avez peut-être besoin de mettre à jour votre bibliothèque memcache PECL",
	'deprecatedfunction' => "Attention : Ce code source utilise une fonction périmée '%s'. Il n'est pas compatible avec cette version de Elgg.",
	'pageownerunavailable' => "Attention : La page de l'utilisateur %d n'est pas accessible.",
	'viewfailure' => "Une erreur interne est survenue dans la vue %s",
	'changebookmark' => "Veuillez changer votre favori pour cette page.",
	'noaccess' => "Ce contenu a été supprimé, est invalide, ou vous n'avez pas les permissions pour y accéder.",

	'error:default' => "Oups... Quelque chose n'a pas fonctionné.",
	'error:404' => 'Désolé. Nous ne pouvons pas trouver la page que vous avez demandé.',

/**
 * API
 */
	'system.api.list' => "Liste tous les appels API au système.",
	'auth.gettoken' => "Cet appel API permet à un utilisateur de se connecter, il retourne une clef d'authentification qui permet de rendre la tentative de connexion unique.",

/**
 * User details
 */
	'name' => "Nom à afficher",
	'email' => "Adresse mail",
	'username' => "Nom d'utilisateur",
	'loginusername' => "Pseudo ou mail",
	'password' => "Mot de passe",
	'passwordagain' => "Confirmation du mot de passe",
	'admin_option' => "Définir cet utilisateur comme administrateur ?",
	'phone' => "Téléphone",
	'about' => "A propos",

/**
 * Access
 */
	'PRIVATE' => "Privé",
	'LOGGED_IN' => "Utilisateurs connectés",
	'PUBLIC' => "Public",
	'access:friends:label' => "Abonnements",
	'access' => "Accès",
	'access:limited:label' => "Limité",
	'access:help' => "Le niveau d'accès",

/**
 * Dashboard and widgets
 */
	'dashboard' => "Mon tableau de bord",
	'dashboard:nowidgets' => "Votre tableau de bord vous permet de suivre l'activité et le contenu vous conçernant.",
	'widgets:add' => "Ajouter des widgets",
	'widgets:add:description' => "Cliquez sur n'importe quel widget ci-dessous pour l'ajouter à la page.",
	'widgets:position:fixed' => "(Position modifiée sur la page)",
	'widget:unavailable' => "Vous avez déjà ajouté ce widget",
	'widget:numbertodisplay' => "Nombre d'éléments à afficher ",
	'widget:delete' => "Supprimer %s",
	'widget:edit' => "Personnaliser ce widget",
	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'widgets:save:success' => "Le widget a été sauvegardé avec succès.",
	'widgets:save:failure' => "Un problème est survenu lors de l'enregistrement de votre widget. Veuillez recommencer.",
	'widgets:add:success' => "Le widget a bien été ajouté.",
	'widgets:add:failure' => "Nous n'avons pas pu ajouter votre widget.",
	'widgets:move:failure' => "Nous n'avons pas pu enregistrer la position du nouveau widget.",
	'widgets:remove:failure' => "Impossible de supprimer ce widget",

/**
 * Groups
 */
	'group' => "Groupe",
	'item:group' => "Groupes",

/**
 * Users
 */
	'user' => "Utilisateur",
	'item:user' => "Utilisateurs",

/**
 * Friends
 */
	'friends' => "Abonnements",
	'friends:yours' => "Abonnés",
	'friends:owned' => "Les personnes qui suivent l'activité de %s",
	'friend:add' => "Suivre son activité",
	'friend:remove' => "Ne plus suivre",

	'friends:add:successful' => "Vous suivez maintenant l'activité de %s.",
	'friends:add:failure' => "Impossible d'ajouter %s aux personnes dont vous suivez l'activité. Merci de réessayer ultérieurement.",

	'friends:remove:successful' => "Vous ne suivez plus l'activité de %s.",
	'friends:remove:failure' => "Impossible de retirer %s aux personnes dont vous suivez l'activité. Merci de réessayer ultérieurement.",

	'friends:none' => "Cet utilisateur ne suit pas encore l'activité de quelqu'un.",
	'friends:none:you' => "Vous ne suivez personne. Ajoutez quelqu'un !",

	'friends:none:found' => "Aucun ami n'a été trouvé.",

	'friends:of:none' => "Personne ne suit l'activité de cet utilisateur.",
	'friends:of:none:you' => "Personne ne suit votre activité. Commencez par remplir votre page profil et publiez du contenu pour que les gens découvrent vos centres d'intérêts et vos spécificités !",

	'friends:of:owned' => "Les personnes qui suivent l'activité de %s",

	'friends:of' => "Abonnés",
	'friends:collections' => "Groupement d'amis",
	'collections:add' => "Nouveau groupement",
	'friends:collections:add' => "Nouveau groupement d'amis",
	'friends:addfriends' => "Sélectionner des amis",
	'friends:collectionname' => "Nom du groupement",
	'friends:collectionfriends' => "Amis dans le groupement",
	'friends:collectionedit' => "Modifier ce groupement",
	'friends:nocollections' => "Vous n'avez pas encore de groupement d'amis.",
	'friends:collectiondeleted' => "Votre groupement d'amis a été supprimé.",
	'friends:collectiondeletefailed' => "Le groupement d'amis n'a pas été supprimé. Vous n'avez pas de droits suffisants, ou un autre problème peut-être en cause.",
	'friends:collectionadded' => "Votre groupement d'amis a été créé avec succès",
	'friends:nocollectionname' => "Vous devez nommer votre groupement d'amis avant qu'il puisse être créé.",
	'friends:collections:members' => "Membres du groupement",
	'friends:collections:edit' => "Modifier le groupement d'amis",

	'friends:river:add' => "%s est maintenant ami avec %s",

	'friendspicker:chararray' => "ABCDEFGHIJKLMNOPQRSTUVWXYZ",

	'avatar' => "Avatar",
	'avatar:edit' => "Modifier mon avatar",
	'avatar:preview' => "Extrait",
	'avatar:upload' => "Envoyer un nouvel avatar",
	'avatar:current' => "Avatar actuel",
	'avatar:revert' => "Rétablir votre avatar avec l'icône par défaut",
	'avatar:crop:title' => "Outil pour recadrer l'avatar",
	'avatar:upload:instructions' => "Votre avatar est affiché sur tout le site. Vous pouvez le changer quand vous le souhaitez. (Formats de fichiers acceptés: GIF, JPG ou PNG)",
	'avatar:create:instructions' => "Cliquez et faites glisser le carré ci-dessous selon la façon dont vous voulez que votre avatar soit recadré. Un aperçu s'affiche sur la droite. Lorsque l'aperçu vous satisfait, cliquez sur «Créez votre avatar». Cette image recadrée sera utilisée sur le site.",
	'avatar:upload:success' => "Avatar téléchargé avec succès",
	'avatar:upload:fail' => "Échec de l'envoi de l'image",
	'avatar:resize:fail' => "Le redimensionnement de l'avatar a échoué",
	'avatar:crop:success' => "Le recadrage de l'avatar a réussi",
	'avatar:crop:fail' => "Le recadrage de l'avatar a échoué",
	'avatar:revert:success' => "Retour à l'avatar réussi",
	'avatar:revert:fail' => "Retour à l'avatar échoué",
	'profile' => "Profil",
	'profile:edit' => "Modifier mon profil",
	'profile:aboutme' => "À propos de moi",
	'profile:description' => "À propos de moi",
	'profile:briefdescription' => "Brève description",
	'profile:location' => "Code postal",
	'profile:skills' => "Compétences",
	'profile:interests' => "Intérêts",
	'profile:contactemail' => "mail",
	'profile:phone' => "Téléphone",
	'profile:mobile' => "Téléphone portable",
	'profile:website' => "Site Web",
	'profile:twitter' => "Twitter",
	'profile:facebook' => "Facebook",
	'profile:saved' => "Votre profil a été correctement enregistré.",
	'profile:time_created' => "Inscrit",

	'profile:field:text' => "Texte court",
	'profile:field:longtext' => "Texte long",
	'profile:field:tags' => "Mots-clés",
	'profile:field:url' => "Adresse Internet",
	'profile:field:email' => "Adresse mail",
	'profile:field:location' => "Position géographique",
	'profile:field:date' => "Date",

	'admin:appearance:profile_fields' => "Champs du profil",
	'profile:edit:default' => "Modifier les champs du profil",
	'profile:label' => "Etiquette du profil",
	'profile:type' => "Type de profil",
	'profile:editdefault:delete:fail' => "Echec de suppresion du champ de profil",
	'profile:editdefault:delete:success' => "Le champ profil a été supprimé",
	'profile:defaultprofile:reset' => "Réinitialisation du profil système",
	'profile:resetdefault' => "Réinitialisation du profil par défaut",
	'profile:explainchangefields' => "Vous pouvez remplacer les champs de profil existants avec les vôtres en utilisant le formulaire ci-dessous.\n\nDonner une étiquette pour le nouveau champ du profil, par exemple, 'Certifications obtenues', puis sélectionnez le type de champ (par exemple texte, URL, adresse), et cliquez sur le bouton 'Ajouter'. Pour réordonner les champs faites glisser la poignée de l'étiquette du champ. Pour modifier un champ d'étiquette - cliquez sur le texte de l'étiquette pour le rendre éditable. A tout moment vous pouvez revenir au profil par défaut, mais vous perdrez toutes les informations déjà entrées dans des champs personnalisés des pages de profil.",
	'profile:editdefault:success' => "Champ ajouté au profil par défaut avec succès",
	'profile:editdefault:fail' => "Le profil par défaut n'a pas pu être sauvé",
	'profile:notfound' => "Désolé, nous n'avons pas pu trouver le profil demandé.",

/**
 * Feeds
 */
	'feed:rss' => "S'abonner au flux RSS de cette page",
	'feed:group:activity' => "Suivre l'activité du groupe par RSS",

/**
 * Links
 */
	'link:view' => "voir le lien",
	'link:view:all' => "Voir tous",

/**
 * River
 */
	'river' => "River",
	'river:friend:user:default' => "%s s'est abonné à %s",
	'river:relationship:friend' => "%s s'est abonné à %s",
	'river:update:user:avatar' => "%s a un nouvel avatar",
	'river:update:user:profile' => "%s ont mis à jour leurs profils",
	'river:noaccess' => "Vous n'avez pas la permission de voir cet élément.",
	'river:posted:generic' => "%s envoyé",
	'riveritem:single:user' => "un utilisateur",
	'riveritem:plural:user' => "des utilisateurs",
	'river:ingroup' => "du groupe %s",
	'river:none' => "Aucune activité",
	'river:update' => "Mise à jour pour %s",

	'river:widget:title' => "Activité",
	'river:widget:description' => "Afficher les dernières activités",
	'river:widget:type' => "Type d'activité",
	'river:widgets:friends' => "Activité de mes abonnements",
	'river:widgets:all' => "Toutes les activités sur le site",

/**
 * Notifications
 */
	'notifications:usersettings' => "Configuration des messages du site",
	'notifications:methods' => "Choisissez votre mode de réception des messages du site.",
	'notification:method:email' => "mail",

	'notifications:usersettings:save:ok' => "La configuration des messages du site a été enregistrée avec succès.",
	'notifications:usersettings:save:fail' => "Il y a eu un problème lors de la sauvegarde des paramètres de configuration des messages du site.",

	'user.notification.get' => "Renvoie les paramètres de messages du site pour un utilisateur donné.",
	'user.notification.set' => "Définir les paramètres de messages du site pour un utilisateur donné.",
/**
 * Search
 */
	'search' => "Rechercher",
	'searchtitle' => "Rechercher : %s",
	'users:searchtitle' => "Recherche des utilisateurs : %s",
	'groups:searchtitle' => "Rechercher des groupes : %s",
	'advancedsearchtitle' => "%s résultat(s) trouvé(s) pour %s",
	'notfound' => "Aucun résultat trouvé.",
	'next' => "Suivant",
	'previous' => "Précédent",

	'viewtype:change' => "Changer le type de liste",
	'viewtype:list' => "Lister les vues",
	'viewtype:gallery' => "Galerie",

	'tag:search:startblurb' => "Eléments avec le(s) mot(s)-clé '%s' :",

	'user:search:startblurb' => "Utilisateurs avec le(s) mot(s)-clé '%s' :",
	'user:search:finishblurb' => "Cliquez ici pour plus de résultats...",

	'group:search:startblurb' => "Groupes qui vérifient le critère : %s",
	'group:search:finishblurb' => "Pour en savoir plus, cliquez ici.",
	'search:go' => "Rechercher",
	'userpicker:only_friends' => "Seulement les amis",

/**
 * Account
 */
	'account' => "Compte",
	'settings' => "Paramètres",
	'tools' => "Outils",
	'settings:edit' => "Editer les paramètres",

	'register' => "S'enregistrer",
	'registration:social-connect' => "S'enregistrer ou se connecter avec",
	'registration:back:socialnetwork' => "... ou utiliser un réseau social ",
	'registerok' => "Vous vous êtes enregistré avec succès sur %s !",
	'registerbad' => "Votre création de compte n'a pas fonctionné pour une raison inconnue.",
	'registerdisabled' => "La création de compte a été désactivé par l'administrateur du site.",
	'register:fields' => "Tous les champs sont requis",

	'registration:notemail' => "Cette adresse mail n'est pas valide.",
	'registration:userexists' => "Ce pseudo est déjà utilisé ou invalide.",
	'registration:username' => "Votre pseudo",
	'registration:helper:username' => "Le pseudo est le nom qui sera utilisé et visible partout.<br>C'est par ce pseudo que l'on pourra vous adresser un message en écrivant « @ » et votre pseudo.<br>Seul les caractères alphanumérique sont autorisés. Il ne peut pas contenir d'espace ni les caractères suivants : '/\*&?#%^(){}[]~?<>;|¬`@-+=\"<br><strong>A savoir :</strong> Ggouv permet d'envoyer et recevoir vos flux Twitter. Utilisez votre pseudo Twitter, c'est plus simple !",
	'registration:usernamenotvalid' => "Désolé, le pseudo que vous avez entré est invalide sur ce site.",
	'registration:usernametooshort' => "Le pseudo doit faire minimum %u caractères.",
	'registration:usernametoolong' => "Le pseudo doit faire maximum %u caractères.",
	'registration:invalidchars' => "Désolé, votre pseudo contient les caractères invalides suivants: %s. Tout ces caractères sont invalides: %s",
	'registration:namecheckcar' => "Caractères alphanumériques seulement, et pas d'espace.",
	'registration:name' => "Votre nom réel",
	'registration:helper:name' => "Il est plutôt bien vu d'indiquer son nom réel. Toutefois, si vous souhaitez garder votre anonymat, vous avez le droit !",
	'registration:passwordtooshort' => "Le mot de passe doit faire minimum %u caractères.",
	'registration:dupeemail' => "Cette adresse mail est déjà utilisée.",
	'registration:helper:email' => "Utilisez votre adresse mail la plus courante, vous y recevrez un mail de confirmation.<br>Votre adresse mail ne sera jamais divulguée à des tiers ou utilisée à des fins marketing.",
	'registration:emailnotvalid' => "Désolé, l'adresse mail que vous avez entré est invalide sur ce site.",
	'registration:locationtooshort' => "Un code postal a 5 chiffres.",
	'registration:localisation:text' => "Vérifiez que vous êtes bien situé.",
	'registration:helper:location' => "Ggouv applique la philosophie «agir local, penser global», c'est pourquoi il vous est demandé votre code postal.<br>Vous allez être membre du groupe de votre commune, ce qui vous permettra de vous organiser et collaborer plus facilement avec des membres près de chez vous.",
	'registration:helper:location:paris' => "Paris est la seule ville à la fois commune et département en France.<br/>Les groupes locaux ont été créés par arrondissement.<br/>Indiquez le votre ! (75001, 75002...)",
	'registration:passwordnotvalid' => "Désolé, le mot de passe que vous avez entré est invalide sur ce site.",
	'registration:passwordagainnotvalid' => "Les mots de passe sont différents !",
	'registration:helper:password' => "6 caractères minimum, mais pas 1 2 3 4 5 6 !<br><strong>Complexité :</strong>",

	'adduser' => "Ajouter un utilisateur",
	'adduser:ok' => "Vous avez ajouté un nouvel utilisateur avec succès.",
	'adduser:bad' => "Le nouvel utilisateur ne peut pas être créé.",

	'user:set:name' => "Nom",
	'user:name:label' => "Votre nom réel",
	'user:name:success' => "Votre nom a été changé avec succès.",
	'user:name:fail' => "Impossible de changer votre nom. Assurez-vous que votre nom n'est pas trop long et essayez à nouveau.",

	'user:set:password' => "Mot de passe",
	'user:current_password:label' => "Mot de passe actuel",
	'user:password:label' => "Votre nouveau mot de passe",
	'user:password2:label' => "Veuillez retaper votre nouveau mot de passe",
	'user:password:success' => "Mot de passe modifié avec succès",
	'user:password:fail' => "Impossible de modifier votre mot de passe.",
	'user:password:fail:notsame' => "Les deux mots de passe ne correspondent pas !",
	'user:password:fail:tooshort' => "Le mot de passe est trop court !",
	'user:password:fail:incorrect_current_password' => "Le mot de passe actuel entré est incorrect.",
	'user:resetpassword:unknown_user' => "Utilisateur inconnu.",
	'user:resetpassword:reset_password_confirm' => "Après réinitialisation de votre mot de passe, celui-ci sera envoyé à votre adresse mail.",

	'user:set:language' => "Langue",
	'user:language:label' => "Votre langue",
	'user:language:success' => "Votre paramètre de langage a été mis à jour.",
	'user:language:fail' => "Votre paramètre de langage n'a pas pu être sauvegardé.",

	'user:username:notfound' => "Nom d'utilisateur %s non trouvé.",

	'user:password:lost' => "Mot de passe perdu ?",
	'user:password:resetreq:success' => "Vous avez demandé un nouveau mot de passe, un mail vous a été envoyé",
	'user:password:resetreq:fail' => "Impossible de demander un nouveau mot de passe.",

	'user:password:text' => "Pour générer un nouveau mot de passe, entrez votre nom d'utilisateur ci-dessous. Vous recevez ensuite un lien par mail. Vous devrez cliquer sur le lien contenu dans le message et un nouveau mot de passe vous sera donné.",
	'user:persistent' => "Se souvenir de moi",

	'walled_garden:welcome' => "Bienvenue dans", // à

/**
 * Administration
 */
	'menu:page:header:administer' => "Administrer",
	'menu:page:header:configure' => "Configurer",
	'menu:page:header:develop' => "Développer",
	'menu:page:header:default' => "Autre",

	'admin:view_site' => "Voir le site",
	'admin:loggedin' => "Connecté en tant que %s",
	'admin:menu' => "Menu",

	'admin:configuration:success' => "Vos paramètres ont été sauvegardés.",
	'admin:configuration:fail' => "Vos paramètres n'ont pas pu être sauvegardés.",

	'admin:unknown_section' => "Section admin invalide.",

	'admin' => "Administration",
	'admin:description' => "Le panneau d'administration vous permet de contrôler tous les aspects du système d'Elgg, de la gestion des utilisateurs à la gestion des outils installés. Choisissez une option dans le menu ci-contre pour commencer.",

	'admin:statistics' => "Statistiques",
	'admin:statistics:overview' => "Vue d'ensemble",
	'admin:statistics:server' => "Info Serveur",

	'admin:appearance' => "Apparence",
	'admin:administer_utilities' => "Utilitaires",
	'admin:utilities' => "Utilitaires",
	'admin:develop_utilities' => "Utilitaires",

	'admin:users' => "Utilisateurs",
	'admin:users:online' => "Actuellement en ligne",
	'admin:users:newest' => "Le plus récent",
	'admin:users:add' => "Ajouter un nouvel utilisateur",
	'admin:users:description' => "Ce panneau d'administration vous permet de contrôler les paramètres des utilisateurs de votre site. Choisissez une option ci-dessous pour commencer.",
	'admin:users:adduser:label' => "Cliquez ici pour ajouter un nouvel utilisateur ...",
	'admin:users:opt:linktext' => "Configurer des utilisateurs ...",
	'admin:users:opt:description' => "Configurer les utilisateurs et les informations des comptes.",
	'admin:users:find' => "Trouver",

	'admin:settings' => "Paramètres",
	'admin:settings:basic' => "Réglages de base",
	'admin:settings:advanced' => "Paramètres avancés",
	'admin:site:description' => "Ce menu vous permet de définir les paramètres principaux de votre site. Choisissez une option ci-dessous pour commencer.",
	'admin:site:opt:linktext' => "Configurer le site...",
	'admin:site:access:warning' => "Changer les paramètres d'accès n'affectera que les permissions de contenu créées dans le futur.",

	'admin:dashboard' => "Tableau de bord",
	'admin:widget:online_users' => "Utilisateurs en ligne",
	'admin:widget:online_users:help' => "Affiche la liste des utilisateurs actuellement sur le site",
	'admin:widget:new_users' => "Nouveaux utilisateurs",
	'admin:widget:new_users:help' => "Affiche la liste des nouveaux utilisateurs",
	'admin:widget:content_stats' => "Statistiques",
	'admin:widget:content_stats:help' => "Garder une trace du contenu créé par les utilisateurs",
	'widget:content_stats:type' => "Type de contenu",
	'widget:content_stats:number' => "Nombre",

	'admin:widget:admin_welcome' => "Bienvenue",
	'admin:widget:admin_welcome:help' => "Une courte introduction à la zone d'administration de Elgg",
	'admin:widget:admin_welcome:intro' =>
"Bienvenue sur Elgg ! Vous êts actuellement sur le tableau de bord de l'administration. Il permet de faire le suivi de ce qui se passe sur le site.",

	'admin:widget:admin_welcome:admin_overview' =>
"La navigation dans l'administration se fait à l'aide du menu de droite. Il est organisé en"
. " 3 sections :
	<dl>
		<dt>Administrer</dt><dd>Les tâches quotidiennes comme le suivi du contenu signalé, l'aperçu des utilisateurs en ligne, l'affichage des statistiques...</dd>
		<dt>Configurer</dt><dd>Les tâches occasionnelles comme le paramétrage du nom du site ou l'activation d'un plugin.</dd>
		<dt>Développer</dt><dd>Pour les développeurs qui créent des plugins ou conçoient des thèmes. (Nécessite des connaissances en programmation.)</dd>
	</dl>
	",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => "<br /> Soyez sûr de vérifier les ressources disponibles via les liens de bas de page et merci d'utiliser Elgg!",

	'admin:widget:control_panel' => "Panneau de Contrôle",
	'admin:widget:control_panel:help' => "Fourni un accès facile aux contrôles communs",

	'admin:cache:flush' => "Nettoyer le cache",
	'admin:cache:flushed' => "Le cache du site a été nettoyé",

	'admin:footer:faq' => "Administration des FAQ",
	'admin:footer:manual' => "Guide sur l'administration",
	'admin:footer:community_forums' => "Forums de la communauté Elgg",
	'admin:footer:blog' => "Blog d'Elgg",

	'admin:plugins:category:all' => "Tous les plugins",
	'admin:plugins:category:active' => "Plugins Actifs",
	'admin:plugins:category:inactive' => "Plugins Inactifs",
	'admin:plugins:category:admin' => "Admin",
	'admin:plugins:category:bundled' => "Empaqueté",
	'admin:plugins:category:nonbundled' => "Non-Empaqueté",
	'admin:plugins:category:content' => "Contenu",
	'admin:plugins:category:development' => "Développement",
	'admin:plugins:category:extension' => "Extensions/Améliorations",
	'admin:plugins:category:service' => "Service/API",
	'admin:plugins:category:communication' => "Communication",
	'admin:plugins:category:security' => "Sécurité et spam",
	'admin:plugins:category:social' => "Social",
	'admin:plugins:category:multimedia' => "Multimédia",
	'admin:plugins:category:theme' => "Thèmes",
	'admin:plugins:category:widget' => "Widgets",
	'admin:plugins:category:utility' => "Utilitaires",

	'admin:plugins:sort:priority' => "Priorité",
	'admin:plugins:sort:alpha' => "Alphabétique",
	'admin:plugins:sort:date' => "Le plus récent",

	'admin:plugins:markdown:unknown_plugin' => "Plugin inconnu.",
	'admin:plugins:markdown:unknown_file' => "fichier inconnu.",


	'admin:notices:could_not_delete' => "Impossible de supprimer la remarque.",

	'admin:options' => "Options Administrateur",

/**
 * Plugins
 */
	'plugins:settings:save:ok' => "Le paramètrage du plugin %s a été enregistré.",
	'plugins:settings:save:fail' => "Il y a eu un problème lors de l'enregistrement des paramètres du plugin %s.",
	'plugins:usersettings:save:ok' => "Le paramètrage du plugin a été enregistré avec succès.",
	'plugins:usersettings:save:fail' => "Il y a eu un problème lors de l'enregistrement du paramètrage du plugin %s.",
	'item:object:plugin' => "Plugins",

	'admin:plugins' => "Administrer les plugins",
	'admin:plugins:activate_all' => "Tout activer",
	'admin:plugins:deactivate_all' => "Tout Désactiver",
	'admin:plugins:activate' => "Activer",
	'admin:plugins:deactivate' => "Désactiver",
	'admin:plugins:description' => "Ce menu vous permet de contrôler et de configurer les outils installés sur votre site.",
	'admin:plugins:opt:linktext' => "Configurer les outils...",
	'admin:plugins:opt:description' => "Configurer les outils installés sur le site.",
	'admin:plugins:label:author' => "Auteur",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => "Catégories",
	'admin:plugins:label:licence' => "Licence",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:moreinfo' => "Plus d'informations",
	'admin:plugins:label:version' => "Version",
	'admin:plugins:label:location' => "Lieu/Adresse",
	'admin:plugins:label:dependencies' => "Dépendances",

	'admin:plugins:warning:elgg_version_unknown' => "Ce plugin utilise un ancien fichier manifest.xml et ne précise pas si cette version est compatible avec l'Elgg actuel. Il ne fonctionnera probablement pas !",
	'admin:plugins:warning:unmet_dependencies' => "Ce plugin ne retrouve pas certaines dépendances et ne peut être activé. Vérifiez les dépendances pour plus d'infos.",
	'admin:plugins:warning:invalid' => "%s n'est pas un plugin valide d'Elgg. Vérifiez <a href='http://docs.elgg.org/Invalid_Plugin'>la documentation d'Elgg</a> les conseils de dépannage.",
	'admin:plugins:cannot_activate' => "Activation impossible",

	'admin:plugins:set_priority:yes' => "%s Réordonné",
	'admin:plugins:set_priority:no' => "Impossible de réordonné %s.",
	'admin:plugins:set_priority:no_with_msg' => "Impossible de réordonner %s. Erreur : %s",
	'admin:plugins:deactivate:yes' => "Désactivé %s.",
	'admin:plugins:deactivate:no' => "Impossible de désactiver %s.",
	'admin:plugins:deactivate:no_with_msg' => "Impossible de désactiver %s. Erreur : %s",
	'admin:plugins:activate:yes' => "%s activé.",
	'admin:plugins:activate:no' => "Impossible d'activer %s.",
	'admin:plugins:activate:no_with_msg' => "Impossible d'activer %s. Erreur : %s",
	'admin:plugins:categories:all' => "Toutes les catégories",
	'admin:plugins:plugin_website' => "Site du plugin",
	'admin:plugins:author' => "%s",
	'admin:plugins:version' => "Version %s",
	'admin:plugins:simple' => "Simple",
	'admin:plugins:advanced' => "Avancé",
	'admin:plugin_settings' => "Paramètres du plugin",
	'admin:plugins:warning:unmet_dependencies_active' => "Ce plugin est actif, mais a des dépendances introuvables. Vous pouvez avoir des problèmes. Voir 'plus d'info' ci-dessous pour plus de détails.",
	'admin:plugins:simple_simple_fail' => "Impossible d'enregistrer les paramètres.",
	'admin:plugins:simple_simple_success' => "Paramètres sauvegardés.",
	'admin:plugins:simple:cannot_activate' => "Impossible d'activer ce plugin. Vérifiez les options avancées du	plugin dans la zone d'administration pour plus d'informations.",

	'admin:plugins:dependencies:type' => "Type",
	'admin:plugins:dependencies:name' => "Nom",
	'admin:plugins:dependencies:expected_value' => "Valeur testée",
	'admin:plugins:dependencies:local_value' => "Valeur réelle",
	'admin:plugins:dependencies:comment' => "Commentaire",

	'admin:statistics:description' => "Cette page est un résumé des statistiques de votre site. Si vous avez besoin de statistiques plus détaillées, une version professionnelle d'administration est disponible.",
	'admin:statistics:opt:description' => "Voir des informations statistiques sur les utilisateurs et les objets de votre site.",
	'admin:statistics:opt:linktext' => "Voir statistiques...",
	'admin:statistics:label:basic' => "Statistiques basiques du site",
	'admin:statistics:label:numentities' => "Entités sur le site",
	'admin:statistics:label:numusers' => "Nombre d'utilisateurs",
	'admin:statistics:label:numonline' => "Nombre d'utilisateurs en ligne",
	'admin:statistics:label:onlineusers' => "Utilisateurs en ligne actuellement",
	'admin:statistics:label:version' => "Version d'Elgg",
	'admin:statistics:label:version:release' => "Révision",
	'admin:statistics:label:version:version' => "Version",

	'admin:server:label:php' => "PHP",
	'admin:server:label:web_server' => "Serveur Web",
	'admin:server:label:server' => "Serveur",
	'admin:server:label:log_location' => "Emplacement Log",
	'admin:server:label:php_version' => "PHP version",
	'admin:server:label:php_ini' => "Emplacement fichier PHP .ini",
	'admin:server:label:php_log' => "Log PHP",
	'admin:server:label:mem_avail' => "Mémoire disponible",
	'admin:server:label:mem_used' => "Mémoire utilisée",
	'admin:server:error_log' => "Serveur Web erreur du log",

	'admin:user:label:search' => "Trouver des utilisateurs :",
	'admin:user:label:searchbutton' => "Chercher",

	'admin:user:ban:no' => "Cet utilisateur ne peut pas être banni",
	'admin:user:ban:yes' => "Utilisateur banni.",
	'admin:user:self:ban:no' => "Vous ne pouvez pas vous bannir vous même",
	'admin:user:unban:no' => "Cet utilisateur ne peut pas être réintégré",
	'admin:user:unban:yes' => "Utilisateur réintégré.",
	'admin:user:delete:no' => "Cet utilisateur ne peut pas être supprimé",
	'admin:user:delete:yes' => "Utilisateur supprimé",
	'admin:user:self:delete:no' => "Vous ne pouvez pas vous supprimer",

	'admin:user:resetpassword:yes' => "Mot de passe réinitialisé, utilisateur notifié.",
	'admin:user:resetpassword:no' => "Le mot de passe n'a pas pu être réinitialisé.",

	'admin:user:makeadmin:yes' => "L'utilisateur est maintenant un administrateur.",
	'admin:user:makeadmin:no' => "Nous ne pouvons pas faire de cet utilisateur un administrateur.",

	'admin:user:removeadmin:yes' => "L'utilisateur n'est plus administrateur.",
	'admin:user:removeadmin:no' => "Nous ne pouvons pas supprimer les privilèges d'administrateur à cet utilisateur.",
	'admin:user:self:removeadmin:no' => "Vous ne pouvez pas supprimer vos propres privilèges d'administrateur.",

	'admin:appearance:menu_items' => "Les éléments de menu",
	'admin:menu_items:configure' => "Configurer les éléments du menu principal",
	'admin:menu_items:description' => "Sélectionnez les éléments de menu que vous voulez afficher en liens directs. Les éléments de menu inutilisés seront ajoutées dans la liste «Plus».",
	'admin:menu_items:hide_toolbar_entries' => "Supprimer les liens dans le menu barre d'outils ?",
	'admin:menu_items:saved' => "Les éléments de menu sauvés.",
	'admin:add_menu_item' => "Ajouter un élément de menu personnalisé",
	'admin:add_menu_item:description' => "Remplissez le nom et l'URL d'affichage pour ajouter des éléments personnalisés à votre menu de navigation.",

	'admin:appearance:default_widgets' => "Widgets par défaut",
	'admin:default_widgets:unknown_type' => "Type de widget inconnu",
	'admin:default_widgets:instructions' => "Ajoutez, supprimez, positionnez et configurez les widgets par défaut pour la page des profils. Ces changements s'appliqueront uniquement aux futurs nouveaux utilisateurs du site.",

/**
 * User settings
 */
	'usersettings:description' => "Le panneau de configuration vous permet de contrôler tous vos paramètres et vos plugins. Choisissez une option ci-dessous pour continuer.",

	'usersettings:statistics' => "Vos statistiques",
	'usersettings:statistics:opt:description' => "VVisualiser les statistiques des utilisateurs et des objets sur votre espace.",
	'usersettings:statistics:opt:linktext' => "Statistiques de votre compte",

	'usersettings:user' => "Paramètres",
	'usersettings:user:opt:description' => "Ceci vous permet de contrôler vos paramètres.",
	'usersettings:user:opt:linktext' => "Mon compte",

	'usersettings:plugins' => "Outils",
	'usersettings:plugins:opt:description' => "Configurer vos paramètres (s'il y en a) pour activer vos outils.",
	'usersettings:plugins:opt:linktext' => "Configurer les outils",

	'usersettings:plugins:description' => "Ce panneau de configuration vous permet de mettre à jour les options de vos outils.",
	'usersettings:statistics:label:numentities' => "Vos entités",

	'usersettings:statistics:yourdetails' => "Vos informations",
	'usersettings:statistics:label:name' => "Votre nom",
	'usersettings:statistics:label:email' => "mail",
	'usersettings:statistics:label:membersince' => "Membre depuis",
	'usersettings:statistics:label:lastlogin' => "Dernière connexion",
	'usersettings:statistics:label:lastaction' => "Dernière action",

/**
 * Activity river
 */
	'river:all' => "Toute l'activité du site",
	'river:mine' => "Mon activité",
	'river:friends' => "Activité de mes abonnements",
	'river:select' => "Afficher %s",
	'river:comments:more' => " +%U plus",
	'river:generic_comment' => "a commenté sur %s %s",

	'friends:widget:description' => "Affiche certains de vos amis.",
	'friends:num_display' => "Nombre d'amis à afficher",
	'friends:icon_size' => "Taille des icônes",
	'friends:tiny' => "minuscule",
	'friends:small' => "petit",

/**
 * Generic action words
 */
	'save' => "Enregistrer",
	'reset' => "Réinitialiser",
	'publish' => "Publier",
	'cancel' => "Annuler",
	'saving' => "Enregistrement en cours",
	'update' => "Mettre à jour",
	'preview' => "Prévisualisation",
	'edit' => "Modifier",
	'delete' => "Supprimer",
	'accept' => "Accepter",
	'load' => "Charger",
	'upload' => "Charger",
	'ban' => "Bannir",
	'unban' => "Réintégrer",
	'banned' => "Banni",
	'enable' => "Activer",
	'disable' => "Désactiver",
	'request' => "Requête",
	'complete' => "Complété",
	'open' => "Ouvrir",
	'close' => "Fermer",
	'reply' => "Répondre",
	'more' => "Plus",
	'comments' => "Commentaires",
	'import' => "Importer",
	'export' => "Exporter",
	'untitled' => "Sans titre",
	'help' => "Aide",
	'send' => "Envoyer",
	'post' => "Poster",
	'submit' => "Soumettre",
	'comment' => "Commentaire",
	'upgrade' => "Mise à jour",
	'sort' => "Trier",
	'filter' => "Filtrer",
	'new' => "Nouveau",
	'add' => "Ajouter",
	'create' => "Créer",
	'revert' => "Revenir",

	'site' => "Site",
	'activity' => "Activité",
	'members' => "Membres",

	'up' => "Monter",
	'down' => "Descendre",
	'top' => "Tout en Haut",
	'bottom' => "Tout en Bas",

	'invite' => "Inviter",

	'resetpassword' => "Réinitialiser le mot de passe",
	'makeadmin' => "Rendre cet utilisateur administrateur",
	'removeadmin' => "Supprimer les droits administrateur de cet utilisateur",

	'option:yes' => "Oui",
	'option:no' => "Non",

	'unknown' => "Inconnu",

	'active' => "Activé",
	'total' => "Total",

	'learnmore' => "Cliquer ici pour en apprendre plus.",

	'content' => "contenu",
	'content:latest' => "Dernière activité",
	'content:latest:blurb' => "Vous pouvez également cliquer ici pour voir les dernières modifications effectuées sur le site.",

	'link:text' => "voir le lien",
/**
 * Generic questions
 */
	'question:areyousure' => "Etês-vous sûr ?",

/**
 * Generic data words
 */
	'title' => "Titre",
	'description' => "Description",
	'tags' => "Mots-clés",
	'spotlight' => "Projecteur sur",
	'all' => "Tous",
	'mine' => "Moi",

	'by' => "par",
	'none' => "aucun",
	'at' => "à",
	'in' => "dans",
	'from' => "depuis",
	'for' => "pour",

	'annotations' => "Annotations",
	'relationships' => "Relations",
	'metadata' => "Métadonnées",

	'tagcloud:cloud' => "Nuage de mots-clés",
	'tagcloud:list' => "Liste de mots-clés",
	'tagcloud:allsitetags' => "Tous les mots-clés",
	'tagtitle' => "%s %s avec ce tag",

	'on' => "Actif",
	'off' => "Inactif",

/**
 * Entity actions
 */
	'edit:this' => "Modifier",
	'delete:this' => "Supprimer",
	'comment:this' => "Commenter",

/**
 * Input / output strings
 */
	'deleteconfirm' => "Etes-vous sur de vouloir supprimer cet élément ?",
	'fileexists' => "Un fichier a déjà été chargé. Pour le remplacer sélectionner le ci-dessous :",

/**
 * User add
 */
	'useradd:subject' => "Compte de l'utilisateur créé",
	'useradd:body' => "
%s,

Votre compte utilisateur vient d'être créé sur %s. Pour vous connecter maintenant, rendez-vous :

%s

Et utilisez les identifiants suivants pour vous connecter :

Nom d'utilisateur : %s
Mot de passe : %s

Une fois sur le site, nous vous conseillons fortement de changer votre mot de passe en cliquant sur 'Votre compte'.
",

/**
 * System messages
 **/
	'systemmessages:dismiss' => "Cliquer pour fermer",

/**
 * Import / export
 */
	'importsuccess' => "L'import des données a été réalisé avec succès",
	'importfail' => "L'import OpenDD des données a échoué.",

/**
 * Time
 */
	'friendlytime:justnow' => "à l'instant",
	'friendlytime:minutes' => "il y a %s minutes",
	'friendlytime:minutes:singular' => "il y a une minute",
	'friendlytime:hours' => "il y a %s heures",
	'friendlytime:hours:singular' => "il y a une heure",
	'friendlytime:days' => "il y a %s jours",
	'friendlytime:days:singular' => "hier",
	'friendlytime:date_format' => "%a %d %b %Y à %T",

	'date:month:01' => "Janvier %s",
	'date:month:02' => "Février %s",
	'date:month:03' => "Mars %s",
	'date:month:04' => "Avril %s",
	'date:month:05' => "Mai %s",
	'date:month:06' => "Juin %s",
	'date:month:07' => "Juillet %s",
	'date:month:08' => "Août %s",
	'date:month:09' => "Septembre %s",
	'date:month:10' => "Octobre %s",
	'date:month:11' => "Novembre %s",
	'date:month:12' => "Décembre %s",

/**
 * System settings
 */
	'installation:sitename' => "Le nom de votre site (par exemple 'Mon site de réseau social') :",
	'installation:sitedescription' => "Brève description du site (facultatif) :",
	'installation:wwwroot' => "L'URL du site, suivie d'un '/' :",
	'installation:path' => "Chemin physique des fichiers sur le serveur, suivi d'un '/' :",
	'installation:dataroot' => "Chemin complet, suivi d'un '/', où seront stockés les fichiers uploadés par les utilisateurs :",
	'installation:dataroot:warning' => "Vous devez créer ce répertoire manuellement. Il doit se situer dans un répertoire différent de votre installation de Elgg.",
	'installation:sitepermissions' => "Les permissions d'accés par défaut :",
	'installation:language' => "La langue par défaut de votre site :",
	'installation:debug' => "Le mode de débogage permet de mettre en évidence certaines erreurs de fonctionnement, cependant il ralenti l'accès au site, il est à utiliser uniquement en cas de problème :",
	'installation:debug:none' => "Désactive le mode debug (recommandé)",
	'installation:debug:error' => "Afficher seulement les erreurs critiques",
	'installation:debug:warning' => "Afficher les erreurs et les avertissements",
	'installation:debug:notice' => "Log toutes les erreurs, les avertissements et les avis",

// Walled Garden support
	'installation:registration:description' => "L'enregistrement d'un utilisateur est activé par défaut. Désactivez cette option si vous ne voulez pas que de nouveaux utilisateurs soient en mesure de s'inscrire eux-mêmes.",
	'installation:registration:label' => "Permettre à de nouveaux utilisateurs de s'enregistrer eux-mêmes",
	'installation:walled_garden:description' => "Autoriser le site à fonctionner comme un réseau privé. Cela empêchera les utilisateurs non connectés d'afficher les pages du site autres que celles expressément spécifiées comme publiques.",
	'installation:walled_garden:label' => "Restreindre les pages aux utilisateurs enregistrés",

	'installation:httpslogin' => "Activer ceci afin que les utilisateurs puissent se connecter via le protocole https. Vous devez avoir https activé sur votre serveur afin que cela fonctionne.",
	'installation:httpslogin:label' => "Activer les connexions HTTPS",
	'installation:view' => "Entrer le nom de la vue qui sera utilisée automatiquement pour l'affichage du site (par exemple : 'mobile'), laissez par défaut en cas de doute :",

	'installation:siteemail' => "L'adresse mail du site (utilisée par le système lors des envois automatiques d'mails)",

	'installation:disableapi' => "Elgg fournit une API RESTful qui permet à des applications distantes d'interagir avec votre site Elgg.",
	'installation:disableapi:label' => "Activer les services Web d'Elgg",

	'installation:allow_user_default_access:description' => "Si coché, les utilisateurs pourront modifier leur niveau d'accés par défaut et pourront surpasser le niveau d'accés mis en place par défaut dans le système.",
	'installation:allow_user_default_access:label' => "Autoriser un niveau d'accés par défaut pour l'utilisateur",

	'installation:simplecache:description' => "Le cache simple augmente les performances en mettant en cache du contenu statique comme des CSS et des fichiers Javascripts. Normalement vous ne devriez pas avoir besoin de l'activer.",
	'installation:simplecache:label' => "Utiliser un cache simple (recommandé)",

	'installation:viewpathcache:description' => "Le cache utilisé pour stocker les chemins vers les vues des greffons réduit le temps de chargement de ces derniers.",
	'installation:viewpathcache:label' => "Utiliser le cache de stockage des chemins vers les vues des greffons (recommandé)",

	'upgrading' => "Mise à jour en cours",
	'upgrade:db' => "Votre base de données a été mise à jour.",
	'upgrade:core' => "Votre installation de Elgg a été mise à jour",
	'upgrade:unable_to_upgrade' => "Impossible de mettre à jour.",
	'upgrade:unable_to_upgrade_info' =>
		"Cette installation ne peut pas être mise à jour, car des fichiers de l'ancienne version
		ont été détectés dans le répertoire du noyau d'Elgg (core). Ces fichiers ont été jugés obsolètes et doivent être
		retirés pour permettre à cette nouvelle version d'Elgg de fonctionner correctement. Si vous n'avez pas apporté de changements au noyau d'Elgg, vous pouvez
		simplement supprimer le répertoire noyau (core) et le remplacer par celui du dernier
		paquet Elgg téléchargé depuis <a href=\"http://elgg.org/\" target='_blank'>elgg.org</a>. <br /> <br />

		Si vous avez besoin d'instructions détaillées, veuillez svp consulter la <a href=\"http://docs.elgg.org/wiki/Upgrading_Elgg\">Documentation sur la mise à niveau d'Elgg </a>. Si vous avez besoin d'aide, n'hésitez pas à demander sur le forum de la communauté: <a href=\"http://community.elgg.org/pg/groups/discussion/\" target='_blank'>Forum aide technique (support)</a>. ",

	'update:twitter_api:deactivated' => "Twitter API (précédemment Twitter Service) a été désactivé lors de la mise à niveau. Veuillez activer manuellement si nécessaire.",
	'update:oauth_api:deactivated' => "OAuth API (précédemment OAuth Lib) a été désactivé lors de la mise à niveau. Veuillez activer manuellement si nécessaire.",

	'deprecated:function' => "%s() a été déclarée obsolète par %s()",

/**
 * Welcome
 */
	'welcome' => "Bienvenue",
	'welcome:user' => "Bienvenue %s",

/**
 * Emails
 */
	'email:settings' => "Paramètres mail",
	'email:address:label' => "Votre adresse mail",

	'email:save:success' => "Votre nouvelle adresse mail a été enregistrée, vous allez recevoir un mail de confirmation.",
	'email:save:fail' => "Votre nouvelle adresse mail n'a pas pu être enregistrée.",

	'friend:newfriend:subject' => "%s suit votre activité sur ggouv.fr !",
	'friend:newfriend:body' => "%s suit votre activité !

Vous pouvez consulter son profil en cliquant sur le lien ci-dessous:

	%s

(Ceci est un mail automatique de notification. Inutile donc d'y répondre directement)",

	'email:resetpassword:subject' => "Réinitialisation du mot de passe !",
	'email:resetpassword:body' => "Bonjour %s,

Votre nouveau mot de passe est : %s",
	'email:resetreq:subject' => "Demander un nouveau mot de passe.",
	'email:resetreq:body' => "Bonjour %s,

Quelqu'un (avec l'adresse IP %s) a demandé un nouveau mot de passe pour ce compte.

Si vous avez demandé ce changement veuillez cliquer sur le lien ci-dessous, sinon veuillez simplement ignorer cet mail.

%s
",

/**
 * user default access
 */
'default_access:settings' => "Votre niveau d'accés par défaut",
'default_access:label' => "Accés par défaut",
'user:default_access:success' => "Votre nouveau niveau d'accés par défaut a été enregistré.",
'user:default_access:failure' => "Votre nouveau niveau d'accés par défaut n'a pas pu être enregistré.",

/**
 * XML-RPC
 */
	'xmlrpc:noinputdata'	=>	"Données d'entrée manquantes",

/**
 * Comments
 */
	'comments:count' => "%s commentaire(s)",

	'riveraction:annotation:generic_comment' => "%s a écrit un commentaire sur %s",

	'generic_comments:add' => "Commenter",
	'generic_comments:post' => "Poster un commentaire",
	'generic_comments:text' => "Commentaire",
	'generic_comments:latest' => "Derniers commentaires",
	'generic_comment:posted' => "Votre commentaire a été publié avec succés.",
	'generic_comment:deleted' => "Votre commentaire a été correctement supprimé.",
	'generic_comment:blank' => "Désolé, vous devez remplir votre commentaire avant de pouvoir l'enregistrer.",
	'generic_comment:notfound' => "Désolé, l'élément recherché n'a pas été trouvé.",
	'generic_comment:notdeleted' => "Désolé, le commentaire n'a pas pu être supprimé.",
	'generic_comment:failure' => "Une erreur est survenue lors de l'ajout de votre commentaire. Veillez réessayer.",
	'generic_comment:none' => "Pas de commentaire",
	'generic_comment:title' => "Commentaire par %s",

	'generic_comment:email:subject' => "Nouveau commentaire sur « %s »",
	'generic_comment:email:body' => "<a href=\"%s\" target=\"_blank\">%s</a> a commenté <a href=\"%s\" target=\"_blank\">%s</a> :

<div style=\"background-color: #FAFAFA;font-size: 1.1em;padding: 10px;\">%s</div>

Pour répondre ou voir le contenu de référence, suivez le lien ci-dessous:
%s",

/**
 * Entities
 */
	'byline' => "Par %s",
	'entity:default:strapline' => "Créé le %s par %s",
	'entity:default:missingsupport:popup' => "Cette entité ne peut pas être affichée correctement. C'est peut-être du à un plugin qui a été supprimé.",

	'entity:delete:success' => "L'entité %s a été effacée",
	'entity:delete:fail' => "L'entité %s n'a pas pu être effacée",

/**
 * Action gatekeeper
 */
	'actiongatekeeper:missingfields' => "Il manque les champs __token ou __ts dans le formulaire.",
	'actiongatekeeper:tokeninvalid' => "Une erreur est survenue. Cela veut probablement dire que la page que vous utilisiez a expiré. Merci de réessayer",
	'actiongatekeeper:timeerror' => "La page a expiré, rafraîchissez cette page (F5) et recommencez à nouveau.",
	'actiongatekeeper:pluginprevents' => "Une extension a empêché ce formulaire d'être envoyé",


/**
 * Word blacklists
 */
	'word:blacklist' => "de,du,des,d',l',c',n',m',t',s',à,le,la,alors,elle,lui,son,sa,ses,ça,ceci,cela,ceux-ci,celle-la,celles-la,celui-la,ceux-la,celui,celle,personne,tous,tout,toute,toutes,quiconque,pas,également,à propos,maintenant,hier,demain,cependant,néanmoins,toutefois,encore,de même,de plus,sinon,par conséquent,à l'inverse,plutôt,par conséquent,en outre,au lieu,pendant ce temps,en conséquence,semble,ce,cet,cette,ces,ceux,aussi,ainsi,qui,que,quoi,dont,mais,ou,où,d'où,est,et,donc,or,ni,car",

/**
 * Tag labels
 */
	'tag_names:tags' => "Mots-clés",
	'tags:site_cloud' => "Nuage de mots-clés du site",

/**
 * Javascript
 */
	'js:security:token_refresh_failed:title' => "Vous avez été absent trop longtemps !",
	'js:security:token_refresh_failed:body' => "En attendant, je me suis mis en veille pour des questions de sécurité...",
	'js:security:token_refresh_failed:wakeup' => "Réveillez moi !",
	'js:security:token_refreshed' => "La connexion à %s a été restaurée !",
	'js:security:token_refresh_failed' => 'Le contact a été perdu avec %s. Rafraîchissez la page.',


/**
 * Languages according to ISO 639-1
 */
	"aa" => "Afar",
	"ab" => "Abkhaze",
	"af" => "Afrikaans",
	"am" => "Amharique",
	"ar" => "Arabe",
	"as" => "Assamais",
	"ay" => "Aymara",
	"az" => "Azéri",
	"ba" => "Bachkir",
	"be" => "Biélorusse",
	"bg" => "Bulgare",
	"bh" => "Bihari",
	"bi" => "Bichelamar",
	"bn" => "Bengalî",
	"bo" => "Tibétain",
	"br" => "Breton",
	"ca" => "Catalan",
	"co" => "Corse",
	"cs" => "Tchèque",
	"cy" => "Gallois",
	"da" => "Danois",
	"de" => "Allemand",
	"dz" => "Dzongkha",
	"el" => "Grec",
	"en" => "Anglais",
	"eo" => "Espéranto",
	"es" => "Espagnol",
	"et" => "Estonien",
	"eu" => "Basque",
	"fa" => "Persan",
	"fi" => "Finnois",
	"fj" => "Fidjien",
	"fo" => "Féringien",
	"fr" => "Français",
	"fy" => "Frison",
	"ga" => "Irlandais",
	"gd" => "Écossais",
	"gl" => "Galicien",
	"gn" => "Guarani",
	"gu" => "Gujarâtî",
	"he" => "Hébreu",
	"ha" => "Haoussa",
	"hi" => "Hindî",
	"hr" => "Croate",
	"hu" => "Hongrois",
	"hy" => "Arménien",
	"ia" => "Interlingua",
	"id" => "Indonésien",
	"ie" => "Occidental",
	"ik" => "Inupiaq",
	//"in" => "Indonésien",
	"is" => "Islandais",
	"it" => "Italien",
	"iu" => "Inuktitut",
	"iw" => "Hébreu (obsolète)",
	"ja" => "Japonais",
	"ji" => "Yiddish (obsolète)",
	"jw" => "Javanais",
	"ka" => "Géorgien",
	"kk" => "Kazakh",
	"kl" => "Kalaallisut",
	"km" => "Khmer",
	"kn" => "Kannara",
	"ko" => "Coréen",
	"ks" => "Kashmiri",
	"ku" => "Kurde",
	"ky" => "Kirghiz",
	"la" => "Latin",
	"ln" => "Lingala",
	"lo" => "Lao",
	"lt" => "Lituanien",
	"lv" => "Letton",
	"mg" => "Malgache",
	"mi" => "Maori",
	"mk" => "Macédonien",
	"ml" => "Malayalam",
	"mn" => "Mongol",
	"mo" => "Moldave",
	"mr" => "Marâthî",
	"ms" => "Malais",
	"mt" => "Maltais",
	"my" => "Birman",
	"na" => "Nauruan",
	"ne" => "Népalais",
	"nl" => "Néerlandais",
	"no" => "Norvégien",
	"oc" => "Occitan",
	"om" => "Oromo",
	"or" => "Oriya",
	"pa" => "Panjâbî",
	"pl" => "Polonais",
	"ps" => "Pachto",
	"pt" => "Portugais",
	"qu" => "Quechua",
	"rm" => "Romanche",
	"rn" => "Kirundi",
	"ro" => "Roumain",
	"ru" => "Russe",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sango",
	"sh" => "Serbo-Croate",
	"si" => "Cingalais",
	"sk" => "Slovaque",
	"sl" => "Slovène",
	"sm" => "Samoan",
	"sn" => "Shona",
	"so" => "Somalien",
	"sq" => "Albanais",
	"sr" => "Serbe",
	"ss" => "Siswati",
	"st" => "Sotho",
	"su" => "Soundanais",
	"sv" => "Suédois",
	"sw" => "Swahili",
	"ta" => "Tamoul",
	"te" => "Télougou",
	"tg" => "Tadjik",
	"th" => "Thaï",
	"ti" => "Tigrinya",
	"tk" => "Turkmène",
	"tl" => "Tagalog",
	"tn" => "Tswana",
	"to" => "Tongien",
	"tr" => "Turc",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Ouïghour",
	"uk" => "Ukrainien",
	"ur" => "Ourdou",
	"uz" => "Ouzbek",
	"vi" => "Vietnamien",
	"vo" => "Volapük",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zhuang",
	"zh" => "Chinois",
	"zu" => "Zoulou",


/**
 * Elgg reported content plugin language pack
 *
 * @package ElggReportedContent
 */
	'item:object:reported_content' => "Eléments signalés",
	'admin:utilities:reportedcontent' => "Contenu signalé ",
	'reportedcontent' => "Contenu signalé",
	'reportedcontent:this' => "Signaler ceci",
	'reportedcontent:this:tooltip' => "Signaler cette page à un administrateur",
	'reportedcontent:none' => "Il n'y a pas de contenu signalé",
	'reportedcontent:report' => "Signaler ceci",
	'reportedcontent:title' => "Titre de la page",
	'reportedcontent:deleted' => "Le contenu signalé a été effacé",
	'reportedcontent:notdeleted' => "Il a été impossible d'effacer ce signalement",
	'reportedcontent:delete' => "L'effacer",
	'reportedcontent:areyousure' => "Etes-vous sûr de vouloir l'effacer ?",
	'reportedcontent:archive' => "L'archiver",
	'reportedcontent:archived' => "Le signalement a bien été archivé",
	'reportedcontent:visit' => "Visiter l'élément signalé",
	'reportedcontent:by' => "Signalé par ",
	'reportedcontent:objecttitle' => "Titre de l'objet",
	'reportedcontent:objecturl' => "URL de l'objet",
	'reportedcontent:reason' => "Motif du signalement",
	'reportedcontent:description' => "Pourquoi souhaitez-vous signaler ceci ?",
	'reportedcontent:address' => "Emplacement de l'élément",
	'reportedcontent:success' => "Votre signalement a bien été envoyé à l'adminsitrateur du site",
	'reportedcontent:failing' => "Votre signalement n'a pu être envoyé",
	'reportedcontent:report' => "Signaler ceci",
	'reportedcontent:moreinfo' => "Plus d'information",
	'reportedcontent:instructions' => "Ce rapport sera envoyé aux administrateurs de ce site à des fins d'examen.",
	'reportedcontent:numbertodisplay' => "Nombre de rapports à afficher",
	'reportedcontent:widget:description' => "Afficher le contenu signalé",
	'reportedcontent:user' => "Rapport utilisateur",

	'reportedcontent:failed' => "Désolé, la tentative de signaler ce contenu a échoué.",
	'reportedcontent:notarchived' => "Il a été impossible d'archiver ce signalement",


/**
 * Elgg groups plugin language pack
 *
 * @package ElggGroups
 */

	/**
	 * Menu items and titles
	 */
	'groups' => "Groupes",
	'groups:owned' => "Les groupes que j'ai créés",
	'groups:memberof' => "Les groupes dont je suis membre",
	'groups:user' => "Les groupes de %s",
	'groups:all' => "Tous les groupes",
	'groups:add' => "Créer un nouveau groupe",
	'groups:edit' => "Modifier le groupe",
	'groups:title:edit' => "Modifier le groupe %s",
	'groups:delete' => "Supprimer le groupe",
	'groups:membershiprequests' => "Gérer les membres souhaitant se joindre au groupe",
	'groups:invitations' => "Invitations du groupe",

	'groups:icon' => "Icone du groupe (Format carré. L'idéal est 200px x 200px)",
	'groups:name' => "Nom du groupe",
	'groups:username' => "Nom court du goupe (Qui s'affichera dans l'URL : en caractères alphanumériques)",
	'groups:description' => "Description",
	'groups:briefdescription' => "Description courte",
	'groups:interests' => "Mots-clés",
	'groups:website' => "Site web",
	'groups:members' => "Membres du groupe",
	'groups:members:title' => "Les membres de %s",
	'groups:members:more' => "Voir tous les membres",
	'groups:membership' => "Permissions d'accès au groupe",
	'groups:access' => "Permissions d'accès",
	'groups:owner' => "Créateur",
	'groups:widget:num_display' => "Nombre de groupes à afficher",
	'groups:widget:membership' => "Adhésion au groupe",
	'groups:widgets:description' => "Afficher les groupes dont vous êtes membres dans votre profil",
	'groups:noaccess' => "Vous n'avez pas accès au groupe",
	'groups:permissions:error' => "Vous n'avez pas les autorisations pour çà",
	'groups:ingroup' => "dans le groupe",
	'groups:cantedit' => "Vous ne pouvez pas modifier ce groupe",
	'groups:saved' => "Groupe enregistré",
	'groups:featured' => "Les groupes à la une",
	'groups:makeunfeatured' => "Enlever de la une",
	'groups:makefeatured' => "Mettre en une",
	'groups:featuredon' => "%s est maintenant un groupe à la une.",
	'groups:unfeatured' => "s% a été enlevé par les groupes à la une.",
	'groups:featured_error' => "Groupe invalide.",
	'groups:joinrequest' => "Demander une adhésion au groupe",
	'groups:join' => "Rejoindre le groupe",
	'groups:leave' => "Quitter le groupe",
	'groups:invite' => "Inviter des contacts",
	'groups:invite:title' => "Invitez des amis à ce groupe",
	'groups:inviteto' => "Inviter des contacts au groupe '%s'",
	'groups:nofriends' => "Vous n'avez plus de contacts à inviter à ce groupe.",
	'groups:nofriendsatall' => "Vous n'avez pas d'amis à inviter !",
	'groups:viagroups' => "Via les groupes",
	'groups:group' => "Groupe",
	'groups:search:tags' => "Tag",
	'groups:search:title' => "Rechercher des groupes qui contiennent le tag '% s'",
	'groups:search:none' => "Aucun groupe correspondant n'a été trouvé",
	'groups:search_in_group' => "Chercher dans ce groupe",
	'groups:acl' => "Groupe : %s",

	'groups:enableactivity' => "Rendre disponible Activité de groupe",
	'groups:activity:none' => "Il n'y a pas encore d'activité de groupe",

	'groups:notfound' => "Le groupe n'a pas été trouvé",
	'groups:notfound:details' => "Le groupe que vous recherchez n'existe pas, ou alors vous n'avez pas la permission d'y accéder",

	'groups:requests:none' => "Il n'y a pas de membre demandant de rejoindre le groupe en ce moment.",

	'groups:invitations:none' => "Il n'y a pas d'invitations en attente.",

	'item:object:groupforumtopic' => "Sujets de discussion",

	'groupforumtopic:new' => "Ajouter un message à la discussion",

	'groups:count' => "groupe créé",
	'groups:open' => "groupe ouvert",
	'groups:closed' => "groupe fermé",
	'groups:member' => "membres",
	'groups:searchtag' => "Rechercher des groupes par des mots-clé",

	'groups:more' => "Plus de groupes",
	'groups:none' => "Aucun groupe",


	/*
	 * Access
	 */
	'groups:access:private' => "Fermé - Les utilisateurs doivent être invités",
	'groups:access:public' => "Ouvert - N'importe quel utilisateur peut rejoindre le groupe",
	'groups:access:group' => "Membres du groupe seulement",
	'groups:closedgroup' => "Ce groupe est en adhésion privée.",
	'groups:closedgroup:request' => "Pour demander à être ajouté, cliquer le lien 'Demander une adhésion'.",
	'groups:visibility' => "Qui peut voir ce groupe?",

	/*
	Group tools
	*/
	'groups:enableforum' => "Activer le module 'discussion' du groupe",
	'groups:yes' => "Oui",
	'groups:no' => "Non",
	'groups:lastupdated' => "Dernière mise à jour %s par %s",
	'groups:lastcomment' => "Dernier commentaire %s by %s",

	/*
	Group discussion
	*/
	'discussion' => "Discussion",
	'discussion:add' => "Ajouter un sujet de discussion",
	'discussion:latest' => "Dernières discussions",
	'discussion:group' => "Groupe de discussion",

	'discussion:topic:created' => "Le sujet de discussion a été créé.",
	'discussion:topic:updated' => "Le sujet de discussion a été mis à jour.",
	'discussion:topic:deleted' => "Le sujet de discussion a été supprimée.",

	'discussion:topic:notfound' => "Le sujet de discussion est introuvable",
	'discussion:error:notsaved' => "Impossible d'enregistrer ce sujet",
	'discussion:error:missing' => "Les deux champs 'titre' et 'message' sont obligatoires",
	'discussion:error:permissions' => "Vous n'avez pas les autorisations pour effectuer cette action",
	'discussion:error:notdeleted' => "Impossible de supprimer le sujet de discussion",

	'discussion:reply:deleted' => "La réponse de la discussion a été supprimée.",
	'discussion:reply:error:notdeleted' => "Impossible de supprimer la réponse de la discussion",

	'reply:this' => "Répondre à çà",

	'group:replies' => "Réponses",
	'groups:forum:created' => "Créé %s avec %d commentaires",
	'groups:forum:created:single' => "Créé %s avec %d réponse",
	'groups:forum' => "Discussion",
	'groups:addtopic' => "Ajouter un sujet",
	'groups:forumlatest' => "Dernière discussion",
	'groups:latestdiscussion' => "Dernières discussions",
	'groups:newest' => "Récents",
	'groups:popular' => "Populaires",
	'groupspost:success' => "Votre réponse a été publié avec succès",
	'groups:alldiscussion' => "Dernière discussion",
	'groups:edittopic' => "Modifier le sujet",
	'groups:topicmessage' => "Message du sujet",
	'groups:topicstatus' => "Statut du sujet",
	'groups:reply' => "Publier un commentaire",
	'groups:topic' => "Sujets",
	'groups:posts' => "Posts",
	'groups:lastperson' => "Dernière personne",
	'groups:when' => "Quand",
	'grouptopic:notcreated' => "Aucun sujet n'a été créé.",
	'groups:topicopen' => "Ouvert",
	'groups:topicclosed' => "Fermé",
	'groups:topicresolved' => "Résolu",
	'grouptopic:created' => "Votre sujet a été créé.",
	'groupstopic:deleted' => "Sujet supprimé",
	'groups:topicsticky' => "Sticky",
	'groups:topicisclosed' => "Cette discussion sujet est fermée.",
	'groups:topiccloseddesc' => "Cette discussion a été fermée et n'accepte plus de nouveaux commentaires.",
	'grouptopic:error' => "Votre sujet n'a pas pu être créé. Merci d'essayer plus tard ou de contacter un administrateur du système.",
	'groups:forumpost:edited' => "Vous avez modifié ce billet avec succés.",
	'groups:forumpost:error' => "Il y a eu un problème lors de la modification du billet.",


	'groups:privategroup' => "Ce groupe est privé. Il est nécessaire de demander une adhésion.",
	'groups:notitle' => "Les groupes doivent avoir un titre",
	'groups:cantjoin' => "N'a pas pu rejoindre le groupe",
	'groups:cantleave' => "N'a pas pu quitter le groupe",
	'groups:addedtogroup' => "A ajouté avec succés l'utilisateur au groupe",
	'groups:joinrequestnotmade' => "La demande d'adhésion n'a pas pu être réalisée",
	'groups:joinrequestmade' => "La demande d'adhésion s'est déroulée avec succés",
	'groups:joined' => "Vous avez rejoint le groupe avec succés !",
	'groups:left' => "Vous avez quitter le groupe avec succés",
	'groups:notowner' => "Désolé, vous n'êtes pas administrateur du groupe.",
	'groups:notmember' => "Désolé, vous n'êtes pas membre de ce groupe.",
	'groups:alreadymember' => "Vous êtes déjà membre de ce groupe !",
	'groups:userinvited' => "L'utilisateur a été invité.",
	'groups:usernotinvited' => "L'utilisateur n'a pas pu être invité",
	'groups:useralreadyinvited' => "L'utilisateur a déjà été invité",
	'groups:invite:subject' => "%s vous avez été invité(e) à rejoindre %s!",
	'groups:updated' => "Derniere réponse par %s %s",
	'groups:started' => "Démarré par %s",
	'groups:joinrequest:remove:check' => "Etes-vous sûr de vouloir supprimer cette demande d'adhésion ?",
	'groups:invite:remove:check' => "Etes-vous sûr de vouloir supprimer cette invitation?",
	'groups:invite:body' => "Bonjour %s,

Vous avez été invité(e) à rejoindre le groupe '%s' cliquez sur le lien ci-dessous pour confirmer:

%s",

	'groups:welcome:subject' => "Bienvenue dans le groupe %s !",
	'groups:welcome:body' => "Bonjour %s!

Vous êtes maintenant membre du groupe '%s' ! Cliquez le lien ci-dessous pour commencer à participer !

%s",

	'groups:request:subject' => "%s a demandé une adhésion à %s",
	'groups:request:body' => "Bonjour %s,

%s a demandé à rejoindre le groupe '%s', cliquez le lien ci-dessous pour voir son profil :

%s

ou cliquez le lien ci-dessous pour confirmer son adhésion :

%s",

	/*
		Forum river items
	*/

	'river:create:group:default' => "%s a créé le groupe %s",
	'river:join:group:default' => "%s a rejoint le groupe %s",
	'forumtopic:river:create' => "a ajouté un nouveau sujet de discussion",
	'groups:river:reply' => "a répondu sur le sujet de discussion",

	'groups:nowidgets' => "Aucun widget n'ont été défini pour ce groupe.",


	'groups:widgets:members:title' => "Membres du groupe",
	'groups:widgets:members:description' => "Lister les membres d'un groupe.",
	'groups:widgets:members:label:displaynum' => "Lister les membres d'un groupe.",
	'groups:widgets:members:label:pleaseedit' => "Merci de configurer ce widget.",

	'groups:widgets:entities:title' => "Objets dans le groupe",
	'groups:widgets:entities:description' => "Lister les objets enregistré dans ce groupe",
	'groups:widgets:entities:label:displaynum' => "Lister les objets d'un groupe.",
	'groups:widgets:entities:label:pleaseedit' => "Merci de configurer ce widget.",

	'groups:forumtopic:edited' => "Sujet du forum modifié avec succés.",

	'groups:allowhiddengroups' => "Voulez-vous permettre les groupes privés (invisibles)?",

	/**
	 * Action messages
	 */
	'group:deleted' => "Contenus du groupe et groupe supprimés",
	'group:notdeleted' => "Le groupe n'a pas pu être supprimé",

	'group:notfound' => "Impossible de trouver le groupe",
	'grouppost:deleted' => "La publication dans le groupe a été effacée",
	'grouppost:notdeleted' => "La publication dans le groupe n'a pas pu être effacée",
	'groupstopic:deleted' => "Sujet supprimé",
	'groupstopic:notdeleted' => "Le sujet n'a pas pu être supprimé",
	'grouptopic:blank' => "Pas de sujet",
	'grouptopic:notfound' => "Le sujet n'a pu être trouvé",
	'grouppost:nopost' => "Pas d'articles",
	'groups:deletewarning' => "Etes-vous sur de vouloir supprimer ce groupe ? Cette action est irréversible !",

	'groups:invitekilled' => "L'invitation a été supprimée",
	'groups:joinrequestkilled' => "La demande d'adhésion a été supprimée.",

	// ecml
	'groups:ecml:discussion' => "Discussions de groupe",
	'groups:ecml:groupprofile' => "Les profils de groupe",


/**
 * Tag cloud English language file
 */
	'tagcloud:widget:title' => "Nuage de Tags",
	'tagcloud:widget:description' => "Nuage de tags",
	'tagcloud:widget:numtags' => "Nombre de tags à afficher",



/**
 * Elgg invite language file
 *
 * @package ElggInviteFriends
 */
	'friends:invite' => "Inviter du monde",

	'invitefriends:registration_disabled' => "L'enregistrement des nouveaux utilisateurs a été désactivé sur ce site, vous ne pouvez pas inviter de nouveaux utilisateurs.",

	'invitefriends:introduction' => "Invitez du monde à rejoindre sur le réseau ! Entrez leurs adresses mail ci-dessous (une par ligne) :",
	'invitefriends:message' => "Ecrivez le message qu'ils reçevront avec votre invitation :",
	'invitefriends:subject' => "Invitation à rejoindre %s",

	'invitefriends:success' => "Vos contacts ont été invités.",
	'invitefriends:invitations_sent' => "Invitation envoyé: %s. Il ya eu les problèmes suivants :",
	'invitefriends:email_error' => "Les invitations ont été envoyées, mais l'adresse suivante comporte des erreurs: %s",
	'invitefriends:already_members' => "Les invités suivants sont déja membres: %s",
	'invitefriends:noemails' => "Aucune adresse email a été entrée",

	'invitefriends:message:default' => "
Salut,

Viens voir http://ggouv.fr C'est un nouveau réseau totalement subversif, et enfin vraiment démocratique !",

	'invitefriends:email' => "
Vous avez été invité à rejoindre %s par %s, qui a ajouté le message suivant :

%s

Pour vous inscrire, cliquez sur le lien suivant :

	%s

",


/**
 * Elgg log browser plugin language pack
 *
 * @package ElggLogBrowser
 */
	'admin:utilities:logbrowser' => "Connection aux journaux",
	'logbrowser' => "Visualiseur de journal",
	'logbrowser:browse' => "Visualiser les journaux système",
	'logbrowser:search' => "Affiner les résultats",
	'logbrowser:user' => "Rechercher par nom d'utilisateur",
	'logbrowser:starttime' => "Heure de début (par exemple 'dernier lundi', 'il y a une heure')",
	'logbrowser:endtime' => "Heure de fin",

	'logbrowser:explore' => "Explorer le journal",

	'logbrowser:date' => "Date et heure",
	'logbrowser:user:name' => "Utilisateur",
	'logbrowser:user:guid' => "Guide de l'utilisateur",
	'logbrowser:object' => "Type de l'objet",
	'logbrowser:object:guid' => "Aide à propos des Objets",
	'logbrowser:action' => "Action",


/**
 * Elgg log rotator language pack.
 *
 * @package ElggLogRotate
 */
	'logrotate:period' => "A quelle fréquence souhaitez-vous archiver les logs du système ?",

	'logrotate:weekly' => "Une fois par semaine",
	'logrotate:monthly' => "Une fois par mois",
	'logrotate:yearly' => "Une fois par an",

	'logrotate:logrotated' => "Rotation du log effectuée\n",
	'logrotate:lognotrotated' => "Erreur lors de la rotation du log\n",

	'logrotate:date' => "Supprimer les journaux archivés plus ancien qu'",

	'logrotate:week' => "une semaine",
	'logrotate:month' => "un mois",
	'logrotate:year' => "une année",

	'logrotate:logdeleted' => "Fichier journal supprimé (fichier log)",
	'logrotate:lognotdeleted' => "Erreur de suppression du journal (fichier log)",


/**
 * Email user validation plugin language pack.
 *
 * @package Elgg.Core.Plugin
 * @subpackage ElggUserValidationByEmail
 */
	'admin:users:unvalidated' => "Invalidées",

	'email:validate:subject' => "%s, confirmez votre adresse email !",
	'email:validate:body' => "Bonjour %s,

Plus qu'un clic et vous êtes sur %s !

C'est bien mon adresse email, je confirme :
%s

Si vous ne pouvez pas cliquer sur le lien, faites un copier/coller dans votre navigateur...
",
	'email:confirm:success' => "Vous avez validé votre adresse de courriel !",
	'email:confirm:fail' => "Votre adresse de courriel n'a pu être vérifiée...",

	'uservalidationbyemail:registerok' => "<p>Pour activer votre compte, veuillez confirmer votre adresse email en cliquant sur le lien qui vient de vous être envoyé (si vous ne recevez rien, veuillez vérifier votre dossier Spam).</p><p>Merci de vous être inscrit !</p>",
	'uservalidationbyemail:login:fail' => "Votre compte n'est pas validé, par conséquent la tentative de connexion a échoué. Un autre email de validation a été envoyé.",

	'uservalidationbyemail:admin:no_unvalidated_users' => "Aucun utilisateurs non-validés.",

	'uservalidationbyemail:admin:unvalidated' => "Invalidés",
	'uservalidationbyemail:admin:user_created' => "%s enregistré",
	'uservalidationbyemail:admin:resend_validation' => "Renvoyer la validation",
	'uservalidationbyemail:admin:validate' => "Valider",
	'uservalidationbyemail:admin:delete' => "Supprimer",
	'uservalidationbyemail:confirm_validate_user' => "Valider %s ?",
	'uservalidationbyemail:confirm_resend_validation' => "Renvoyer la validation email à %s?",
	'uservalidationbyemail:confirm_delete' => "Supprimer %s?",
	'uservalidationbyemail:confirm_validate_checked' => "Valider les utilisateurs cochés ?",
	'uservalidationbyemail:confirm_resend_validation_checked' => "Renvoyer la validation aux utilisateurs cochés ?",
	'uservalidationbyemail:confirm_delete_checked' => "Supprimer les utilisateurs cochés ?",
	'uservalidationbyemail:check_all' => "Tous",

	'uservalidationbyemail:errors:unknown_users' => "Utilisateurs inconnus",
	'uservalidationbyemail:errors:could_not_validate_user' => "Impossible de valider l'utilisateur.",
	'uservalidationbyemail:errors:could_not_validate_users' => "Impossible de valider tout les utilisateurs cochés.",
	'uservalidationbyemail:errors:could_not_delete_user' => "Impossible de supprimer l'utilisateur.",
	'uservalidationbyemail:errors:could_not_delete_users' => "Impossible de supprimer tout les utilisateurs cochés.",
	'uservalidationbyemail:errors:could_not_resend_validation' => "Impossible de renvoyer la demande de validation.",
	'uservalidationbyemail:errors:could_not_resend_validations' => "Impossible de renvoyer toutes les demandes de validation aux utilisateurs cochés.",

	'uservalidationbyemail:messages:validated_user' => "Utilisateur validé.",
	'uservalidationbyemail:messages:validated_users' => "Tout les utilisateurs cochés validés.",
	'uservalidationbyemail:messages:deleted_user' => "Utilisateur supprimé.",
	'uservalidationbyemail:messages:deleted_users' => "Tout les utilisateurs cochés supprimé.",
	'uservalidationbyemail:messages:resent_validation' => "Demande de validation renvoyée.",
	'uservalidationbyemail:messages:resent_validations' => "Demandes de validation renvoyées à tout les utilisateurs cochés.",


/*
 * Notification plugin
 */
	'friends:all' => "Tous les contacts",

	'notifications:subscriptions:personal:description' => "Recevoir des notifications quand des actions concernent vos contenus",
	'notifications:subscriptions:personal:title' => "Notifications personnelles",

	'notifications:subscriptions:friends:title' => "Abonnements",
	'notifications:subscriptions:friends:description' => "Ce qui suit est une collection automatique faite à partie de vos abonnements. Pour recevoir les mises à jour choisissez ci-dessous. Cela affectera, pour les utilisateurs correspondant, le panneau principal des paramètres de notifications, en bas de la page.",
	'notifications:subscriptions:collections:edit' => "Pour éditer vos notifications d'accès partagés, cliquez ici.",

	'notifications:subscriptions:changesettings' => "Notifications",
	'notifications:subscriptions:changesettings:groups' => "Notifications pour les groupes",
	'notification:method:email' => "Email",

	'notifications:subscriptions:title' => "Notifications par utilisateur",
	'notifications:subscriptions:description' => "Pour recevoir des notifications de vos abonnements (sur une base individuelle) quand ils créent de nouveaux contenus, trouvez-les ci-dessous, et choisissez le mode de notifications que vous souhaitez utiliser.",

	'notifications:subscriptions:groups:description' => "Pour recevoir des notifications lorsque de nouveaux contenus sont ajoutés à un groupe auquel vous appartenez, sélectionnez-les ci-dessous, et choisissez le mode de notifications que vous souhaitez utiliser.",

	'notifications:subscriptions:success' => "Vos paramètres de notifications ont bien été enregistrés.",

		/* @package html_email_handler */
		'html_email_handler' => "HTML email Handler",

		'html_email_handler:theme_preview:menu' => "HTML notification",

		// settings
		'html_email_handler:settings:notifications:description' => "When you enable this option all notifications to the users of your site will be in HTML format.",
		'html_email_handler:settings:notifications' => "Use as default email notification handler",
		'html_email_handler:settings:notifications:subtext' => "This will send all outgoing emails as HTML mails",

		'html_email_handler:settings:sendmail_options' => "Additional parameters for use with sendmail (optional)",
		'html_email_handler:settings:fallback_email' => 'Set a dedicated From email address for your site, something like notifications@yoursite.com',
		'html_email_handler:settings:sendmail_options:description' => "Here you can configure additional setting when using sendmail, for example -f%s (to better prevent mails being marked as spam)",
		'html_email_handler:settings:fallback_email_options:description' => 'It is bad business practice not to provide a real email address that people can respond to. Here is an article discussing this topic <a href="http://www.netmagazine.com/opinions/why-noreply-email-addresses-are-bad-business">Why noreply addresses are bad for business</a>, therefore, it is recommended that you provide your users with a valid email which they can respond to.',

		// notification body
		'html_email_handler:notification:footer:settings' => "%sConfigurez vos paramètres de notifications%s",
		'html_email_handler:notification:footer:advice' => "Ceci est un mail automatique de notification, inutile d'y répondre directement.",


/*
 * Search plugin
 */
	'search:enter_term' => "Entrer un terme de recherche :",
	'search:no_results' => "Aucun résultat.",
	'search:matched' => "Correspondant: ",
	'search:results' => "Résultats pour %s",
	'search:no_query' => "Veuillez entrer une requête de recherche.",
	'search:search_error' => "Erreur",

	'search:more' => "+%s plus %s",

	'search_types:tags' => "Tags",

	'search_types:comments' => "Commentaires",
	'search:comment_on' => "Commentaire sur '%s'",
	'search:comment_by' => "par",
	'search:unavailable_entity' => "Entité indisponible",







/**
 *	Elgg-ggouv_template plugin
 *	@package elgg-ggouv_template
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ggouv/elgg-ggouv_template
 *
 *	Elgg-ggouv_template English language
 *
 */

	/**
	 * Home
	 */
	'ggouv:register:contamined' => "&nbsp;&nbsp;&nbsp;&nbsp;Je suis<br>contaminé.<br>Je veux<br>&nbsp;&nbsp;&nbsp;&nbsp;participer !",
	'ggouv:register:wannaplay' => "&nbsp;&nbsp;&nbsp;&nbsp;Tout ça<br>me plait.<br>Je veux<br>&nbsp;&nbsp;&nbsp;&nbsp;jouer !",
	'ggouv:home:objectives' => "Ce que veulent les gitoyens",
	'ggouv:share' => "Partager",

	/**
	 * Sidebar
	 */
	'back:to:top' => "Remonter en haut",

	/**
	 * topbar
	 */
	'my_wiki_pages' => "Mes pages",
	'my_board' => "Ma board",
	'my_pads' => "Mes pads",
	'my_ideas' => "Mes idées",
	'my_blog' => "Mon blog",

	/**
	 * Tour
	 */
	'help:start-tour' => "Démarrer la visite guidée",
	'help:no-start-tour' => "Je ferai la visite plus tard...",

	/**
	 * Comment
	 */
	'comment:error'  =>  "Erreur, impossible d'enregistrer le commentaire",
	'comment:edited' => "Commentaire modifié.",
	'comment:edit' => "Modifier le commentaire",
	'comment:mention' => "Mentionner l'auteur<br>(et citer le texte sélectionné)",

	/**
	 * Members
	 */
	'gitoyens' => "gitoyens",
	'members:all' => 'Tous les membres',
	'members:label:newest' => 'Nouveaux',
	'members:label:popular' => 'Les plus suivis',
	'members:label:random' => "Au hasard",
	'members:label:online' => 'En ligne',
	'members:searchname' => 'Chercher un membre par son nom',
	'members:searchtag' => 'Chercher un membre par compétence',
	'members:title:searchname' => "Résultat de recherche d'un utilisateur au nom de %s",
	'members:title:searchtag' => 'Résultat de recherche pour une compétence de %s',
	'members:title:followers' => "Les personnes qui suivent %s",
	'members:title:following' => "Les personnes que suit %s",
	'friends:following' => "Abonnements",
	'friends:my:following' => "Mes abonnements",
	'friends:title:following' => "Les personnes dont vous suivez l'activité",
	'friends:followers' => "Abonnés",
	'friends:my:followers' => "Mes abonnés",
	'friends:title:followers' => "Les personnes qui vous suivent",

	/**
	 * Groups
	 */
	'groups:summary:members' => "Membres dans<br/>ce groupe",
	'groups:summary:created' => "Créé %s",
	'groups:metagroup' => "Méta-groupe",
	'groups:typogroup' => "Typo-groupe",
	'groups:localgroup' => "Groupe local",
	'groups:metagroups' => "Méta groupes",
	'groups:typogroups' => "Typo groupes",
	'groups:localgroups' => "Groupes locaux",
	'groups:localgroup:departement' => "Département",
	'groups:description:show_more' => "Afficher plus",
	'groups:description:show_less' => "Afficher moins",

	"relatedgroups" => 'Groupes en relation',
	"relatedgroups:more" => 'Voir tous les groupes en relations',
	"relatedgroups:owned" => 'Groupes en relation avec %s',
	"relatedgroups:in_frontpage" => 'Afficher les groupes en relation sur la page du groupe',
	"relatedgroups:manage" => 'Gérer les groupes en relations',
	"relatedgroups:related" => 'Groupes en relations',
	"relatedgroups:add:label" => "Écrire le nom du groupe",
	"relatedgroups:addurl:label" => "Copier l'URL du groupe ici",
	"relatedgroups:add:button" => 'Mettre en relation',
	"relatedgroups:dontwork" => "Ça ne marche pas ?",
	"relatedgroups:unrelated" => 'Défaire la relation entre les groupes',
	"relatedgroups:nopermissons" => "Vous n'avez pas la permisson de modifier les groupes en relation",
	"relatedgroups:add:error" => 'Il y a une erreur. Avez-vous correctement inscrit le groupe ?',

	/**
	 * Forms
	 */
	'ggouv:search:localgroups' => "Cherchez un groupe local par son code postal ou son nom :",
	'ggouv:search:localgroups:notfound' => "La commune n'a pas été trouvée.",
	'xoxco:removing_tag' => "Enlever ce mot-clé",
	'xoxco:input:default' => "Tapez ↲ ou , pour séparer les mots-clés",
	'autocomplete:placeholder' => "Entrez le nom",
	'autocomplete:placeholder:all' => "Entrez le nom",
	'autocomplete:placeholder:groups' => "Entrez le nom du groupe",
	'autocomplete:placeholder:users' => "Entrez le nom du gitoyen",
	'forms:not_valid' => "Le formulaire n'est pas valide !",
	'forms:required' => "Ce champ est obligatoire !",
	'forms:remote' => "Ce champ n'est pas valide.",
	'forms:url' => "Veuillez entrer une URL valide.",
	'forms:date' => "Veuillez entrer une date valide.",
	'forms:dateISO' => "Veuillez entrer une date valide (ISO).",
	'forms:number' => "Veuillez entrer un nombre valide.",
	'forms:digits' => "Entrez seulement des chiffres !",
	'forms:minlength' => "Entrez au moins {0} caractères !",
	'forms:maxlength' => "Entrez {0} caractères maximum !",

	/*
	 * Menus
	 */
	'menu:shortlink' => "Partager",
	'thewire:put_shortlink_in_wire' => "Ajouter le lien dans le lanceur",
	'thewire:put_title_shortlink_in_wire' => "Ajouter le titre et le lien",
	'share:on' => "Partager sur",
	'ggouvlet:install' => "Installer le ggouvlet",

	/**
	 * System message
	 */
	'groups:error:more_five_groups' => "Vous ne pouvez pas créer plus de 5 groupes.",

	/**
	 * Footer
	 */
	'ggouv_template:home' => "Accueil",
	'ggouv_template:about' => "A propos",
	'ggouv_template:legal_mentions' => "Mentions légales",
	'ggouv_template:conditions' => "Conditions d'utilisations",
	'ggouv_template:privacy' => "Confidentialité",
	'ggouv_template:assembly' => "Assemblée",
	'ggouv_template:blog' => "Blog",
	'ggouv_template:dev:group_of_help' => "Groupe d'aide",
	'ggouv_template:dev:wiki_of_help' => "Toute l'aide",
	'ggouv_template:dev:faq_of_help' => "Foire aux questions",
	'ggouv_template:spotlight:contact' => "Contact",
	'ggouv_template:contact:mail' => "Mail",
	'ggouv_template:contact:irc' => "Salon IRC",
	'ggouv_template:spotlight:dev' => "Développement",
	'ggouv_template:dev:repo' => "Dépôt Github",
	'ggouv_template:dev:group_of_dev' => "Groupe des développeurs",
	'ggouv_template:dev:ideas_of_dev' => "Suggérer une idée",
	'ggouv_template:dev:bugs_of_dev' => "Signaler un bug",
	'ggouv_template:spotlight:stats' => "Statistiques",


	/**
	 * Plugin settings and usersettings
	 */
	'ggouv_template:markdown_wiki_page_for_home' => "GUID de la page d'accueil",
	'ggouv_template:group_of_help' => "URL du groupe d'aide",
	'ggouv_template:wiki_of_help' => "URL du wiki du groupe d'aide",
	'ggouv_template:faq_of_help' => "URL de la faq de l'aide",
	'ggouv_template:group_of_dev' => "URL du groupe des développeurs",
	'ggouv_template:ideas_of_dev' => "URL du remue-méninge du groupe de développement",
	'ggouv_template:bugs_of_dev' => "URL de la liste des bugs de la board des développeurs",
	'ggouv_template:bot_string' => "Bot avec les droits administrateurs",
	'ggouv_template:piwik_tracker' => "Adresse du trackeur de piwik (sans http://)",

	'ggouv_template:tiny_ownerblock' => "Afficher seulement les icônes dans le menu des groupes",

	/**
	 *	River
	 */
	'river:ingroup:create:object:idea' => "dans le groupe %s",
	'river:ingroup:comment:object:idea' => "dans le groupe %s",
	'river:ingroup:create:object:markdown_wiki' => "dans le groupe %s",
	'river:ingroup:comment:object:candidat' => "pour le groupe %s",
	'river:ingroup:create:object:mandat' => "pour le groupe %s",
	'river:ingroup:create:object:question' => "au groupe %s",
	'river:ingroup:create:object:bookmarks' => "dans le groupe %s",
	'river:ingroup:create:object:pad' => "dans le groupe %s",

	'river_elected_more_message:first_random' => "<br/><span class='elgg-subtext'>Tirage au sort lancé automatiquement pour la première élection.</span>",

	/*
	 * Other plugins
	 */
	'bookmarks:wiki_of_this_site' => "Page wiki sur %s",

	/*
	 * First login welcome message + automaticly created list and cards
	 */
	'signup:welcomemessage:location_change' => "Info : la localité que vous avez saisie n'existe pas dans ggouv. %s a été sélectionné à la place de %s.",
	'signup:welcomemessage:title' => "Bienvenue sur Ggouv !",
	'signup:welcomemessage:body' => "<p>Félicitation, vous êtes maintenant un «gitoyen».</p>
	<p>Vous avez envie de changer les choses ? Expérimenter de nouvelles manières de s'organiser ? Vous avez plein d'idées ? Vous êtes au bon endroit !</p>
	<p>Ggouv.fr est une expérience démocratique, un mouvement, une tentative de faire émerger quelque chose de nouveau...<br/>Et cela ne tient qu'à vous pour que cette chose grandisse, alors n'hésitez pas à vous exprimer !</p>
	<p>La plateforme est encore en béta et des corrections et ajustements sont en cours. Si vous trouvez un bug, quelque chose à corriger, informez l'équipe des développeurs.</p>
	<p>Vous pouvez démarrer la viste guidée maintenant, ou la faire plus tard depuis le menu aide (la bouée en bas à gauche).</p>
	<p>Amusez-vous bien !</p>
	",
	'deck_river:signup:list' => "À faire",
	'deck_river:signup:card:title:0' => "Lire l'aide",
	'deck_river:signup:card:description:0' => "L'aide se trouve [ici](%s).  \nSi vous êtes perdu, vous pouvez demander de l'aide à tout moment en écrivant dans le lanceur de message (dans le bandeau bleu horizontal en haut) votre question en mentionnant le groupe !aide. Exemple :  \n> !aide Comment s'inscrire à un groupe ?\n\nQuelqu'un vous répondra dès qu'il aura vu votre question.\n\nVous pouvez aussi [chercher](%s) si votre question n'a pas déjà été posée...",
	'deck_river:signup:card:title:1' => "Compléter mon profil",
	'deck_river:signup:card:description:1' => "On a envie d'en savoir un peu plus sur vous !  \nPar soucis de transparence, et si vous voulez que l'on s'intéresse à vous, il est plutôt conseillé de compléter [votre profile](%s).\n\nVous pouvez le compléter [ici](%s).",
	'deck_river:signup:card:title:2' => "Voter pour les objectifs communs",
	'deck_river:signup:card:description:2' => "Vous êtes maintenant un «gitoyen» !  \nAfin de déterminer les grands objectifs du réseau, vous pouvez [voter pour les idées](%s) dont vous souhaitez que l'on s'organise pour les concrétiser ensemble.",


	/*
	 * Widgets
	 */
	'widget:sort_items:last_action' => "Plus actif",
	'widget:sort_items:creation' => "Date de création",
	'widget:sort_items:abc' => "Ordre alphabétique",
	'widgets:size_items' => "Taille",
	'widget:size_items:small' => "Normal",
	'widget:size_items:tiny' => "Petit",
	'widget:size_items:small_image' => "Image seulement",
	'widget:size_items:tiny_image' => "Petite image",
	'slidr:widget:group:owned' => "Afficher les groupes que j'ai créé",
	'slidr:widget:group:memberof' => "Afficher les groupes dont je suis membre",
	'groups:favorites' => "Groupes favoris",
	'search:group' => "Chercher un groupe",
	'dashboard:widget:owned_groups:nogroup' => "Vous n'avez pas encore créé de groupe.",
	'dashboard:widget:memberof_groups:nogroup' => "Allez sur la page affichant <a href=\"%s\">tous les groupes</a> et rejoignez les groupes qui vous plaisent !",
	'dashboard:widget:favorite_groups:nogroup' => "Glissez un groupe ici pour l'ajouter.",

);

add_translation("fr", $french);
