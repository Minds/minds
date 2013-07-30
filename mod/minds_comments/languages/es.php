<?php

$spanish = array(
	'minds_comments:save:success' => 'Tu comentario ha sido guardado',
    /**
     * Comments
     */
    'hj:alive:comments:likebutton' => 'Como',
    'hj:alive:comments:unlikebutton' => 'Desemejante',
    'hj:alive:comments:commentsbutton' => 'Comentario',
    'hj:alive:comments:sharebutton' => 'Participación',
    'hj:alive:comments:viewall' => 'Ver todos los %s comentarios',
    'hj:alive:comments:remainder' => 'Ver restantes %s comentarios',
    'hj:alive:comments:nocomments' => 'Sé el primero en comentar',
    'hj:comment:commenton' => 'Opina sobre %s',
    'hj:alive:comments:valuecantbeblank' => 'El comentario no puede estar en blanco',

    'hj:alive:comments:lang:you' => 'Usted ',
    'hj:alive:comments:lang:and' => 'y ',
    'hj:alive:comments:lang:others' => 'otras personas ',
    'hj:alive:comments:lang:othersone' => 'otra persona',
    'hj:alive:comments:lang:people' => 'personas ',
    'hj:alive:comments:lang:peopleone' => 'persona ',
    'hj:alive:comments:lang:likethis' => 'como este',
    'hj:alive:comments:lang:likesthis' => 'le gusta esto',


    'hj:alive:comments:count' => 'Comentarios',
    'hj:alive:comments:comments' => 'Comentarios',
    'hj:alive:comments:delete' => 'Borrar',
    'hj:alive:comments:newcomment' => 'Escribir un comentario',

    'hj:alive:comments:addtopic' => 'Añadir un nuevo tema',
    'hj:alive:comments:forumtopictitle' => 'Escriba el título de su foro...',
    'hj:alive:comments:forumtopicdescription' => 'Ingrese el mensaje Foro...',
    'eComents:forumtopicaddbutton' => 'Añadir',

    'hj:alive:comments:commentmissing' => 'Oh, su comentario no se encuentra',
    'hj:alive:comments:bodymissing' => 'Oh, usted no ha introducido ningún texto',
    'hj:alive:comments:topicmissing' => 'Oh, es necesario introducir un nombre para el tema del foro',

    'hj:alive:comments:commenton' => 'Opina sobre %s',
    'hj:alive:comments:commentcontent' => '%s: %s',
	'hj:alive:comment_on:river' => 'Opina sobre una actividad: %s',

    'hj:comments:cantfind' => 'Vaya, hubo un problema al añadir tu comentario. El artículo debe haber sido eliminado',
    'hj:comments:savesuccess' => 'Tu comentario ha sido añadido',
    'hj:comments:refreshing' => 'Refrescante...',

    'hj:likes:savesuccess' => 'Ahora usted tiene gusto de este',
    'hj:likes:saveerror' => "Lo sentimos, pero no podía \ 't proceso de su gusto",
    'hj:likes:likeremoved' => 'Tu como fue removido',

    /**
     * NOTIFICATIONS
     */
    'hj:comments:notify:activity_type:create' => 'Nuevo %s %s adicional',
    'hj:comments:notify:activity_type:update' => 'actualizaciones %s %s',
    'hj:comments:notify:activity' => 'actividad | <br />%s',

    'hj:comments:notify:post' => 'contenido | %s %s',

    // Level 1
    'generic_comment:email:level1:subject' => 'Usted tiene un nuevo comentario',
    'generic_comment:email:level1:body' =>
            "Usted tiene un nuevo mensaje de %s en su %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Usted puede responder aquí: <br />
                %s.
            ",

    // Level 2
    'generic_comment:email:level2:subject' => 'Nuevo comentario en un hilo de discusión',
    'generic_comment:email:level2:body' =>
            "Hay un nuevo comentario de %s en un debate sobre %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Usted puede responder aquí: <br />
                %s.
            ",

    'group_topic_post:email:level1:subject' => 'Nuevo post en el tema del grupo',
    'group_topic_post:email:level1:body' =>
            "Usted tiene un nuevo mensaje de %s en su %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Usted puede responder aquí: <br />
                %s.
            ",

    'group_topic_post:email:level2:subject' => 'New post tema del grupo',
    'group_topic_post:email:level2:body' =>
            "Hay un nuevo mensaje de %s en un debate sobre %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                Usted puede responder aquí: <br />
                %s.
            ",

    // Level 1
    'likes:email:level1:subject' => 'Usted tiene un nuevo como',
    'likes:email:level1:body' =>
            "%s le gusta su %s <br />
            ",

    // Level 2
    'likes:email:level2:subject' => 'Nuevo como en un hilo de discusión',
    'likes:email:level2:body' =>
            "%s le gusta una de las respuestas en un debate sobre %s<br />
                <br />
            ",

    /**
     * LiveSearch
     */
    'hj:alive:search:user' => 'Usuarios',
    'hj:alive:search:group' => 'Grupos',
    'hj:alive:search:blog' => 'Blogs',
    'hj:alive:search:bookmarks' => 'Marcadores',
    'hj:alive:search:file' => 'Archivos',

	'search_types:group_topic_posts' => 'Mensajes discusión',
	'hj:alive:reply_to' => 'Responder al tema "%s" en el grupo "%s"',
);

add_translation("es", $spanish);

?>