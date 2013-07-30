<?php

	/**
	* Elgg webinar plugin language pack
	*
	* @package ElggGroups
	**/

	$french = array (
	

		'item:object:webinar' => "Webinar",
		'webinar:access_id' => 'Accès',
		'webinar:adminPwd' => 'Mot de passe administrateur',
		'webinar:attendee:title' => "Les participants du webinar",
		'webinar:default:adminPwd' => 'admin',
		'webinar:default:description' => '',
		'webinar:default:title' => 'Webinar du groupe %s',
		'webinar:default:userPwd' => 'user',
		'webinar:default:welcome' => 'Bienvenue au webinar du groupe %s',
		'webinar:delete:success' => 'Webinar supprimé',
		'webinar:description' => 'Description',
		'webinar:edit:save' => 'Enregistrer',
		'webinar:enable' => 'Activer les webinars',
		'webinar:group:menu:new' => 'Créer un webinar',
		'webinar:index'  => 'Tous les webinars du groupe %s',
		'webinar:isDone' => "Le webinar est terminé",
		'webinar:list:attendee' => "Les participants",
		'webinar:list:registered' => "Les inscrits",
		'webinar:logoutURL' => 'URL de retour de webinar',
		'webinar:menu:attend' => "Rejoindre",
		'webinar:menu:delete' => 'Supprimer',
		'webinar:menu:edit' => 'Editer',
		'webinar:menu:new' => 'Créer un nouveau webinar',
		'webinar:menu:start' => 'Démarrer',
		'webinar:menu:stop' => 'Stopper',
		'webinar:menu:subscribe' => "S'inscrire",
		'webinar:menu:unsubscribe' => "Se désinscrire",
		'webinar:menu:view' => "Voir les webinar du groupe",
		'webinar:new' => 'Nouveau webinar',
		'webinar:new:river' => 'Nouveau webinar  dans le groupe %s',
		'webinar:notify:new' => '[webinar]',
		'webinar:notrunning' => "PB le webinar n'existe pas sur le serveur",
		'webinar:profilegroup' => 'Webinars du groupe',
		'webinar:registered:title' => "Les inscrits au webinar",
		'webinar:salt' => 'Security Salt du serveur BigBlueButton',
		'webinar:serverURL' => 'URL du serveur BigBlueButton',
		'webinar:settings' => "Erreur : demander à l'admin de vérifier les settings du plugin",
		'webinar:slot' => " Si oui, choisir un créneau libre : ",
		'webinar:slot:default' => "Créer un rendez-vous dans l'agenda ?",
		'webinar:start:failed' => 'Webinar action start failed',
		'webinar:start:running' => 'Le webinar est déjà lancé',
		'webinar:start:salterror' => 'Erreur de checksum. le security Salt est il correct ?',
		'webinar:start:timeout' => "Impossible de joindre le serveur BBB. vérifier l'url PUIS si le service BigBlueButton est démarré.",
		'webinar:status' => 'état',
		'webinar:status:cancel' => "annulé",
		'webinar:status:done' => "terminé",
		'webinar:status:running' => "en cours",
		'webinar:status:title' => "Le webinar est ",
		'webinar:status:upcoming' => "à venir",
		'webinar:stop:failed' => 'Webinar action stop failed',
		'webinar:stop:norunning' => "le webinar n'est pas démarré sur le serveur",
		'webinar:subscribe:duplicate' => "Vous avez déjà fait cette action",
		'webinar:subscribe:success' => "Votre inscription est enregistrée",
		'webinar:tags' => 'Mots clés séparés par des virgules',
		'webinar:title' => 'Titre',
		'webinar:unsubscribe:impossible' => "Vous n'etiez pas inscrit",
		'webinar:unsubscribe:success' => "Vous êtes bien désinscrit",
		'webinar:userPwd' => 'Mot de passe utilisateur',
		'webinar:welcomeString' => "Message d'accueil",
		'webinar:write_access_id' => 'Accès en écriture',
		'webinar:settings:help:serverSalt' => 'Par exemple : 667074052cc5e0b27d036b00fd7c7c3c',
		'webinar:settings:help:serverURL' => 'par exemple : http://d2toast.inrialpes.fr/bigbluebutton/',
		'webinar:settings:label:server' => 'Serveur Big Blue Button',
		'webinar:settings:label:serverSalt' => 'Security Salt',
		'webinar:settings:label:serverURL' => 'URL',
		'webinar:river:create' => "%s a créé le webinar",
		'webinar:river:start' => "Le webinar %s vient de démarrer !!",
		'webinar:river:registered:create' => "%s s'est inscrit au webinar",
		'webinar:river:attendee:create' => "%s participe au webinar",
		'webinar:sms' => " a créé un webinar intitulé ",
		'webinar:tab' => 'Webinars',
		'webinar:none' => 'Aucun webinar pour le moment',
	
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
