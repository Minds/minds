<?php 
	/**
	* Profile Manager
	* 
	* English language
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	$italian = array(
		'profile_manager' => "Amministrazione profili",
		'custom_profile_fields' => "Campi personalizzati",
		'item:object:custom_profile_field' => 'Campo personalizzato',
		'item:object:custom_profile_field_category' => 'Categoria personalizzata',
		'item:object:custom_profile_type' => 'Tipo personalizzato',
		'item:object:custom_group_field' => 'Campi personalizzati per i gruppi',
		
		// admin
		'profile_manager:admin:metadata_name' => 'Nome',	
		'profile_manager:admin:metadata_label' => 'Etichetta',
		'profile_manager:admin:metadata_hint' => 'Tooltip',
		'profile_manager:admin:metadata_description' => 'Descrizione',
		'profile_manager:admin:metadata_label_translated' => 'Etichetta (Tradotto)',
		'profile_manager:admin:metadata_label_untranslated' => 'Etichetta (Non tradotto)',
		'profile_manager:admin:metadata_options' => 'Opzioni (separate da virgola)',
		'profile_manager:admin:options:datepicker' => 'Data',
		'profile_manager:admin:options:pulldown' => 'Select',
		'profile_manager:admin:options:radio' => 'Radio',
		'profile_manager:admin:options:multiselect' => 'MultiSelect',
		'profile_manager:admin:show_on_members' => "Mostra come filtro nella pagina Membri",
		
		'profile_manager:admin:additional_options' => 'Opzioni supplementari',
		'profile_manager:admin:show_on_register' => 'Mostra nel form di registrazione',	
		'profile_manager:admin:mandatory' => 'Obbligatorio',
		'profile_manager:admin:user_editable' => 'L&acute;utente pu&ograve; modificare questo campo',
		'profile_manager:admin:output_as_tags' => 'Mostra nel profilo come tag',
		'profile_manager:admin:admin_only' => 'Solo per l&acute;amministratore',
		'profile_manager:admin:simple_search' => 'Mostra nel form semplice di ricerca',	
		'profile_manager:admin:advanced_search' => 'Mostra nel form avanzato di ricerca',		
		'profile_manager:admin:option_unavailable' => 'Opzione non disponibile',
	
		'profile_manager:admin:profile_icon_on_register' => 'Aggiungi un campo per l\'icona obbligatorio nel form di registrazione',
		'profile_manager:admin:simple_access_control' => 'Mostra il controllo della privacy una volta sola',
		
		'profile_manager:admin:hide_non_editables' => 'Nascondi i campi non modificabili nel form modifica',
	
		'profile_manager:admin:edit_profile_mode' => "Come mostrare il form modifica",
		'profile_manager:admin:edit_profile_mode:list' => "Lista",
		'profile_manager:admin:edit_profile_mode:tabbed' => "In gruppi",
	
		'profile_manager:admin:show_full_profile_link' => 'Mostra un link al profilo completo',
	
		'profile_manager:admin:display_categories' => 'Come mostrare le diverse categorie',
		'profile_manager:admin:display_categories:option:plain' => 'Lista',
		'profile_manager:admin:display_categories:option:accordion' => 'In gruppi',
	
		'profile_manager:admin:profile_type_selection' => 'Chi pu&ograve; cambiare i tipi di profilo?',
		'profile_manager:admin:profile_type_selection:option:user' => 'Utenti',
		'profile_manager:admin:profile_type_selection:option:admin' => 'Amministratori',
	
		'profile_manager:admin:show_admin_stats' => "Mostra statistiche",
		'profile_manager:admin:show_members_search' => "Mostra la pagina ricerca utenti personalizzata",
	
		'profile_manager:admin:warning:profile' => "Attenzione: Questo plugin deve trovarsi sotto il plugin Profile",
	
		// profile field additionals description
		'profile_manager:admin:show_on_register:description' => "Se vuoi che questo campo appaia nel form di registrazione.",	
		'profile_manager:admin:mandatory:description' => "Se vuoi che questo campo sia obbligatorio (solo nel form di registrazione).",
		'profile_manager:admin:user_editable:description' => "Se imposti 'No' l'utente non potr&agrave; modificare questo campo.",
		'profile_manager:admin:output_as_tags:description' => "Visualizza il campo come tag nel profilo.",
		'profile_manager:admin:admin_only:description' => "Seleziona 'Si' se vuoi che questo campo sia disponibile solo per l'amministratore.",
		'profile_manager:admin:simple_search:description' => "Seleziona 'Si' se vuoi che il campo sia disponibile nel form semplice di ricerca.",	
		'profile_manager:admin:advanced_search:description' => "Seleziona 'Si' se vuoi che il campo sia disponibile nel form avanzato di ricerca.",
		
		// non_editable
		'profile_manager:non_editable:info' => 'Questo campo non pu&ograve; essere modificato',
	
		// profile user links
		'profile_manager:show_full_profile' => 'Profilo completo',
	
		// datepicker
		'profile_manager:datepicker:output:dateformat' => '%a %d %b %Y', // For available notations see http://nl.php.net/manual/en/function.strftime.php
		'profile_manager:datepicker:input:localisation' => '', // change it to the available localized js files in custom_profile_fields/vendors/jquery.datepick.package-3.5.2 (e.g. jquery.datepick-nl.js), leave blank for default 
		'profile_manager:datepicker:input:dateformat' => '%m/%d/%Y', // Notation is based on strftime, but must result in output like http://keith-wood.name/datepick.html#format
		'profile_manager:datepicker:input:dateformat_js' => 'mm/dd/yy', // Notation is based on strftime, but must result in output like http://keith-wood.name/datepick.html#format
		
		// register form mandatory notice
		'profile_manager:register:mandatory' => "I campi contrassegnati da * sono obbligatori.",	
	
		// register profile icon
		'profile_manager:register:profile_icon' => 'Devi selezionare una foto per il profilo.',
		
		// simple access control
		'profile_manager:simple_access_control' => 'Scegli chi pu&ograve; vedere le informazioni del tuo profilo.',
	
		// register pre check
		'profile_manager:register_pre_check:missing' => 'Non pu&ograve; essere vuoto: %s',
		'profile_manager:register_pre_check:profile_icon:error' => 'Errore nel caricare la foto.',
		'profile_manager:register_pre_check:profile_icon:nosupportedimage' => 'La foto caricata non &egrave; del formato giusto (jpg, gif, png)',
	
		// actions
		// new
		'profile_manager:actions:new:success' => 'Campo aggiunto con successo.',	
		'profile_manager:actions:new:error:metadata_name_missing' => 'Nessun nome specificato.',	
		'profile_manager:actions:new:error:metadata_name_invalid' => 'Nome non valido.',	
		'profile_manager:actions:new:error:metadata_options' => 'Devi inserire delle opzioni se scegli questo tipo.',	
		'profile_manager:actions:new:error:unknown' => 'Errore sconosciuto.',
		'profile_manager:action:new:error:type' => 'Tipo non valido.',
		
		// edit
		'profile_manager:actions:edit:error:unknown' => 'Errore nel cercare i campi del profilo.',
	
		//reset
		'profile_manager:actions:reset' => 'Reset',
		'profile_manager:actions:reset:description' => 'Rimuovi tutti i campi personalizzati.',
		'profile_manager:actions:reset:confirm' => 'Sei sicuro di voler resettare il profilo?',
		'profile_manager:actions:reset:error:unknown' => 'Errore sconosciuto.',
		'profile_manager:actions:reset:error:wrong_type' => 'Tipo non valido',
		'profile_manager:actions:reset:success' => 'Profilo resettato.',
	
		//delete
		'profile_manager:actions:delete:confirm' => 'Sei sicuro di voler rimuovere questo campo?',
		'profile_manager:actions:delete:error:unknown' => 'Errore sconosciuto',

		// toggle option
		'profile_manager:actions:toggle_option:error:unknown' => 'Errore sconosciuto',

		// actions
		'profile_manager:actions:title' => 'Azioni',
	
		// import from custom
		'profile_manager:actions:import:from_custom' => 'Importa personalizzazione',
		'profile_manager:actions:import:from_custom:description' => 'Importa una personalizzazione precedente.',
		'profile_manager:actions:import:from_custom:confirm' => 'Sei sicuro di voler importare queste configurazioni?',
		'profile_manager:actions:import:from_custom:no_fields' => 'Nessun campo personalizzato da importare',
		'profile_manager:actions:import:from_custom:new_fields' => 'Campo <b>%s</b> importato con successo.',
	
		// import from default
		'profile_manager:actions:import:from_default' => 'Importa personalizzazione di default',
		'profile_manager:actions:import:from_default:description' => "Importa i campi di default di Elgg.",
				
		'profile_manager:actions:import:from_default:confirm' => 'Sei sicuro di voler importare le configurazioni di default?',
		'profile_manager:actions:import:from_default:no_fields' => 'Nessun campo di default da importare',
		'profile_manager:actions:import:from_default:new_fields' => 'Campo <b>%s</b> importato con successo',
		'profile_manager:actions:import:from_default:error:wrong_type' => 'Tipo non valido',
	
		// category to field
		'profile_manager:actions:change_category:error:unknown' => "Errore sconosciuto",
	
		// add category
		'profile_manager:action:category:add:error:name' => "Nessun nome specificato",
		'profile_manager:action:category:add:error:object' => "Errore nel creare la categoria",
		'profile_manager:action:category:add:error:save' => "Errore nel salvare la categoria",
		'profile_manager:action:category:add:succes' => "Categoria creata con successo",
	
		// delete category
		'profile_manager:action:category:delete:error:guid' => "Nessun GUID",
		'profile_manager:action:category:delete:error:type' => "GUID inserito non valido",
		'profile_manager:action:category:delete:error:delete' => "Errore nel rimuovere la categoria",
		'profile_manager:action:category:delete:succes' => "Categoria rimossa con successo",
	
		// add profile type
		'profile_manager:action:profile_types:add:error:name' => "Nessun nome specificato",
		'profile_manager:action:profile_types:add:error:object' => "Errore nel creare il tipo",
		'profile_manager:action:profile_types:add:error:save' => "Errore nel salvare il tipo",
		'profile_manager:action:profile_types:add:succes' => "Tipo creato con successo",
		
		// delete profile type
		'profile_manager:action:profile_types:delete:error:guid' => "Nessun GUID",
		'profile_manager:action:profile_types:delete:error:type' => "GUID inserito non valido",
		'profile_manager:action:profile_types:delete:error:delete' => "Errore sconosciuto",
		'profile_manager:action:profile_types:delete:succes' => "Tipo rimosso con successo",
		
		// Custom Group Fields
		'profile_manager:group_fields' => "Modifica i campi dei gruppi",
		'profile_manager:group_fields:title' => "Modifica i campi dei gruppi",
		
		'profile_manager:group_fields:add:description' => "Qui puoi modificare i campi che appariranno nel profilo del gruppo",
		'profile_manager:group_fields:add:link' => "Aggiungi un nuovo campo ai gruppi",
		
		'profile_manager:profile_fields:add:description' => "Qui puoi modificare i campi che appariranno nel profilo utente",
		'profile_manager:profile_fields:add:link' => "Aggiungi un nuovo campo al profilo",
	
		// Custom fields categories
		'profile_manager:categories:add:link' => "Aggiungi categoria",
		
		'profile_manager:categories:list:title' => "Categorie",
		'profile_manager:categories:list:default' => "Profilo",
		'profile_manager:categories:list:view_all' => "Vedi tutti i campi",
		'profile_manager:categories:list:no_categories' => "Nessuna categoria definita",
		
		'profile_manager:categories:delete:confirm' => "Sei sicuro di voler rimuovere questa categoria?",
		
		// Custom Profile Types
		'profile_manager:profile_types:add:link' => "Aggiungi tipo",
		
		'profile_manager:profile_types:list:title' => "Tipi di profilo",
		'profile_manager:profile_types:list:no_types' => "nessun tipo definito",
	
		'profile_manager:profile_types:delete:confirm' => "Sei sicuro di voler rimuovere questo tipo?",
		
		// Export
		'profile_manager:actions:export' => "Esporta personalizzazione",
		'profile_manager:actions:export:description' => "Esporta in csv",
		'profile_manager:export:title' => "Esporta personalizzazione",
		'profile_manager:export:description:custom_profile_field' => "This function will export all <b>user</b> metadata based on selected fields.",
		'profile_manager:export:description:custom_group_field' => "This function will export all <b>group</b> metadata based on selected fields.",
		'profile_manager:export:list:title' => "Seleziona i campi da esportare",
		'profile_manager:export:nofields' => "Nessun campo disponibile per l'esportazione",
	
		// Configuration Backup and Restore
		'profile_manager:actions:configuration:backup' => "Backup configurazione",
		'profile_manager:actions:configuration:backup:description' => "Backup the configuration of these fields (<b>categories and types are not backed up</b>)",
		'profile_manager:actions:configuration:restore' => "Ripristina configurazione",
		'profile_manager:actions:configuration:restore:description' => "Restore a previously backed up configuration file (<b>you will loose relations between fields and categories</b>)",
		
		'profile_manager:actions:configuration:restore:upload' => "Ripristina",
	
		'profile_manager:actions:restore:success' => "Ripristino avvenuto con successo",
		'profile_manager:actions:restore:error:deleting' => "Errore di ripristino: non &egrave; possibile eliminare i campi correnti",	
		'profile_manager:actions:restore:error:fieldtype' => "Errore di ripristino: il tipo dei campi non corrisponde",
		'profile_manager:actions:restore:error:corrupt' => "Errore di ripristino: il backup &egrave; rovinato",
		'profile_manager:actions:restore:error:json' => "Errore di ripristino: invalid json file",
		'profile_manager:actions:restore:error:nofile' => "Errore di ripristino: nessun file selezionato",
	
		// Tooltips
		'profile_manager:tooltips:profile_field' => "
			<b>Profile Field</b><br />
			Here you can add a new profile field.<br /><br />
			If you leave the label empty, you can internationalize the profile field label (<i>profile:[name]</i>).<br /><br />
			Use the hint field to supply on input forms (register and profile/group edit) a hoverable icon with a field description.<br /><br />
			Options are only mandatory for fieldtypes <i>Pulldown, Radio and MultiSelect</i>.
		",
		'profile_manager:tooltips:profile_field_additional' => "
			<b>Show on register</b><br />
			If you want this field to be on the register form.<br /><br />
			
			<b>Mandatory</b><br />
			If you want this field to be mandatory (only applies to the register form).<br /><br />
			
			<b>User editable</b><br />
			If set to 'No' users can't edit this field (handy when data is managed in an external system).<br /><br />
			
			<b>Show as tags</b><br />
			Data output will be handle as tags (only applies on user profile).<br /><br />
			
			<b>Admin only field</b><br />
			Select 'Yes' if field is only available for admins.
		",
		'profile_manager:tooltips:category' => "
			<b>Category</b><br />
			Here you can add a new profile category.<br /><br />
			If you leave the label empty, you can internationalize the category label (<i>profile:categories:[name]</i>).<br /><br />
			
			If Profile Types are defined you can choose on which profile type this category applies. If no profile is specified, the category applies to all profile types (even undefined).
		",
		'profile_manager:tooltips:category_list' => "
			<b>Categories</b><br />
			Shows a list of all configured categories.<br /><br />
			
			<i>Default</i> is the category that applies to all profiles.<br /><br />
			
			Add fields to these categories by dropping them on the categories.<br /><br />
			
			Click the category label to filter the visible fields. Clicking view all fields shows all fields.<br /><br />
			
			You can also change the order of the categories by dragging them (<i>Default can't be dragged</i>. <br /><br />
			
			Click the edit icon to edit the category.
		",
		'profile_manager:tooltips:profile_type' => "
			<b>Profile Type</b><br />
			Here you can add a new profile type.<br /><br />
			If you leave the label empty, you can internationalize the profile type label (<i>profile:types:[name]</i>).<br /><br />
			Enter a description which users can see when selecting this profile type or leave it empty to internationalize (<i>profile:types:[name]:description</i>).<br /><br />
			You can add this profile type as filterable to the members search page<br /><br />
			
			If Categories are defined you can choose which categories apply to this profile type.
		",
		'profile_manager:tooltips:profile_type_list' => "
			<b>Profile Types</b><br />
			Shows a list of all configured profile types.<br /><br />
			Click the edit icon to edit the profile type.
		",
		'profile_manager:tooltips:actions' => "
			<b>Actions</b><br />
			Various actions related to these profile fields.
		",
		
		// Edit profile => profile type selector
		'profile_manager:profile:edit:custom_profile_type:label' => "Seleziona il tipo di profilo",
		'profile_manager:profile:edit:custom_profile_type:description' => "Descrizione",
		'profile_manager:profile:edit:custom_profile_type:default' => "Ospite",
	
		// Admin Stats
		'profile_manager:admin_stats:title'=> "Statistiche",
		'profile_manager:admin_stats:total'=> "Utenti",
		'profile_manager:admin_stats:profile_types'=> "Numero di utenti con profilo",
	
		// Members
		'profile_manager:members:menu' => "Membri",
		'profile_manager:members:submenu' => "Cerca membri",
		'profile_manager:members:searchform:title' => "Ricerca membri",
		'profile_manager:members:searchform:simple:title' => "Ricerca semplice",
		'profile_manager:members:searchform:advanced:title' => "Ricerca avanzata",
		'profile_manager:members:searchform:sorting' => "Ordinamento",
		'profile_manager:members:searchform:date:from' => "da",
		'profile_manager:members:searchform:date:to' => "a",
		'profile_manager:members:searchresults:title' => "Risultati della ricerca",
		'profile_manager:members:searchresults:query' => "QUERY",
		'profile_manager:members:searchresults:noresults' => "Nessun utente trovato",
		
	
		// Admin add user form
		'profile_manager:admin:adduser:notify' => "Notifica all'utente",
		'profile_manager:admin:adduser:use_default_access' => "Campi creati automaticamente dalle impostazioni di default",
		'profile_manager:admin:adduser:extra_metadata' => "Aggiungi campi personalizzati",
	
	);
	
	add_translation("it", $italian);
?>