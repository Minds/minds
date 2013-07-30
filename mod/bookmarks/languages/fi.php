<?php
/**
 * Bookmarks Finnish language file
 */

$finnish = array(

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "Kirjanmerkit",
	'bookmarks:add' => "Lisää kirjanmerkki",
	'bookmarks:edit' => "Muokkaa kirjanmerkkiä",
	'bookmarks:owner' => "käyttäjän %s kirjanmerkit",
	'bookmarks:friends' => "Ystävien' kirjanmerkit",
	'bookmarks:everyone' => "Kaikki sivuston kirjanmerkit",
	'bookmarks:this' => "Merkkaa tämä sivu",
	'bookmarks:this:group' => "Kirjanmerkki sivulla %s",
	'bookmarks:bookmarklet' => "Hanki bookmarklet",
	'bookmarks:bookmarklet:group' => "Hanki ryhmä bookmarklet",
	'bookmarks:inbox' => "Kirjanmerkkien saapuneet-kansio",
	'bookmarks:morebookmarks' => "Lisää kirjanmerkkejä",
	'bookmarks:more' => "Lisää",
	'bookmarks:with' => "Jaa",
	'bookmarks:new' => "Uusi kirjanmerkki",
	'bookmarks:via' => "via kirjanmerkit",
	'bookmarks:address' => "Lähteen osoite kirjanmerkkiin",
	'bookmarks:none' => 'Ei kirjanmerkkejä',

	'bookmarks:delete:confirm' => "Oletko varma, että haluat poistaa tämän lähteen?",

	'bookmarks:numbertodisplay' => 'kirjanmerkkien määrä, jotka näytetään',

	'bookmarks:shared' => "Merkattu",
	'bookmarks:visit' => "Vieraile lähteellä",
	'bookmarks:recent' => "Viimeisimmät kirjanmerkit",

	'river:create:object:bookmarks' => '%s merkkasi %s',
	'river:comment:object:bookmarks' => '%s kommentoi kirjanmerkkiä %s',
	'bookmarks:river:annotate' => 'kommentti tässä kirjanmerkissä',
	'bookmarks:river:item' => 'itemi',

	'item:object:bookmarks' => 'Kirjanmerkit',

	'bookmarks:group' => 'Ryhmän kirjanmerkit',
	'bookmarks:enablebookmarks' => 'Salli ryhmän kirjanmerkit',
	'bookmarks:nogroup' => 'Tällä ryhmällä ei ole vielä kirjanmerkkejä',
	'bookmarks:more' => 'Lisää kirjanmerkkejä',

	'bookmarks:no_title' => 'Ei otsikkoa',

	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Näytä viimeisimmät kirjanmerkkisi.",

	'bookmarks:bookmarklet:description' =>
			"Kirjanmerkkien bookmarklet sallii sinun jakaa minkä tahansa lähteen muiden kanssa, tai vain muistutukseksi itsellesi. Käyttääksesi sitä, yksikertaisesti vedät seuraavan napin selaimesi osoiteviivalle:",

	'bookmarks:bookmarklet:descriptionie' =>
			"Jos käytät Internet Exploreria, sinun täytyy klikata hiiren oikealla napilla bookmarklettia, valitse 'lisää suosikkeihin', ja sitten Linkit-osaa.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"Voit sitten tallentaa minkä tahansa sivun vain klikkaamalla sen osoitetta.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Kohde merkattu onnistuneesti.",
	'bookmarks:delete:success' => "Kirjanmerkkisi poistettiin.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Kirjanmerkkiäsi ei voitu tallentaa. Varmista että olet antanut otsikon ja tarvittavat tiedot.",
	'bookmarks:delete:failed' => "Kirjanmerkkiäsi ei voitu poistaa, yritä uudelleen.",
);

add_translation('fi', $finnish);