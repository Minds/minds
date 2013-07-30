<?php

	$german = array(
	
		/**
		 * Menu items and titles
		 */
	
			'poll' => "Abstimmen",
            'polls:add' => "Neue Abstimmung",
			'polls' => "Voting",
			'polls:votes' => "Stimmen",
			'polls:user' => "%s's abstimmen",
			'polls:group_polls' => "Gruppe Stimmen",
			'polls:group_polls:listing:title' => "%s's Stimmen",
			'polls:user:friends' => "%s's Freunde Abstimmung",
			'polls:your' => "Ihre Stimmen",
			'polls:not_me' => "%s's Stimmen",
			'polls:posttitle' => "%s's Stimmen: %s",
			'polls:friends' => "Freunde Stimmen",
			'polls:not_me_friends' => "%s's Freundes Stimmen",
			'polls:yourfriends' => "Ihre Freunde neuesten Stimmen",
			'polls:everyone' => "Alle Website-Stimmen",
			'polls:read' => "Lesen Sie abstimmen",
			'polls:addpost' => "Erstellen Sie eine Stimme",
			'polls:editpost' => "Bearbeiten einer Abstimmung: %s",
			'polls:edit' => "Bearbeiten einer Abstimmung",
			'polls:text' => "Abstimmen text",
			'polls:strapline' => "%s",			
			'item:object:poll' => 'Stimmen',
			'item:object:poll_choice' => "Vote Entscheidungen",
			'polls:question' => "abstimmen Frage",
			'polls:responses' => "Reaktionszeit Auswahl",
			'polls:results' => "[+] Zeige die Ergebnisse",
			'polls:show_results' => "Ergebnisse anzeigen",
			'polls:show_poll' => "Zeigen abstimmen",
			'polls:add_choice' => "In Reaktion Wahl",
			'polls:delete_choice' => "Löschen Sie diese Wahl",
			'polls:settings:group:title' => "Gruppe Stimmen",
			'polls:settings:group_polls_default' => "ja, standardmäßig",
			'polls:settings:group_polls_not_default' => "ja, standardmäßig deaktiviert",
			'polls:settings:no' => "nein",
			'polls:settings:group_profile_display:title' => "Wenn Gruppe Stimmen aktiviert sind, sollten, wenn Abstimmungen Inhalt in der Gruppe Profile angezeigt werden?",
			'polls:settings:group_profile_display_option:left' => "links",
			'polls:settings:group_profile_display_option:right' => "richtig",
			'polls:settings:group_profile_display_option:none' => "keine",
			'polls:settings:group_access:title' => "Wenn Gruppe Stimmen aktiviert sind, wird die, Umfragen zu erstellen?",
			'polls:settings:group_access:admins' => "Eigentümer und Gruppe nur Administratoren",
			'polls:settings:group_access:members' => "einem Gruppenmitglied",
			'polls:settings:front_page:title' => "Admins können eine Titelseite Umfrage (erfordert Theme-Unterstützung)",
			'polls:none' => "Noch keine Bewertungen vorhanden.",
			'polls:permission_error' => "Sie haben nicht die Berechtigung, diese Abstimmung zu bearbeiten.",
			'polls:vote' => "Abstimmen",
			'polls:login' => "Bitte loggen Sie sich ein, wenn Sie möchten, in dieser Abstimmung stimmen.",
			'group:polls:empty' => "Keine Umfragen",
			'polls:settings:site_access:title' => "Wer kann erstellen siteweiten Stimmen?",
			'polls:settings:site_access:admins' => "Admins nur",
			'polls:settings:site_access:all' => "Jeder angemeldete Benutzer",
			'polls:can_not_create' => "Sie haben keine Berechtigung, um Stimmen zu erstellen.",
			'polls:front_page_label' => "Legen Sie diese Abstimmung auf der Titelseite.",
		/**
	     * poll widget
	     **/
			'polls:latest_widget_title' => "Aktuelle Community Stimmen",
			'polls:latest_widget_description' => "Zeigt die neuesten Stimmen.",
			'polls:my_widget_title' => "Meine Stimmen",
			'polls:my_widget_description' => "Dieses Widget zeigt Ihre Stimmen.",
			'polls:widget:label:displaynum' => "Wie viele Stimmen die Sie anzeigen möchten?",
			'polls:individual' => "Letzte Abstimmung",
			'poll_individual_group:widget:description' => "Anzeige der neuesten Abstimmung für diese Gruppe.",
			'poll_individual:widget:description' => "Zeigen Sie Ihre neuesten Abstimmung",
			'polls:widget:no_polls' => "Es sind keine Stimmen für %s noch.",
			'polls:widget:nonefound' => "Noch keine Bewertungen vorhanden.",
			'polls:widget:think' => "Lassen %s wissen, was Sie denken!",
			'polls:enable_polls' => "Aktivieren Stimmen",
			'polls:group_identifier' => "(in %s)",
			'polls:noun_response' => "Antwort",
			'polls:noun_responses' => "Antworten",
	        'polls:settings:yes' => "ja",
			'polls:settings:no' => "nein",
			
         /**
	     * poll river
	     **/
	        'polls:settings:create_in_river:title' => "Zeigen Sie stimmen Schöpfung in Aktivität Fluss",
			'polls:settings:vote_in_river:title' => "Zeigen Sie stimmen in Abstimmung Aktivität Fluss",
			'river:create:object:poll' => '%s erstellt eine Abstimmung %s',
			'river:vote:object:poll' => '%s abgestimmt %s',
			'river:comment:object:poll' => '%s kommentiert %s',
		/**
		 * Status messages
		 */
	
			'polls:added' => "Your vote was created.",
			'polls:edited' => "Your vote was saved.",
			'polls:responded' => "Vielen Dank für die Beantwortung, wurde Ihre Stimme aufgezeichnet.",
			'polls:deleted' => "Ihre Stimme wurde erfolgreich gelöscht.",
			'polls:totalvotes' => "Gesamtzahl der Stimmen: ",
			'polls:voted' => "Ihre Stimme wurde gezählt. Vielen Dank für Ihre Stimme.",
			
	
		/**
		 * Error messages
		 */
	
			'polls:save:failure' => "Ihre Stimme konnte nicht gespeichert werden. Bitte versuchen Sie es erneut.",
			'polls:blank' => "Es tut uns leid: Sie müssen sowohl in der Frage und Antworten füllen, bevor Sie eine Abstimmung machen können.",
			'polls:novote' => "Es tut uns leid: Sie brauchen, um eine Option, um in dieser Abstimmung Abstimmung wählen.",
			'polls:notfound' => "Es tut uns leid, wir konnten nicht finden, die angegebene Stimme.",
			'polls:nonefound' => "Keine Umfragen wurden gefunden %s",
			'polls:notdeleted' => "Es tut uns leid, wir konnten nicht gelöscht werden dieses Votum.",
		
		/**
		 * Filters
		 */
		 	'polls:top' => 'Spitze',
		 	'polls:history' => 'Geschichte',
	);
					
	add_translation("de",$german);

?>
