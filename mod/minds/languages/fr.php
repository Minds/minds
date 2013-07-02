<?php


$french = array(
	
	/* Minds renamings and overrides
	 */
	'more' => '&#57349;',
	
	'widgets:add' => 'Widgets',
	
	//login
	'login' => 'Entrer',
	'logout' => 'quitter',
	'register' => 'Créer un canal',
	'register:early' => 'Demander Early Access',
	
	'post' => 'poster',
	
	//change friends to channels
	'access:friends:label' => "canaux",
	
	'friends' => "Réseau",
	'friends:yours' => "Canaux auxquels vous avez souscrits",
	'friends:owned' => "Chaînes %s's souscrit à",
	'friend:add' => "Souscrire",
	'friend:remove' => "Inscrit",

	'friends:add:successful' => "Vous êtes inscrit à %s.",
	'friends:add:failure' => "Nous ne pouvions pas vous abonner à %s.",

	'friends:remove:successful' => "Vous avez supprimé %s de vos abonnements.",
	'friends:remove:failure' => "Nous ne pouvions pas supprimer %s de vos abonnements.",

	'friends:none' => "Pas encore de canaux.",
	'friends:none:you' => "Vous n'êtes pas encore abonné à tous les canaux",

	'friends:none:found' => "Aucune chaîne n'a été trouvé.",

	'friends:of:none' => "Personne n'a encore abonné à la chaîne.",
	'friends:of:none:you' => "Personne n'a souscrit à vous encore. D'ajouter du contenu et remplir votre profil pour que les gens vous trouvent!",

	'friends:of:owned' => "Les personnes qui ont souscrit à %s",

	'friends:of' => "Abonnés",
	'friends:collections' => "Collections de la Manche",
	'collections:add' => "Nouvelle collection",
	'friends:collections:add' => "Collection nouvelle chaîne",
	'friends:addfriends' => "Sélectionnez les canaux",
	'friends:collectionname' => "Nom de la collection",
	'friends:collectionfriends' => "Canaux de collection",
	'friends:collectionedit' => "Modifier cette collection",
	'friends:nocollections' => "Vous n'avez pas encore de collections.",
	'friends:collectiondeleted' => "Votre collection a été supprimé.",
	'friends:collectiondeletefailed' => "Nous n'avons pas réussi à supprimer la collection. Soit vous n'avez pas la permission, ou de quelque autre problème est survenu.",
	'friends:collectionadded' => "Votre collection a été créée avec succès",
	'friends:nocollectionname' => "Vous devez donner à votre collection un nom avant qu'il puisse être créé.",
	'friends:collections:members' => "Membres de la collection",
	'friends:collections:edit' => "Modifier collection",
	'friends:collections:edited' => "Collection sauvé",
	'friends:collection:edit_failed' => "Impossible d'enregistrer collection.",
	
	'river:friend:user:default' => "%s souscrit à %s",
	
	/**
 * Emails
 */
	'email:settings' => "Paramètres de messagerie",
	'email:address:label' => "Votre adresse e-mail",

	'email:save:success' => "Nouvelle adresse e-mail enregistrée.",
	'email:save:fail' => "Votre nouvelle adresse e-mail n'a pas pu être sauvé.",

	'friend:newfriend:subject' => "%s a souscrit pour vous!",
	'friend:newfriend:body' => "%s a souscrit pour vous le Minds!

Pour voir leur canal, cliquez ici:

%s

Vous ne pouvez pas répondre à ce courriel.",



	'email:resetpassword:subject' => "Mot de passe réinitialisé!",
	'email:resetpassword:body' => "Salut %s,

Votre mot de passe a été réinitialisé à: %s",


	'email:resetreq:subject' => "Demande de nouveau mot de passe.",
	'email:resetreq:body' => "Salut %s,

Quelqu'un (à partir de l'adresse IP %s) a demandé un nouveau mot de passe pour leur compte.

Si vous avez demandé cela, cliquez sur le lien ci-dessous. Sinon ignorer cet e-mail.

%s
",
	
	//river menu
	'river:featured' => 'Sélection',
	'river:trending' => 'Tendances',
	'river:thumbs-up' => 'Pouces vers le haut',
	'river:thumbs-down' => 'Pouces vers le bas',
	
	//change activity to news
	'news' => 'Nouvelles', 
	'minds:riverdashboard:addwire' => 'Partagez votre opinion',
	'minds:riverdashboard:annoucement' => 'Annonce',
	'minds:riverdashboard:changeannoucement' => "Modifier l'annonce",
	
	//Minds Specific
	'minds:register:terms:failed' => "S'il vous plaît accepter les termes et conditions pour s'inscrire",
	'minds:register:terms:read' => "J'accepte les termes et conditions",
	'minds:regsiter:terms:link' => ' (Lire)',
	
	'minds:comments:commentcontent' => '%s: %s',
	'minds:comments:likebutton' => 'Comme',
    'minds:comments:unlikebutton' => 'Contrairement',
    'minds:comments:commentsbutton' => 'Commenter',
    'minds:comments:sharebutton' => 'Partager',
    'minds:comments:viewall' => 'Voir tous les commentaires %s',
    'minds:comments:remainder' => 'Visionner les autres commentaires %s',
    'minds:comments:nocomments' => 'Soyez le premier à commenter',
    'minds:commenton' => 'Commentez %s',
    'minds:comments:valuecantbeblank' => 'Commentaire ne peut être vide',
    'minds:remind' => 'ReMind (repost)',
    'minds:remind:success' => 'Succès reMinded',
    
	//river
	'river:remind:object:wall' => '%s reMinded %s\'s pensée',
	'river:remind:object:kaltura' => '%s reMinded %s\'s médias: %s',
	'river:remind:object:blog' => '%s reMinded %s\'s blog',
	'river:remind:api' => '%s reMinded %s',
	
	'river:feature:object:kaltura' => '%s\'s médias %s a été sélectionnée',
	'river:feature:object:blog' => '%s\'s blog a été sélectionnée',
	'river:feature:object:album' => '%s\'s album %s a été présenté',
	'river:feature:object:image' => '%s\'s image %s a été présenté',
	'river:feature:object:tidypics_batch' => '%s\'s %s images ont été présentés',
	
	/* Quota 
	 */
	'minds:quota:statisitcs:title' => 'Votre utilisation',
	'minds:quota:statisitcs:storage' => 'Stockage',
	'minds:quota:statisitcs:bandwidth' => 'Bande passante',
	
	/**
	 * ONLINE USER STATUS
	 *
	 */
	'minds:online_status:online' => 'En ligne',
	
	/**
	 * Thoughts
	 */
	 'minds:thoughts' => 'Pensées',
	
	/**
	 * Minds Universal upload form
	 */
	'minds:upload'=>'Téléchargez',
	'minds:upload:file'=>'Dossier',
	'minds:upload:nofile' => 'Aucun fichier a été téléchargé.',
	
	/* Licenses
	 */
	'minds:license:all' => "All licenses",
	'minds:license:label' => 'License <a href="' . elgg_get_site_url() . 'licenses" target="_blank"> (?) </a>',
	'minds:license:not-selected' => '-- Please select a license --',
	'minds:license:attribution-cc' => 'Attribution CC BY',
	'minds:license:attribution-sharealike-cc' => 'Attribution-ShareAlike BY-SA',
	'minds:license:attribution-noderivs-cc' => 'Attribution-NoDerivs CC BY-ND',
	'minds:license:attribution-noncommerical-cc' => 'Attribution-NonCommerical CC BY-NC',
	'minds:license:attribution-noncommercial-sharealike-cc' => 'Attribution-NonCommerical-ShareAlike CC BY-NC-SA',
	'minds:license:attribution-noncommercial-noderivs-cc' => 'Attribution-NonCommerical-NoDerivs CC BY-NC-ND',
	'minds:license:publicdomaincco' => 'Public Domain CCO "No Rights Reserved"',
	'minds:license:gnuv3' => 'GNU v3 General Public License',
	'minds:license:gnuv1.3' => 'GNU v1.3 Free Documentation License',
	'minds:license:gnu-lgpl' => 'GNU Lesser General Public License',
	'minds:license:gnu-affero' => 'GNU Affero General Public License',
	'minds:license:apache-v1' => 'Apache License, Version 1.0',
	'minds:license:apache-v1.1' => 'Apache License, Version 1.1',
	'minds:license:apache-v2' => 'Apache License, Version 2.0',
	'minds:license:mozillapublic' => 'Mozilla Public License',
	'minds:license:bsd' => 'BSD License',
	
	'categories' => 'Category',
	
	'blog:owner_more_posts' => 'More blogs from %s',
	'blog:featured' => 'Featured blogs',
	'readmore' => '→ read more',
	'minds:embed:youtube' => 'Youtube',

    
    
        'register:node' => 'Launch a social network',
        "register:node:testping" => 'Multisite node DNS Test',
);
		
add_translation("fr", $french);