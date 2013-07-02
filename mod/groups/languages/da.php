<?php
/**
 * Elgg groups plugin Danish language pack
 *
 * @package ElggGroups
 */

$danish = array(

/**
 * Menu items and titles
 */
	'groups' => "Grupper", 
	'groups:owned' => "Grupper jeg styrer", 
	'groups:yours' => "Dine grupper", 
	'groups:user' => "%s's grupper", 
	'groups:all' => "Alle grupper", 
	'groups:add' => "Opret en ny gruppe", 
	'groups:edit' => "Rediger gruppe",
	'groups:delete' => 'Slet gruppe',
	'groups:membershiprequests' => 'Administrer anmodning om deltagelse',
	'groups:invitations' => 'Gruppe invitationer',
			 
	'groups:icon' => "Gruppe ikon (efterlad blank for at beholde det uændret)", 
	'groups:name' => "Gruppe navn", 
	'groups:username' => "Gruppens korte navn (vises i web adressen, brug kun alfanumeriske tegn, dvs. A-Z og 0-9)",
	'groups:description' => "Beskrivelse", 
	'groups:briefdescription' => "Kort beskrivelse", 
	'groups:interests' => "Tags", 
	'groups:website' => "Hjemmeside", 
	'groups:members' => "Medlemmer af gruppen",
	'groups:members:title' => 'Medlemmer af %s',
	'groups:members:more' => "Se alle medlemmer", 
	'groups:membership' => "Medlemsskab", 
	'groups:access' => "Adgangs tilladelser", 
	'groups:owner' => "Ejer", 
	'groups:widget:num_display' => "Antal af grupper der skal vises", 
	'groups:widget:membership' => "Grupper",
	'groups:widgets:description' => "Vis de grupper, som du er medlem af, på din profil", 
	'groups:noaccess' => "Ikke adgang til gruppen",
	'groups:permissions:error' => 'Du har ikke tilladelser til dette',
	'groups:ingroup' => 'i gruppen',
	'groups:cantedit' => 'Du kan ikke redigere denne gruppe', 
	'groups:saved' => "Gruppe gemt",
	'groups:featured' => 'Foretrukne grupper',
	'groups:makeunfeatured' => 'Vælg fra',
	'groups:makefeatured' => 'Vælg til',
	'groups:featuredon' => '%s er nu en foretrukket gruppe',
	'groups:unfeature' => '%s er nu fjernet fra listen med foretrukne',
	'groups:featured_error' => 'Ugyldig gruppe.', 
	'groups:joinrequest' => "Ansøg om medlemsskab", 
	'groups:join' => "Bliv medlem af gruppen", 
	'groups:leave' => "Forlad gruppen", 
	'groups:invite' => "Inviter venner",
	'groups:invite:title' => 'Inviter venner til gruppen', 
	'groups:inviteto' => "Inviter venner til '%s'", 
	'groups:nofriends' => "Ingen af dine venner mangler at blive inviteret til gruppen.",
	'groups:nofriendsatall' => 'Du har ingen venner at invitere!',
	'groups:viagroups' => "via grupper",
	'groups:group' => "Gruppe",
	'groups:search:tags' => "tag",
	'groups:search:title' => "Søg efter grupper tagget med '%s'",
	'groups:search:none' => "Ingen match blev fundet",

	'groups:activity' => "Gruppeaktivitet",
	'groups:enableactivity' => 'Aktiver gruppeaktivitet',
	'groups:activity:none' => "Der er ingen gruppeaktivitet endnu",
			
	'groups:notfound' => "Gruppe ikke fundet",
	'groups:notfound:details' => "Den forespurgte gruppe eksisterer ikke eller du har ikke adgang til den",
	
	'groups:requests:none' => 'Der er ingen udestående anmodninger om medlemskab.',
	
	'groups:invitations:none' => 'Der er ingen udestående invitationer.',
	
	'item:object:groupforumtopic' => "Diskussions emner",
	
	'groupforumtopic:new' => "Nyt diskussions emne",
	
	'groups:count' => "grupper oprettet",
	'groups:open' => "åben gruppe",
	'groups:closed' => "lukket gruppe",
	'groups:member' => "medlemmer",
	'groups:searchtag' => "Søg grupper efter tag",

	'groups:more' => 'Flere grupper',
	'groups:none' => 'Ingen grupper',

		
/*
* Access
*/
	'groups:access:private' => 'Lukket - brugere skal inviteres',
	'groups:access:public' => 'Åben - alle kan deltage',
	'groups:access:group' => 'Kun for medlemmer',
	'groups:closedgroup' => 'Denne gruppe er for medlemmer.',
	'groups:closedgroup:request' => 'Anmod om medlemskab ved at klikke på "Anmod om medlemskab" i menuen.',
	'groups:visibility' => 'Hvem kan se denne gruppe?',
	
/*
Group tools
*/
	'groups:enableforum' => 'Aktiver gruppedebat',
	'groups:yes' => 'ja',
	'groups:no' => 'nej',
	'groups:lastupdated' => 'Sidst opdateret %s af %s',
	'groups:lastcomment' => 'Seneste kommentar %s af %s',

	/*
	Group discussion
	*/
	'discussion' => 'Diskussion',
	'discussion:add' => 'Tilføj diskussionsemne',
	'discussion:latest' => 'Sidste diskussion',
	'discussion:group' => 'Gruppediskussioner',

	'discussion:topic:created' => 'Diskussionsemnet blev oprettet.',
	'discussion:topic:updated' => 'Diskussionsemnet blev opdateret.',
	'discussion:topic:deleted' => 'Diskussionsemne er blevet slettet.',

	'discussion:topic:notfound' => 'Diskussionsemne ikke fundet',
	'discussion:error:notsaved' => 'Kan ikke gemme dette emne',
	'discussion:error:missing' => 'Både titel og besked skal udfyldes',
	'discussion:error:permissions' => 'Du har ikke tilladelse til at udføre denne handling',
	'discussion:error:notdeleted' => 'Kunne ikke slette diskussionsemne',

	'discussion:reply:deleted' => 'Svaret er blevet slettet.',
	'discussion:reply:error:notdeleted' => 'Kunne ikke slette diskussionssvaret',
	
/*
Group forum strings
*/
	 
	'group:replies' => "Svar", 
	'groups:forum:created' => 'Oprettet %s med %d kommentarer',
	'groups:forum:created:single' => 'Oprettet %s med %d kommentar',
	'groups:forum' => 'Discussion',
	'groups:addtopic' => "Tilføj et emne", 
	'groups:forumlatest' => "Forum senest", 
	'groups:latestdiscussion' => "Seneste diskussion",	
	'groups:newest' => 'Seneste',
	'groups:popular' => 'Populær', 
	'groupspost:success' => "Din kommentar er tilføjet", 
	'groups:alldiscussion' => "Seneste diskussion", 
	'groups:edittopic' => "Rediger emne",
	'groups:topicmessage' => "Emne besked", 
	'groups:topicstatus' => "Emne status", 
	'groups:reply' => "Send en kommentar", 
	'groups:topic' => "Emne", 
	'groups:posts' => "Indlæg", 
	'groups:lastperson' => "Sidste person", 
	'groups:when' => "Når", 
	'grouptopic:notcreated' => "Ingen enmer er blevet oprettet.", 
	'groups:topicopen' => "Åben", 
	'groups:topicclosed' => "Lukket",
	'groups:topicresolved' => "Løst", 
	'grouptopic:created' => "Dit emne blev oprettet.", 
	'groupstopic:deleted' => "Emnet er blevet slettet.", 
	'groups:topicsticky' => "Vigtig", 
	'groups:topicisclosed' => "Dette ene er lukket.", 
	'groups:topiccloseddesc' => "Dette emne er nu blevet lukket og kan ikke modtage nye kommenterer.", 
	'grouptopic:error' => "Dit gruppeemne kunne ikke oprettes. Prøv venligst igen eller kontakt systemadministratoren.",
	'groups:forumpost:edited' => "Du har redigeret forumindlægget korrekt.",
	'groups:forumpost:error' => "Der opstod et problem med at redigere forumindlægget.",

	 
	'groups:privategroup' => "Denne gruppe er privat, kræver medlemsskab.", 
	'groups:notitle' => "Grupper skal have en titel", 
	'groups:cantjoin' => "Kunne ikke blive medlem af gruppen",
	'groups:cantleave' => "Kunne ikke forlade gruppen",
	'groups:removeuser' => 'Fjern fra gruppe',
	'groups:cantremove' => 'Kan ikke fjerne bruger fra gruppe',
	'groups:removed' => '%s er fjernet fra gruppen', 
	'groups:addedtogroup' => "Brugeren blev tilføjet til gruppen", 
	'groups:joinrequestnotmade' => "Kunne ikke ansøge om at blive medlem", 
	'groups:joinrequestmade' => "Ansøgning om at blive medlem af gruppen er gennemført", 
	'groups:joined' => "Du er blevet medlem af gruppen!", 
	'groups:left' => "Du er frameldt gruppen!", 
	'groups:notowner' => "Beklager, du ejer ikke denne gruppe.",
	'groups:notmember' => 'Beklager, du er ikke medlem af denne gruppe.', 
	'groups:alreadymember' => "Du er allerede medlem af denne gruppe!", 
	'groups:userinvited' => "Brugeren er blevet inviteret.", 
	'groups:usernotinvited' => "Brugeren kunne ikke inviteres.",
	'groups:useralreadyinvited' => 'Brugeren er allerede blevet inviteret',
	'groups:invite:subject' => "%s du er blevet inviteret til at blive medlem af %s!",
	'groups:updated' => "Seneste kommentar af %s %s",
	'groups:started' => "Startet af %s",
	'groups:joinrequest:remove:check' => 'Er du sikker på, at du vil fjerne denne anmodning om tilmelding?',
	'groups:invite:remove:check' => 'Er du sikker på, at du vil fjerne denne invitation?', 
	'groups:invite:body' => "Hej %s,
	
%s inviterede dig til at være med i '%s' gruppen, klik herunder for at bekræfte:

%s", 
	
	'groups:welcome:subject' => "Velkommen til %s gruppen!", 
	'groups:welcome:body' => "Hej %s!
	
Du er nu medlem af '%s' gruppen! Klik herunder for at begynde med at skrive!

%s",
	 
	'groups:request:subject' => "%s har ønsket at blive medlem af %s", 
	'groups:request:body' => "Hej %s,
	
%s har bedt om at måtte være med i '%s' gruppen, klik nedenfor for at se deres profil:

%s

eller klik nedenfor for at se gruppens anmodningsliste:

%s",

/*
Forum river items
*/

	'river:create:group:default' => '%s oprettede gruppen %s',
	'river:join:group:default' => '%s blev medlem af gruppen %s',
	'river:create:object:groupforumtopic' => '%s tilføjede et nyt diskussionsemne %s',
	'river:reply:object:groupforumtopic' => '%s svarede på diskussionsemnet %s',

	'groups:nowidgets' => "Ingen widgets defineret for denne gruppe.",


	'groups:widgets:members:title' => "Gruppens medlemmer", 
	'groups:widgets:members:description' => "Vis en gruppes medlemmer.",
	'groups:widgets:members:label:displaynum' => "Vis en gruppes medlemmer.", 
	'groups:widgets:members:label:pleaseedit' => "Indstil venligst denne widget.",

	'groups:widgets:entities:title' => "Objekter i gruppen", 
	'groups:widgets:entities:description' => "Vis objekterne gemt i denne gruppe", 
	'groups:widgets:entities:label:displaynum' => "Vis en gruppes objekter", 
	'groups:widgets:entities:label:pleaseedit' => "Indstil venligst denne widget.",

	'groups:forumtopic:edited' => 'Forumemne succesfuldt redigeret.',

	'groups:allowhiddengroups' => 'Vil du tillade private (skjulte) grupper?',
	
/**
* Action messages
*/
	'group:deleted' => 'Gruppe og gruppeindhold slettet',
	'group:notdeleted' => 'Gruppen kunne ikke slettes',

	'group:notfound' => 'Kunne ikke finde gruppen',	
	'grouppost:deleted' => 'Gruppeindlæg slettet korrekt',
	'grouppost:notdeleted' => 'Gruppeindlæg kunne ikke slettes',
	'groupstopic:deleted' => 'Emne slettet',
	'groupstopic:notdeleted' => 'Emne kunne ikke slettes',
	'grouptopic:blank' => 'Ingen emner',
	'grouptopic:notfound' => 'Kunne ikke finde emnet',
	'grouppost:nopost' => 'Tom post',
	'groups:deletewarning' => "Er du sikker på at du vil slette denne gruppe? Du kan ikke gøre det om!",

	'groups:invitekilled' => 'Invitationen er blevet slettet.',	
	'groups:joinrequestkilled' => 'Anmodningen om tilslutning er blevet slettet.',

	// ecml
	'groups:ecml:discussion' => 'Gruppediskussioner',
	'groups:ecml:groupprofile' => 'Gruppeprofiler',
		
);
				
add_translation('da',$danish);

?>