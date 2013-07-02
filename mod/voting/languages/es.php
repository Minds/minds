<?php

	$spanish = array(
	
		/**
		 * Menu items and titles
		 */
	
			'poll' => "Votar",
            'polls:add' => "Nueva Vote",
			'polls' => "Votación",
			'polls:votes' => "Votos",
			'polls:user' => "%s's votar",
			'polls:group_polls' => "Votos del Grupo",
			'polls:group_polls:listing:title' => "%s's votos",
			'polls:user:friends' => "%s's voto amigos",
			'polls:your' => "Tus votos",
			'polls:not_me' => "%s's votos",
			'polls:posttitle' => "%s's votos: %s",
			'polls:friends' => "Amigos votos",
			'polls:not_me_friends' => "%s's Los votos del amigo",
			'polls:yourfriends' => "Últimas votos Los amigos",
			'polls:everyone' => "Todos los votos del sitio",
			'polls:read' => "Leer votar",
			'polls:addpost' => "Crear un voto",
			'polls:editpost' => "Editar un voto: %s",
			'polls:edit' => "Editar un voto",
			'polls:text' => "Vota texto",
			'polls:strapline' => "%s",			
			'item:object:poll' => 'Votos',
			'item:object:poll_choice' => "Vota opciones",
			'polls:question' => "Vota pregunta",
			'polls:responses' => "opciones de respuesta",
			'polls:results' => "[+] Mostrar los resultados",
			'polls:show_results' => "Mostrar resultados",
			'polls:show_poll' => "Mostrar votar",
			'polls:add_choice' => "Añadir opción de respuesta",
			'polls:delete_choice' => "Eliminar esta opción",
			'polls:settings:group:title' => "votos del Grupo",
			'polls:settings:group_polls_default' => "sí, de forma predeterminada",
			'polls:settings:group_polls_not_default' => "sí, desactivada por defecto",
			'polls:settings:no' => "no",
			'polls:settings:group_profile_display:title' => "Si se activan los votos del grupo, donde se debe mostrar votos contenido en los perfiles de grupo?",
			'polls:settings:group_profile_display_option:left' => "izquierda",
			'polls:settings:group_profile_display_option:right' => "derecho",
			'polls:settings:group_profile_display_option:none' => "ninguno",
			'polls:settings:group_access:title' => "Si se activan los votos del grupo, que consigue crear encuestas?",
			'polls:settings:group_access:admins' => "propietarios y administradores de grupo sólo",
			'polls:settings:group_access:members' => "cualquier miembro del grupo",
			'polls:settings:front_page:title' => "Los administradores pueden configurar un sondeo de la primera página (requiere la ayuda del tema)",
			'polls:none' => "Sin votos encontrados.",
			'polls:permission_error' => "No tienes permiso para modificar esta votación.",
			'polls:vote' => "Votar",
			'polls:login' => "Por favor ingresa si desea votar en esta votación.",
			'group:polls:empty' => "No hay encuestas",
			'polls:settings:site_access:title' => "Quién puede crear todo el sitio votos?",
			'polls:settings:site_access:admins' => "Sólo administradores",
			'polls:settings:site_access:all' => "Cualquier usuario registrado",
			'polls:can_not_create' => "Usted no tiene permiso para crear votos.",
			'polls:front_page_label' => "Coloque este voto en la primera página.",
		/**
	     * poll widget
	     **/
			'polls:latest_widget_title' => "Últimas comunidad vota",
			'polls:latest_widget_description' => "Muestra los más recientes votos.",
			'polls:my_widget_title' => "Mis útiles",
			'polls:my_widget_description' => "Este widget te mostrará los votos.",
			'polls:widget:label:displaynum' => "Cuántos votos que desea mostrar?",
			'polls:individual' => "Último voto",
			'poll_individual_group:widget:description' => "Visualice la última votación para este grupo.",
			'poll_individual:widget:description' => "Mostrar su última votación",
			'polls:widget:no_polls' => "No hay votos para %s todavía.",
			'polls:widget:nonefound' => "Sin votos encontrados.",
			'polls:widget:think' => "Deje que %s saber lo que piensa!",
			'polls:enable_polls' => "Habilitar votos",
			'polls:group_identifier' => "(en %s)",
			'polls:noun_response' => "respuesta",
			'polls:noun_responses' => "respuestas",
	        'polls:settings:yes' => "sí",
			'polls:settings:no' => "no",
			
         /**
	     * poll river
	     **/
	        'polls:settings:create_in_river:title' => "Mostrar votar creación en Río actividad",
			'polls:settings:vote_in_river:title' => "Mostrar votar voto en el río actividad",
			'river:create:object:poll' => '%s creado un voto %s',
			'river:vote:object:poll' => '%s votado %s',
			'river:comment:object:poll' => '%s comentado %s',
		/**
		 * Status messages
		 */
	
			'polls:added' => "Su voto fue creado.",
			'polls:edited' => "Su voto fue salvado.",
			'polls:responded' => "Gracias por responder, se registró su voto.",
			'polls:deleted' => "Su voto ha sido eliminada con éxito.",
			'polls:totalvotes' => "Número total de votos: ",
			'polls:voted' => "Su voto ha sido emitido. Gracias por votar.",
			
	
		/**
		 * Error messages
		 */
	
			'polls:save:failure' => "Su voto no se pudo guardar. Por favor, inténtelo de nuevo.",
			'polls:blank' => "Lo sentimos: hay que rellenar la pregunta y las respuestas antes de hacer un voto.",
			'polls:novote' => "Lo sentimos: tiene que elegir una opción de votar en esta votación.",
			'polls:notfound' => "Lo sentimos pero no hemos encontrado el voto especificado.",
			'polls:nonefound' => "No hay encuestas fueron encontrados en %s",
			'polls:notdeleted' => "Lo sentimos: no podíamos eliminar esta votación.",
		
		/**
		 * Filters
		 */
		 	'polls:top' => 'Superior',
		 	'polls:history' => 'Historia',
	);
					
	add_translation("es",$spanish);

?>
