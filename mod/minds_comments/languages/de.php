<?php

$german = array(
	'minds_comments:save:success' => 'Ihr Kommentar wurde gespeichert',
    /**
     * Comments
     */
    'hj:alive:comments:likebutton' => 'Wie',
    'hj:alive:comments:unlikebutton' => 'Im Gegensatz zu',
    'hj:alive:comments:commentsbutton' => 'Kommentar',
    'hj:alive:comments:sharebutton' => 'Teilen',
    'hj:alive:comments:viewall' => 'Alle %s Kommentare',
    'hj:alive:comments:remainder' => 'Sehen restlichen %s Kommentare',
    'hj:alive:comments:nocomments' => 'Als Erster einen Kommentar',
    'hj:comment:commenton' => 'Ihre Meinung zu %s',
    'hj:alive:comments:valuecantbeblank' => 'Kommentar darf nicht leer sein',

    'hj:alive:comments:lang:you' => 'Sie ',
    'hj:alive:comments:lang:and' => 'und ',
    'hj:alive:comments:lang:others' => 'anderen Menschen ',
    'hj:alive:comments:lang:othersone' => 'andere Person ',
    'hj:alive:comments:lang:people' => 'menschen ',
    'hj:alive:comments:lang:peopleone' => 'person ',
    'hj:alive:comments:lang:likethis' => 'folgendermaßen',
    'hj:alive:comments:lang:likesthis' => 'gefällt das.',


    'hj:alive:comments:count' => 'Kommentare',
    'hj:alive:comments:comments' => 'Kommentare',
    'hj:alive:comments:delete' => 'löschen',
    'hj:alive:comments:newcomment' => 'Schreibe einen Kommentar',

    'hj:alive:comments:addtopic' => 'Neues Thema hinzufügen',
    'hj:alive:comments:forumtopictitle' => 'Geben Sie Ihre Forentitel...',
    'hj:alive:comments:forumtopicdescription' => 'Geben Sie Ihren Kommentar Nachricht...',
    'eComents:forumtopicaddbutton' => 'Hinzufügen',

    'hj:alive:comments:commentmissing' => 'Oh, ist Ihr Kommentar fehlt',
    'hj:alive:comments:bodymissing' => 'Oh, Sie haben keinen Text eingegeben',
    'hj:alive:comments:topicmissing' => 'Oh, müssen Sie einen Namen für Ihr Forum Thema geben',

    'hj:alive:comments:commenton' => 'Ihre Meinung zu %s',
    'hj:alive:comments:commentcontent' => '%s: %s',
	'hj:alive:comment_on:river' => 'Ihre Meinung zu einer Aktivität: %s',

    'hj:comments:cantfind' => 'Ups, da war ein Problem, indem Sie Ihren Kommentar. Der Artikel muss gelöscht wurden',
    'hj:comments:savesuccess' => 'Ihr Kommentar wurde erfolgreich hinzugefügt',
    'hj:comments:refreshing' => 'Erfrischend...',

    'hj:likes:savesuccess' => 'Nun mag dies',
    'hj:likes:saveerror' => "Es tut uns leid, wir konnten \ 't wie Ihr Prozess",
    'hj:likes:likeremoved' => 'Ihr wie wurde entfernt',

    /**
     * NOTIFICATIONS
     */
    'hj:comments:notify:activity_type:create' => 'Neue %s %s hinzugefügt',
    'hj:comments:notify:activity_type:update' => 'Updates für %s %s',
    'hj:comments:notify:activity' => 'Aktivität | <br />%s',

    'hj:comments:notify:post' => 'Inhalt | %s %s',

    // Level 1
    'generic_comment:email:level1:subject' => 'Sie haben einen neuen Kommentar',
    'generic_comment:email:level1:body' =>
            "Sie haben einen neuen Kommentar ab %s auf Ihrem %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Sie können hier antworten: <br />
                %s.
            ",

    // Level 2
    'generic_comment:email:level2:subject' => 'Neuer Kommentar in einer Diskussion',
    'generic_comment:email:level2:body' =>
            "Es gibt einen neuen Beitrag von %s in einer Diskussion über %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Sie können hier antworten: <br />
                %s.
            ",

    'group_topic_post:email:level1:subject' => 'Neuer Beitrag auf Ihrer Gruppe Thema',
    'group_topic_post:email:level1:body' =>
            "Sie haben einen neuen Beitrag von %s auf Ihrem %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Sie können hier antworten: <br />
                %s.
            ",

    'group_topic_post:email:level2:subject' => 'Neue Gruppe Thema Beitrag',
    'group_topic_post:email:level2:body' =>
            "Es gibt einen neuen Beitrag von %s in einer Diskussion über %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Sie können hier antworten: <br />
                %s.
            ",

    // Level 1
    'likes:email:level1:subject' => 'Sie haben eine neue wie',
    'likes:email:level1:body' =>
            "%s mag Ihre %s <br />
            ",

    // Level 2
    'likes:email:level2:subject' => 'New wie in einer Diskussion',
    'likes:email:level2:body' =>
            "%s mag eine der Antworten in einer Diskussion über %s<br />
                <br />
            ",

    /**
     * LiveSearch
     */
    'hj:alive:search:user' => 'Benutzer',
    'hj:alive:search:group' => 'Gruppen',
    'hj:alive:search:blog' => 'Blogs',
    'hj:alive:search:bookmarks' => 'Lesezeichen',
    'hj:alive:search:file' => 'Dateien',

	'search_types:group_topic_posts' => 'Diskussion Beiträge',
	'hj:alive:reply_to' => 'Auf Thema antworten "%s" in der Gruppe "%s"',
);

add_translation("de", $german);

?>