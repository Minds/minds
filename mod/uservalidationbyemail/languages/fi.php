<?php
/**
 * Email user validation plugin language pack.
 *
 * @package Elgg.Core.Plugin
 * @subpackage ElggUserValidationByEmail
 */

$finnish = array(
	'admin:users:unvalidated' => 'Vahvistamattomat',
	
	'email:validate:subject' => "%s varmista sähköpostiosoitteesi %s!",
	'email:validate:body' => "%s,

Ennen kuin voit alkaa käyttää %s, sinun täytyy vahvistaa sähköpostiosoitteesi.

vahvista sähköpostiosoitteesi klikkaamalla alla olevaa linkkiä:

%s

jos et voi klikata sitä, kopioi se selaimesi osoiteviivalle manuaalisesti.

%s
%s
",
	'email:confirm:success' => "Sähköpostiosoitteesi on varmistettu!",
	'email:confirm:fail' => "Sähköpostiosoitettasi ei voitu varmentaa...",

	'uservalidationbyemail:registerok' => "Aktivoidaksesi, klikkaa linkkiä jonka juuri lähetimme sähköpostiisi.",
	'uservalidationbyemail:login:fail' => "Tiliäsi ei ole aktivoitu joten kirjautumisesi epäonnistui.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'No unvalidated users.',

	'uservalidationbyemail:admin:unvalidated' => 'Vahvistamattomat',
	'uservalidationbyemail:admin:user_created' => 'Rekisteröityneet %s',
	'uservalidationbyemail:admin:resend_validation' => 'Lähetä varmistus uudelleen',
	'uservalidationbyemail:admin:validate' => 'Varmista',
	'uservalidationbyemail:admin:delete' => 'Poista',
	'uservalidationbyemail:confirm_validate_user' => 'Varmista %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Lähetä varmistussähköposti osoitteeseen %s?',
	'uservalidationbyemail:confirm_delete' => 'Poista %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Varmenna valitut käyttäjät?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'LÄhetä varmistus uudelleen valituille käyttäjille?',
	'uservalidationbyemail:confirm_delete_checked' => 'Poista merkatut käyttäjät?',
	'uservalidationbyemail:check_all' => 'Kaikki',

	'uservalidationbyemail:errors:unknown_users' => 'Tunnistamattomia käyttäjiä',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Käyttäjää ei voitu varmentaa.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Kaikkia ei voitu varmentaa.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Käyttäjää ei voitu poistaa.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Kaikkia käyttäjiä ei voitu poistaa.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Ei voitu lähettää varmistusviestiä.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Ei voitu lähettää varmistusviestiä seuraaville käyttäjille.',

	'uservalidationbyemail:messages:validated_user' => 'Käyttäjä varmennettu.',
	'uservalidationbyemail:messages:validated_users' => 'Kaikki käyttäjät varmennettu.',
	'uservalidationbyemail:messages:deleted_user' => 'Käyttäjä poistettu.',
	'uservalidationbyemail:messages:deleted_users' => 'Kaikki merkatut käyttäjät poistettu.',
	'uservalidationbyemail:messages:resent_validation' => 'Varmennuspyyntö uudelleenlähetetty.',
	'uservalidationbyemail:messages:resent_validations' => 'Varmennuspyyntö lähetetty merkatuille uudelleen.'

);

add_translation("fi", $finnish);