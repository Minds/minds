<?php
/**
 * Elgg file plugin language pack
 *
 * @package ElggFile
 */

$finnish = array(

	/**
	 * Menu items and titles
	 */
	'file' => "Tiedostot",
	'file:user' => "käyttäjän %s tiedostot",
	'file:friends' => "Ystävien tiedostot",
	'file:all' => "Kaikki sivuston tiedostot",
	'file:edit' => "Muokkaa tiedostoa",
	'file:more' => "Lisää tiedostoja",
	'file:list' => "Listan näkymä",
	'file:group' => "Ryhmätiedostot",
	'file:gallery' => "galleria näkymä",
	'file:gallery_list' => "Galleria tai listanäkymä",
	'file:num_files' => "Tiedostojen määrä, jotka näytetään",
	'file:user:gallery'=>'Näytä käyttäjän %s galleria',
	'file:via' => 'via tiedostot',
	'file:upload' => "Lähetä tiedosto",
	'file:replace' => 'Korvaa tiedostosisältö (jätä tyhjäksi jos et halua muokata tiedostoa)',
	'file:list:title' => "%s's %s %s",
	'file:title:friends' => "Ystävät'",

	'file:add' => 'Lähetä tiedosto',

	'file:file' => "Tiedosto",
	'file:title' => "Otsikko",
	'file:desc' => "Kuvaus",
	'file:tags' => "Tagit",

	'file:types' => "Lähetetyt tiedostotyypit",

	'file:type:' => 'Tiedostot',
	'file:type:all' => "Kaikki tiedostot",
	'file:type:video' => "Videot",
	'file:type:document' => "Dokumentit",
	'file:type:audio' => "Ääni",
	'file:type:image' => "Kuvat",
	'file:type:general' => "Yleiset",

	'file:user:type:video' => "Käyttäjän %s videot",
	'file:user:type:document' => "Käyttäjän %s dokumentit",
	'file:user:type:audio' => "Käyttäjän %s äänitiedostot",
	'file:user:type:image' => "Käyttäjän %s kuvat",
	'file:user:type:general' => "Käyttäjän %s muut tiedostot",

	'file:friends:type:video' => "Ystäviesi videot",
	'file:friends:type:document' => "Ystäviesi tiedostot",
	'file:friends:type:audio' => "Ystäviesi äänitiedostot",
	'file:friends:type:image' => "Ystäviesi kuvat",
	'file:friends:type:general' => "Ystäviesi muut tiedostot",

	'file:widget' => "Tiedosto vimpain",
	'file:widget:description' => "Näytä viimeisimmät tiedostosi",

	'groups:enablefiles' => 'Salli ryhmätiedostot',

	'file:download' => "Lataa tämä",

	'file:delete:confirm' => "Haluatko varmasti poistaa tämän tiedoston?",

	'file:tagcloud' => "Tagi Pilvi",

	'file:display:number' => "Tiedostojen määrä, jotka näytetään",

	'river:create:object:file' => '%s lähetti tiedoston %s',
	'river:comment:object:file' => '%s kommentoi tiedostoa %s',

	'item:object:file' => 'Tiedostot',

	/**
	 * Embed media
	 **/

		'file:embed' => "Upota media",
		'file:embedall' => "Kaikki",

	/**
	 * Status messages
	 */

		'file:saved' => "Tiedostosi tallennettiin onnistuneesti.",
		'file:deleted' => "Tiedostosi poistettiin onnistuneesti.",

	/**
	 * Error messages
	 */

		'file:none' => "Ei lähetettyjä tiedostoja.",
		'file:uploadfailed' => "Tiedostoasi ei voitu tallentaa.",
		'file:downloadfailed' => "tämä tiedosto ei ole saatavilla juuri nyt.",
		'file:deletefailed' => "Tiedostoasi ei voitu poistaa juuri nyt.",
		'file:noaccess' => "Sinulla ei ole lupaa muuttaa tätä tiedostoa",
		'file:cannotload' => "Tapahtui virhe ladattaessa tiedostoa",
		'file:nofile' => "Sinun pitää valita tiedosto",
);

add_translation("fi", $finnish);