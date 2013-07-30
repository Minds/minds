<?php

/**
 * Elgg invite language file
 * 
 * @package ElggInviteFriends
 */

$french = array(

	'friends:invite' => "Inviter des contacts",
	
	'invitefriends:registration_disabled' => "L'enregistrement des nouveaux utilisateurs a été désactivé sur ce site, vous ne pouvez pas inviter de nouveaux utilisateurs.",
	
	'invitefriends:introduction' => "Pour inviter des contacts à vous rejoindre sur ce réseau, entrez leurs adresses mail ci-dessous (une par ligne) :",
	'invitefriends:message' => "Ecrivez un message qu'ils vont recevoir avec votre invitation :",
	'invitefriends:subject' => "Invitation à rejoindre %s",

	'invitefriends:success' => "Vos contacts ont été invités.",
	'invitefriends:invitations_sent' => "Invitation envoyé: %s. Il ya eu les problèmes suivants :",
	'invitefriends:email_error' => "Les invitations ont été envoyées, mais l'adresse suivante comporte des erreurs: %s",
	'invitefriends:already_members' => "Les invités suivants sont déja membres: %s",
	'invitefriends:noemails' => "Aucune adresse email a été entrée",
	
	'invitefriends:message:default' => "
Bonjour,

Je souhaiterais vous inviter à rejoindre mon réseau sur %s.",

	'invitefriends:email' => "
Vous avez été invité à rejoindre %s par %s, qui a ajouté le message suivant :

%s

Pour vous inscrire, cliquez sur le lien suivant :

%s

Ils seront automatiquement ajoutés à vos contacts quand vous aurez créé votre compte.",
	
	);
					
add_translation("fr", $french);
