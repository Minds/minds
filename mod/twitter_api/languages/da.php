<?php
/**
 * An Danish language definition file
 */

$danish = array(
	'twitter_api' => 'Twitter Services',

	'twitter_api:requires_oauth' => 'Twitter Services kræver at OAuth Libraries plugin er aktiveret.',

	'twitter_api:consumer_key' => 'Consumer Key',
	'twitter_api:consumer_secret' => 'Consumer Secret',

	'twitter_api:settings:instructions' => 'Du skal indhente en Consumer Key og Consumer Secret fra <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>. Udfyld den nye app ansøgning. Vælg "Browser" som ansøgningstype og "Read & Write" som adgangstype. Callback url er %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Link din %s konto med Twitter.",
	'twitter_api:usersettings:request' => "Du skal først <a href=\"%s\">autorisere</a> %s for at få adgang til din Twitter konto.",
	'twitter_api:authorize:error' => 'Kunne ikke autorisere Twitter.',
	'twitter_api:authorize:success' => 'Twitter adgang er blevet autoriseret.',

	'twitter_api:usersettings:authorized' => "Du har autoriseret %s til at tilgå din Twitter konto: @%s.",
	'twitter_api:usersettings:revoke' => 'Klik <a href="%s">her</a> for at tilbagekalde adgangstilladelse.',
	'twitter_api:revoke:success' => 'Twitter adgang er blevet tilbagekaldt.',

	'twitter_api:login' => 'Tillad de eksisterende brugere, der har forbundet deres Twitter konto, at logge ind med Twitter?',
	'twitter_api:new_users' => 'Tillad nye brugere at tilmelde sig ved hjælp af deres Twitter konto, selvom brugerregistrering er deaktiveret?',
	'twitter_api:login:success' => 'Du er blevet logget ind.',
	'twitter_api:login:error' => 'Kunne ikke logge ind med Twitter.',
	'twitter_api:login:email' => "Du skal indtaste en gyldig e-mail adresse til din nye %s konto.",

	'twitter_api:deprecated_callback_url' => 'Callback URL\'en er ændret for Twitter API til %s.  Bed din administrator om at ændre det.',
	
);
				
add_translation('da',$danish);

?>