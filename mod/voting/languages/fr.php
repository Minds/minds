<?php

	$french = array(
	
		/**
		 * Menu items and titles
		 */
	
			'poll' => "Voter",
            'polls:add' => "nouveau vote",
			'polls' => "Vote",
			'polls:votes' => "votes",
			'polls:user' => "%s's voter",
			'polls:group_polls' => "Votes du groupe",
			'polls:group_polls:listing:title' => "%s's votes",
			'polls:user:friends' => "%s's Le vote amis",
			'polls:your' => "vos votes",
			'polls:not_me' => "%s's votes",
			'polls:posttitle' => "%s's votes: %s",
			'polls:friends' => "Le vote amis",
			'polls:not_me_friends' => "%s's le vote amis",
			'polls:yourfriends' => "Vos amis derniers votes",
			'polls:everyone' => "Tous les votes du site",
			'polls:read' => "Lire voter",
			'polls:addpost' => "Créez un vote",
			'polls:editpost' => "Modifier un vote: %s",
			'polls:edit' => "Modifier un vote",
			'polls:text' => "vote texte",
			'polls:strapline' => "%s",			
			'item:object:poll' => 'Votes',
			'item:object:poll_choice' => "vote choix",
			'polls:question' => "vote question",
			'polls:responses' => "Les choix de réponse",
			'polls:results' => "[+] Voir les résultats",
			'polls:show_results' => "Voir les résultats",
			'polls:show_poll' => "Afficher voter",
			'polls:add_choice' => "Ajoutez choix de réponse",
			'polls:delete_choice' => "Supprimer ce choix",
			'polls:settings:group:title' => "Votes du groupe",
			'polls:settings:group_polls_default' => "Oui, par défaut",
			'polls:settings:group_polls_not_default' => "oui, désactivé par défaut",
			'polls:settings:no' => "pas",
			'polls:settings:group_profile_display:title' => "Si les votes du groupe sont activés, où devrait le contenu des votes sera affiché dans les profils de groupe?",
			'polls:settings:group_profile_display_option:left' => "à gauche",
			'polls:settings:group_profile_display_option:right' => "droite",
			'polls:settings:group_profile_display_option:none' => "aucun",
			'polls:settings:group_access:title' => "Si les votes du groupe sont activés, qui arrive à créer des sondages?",
			'polls:settings:group_access:admins' => "Les propriétaires de groupes et les administrateurs seulement",
			'polls:settings:group_access:members' => "tout membre du groupe",
			'polls:settings:front_page:title' => "Les administrateurs peuvent définir une page poll avant (nécessite un soutien à thème)",
			'polls:none' => "Pas de votes trouvés.",
			'polls:permission_error' => "Vous n'avez pas la permission de modifier ce vote.",
			'polls:vote' => "Voter",
			'polls:login' => "S'il vous plaît vous connecter si vous souhaitez voter à ce vote.",
			'group:polls:empty' => "aucun sondage",
			'polls:settings:site_access:title' => "Qui peut créer votes échelle du site?",
			'polls:settings:site_access:admins' => "Administrateurs seulement ",
			'polls:settings:site_access:all' => "Tout utilisateur connecté",
			'polls:can_not_create' => "Vous n'avez pas la permission de créer votes.",
			'polls:front_page_label' => "Placez ce vote sur la première page.",
		/**
	     * poll widget
	     **/
			'polls:latest_widget_title' => "Derniers votes de la communauté",
			'polls:latest_widget_description' => "Affiche les votes les plus récentes.",
			'polls:my_widget_title' => "mes votes",
			'polls:my_widget_description' => "Ce widget permet d'afficher vos votes.",
			'polls:widget:label:displaynum' => "Combien de voix que vous souhaitez afficher?",
			'polls:individual' => "dernières vote",
			'poll_individual_group:widget:description' => "Afficher le dernier vote pour ce groupe.",
			'poll_individual:widget:description' => "Affichez votre vote",
			'polls:widget:no_polls' => "Il n'y a pas de votes pour %s encore.",
			'polls:widget:nonefound' => "Pas de votes trouvés.",
			'polls:widget:think' => "Laisser %s savez ce que vous en pensez!",
			'polls:enable_polls' => "Activer votes",
			'polls:group_identifier' => "(à %s)",
			'polls:noun_response' => "réponse",
			'polls:noun_responses' => "réponses",
	        'polls:settings:yes' => "Oui",
			'polls:settings:no' => "pas",
			
         /**
	     * poll river
	     **/
	        'polls:settings:create_in_river:title' => "Afficher voter création en rivière d'activité",
			'polls:settings:vote_in_river:title' => "Afficher voter vote dans la rivière de l'activité",
			'river:create:object:poll' => '%s créé un vote %s',
			'river:vote:object:poll' => '%s voté %s',
			'river:comment:object:poll' => '%s commenté %s',
		/**
		 * Status messages
		 */
	
			'polls:added' => "Votre vote a été créé.",
			'polls:edited' => "Votre vote a été enregistré.",
			'polls:responded' => "Merci d'avoir répondu, votre vote a été enregistré.",
			'polls:deleted' => "Votre vote a été supprimé avec succès.",
			'polls:totalvotes' => "Nombre total de votes: ",
			'polls:voted' => "Votre vote a été enregistré. Merci d'avoir voté.",
			
	
		/**
		 * Error messages
		 */
	
			'polls:save:failure' => "Votre vote n'a pas pu être sauvé. S'il vous plaît essayer de nouveau.",
			'polls:blank' => "Désolé: vous devez remplir à la fois la question et les réponses avant que vous puissiez faire un vote.",
			'polls:novote' => "Désolé: vous devez choisir une option de voter lors de ce vote.",
			'polls:notfound' => "Désolé: on ne pouvait pas trouver le vote spécifié.",
			'polls:nonefound' => "Aucun sondage n'a été trouvé à partir de %s",
			'polls:notdeleted' => "Désolé: on ne pouvait pas supprimer ce vote.",
		
		/**
		 * Filters
		 */
		 	'polls:top' => 'Supérieur',
		 	'polls:history' => 'Histoire',
	);
					
	add_translation("fr",$french);

?>
