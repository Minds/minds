<?php

/**
 * Elgg invite language file
 * 
 * @package ElggInviteFriends
 */

$finnish = array(

	'friends:invite' => 'Kutsu Ystäviä',
	
	'invitefriends:registration_disabled' => 'Et voi juuri nyt kutsua uusia ystäviä.',
	
	'invitefriends:introduction' => 'kutsuaksesi ystäviäsi tähän verkostoon, kirjoita heidän sähköpostiosoitteensa(yksi per rivi):',
	'invitefriends:message' => 'Anna viesti minkä he näkevät kutsun saadessaan:',
	'invitefriends:subject' => 'Kutsu liittyäksesi %s',

	'invitefriends:success' => 'Ystäväsi kutsuttiin.',
	'invitefriends:invitations_sent' => 'Kutsuja lähetetty: %s. Seuraavia ongelmia tapahtui:',
	'invitefriends:email_error' => 'Seuraavat osoitteet eivät kelvanneet: %s',
	'invitefriends:already_members' => 'Seuraavat käyttäjät ovat jo jäseniä: %s',
	'invitefriends:noemails' => 'Ei sähköpostiosoitteita annettu.',
	
	'invitefriends:message:default' => '
Hei,

Haluan kutsua sinut liittymään verkostooni %s.',

	'invitefriends:email' => '
Sinut on kutsuttu liittymään verkostoon %s käyttäjän %s toimesta. Tämä seuraava viesti on häneltä:

%s

Liityäksesi klikkaa seuraavaa linkkiä:

%s

Lisäät heidät automaattisesti kavereiksesi kun olet tehnyt uuden tilin.',
	
	);
					
add_translation("fi", $finnish);
