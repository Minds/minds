<?php
/**
 * Pages languages
 *
 * @package ElggPages
 */

$finnish = array(

	/**
	 * Menu items and titles
	 */

	'pages' => "Sivut",
	'pages:owner' => "Käyttäjän %s sivut",
	'pages:friends' => "Ystävien sivut",
	'pages:all' => "Kaikki sivuston sivut",
	'pages:add' => "Lisää sivu",

	'pages:group' => "Ryhmä sivut",
	'groups:enablepages' => 'Salli ryhmäsivut',

	'pages:edit' => "Muokkaa tätä sivua",
	'pages:delete' => "Poista tämä sivu",
	'pages:history' => "Historia",
	'pages:view' => "Näytä sivu",
	'pages:revision' => "Revisioni",

	'pages:navigation' => "Navigaatio",
	'pages:via' => "via sivut",
	'item:object:page_top' => 'Ylä-tason sivut',
	'item:object:page' => 'Sivut',
	'pages:nogroup' => 'Tällä ryhmällä ei ole yhtään sivuja vielä',
	'pages:more' => 'Lisää sivuja',
	'pages:none' => 'Ei sivuja luotuna vielä',

	/**
	* River
	**/

	'river:create:object:page' => '%s loi sivun %s',
	'river:create:object:page_top' => '%s loi sivun %s',
	'river:update:object:page' => '%s päivitti sivun %s',
	'river:update:object:page_top' => '%s päivitti sivun %s',
	'river:comment:object:page' => '%s komentoi sivua nimeltään %s',
	'river:comment:object:page_top' => '%s kommentoi sivua nimeltään %s',

	/**
	 * Form fields
	 */

	'pages:title' => 'Sivun otsikko',
	'pages:description' => 'Sivun teksti',
	'pages:tags' => 'Tagit',
	'pages:access_id' => 'Lukuoikeus',
	'pages:write_access_id' => 'Kirjoitusoikeus',

	/**
	 * Status and error messages
	 */
	'pages:noaccess' => 'Ei pääsyä sivulle',
	'pages:cantedit' => 'Et voi muokata tätä sivua',
	'pages:saved' => 'Sivu tallennettu',
	'pages:notsaved' => 'Sivua ei voitu tallentaa',
	'pages:error:no_title' => 'Sinun pitää antaa otsikko tälle sivulle.',
	'pages:delete:success' => 'Sivu poistettiin.',
	'pages:delete:failure' => 'Sivua ei voitu poistaa.',

	/**
	 * Page
	 */
	'pages:strapline' => 'Viimeksi päivitetty %s käyttäjän %s toimesta',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revisioni luotu %s käyttäjän %s toimesta',

	/**
	 * Widget
	 **/

	'pages:num' => 'Sivujen määrä, jotka näytetään',
	'pages:widget:description' => "Tämä on lista sivuistasi.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Näytä sivu",
	'pages:label:edit' => "Muokkaa sivua",
	'pages:label:history' => "Sivun historia",

	/**
	 * Sidebar items
	 */
	'pages:sidebar:this' => "Tämä sivu",
	'pages:sidebar:children' => "Ala-sivut",
	'pages:sidebar:parent' => "Omistaja",

	'pages:newchild' => "Luo ala-sivu",
	'pages:backtoparent' => "Takaisin '%s'",
);

add_translation("fi", $finnish);