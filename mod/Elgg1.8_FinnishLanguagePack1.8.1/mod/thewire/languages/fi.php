<?php
/**
 * The Wire English language file
 */

$finnish = array(

	/**
	 * Menu items and titles
	 */
	'thewire' => "Kaapeli",
	'thewire:everyone' => "Kaikki kaapelin viestit",
	'thewire:user' => "käyttäjän %s kaapeliviestit",
	'thewire:friends' => "Ystävien kaapeliviestit",
	'thewire:reply' => "Vastaa",
	'thewire:replying' => "Vertaa keneen %s (@%s) kuka kirjoitti",
	'thewire:thread' => "Aihe",
	'thewire:charleft' => "merkkiä jäljellä",
	'thewire:tags' => "Kaapeliviestit joissa tagit '%s'",
	'thewire:noposts' => "Ei Kaapeliviestejä vielä",
	'item:object:thewire' => "Kaapeli viestit",
	'thewire:update' => 'Päivitä',

	'thewire:previous' => "Edellinen",
	'thewire:hide' => "Piilota",
	'thewire:previous:help' => "Näytä edellinen viesti",
	'thewire:hide:help' => "Piilota edellinen viesti",

	/**
	 * The wire river
	 */
	'river:create:object:thewire' => "%s kirjoitti %s",
	'thewire:wire' => 'Kaapeli',

	/**
	 * Wire widget
	 */
	'thewire:widget:desc' => 'Näytä viimeisimmät kaapeliviestisi',
	'thewire:num' => 'viestien määrä, jotka näytetään',
	'thewire:moreposts' => 'Lisää kaapeliviestejä',

	/**
	 * Status messages
	 */
	'thewire:posted' => "Viestisi lähetettiin kaapeliin.",
	'thewire:deleted' => "Kaapeliviesti poistettiin.",
	'thewire:blank' => "Kirjoita jotain niin voimme lähettää viestin.",
	'thewire:notfound' => "etsimääsi kaapeliviestiä ei löytynyt.",
	'thewire:notdeleted' => "Tätä kaapeliviestiä ei voitu poistaa.",

	/**
	 * Notifications
	 */
	'thewire:notify:subject' => "Uusi kaapeliviesti",
	'thewire:notify:reply' => '%s vastasi %s kaapelissa:',
	'thewire:notify:post' => '%s kirjoitti kaapeliin:',

);

add_translation("fi", $finnish);
