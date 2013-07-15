<?php
/**
* Elgg send a message action page
* 
* @package ElggMessages
*/

$finnish = array(
	/**
	* Menu items and titles
	*/

	'messages' => "Viestit",
	'messages:back' => "takaisin viesteihin",
	'messages:user' => "käyttäjän %s saapuneet viestit",
	'messages:posttitle' => "käyttäjän %s viestit: %s",
	'messages:inbox' => "Saapuneet",
	'messages:send' => "Lähetä",
	'messages:sent' => "Lähetetyt",
	'messages:message' => "Viesti",
	'messages:title' => "Aihe",
	'messages:to' => "käyttäjälle",
	'messages:from' => "Käyttäjältä",
	'messages:fly' => "Lähetä",
	'messages:replying' => "Viesti joka viittaa",
	'messages:inbox' => "Saapuneet",
	'messages:sendmessage' => "Lähetä viesti",
	'messages:compose' => "Kirjoita viesti",
	'messages:add' => "Kirjoita viesti",
	'messages:sentmessages' => "Lähetetyt viestit",
	'messages:recent' => "Uudet viestit",
	'messages:original' => "Alkuperäinen viesti",
	'messages:yours' => "Sinun viestisi",
	'messages:answer' => "Vastaa",
	'messages:toggle' => 'äytä kaikki',
	'messages:markread' => 'Merkkaa luetuksi',
	'messages:recipient' => 'Valitse recipient&hellip;',
	'messages:to_user' => 'Käyttäjälle: %s',

	'messages:new' => 'Uusi viesti',

	'notification:method:site' => 'Viestit',

	'messages:error' => 'Virhe tallennettaessa viestiä, yritä uudelleen.',

	'item:object:messages' => 'Viestit',

	/**
	* Status messages
	*/

	'messages:posted' => "Viesti lähetettiin.",
	'messages:success:delete:single' => 'Viesti poistettiin',
	'messages:success:delete' => 'Viestit poistettiin',
	'messages:success:read' => 'Viestit merkattu luetuiksi',
	'messages:error:messages_not_selected' => 'Ei viestejä valittu',
	'messages:error:delete:single' => 'Viestiä ei voitu poistaa',

	/**
	* Email messages
	*/

	'messages:email:subject' => 'Sinulle on uusi viesti!',
	'messages:email:body' => "Sinulle on uusi viesti käyttäjältä %s. Siinä lukee:


	%s


	Nähdäksesi viestisi, klikkaa tästä:

	%s

	Lähettääksesi käyttäjälle %s viestin, klikkaa tästä:

	%s

	Et voi vastata tähän viestiin.",

	/**
	* Error messages
	*/

	'messages:blank' => "Sinun pitää kirjoittaa ensin viestiin jotain ennen kuin se voidaan tallentaa.",
	'messages:notfound' => "Etsimääsi veistiä ei löydetty.",
	'messages:notdeleted' => "Tätä viestiä ei voitu poistaa.",
	'messages:nopermission' => "Sinulla ei ole lupaa muuttaa tuota viestiä.",
	'messages:nomessages' => "Ei viestejä.",
	'messages:user:nonexist' => "emme löytäneet vastaanottajaa databasesta.",
	'messages:user:blank' => "Et valinnut viestille vastaanottajaa.",

	'messages:deleted_sender' => 'Poistettu käyttäjä',

);
		
add_translation("fi", $finnish);