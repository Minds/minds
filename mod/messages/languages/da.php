<?php
/**
* Elgg Messages Danish language file
* 
*/

$danish = array(

/**
 * Menu items and titles
 */
 
	'messages' => "Beskeder",
	'messages:back' => "Tilbage til beskeder",
	'messages:user' => "%s's indbakke",
	'messages:posttitle' => "%s's beskeder: %s",
	'messages:inbox' => "Indbakke",
	'messages:send' => "Send en besked",
	'messages:sent' => "Sendte beskeder",
	'messages:message' => "Besked",
	'messages:title' => "Emne",
	'messages:to' => "Til",
	'messages:from' => "Fra",
	'messages:fly' => "Send",
	'messages:replying' => "Besked som svar til",
	'messages:inbox' => "Indbakke",
	'messages:sendmessage' => "Send en besked",
	'messages:compose' => "Opret en besked",
	'messages:add' => "Opret en besked",
	'messages:sentmessages' => "Sendte beskeder",
	'messages:recent' => "Seneste beskeder",
	'messages:original' => "Original besked",
	'messages:yours' => "Din besked",
	'messages:answer' => "Svar",
	'messages:toggle' => 'Marker alle',
	'messages:markread' => 'Marker som læst',
	'messages:recipient' => 'Vælg en modtager&hellip;',
	'messages:to_user' => 'To: %s',
		
	'messages:new' => 'Ny besked',

	'notification:method:site' => 'Beskeder',

	'messages:error' => 'Der opstod et problem med at gemme din besked. Prøv venligst igen.',

	'item:object:messages' => 'Beskeder',

/**
 * Status messages
 */

	'messages:posted' => "Din besked blev sendt.",
	'messages:success:delete:single' => "Din besked blev slettet.",
	'messages:success:delete' => 'Dine beskeder blev slettet.',
	'messages:success:read' => 'Beskeder markeret som læst',
	'messages:error:messages_not_selected' => 'Ingen beskeder valgt',
	'messages:error:delete:single' => 'Kunne ikke slette beskeden',

/**
 * Email messages
 */

	'messages:email:subject' => 'Du har en ny besked!',
	'messages:email:body' => "Du har en ny besked fra %s. Den lyder:

	
%s


Klik her for at se dine beskeder:

%s

Klik her for at sende %s en besked:

%s

Du kan ikke svare via denne mail.",

/**
 * Error messages
 */

	'messages:blank' => "Beklager, du skal skrive noget i beskedfeltet, før den kan gemmes.",
	'messages:notfound' => "Beklager, vi kunne ikke finde den specificerede besked.",
	'messages:notdeleted' => "Beklager, beskeden kunne ikke slettes.",
	'messages:nopermission' => "Du har ikke tilladelse til at ændre beskeden.",
	'messages:nomessages' => "Der er ingen beskeder at vise.",
	'messages:user:nonexist' => "Modtageren kunne ikke findes i databasen.",
	'messages:user:blank' => "Du har ikke valgt nogen at sende til.",

	'messages:deleted_sender' => 'Slettet bruger',
);

add_translation("da",$danish);

?>