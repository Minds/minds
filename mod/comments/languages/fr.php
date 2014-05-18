<?php

$french = array(
	'minds_comments:save:success' => 'Votre commentaire a été enregistré',
    /**
     * Comments
     */
    'hj:alive:comments:likebutton' => 'Comme',
    'hj:alive:comments:unlikebutton' => 'Contrairement à',
    'hj:alive:comments:commentsbutton' => 'Commenter',
    'hj:alive:comments:sharebutton' => 'Partager',
    'hj:alive:comments:viewall' => 'Voir tous les %s commentaires',
    'hj:alive:comments:remainder' => 'Voir les %s autres commentaires',
    'hj:alive:comments:nocomments' => 'Soyez le premier à commenter',
    'hj:comment:commenton' => 'Commentez %s',
    'hj:alive:comments:valuecantbeblank' => 'Commentaire ne peut être vide',

    'hj:alive:comments:lang:you' => 'Vous ',
    'hj:alive:comments:lang:and' => 'et ',
    'hj:alive:comments:lang:others' => "d'autres personnes ",
    'hj:alive:comments:lang:othersone' => 'autre personne ',
    'hj:alive:comments:lang:people' => 'personnes ',
    'hj:alive:comments:lang:peopleone' => 'personne ',
    'hj:alive:comments:lang:likethis' => 'comme ça',
    'hj:alive:comments:lang:likesthis' => 'aime ça',


    'hj:alive:comments:count' => 'commentaires',
    'hj:alive:comments:comments' => 'commentaires',
    'hj:alive:comments:delete' => 'Supprimer',
    'hj:alive:comments:newcomment' => 'Écrire un commentaire',

    'hj:alive:comments:addtopic' => 'Ajouter un nouveau sujet',
    'hj:alive:comments:forumtopictitle' => 'Entrez le titre de votre forum...',
    'hj:alive:comments:forumtopicdescription' => 'Entrez votre message dans le forum...',
    'eComents:forumtopicaddbutton' => 'Ajouter',

    'hj:alive:comments:commentmissing' => 'Oh, votre commentaire est manquante',
    'hj:alive:comments:bodymissing' => "Oh, vous n'avez pas entré de texte",
    'hj:alive:comments:topicmissing' => 'Oh, vous devez entrer un nom pour votre sujet sur le forum',

    'hj:alive:comments:commenton' => 'Commentez %s',
    'hj:alive:comments:commentcontent' => '%s: %s',
	'hj:alive:comment_on:river' => 'Commentaire sur une activité: %s',

    'hj:comments:cantfind' => "Oops, il y avait un problème en ajoutant votre commentaire. L'article doit avoir été supprimé",
    'hj:comments:savesuccess' => 'Votre commentaire a été ajouté avec succès',
    'hj:comments:refreshing' => 'Réconfortant...',

    'hj:likes:savesuccess' => 'Vous aimez maintenant cette',
    'hj:likes:saveerror' => "Désolé, nous n \ 't votre processus comme",
    'hj:likes:likeremoved' => 'Votre similaire a été retiré',

    /**
     * NOTIFICATIONS
     */
    'hj:comments:notify:activity_type:create' => 'Nouveau %s %s ajoutée',
    'hj:comments:notify:activity_type:update' => 'Mises à jour %s %s',
    'hj:comments:notify:activity' => 'activité | <br />%s',

    'hj:comments:notify:post' => 'contenu | %s %s',

    // Level 1
    'generic_comment:email:level1:subject' => 'Vous avez un nouveau commentaire',
    'generic_comment:email:level1:body' =>
            "Vous avez un nouveau commentaire de %s sur votre %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Vous pouvez répondre ici: <br />
                %s.
            ",

    // Level 2
    'generic_comment:email:level2:subject' => 'Nouveau commentaire dans un fil de discussion',
    'generic_comment:email:level2:body' =>
            "Il s'agit d'un nouveau commentaire de %s à une discussion sur %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Vous pouvez répondre ici: <br />
                %s.
            ",

    'group_topic_post:email:level1:subject' => 'Nouveau message sur le sujet de votre groupe',
    'group_topic_post:email:level1:body' =>
            "Vous avez un nouveau message de %s sur votre %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Vous pouvez répondre ici: <br />
                %s.
            ",

    'group_topic_post:email:level2:subject' => 'Nouveau groupe post sujet',
    'group_topic_post:email:level2:body' =>
            "Il s'agit d'un nouveau poste de %s à une discussion sur %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Vous pouvez répondre ici: <br />
                %s.
            ",

    // Level 1
    'likes:email:level1:subject' => 'Vous avez un nouveau comme',
    'likes:email:level1:body' =>
            "%s aime votre %s <br />
            ",

    // Level 2
    'likes:email:level2:subject' => 'Nouveau comme dans un fil de discussion',
    'likes:email:level2:body' =>
            "%s aime l'une des réponses à une discussion sur %s<br />
                <br />
            ",

    /**
     * LiveSearch
     */
    'hj:alive:search:user' => 'Utilisateurs',
    'hj:alive:search:group' => 'Groupes',
    'hj:alive:search:blog' => 'Blogs',
    'hj:alive:search:bookmarks' => 'Bookmarks',
    'hj:alive:search:file' => 'Fichiers',

	'search_types:group_topic_posts' => 'Messages de discussion',
	'hj:alive:reply_to' => 'Répondre au sujet "%s" dans le groupe "%s"',
);

add_translation("fr", $french);

?>