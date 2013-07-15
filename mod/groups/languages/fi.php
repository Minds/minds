<?php
/**
 * Elgg groups plugin language pack
 *
 * @package ElggGroups
 */

$finnish = array(

	/**
	 * Menu items and titles
	 */
	'groups' => "Groups",
	'groups:owned' => "Ryhmät jotka omistan",
	'groups:yours' => "Minun ryhmäni",
	'groups:user' => "käyttäjän %s ryhmät",
	'groups:all' => "Kaikki ryhmät",
	'groups:add' => "Luo uusi ryhmä",
	'groups:edit' => "Muokkaa ryhmää",
	'groups:delete' => 'Poista ryhmä',
	'groups:membershiprequests' => 'Hallitse liittymiskutsuja',
	'groups:invitations' => 'Ryhmäkutsut',

	'groups:icon' => 'Ryhmän kuvake (jätä tyhjäksi jos haluat pitää sen muuttumattomana)',
	'groups:name' => 'Ryhmän nimi',
	'groups:username' => 'Ryhmän lyhyt nimi (näytetään URL-osoitteessa, alphanumeeriset merkit ainoastaan)',
	'groups:description' => 'Kuvaus',
	'groups:briefdescription' => 'Lyhyt kuvaus',
	'groups:interests' => 'Tagit',
	'groups:website' => 'Webbisivu',
	'groups:members' => 'Ryhmän jäsenet',
	'groups:members:title' => 'Jäsen %s',
	'groups:members:more' => "Näytä kaikki jäsenet",
	'groups:membership' => "ryhmän jäsenyysluvat",
	'groups:access' => "Pääsyoikeudet",
	'groups:owner' => "Omistaja",
	'groups:widget:num_display' => 'Ryhmien määrä, jotka näytetään',
	'groups:widget:membership' => 'Ryhmän jäsenyys',
	'groups:widgets:description' => 'Näytä ryhmät joiden jäsen olet profiilissasi',
	'groups:noaccess' => 'Ei pääsyoikeutta ryhmään',
	'groups:permissions:error' => 'Sinulla ei ole lupaa tähän',
	'groups:ingroup' => 'ryhmässä',
	'groups:cantedit' => 'Et voi muokata tätä ryhmää',
	'groups:saved' => 'Ryhmä tallennettu',
	'groups:featured' => 'Seuratut ryhmät',
	'groups:makeunfeatured' => 'Ei seuratut',
	'groups:makefeatured' => 'Seuraa',
	'groups:featuredon' => '%s on nyt seurattu ryhmä.',
	'groups:unfeatured' => '%s on poistettu seuratuista ryhmistä.',
	'groups:featured_error' => 'Epäkelpo ryhmä.',
	'groups:joinrequest' => 'Pyydä pääsylupaa ryhmään',
	'groups:join' => 'Liity ryhmään',
	'groups:leave' => 'Lähde ryhmästä',
	'groups:invite' => 'Kutsu ystäviä',
	'groups:invite:title' => 'Kutsu ystäviä tähän ryhmään',
	'groups:inviteto' => "Kutsu ystäviä ryhmään '%s'",
	'groups:nofriends' => "Sinulla ei ole jäljellä ystäviä joita et olisi kutsunut tähän ryhmään.",
	'groups:nofriendsatall' => 'Sinulla ei ole ystäviä joita kutsua!',
	'groups:viagroups' => "via ryhmät",
	'groups:group' => "Ryhmä",
	'groups:search:tags' => "tagi",
	'groups:search:title' => "Etsi ryhmiä tageilla '%s'",
	'groups:search:none' => "Sopivia ryhmiä ei löytynyt",

	'groups:activity' => "Ryhmän aktiivisuus",
	'groups:enableactivity' => 'Salli ryhmäaktiivisuus',
	'groups:activity:none' => "Ryhmäaktiivisuutta ei vielä ole",

	'groups:notfound' => "Ryhmää ei löytynyt",
	'groups:notfound:details' => "Pyydettyä ryhmää ei joko ole olemassa tai sinulla ei ole lupaa päästä siihen",

	'groups:requests:none' => 'Ei jäsenpyyntöjä tällä hetkellä.',

	'groups:invitations:none' => 'Ei kutsuja tällä hetkellä.',

	'item:object:groupforumtopic' => "Keskustelunaiheet",

	'groupforumtopic:new' => "Lisää keskustelunaihe",

	'groups:count' => "ryhmiä luotu",
	'groups:open' => "avoin ryhmä",
	'groups:closed' => "suljettu ryhmä",
	'groups:member' => "jäsenet",
	'groups:searchtag' => "etsi ryhmiä tagien avulla",

	'groups:more' => 'Lisää ryhmiä',
	'groups:none' => 'Ei ryhmiä',


	/*
	 * Access
	 */
	'groups:access:private' => 'Suljettu - Käyttäjät täytyy kutsua',
	'groups:access:public' => 'Avoin - kuka tahansa voi liittyä',
	'groups:access:group' => 'Vain Ryhmän jäsenille',
	'groups:closedgroup' => 'Tämä ryhmä on suljettu.',
	'groups:closedgroup:request' => 'Pyytääksesi pääsyä ryhmään, klikkaa "pyydä pääsylupaa" linkkiä.',
	'groups:visibility' => 'Kuka voi nähdä tämä ryhmän?',

	/*
	Group tools
	*/
	'groups:enableforum' => 'Salli ryhmäkeskustelut',
	'groups:yes' => 'kyllä',
	'groups:no' => 'ei',
	'groups:lastupdated' => 'Viimeksi päivitetty %s käyttäjän %s toimesta',
	'groups:lastcomment' => 'Viimeinen kommentti %s käyttäjän %s toimesta',

	/*
	Group discussion
	*/
	'discussion' => 'Keskustelu',
	'discussion:add' => 'Lisää keskustelun aihe',
	'discussion:latest' => 'Viimeisin keskustelunaihe',
	'discussion:group' => 'Ryhmän keskustelu',

	'discussion:topic:created' => 'Keskustelun aihe on luotu.',
	'discussion:topic:updated' => 'Keskutelun aihe on päivitetty.',
	'discussion:topic:deleted' => 'Keskustelun aihe on poistettu.',

	'discussion:topic:notfound' => 'Keskustelun aihetta ei löytynyt',
	'discussion:error:notsaved' => 'ei voitu tallentaa',
	'discussion:error:missing' => 'Otsikko ja viesti ovat pakollisia kenttiä',
	'discussion:error:permissions' => 'Sinulla ei ole lupaa suorittaa tätä toimintoa',
	'discussion:error:notdeleted' => 'Keskustelunaihetta ei voitu poistaa',

	'discussion:reply:deleted' => 'Keskustelun vastaus on poistettu.',
	'discussion:reply:error:notdeleted' => 'Ei voitu poistaa keskustelun vastausta',

	'reply:this' => 'Vastaa tähän',

	'group:replies' => 'Vastauksia',
	'groups:forum:created' => 'Luotu %s jolla %d kommenttia',
	'groups:forum:created:single' => 'Luotu %s jolla %d vastaus',
	'groups:forum' => 'Keskustelu',
	'groups:addtopic' => 'Lisää aihe',
	'groups:forumlatest' => 'Viimeisimmät keskustelut',
	'groups:latestdiscussion' => 'Viimeisimmät keskustelut',
	'groups:newest' => 'Uusimmat',
	'groups:popular' => 'Suosituimmat',
	'groupspost:success' => 'Vastauksesi lähetettiin',
	'groups:alldiscussion' => 'Viimeisi keskustelu',
	'groups:edittopic' => 'Muokkaa aihetta',
	'groups:topicmessage' => 'Aiheen viesti',
	'groups:topicstatus' => 'Aiheen status',
	'groups:reply' => 'Lähetä kommentti',
	'groups:topic' => 'Aihe',
	'groups:posts' => 'Viestejä',
	'groups:lastperson' => 'Viimeisin henkilö',
	'groups:when' => 'Koska',
	'grouptopic:notcreated' => 'Aihetta ei ole luotu.',
	'groups:topicopen' => 'Avoin',
	'groups:topicclosed' => 'Suljettu',
	'groups:topicresolved' => 'Selvitetty',
	'grouptopic:created' => 'Aiheesi luotiin.',
	'groupstopic:deleted' => 'Aihe on poistettu.',
	'groups:topicsticky' => 'Kiinnitä',
	'groups:topicisclosed' => 'Tämä keskustelu on suljettu.',
	'groups:topiccloseddesc' => 'Tämä keskustelu on suljettu ja siihen ei voi lähettää nyt viestejä.',
	'grouptopic:error' => 'Ryhmäaihettasi ei voitu luoda. Yritä uudelleen tai ota yhteyttä valvojaan.',
	'groups:forumpost:edited' => "Olet onnistuneesti muokannut foorumin viestiä.",
	'groups:forumpost:error' => "tapahtui virhe muokattaessa foorumin viestiä.",


	'groups:privategroup' => 'Tämä ryhmä on suljettu. Pyydetään pääsylupaa.',
	'groups:notitle' => 'Ryhmillä pitää olla otsikko',
	'groups:cantjoin' => 'Ei voi liittyä ryhmään',
	'groups:cantleave' => 'Ei voinut jättää ryhmää',
	'groups:removeuser' => 'Poista ryhmästä',
	'groups:cantremove' => 'Käyttäjää ei voitu poistaa ryhmästä',
	'groups:removed' => 'Siirrettiin %s ryhmästä',
	'groups:addedtogroup' => 'käyttäjä lisättiin ryhmään onnistuneesti',
	'groups:joinrequestnotmade' => 'ei voitu pyytää lupaa liittyä ryhmään',
	'groups:joinrequestmade' => 'Lupaa ryhmään liittymiseen pyydetty',
	'groups:joined' => 'Liityit ryhmään!',
	'groups:left' => 'Lähdit ryhmästä',
	'groups:notowner' => 'Et ole tämän ryhmän omistaja.',
	'groups:notmember' => 'Et ole tämän ryhmän jäsen.',
	'groups:alreadymember' => 'Olet jo tämän ryhmän jäsen!',
	'groups:userinvited' => 'Käyttäjä on kutsuttu.',
	'groups:usernotinvited' => 'Käyttäjää ei voitu kutsua.',
	'groups:useralreadyinvited' => 'Käyttäjä on jo kutsuttu',
	'groups:invite:subject' => "%s sinut on kutsuttu ryhmään %s!",
	'groups:updated' => "Viimeinen viesti käyttäjältä %s %s",
	'groups:started' => "Aloittanut %s",
	'groups:joinrequest:remove:check' => 'Oletko varma, että haluat poistaa tämän liittymispyynnön?',
	'groups:invite:remove:check' => 'Oletko varma, että haluat poistaa tämän pyynnön?',
	'groups:invite:body' => "Hei %s,

%s kutsui sinut ryhmään '%s'. klikkaa alla olevaa jos haluat nähdä muut kutsusi:

%s",

	'groups:welcome:subject' => "Tervetuloa ryhmään %s !",
	'groups:welcome:body' => "Hei %s!

Olet nyt jäsen ryhmässä '%s'! Klikkaa alla olevaa aloittaksesi jutustelu!

%s",

	'groups:request:subject' => "%s on pyytänyt lupaa liittyä %s",
	'groups:request:body' => "Hei %s,

%s on pyytänyt lupaa liittyä ryhmään '%s'. klikkaa alla olevaa nähdäksesi heidän profiilinsa:

%s

tai klikkaa alla olevaa nähdäksesi ryhmän muut kutsut:

%s",

	/*
		Forum river items
	*/

	'river:create:group:default' => '%s loi ryhmän %s',
	'river:join:group:default' => '%s liittyi ryhmään %s',
	'river:create:object:groupforumtopic' => '%s lisäsi uuden keskustelun aiheen %s',
	'river:reply:object:groupforumtopic' => '%s vastasi keskustelunaiheeseen %s',
	
	'groups:nowidgets' => 'ei vimpaimia luotu tälle ryhmälle.',


	'groups:widgets:members:title' => 'Ryhmän jäsenet',
	'groups:widgets:members:description' => 'Lista ryhmän jäsenistä.',
	'groups:widgets:members:label:displaynum' => 'Listaa ryhmän jäsenet.',
	'groups:widgets:members:label:pleaseedit' => 'Muokkaa tämän vimpaimen asetukset.',

	'groups:widgets:entities:title' => "Objektia ryhmässä",
	'groups:widgets:entities:description' => "Listaa objektit tässä ryhmässä",
	'groups:widgets:entities:label:displaynum' => 'Listaa ryhmän objektit',
	'groups:widgets:entities:label:pleaseedit' => 'Muokkaa tämän vimpaimen asetukset.',

	'groups:forumtopic:edited' => 'Foorumin aihetta muokattiin onnistuneesti.',

	'groups:allowhiddengroups' => 'haluatko sallia yksityiset (näkymättömät) ryhmät?',

	/**
	 * Action messages
	 */
	'group:deleted' => 'Ryhmä ja sen sisältö poistettu',
	'group:notdeleted' => 'Ryhmää ei voitu poistaa',

	'group:notfound' => 'Ryhmää ei löydetty',
	'grouppost:deleted' => 'Poistettiin onnistuneesti',
	'grouppost:notdeleted' => 'Ei voitu poistaa',
	'groupstopic:deleted' => 'Aihe poistettu',
	'groupstopic:notdeleted' => 'Aihetta ei poistettu',
	'grouptopic:blank' => 'Ei aihetta',
	'grouptopic:notfound' => 'aihetta ei löytynyt',
	'grouppost:nopost' => 'Tyhjä viesti',
	'groups:deletewarning' => "Oletko varma, että haluat poistaa tämän viestin? Et voi perua tätä!",

	'groups:invitekilled' => 'Kutsu poistettiin.',
	'groups:joinrequestkilled' => 'Liittymispyyntö on poistettu.',

	// ecml
	'groups:ecml:discussion' => 'Ryhmäkeskustelut',
	'groups:ecml:groupprofile' => 'Ryhmäprofiilit',

);

add_translation("fi", $finnish);