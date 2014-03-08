<?php

	/**
	* Elgg webinar plugin language pack
	*
	* @package ElggGroups
	**/

	$french = array (
	

		'item:object:webinar' => "Webinar",
		'gatherings:access_id' => 'Accès',
		'gatherings:adminPwd' => 'Mot de passe administrateur',
		'gatherings:attendee:title' => "Les participants du webinar",
		'gatherings:default:adminPwd' => 'admin',
		'gatherings:default:description' => '',
		'gatherings:default:title' => 'Webinar du groupe %s',
		'gatherings:default:userPwd' => 'user',
		'gatherings:default:welcome' => 'Bienvenue au webinar du groupe %s',
		'gatherings:delete:success' => 'Webinar supprimé',
		'gatherings:description' => 'Description',
		'gatherings:edit:save' => 'Enregistrer',
		'gatherings:enable' => 'Activer les webinars',
		'gatherings:group:menu:new' => 'Créer un webinar',
		'gatherings:index'  => 'Tous les webinars du groupe %s',
		'gatherings:isDone' => "Le webinar est terminé",
		'gatherings:list:attendee' => "Les participants",
		'gatherings:list:registered' => "Les inscrits",
		'gatherings:logoutURL' => 'URL de retour de webinar',
		'gatherings:menu:attend' => "Rejoindre",
		'gatherings:menu:delete' => 'Supprimer',
		'gatherings:menu:edit' => 'Editer',
		'gatherings:menu:new' => 'Créer un nouveau webinar',
		'gatherings:menu:start' => 'Démarrer',
		'gatherings:menu:stop' => 'Stopper',
		'gatherings:menu:subscribe' => "S'inscrire",
		'gatherings:menu:unsubscribe' => "Se désinscrire",
		'gatherings:menu:view' => "Voir les webinar du groupe",
		'gatherings:new' => 'Nouveau webinar',
		'gatherings:new:river' => 'Nouveau webinar  dans le groupe %s',
		'gatherings:notify:new' => '[webinar]',
		'gatherings:notrunning' => "PB le webinar n'existe pas sur le serveur",
		'gatherings:profilegroup' => 'Webinars du groupe',
		'gatherings:registered:title' => "Les inscrits au webinar",
		'gatherings:salt' => 'Security Salt du serveur BigBlueButton',
		'gatherings:serverURL' => 'URL du serveur BigBlueButton',
		'gatherings:settings' => "Erreur : demander à l'admin de vérifier les settings du plugin",
		'gatherings:slot' => " Si oui, choisir un créneau libre : ",
		'gatherings:slot:default' => "Créer un rendez-vous dans l'agenda ?",
		'gatherings:start:failed' => 'Webinar action start failed',
		'gatherings:start:running' => 'Le webinar est déjà lancé',
		'gatherings:start:salterror' => 'Erreur de checksum. le security Salt est il correct ?',
		'gatherings:start:timeout' => "Impossible de joindre le serveur BBB. vérifier l'url PUIS si le service BigBlueButton est démarré.",
		'gatherings:status' => 'état',
		'gatherings:status:cancel' => "annulé",
		'gatherings:status:done' => "terminé",
		'gatherings:status:running' => "en cours",
		'gatherings:status:title' => "Le webinar est ",
		'gatherings:status:upcoming' => "à venir",
		'gatherings:stop:failed' => 'Webinar action stop failed',
		'gatherings:stop:norunning' => "le webinar n'est pas démarré sur le serveur",
		'gatherings:subscribe:duplicate' => "Vous avez déjà fait cette action",
		'gatherings:subscribe:success' => "Votre inscription est enregistrée",
		'gatherings:tags' => 'Mots clés séparés par des virgules',
		'gatherings:title' => 'Titre',
		'gatherings:unsubscribe:impossible' => "Vous n'etiez pas inscrit",
		'gatherings:unsubscribe:success' => "Vous êtes bien désinscrit",
		'gatherings:userPwd' => 'Mot de passe utilisateur',
		'gatherings:welcomeString' => "Message d'accueil",
		'gatherings:write_access_id' => 'Accès en écriture',
		'gatherings:settings:help:serverSalt' => 'Par exemple : 667074052cc5e0b27d036b00fd7c7c3c',
		'gatherings:settings:help:serverURL' => 'par exemple : http://d2toast.inrialpes.fr/bigbluebutton/',
		'gatherings:settings:label:server' => 'Serveur Big Blue Button',
		'gatherings:settings:label:serverSalt' => 'Security Salt',
		'gatherings:settings:label:serverURL' => 'URL',
		'gatherings:river:create' => "%s a créé le webinar",
		'gatherings:river:start' => "Le webinar %s vient de démarrer !!",
		'gatherings:river:registered:create' => "%s s'est inscrit au webinar",
		'gatherings:river:attendee:create' => "%s participe au webinar",
		'gatherings:sms' => " a créé un webinar intitulé ",
		'gatherings:tab' => 'Webinars',
		'gatherings:none' => 'Aucun webinar pour le moment',
	
	/**
		 * River
		 **/
		
		'river:create:object:webinar' => '%s créé un rassemblement%s',
		'river:start:object:webinar' => '%s commencé la collecte %s',
		'river:attendee:object:webinar' => '%s souscrit à la collecte - %s',
		'river:registered:object:webinar' => "%s rejoint l' %s rassemblement",
		
	);
					
	add_translation("fr",$french);

?>
