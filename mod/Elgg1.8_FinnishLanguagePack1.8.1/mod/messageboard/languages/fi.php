<?php

$finnish = array(

	/**
	 * Menu items and titles
	 */

	'messageboard:board' => "Viestilauta",
	'messageboard:messageboard' => "Viestilauta",
	'messageboard:viewall' => "Näytä kaikki",
	'messageboard:postit' => "viestit",
	'messageboard:history:title' => "Historia",
	'messageboard:none' => "Täällä ei ole vielä mitään",
	'messageboard:num_display' => "Viestien määrä, jotka näytetään",
	'messageboard:desc' => "Tämä on viestilauta jonka voit lisätä profiiliisi ja johon muut käyttäjät voivat lähettää viestejä.",

	'messageboard:user' => "käyttäjän %s viestilauta",

	'messageboard:replyon' => 'vastaa',
	'messageboard:history' => "historia",

	'messageboard:owner' => 'käyttäjän %s\ viestilauta',
	'messageboard:owner_history' => 'käyttäjän %s\ viesti on käyttäjän %s\ viestilaudalla',

	/**
	 * Message board widget river
	 */
	'river:messageboard:user:default' => "käyttäjä %s lähetti viestin käyttäjän %s viestilaudalle",

	/**
	 * Status messages
	 */

	'messageboard:posted' => "Viestisi on lähetetty.",
	'messageboard:deleted' => "Viesti on poistettu.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Sinulla on uusi kommentti viestilaudalla!',
	'messageboard:email:body' => "Sinulla on uusi viesti viestilaudalla käyttäjältä %s. Siinä lukee:


%s


Nähdäksesi viestilautasi kommentit, klikkaa tästä:

	%s

nähdäksesi käyttäjän %s profiilin, klikkaa tästä:

	%s

Et voi vastata tähän viestiin.",

	/**
	 * Error messages
	 */

	'messageboard:blank' => "Et voi lähettää tyhjää viestiä.",
	'messageboard:notfound' => "emme löytäneet haluttua kohdetta.",
	'messageboard:notdeleted' => "Viestiä ei voitu poistaa.",
	'messageboard:somethingwentwrong' => "Jokin meni pieleen kun viestiäsi yritettiin tallentaa, varmista että oikeasti kirjoitit jotain viestiin.",

	'messageboard:failure' => "Odottamaton virhe tapahtui, yritä uudelleen.",

);

add_translation("fi", $finnish);
