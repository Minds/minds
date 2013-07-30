<?php
/**
 * Pages Danish language file
 *
 * @package ElggPages
 */
	 
$danish = array(

/**
 * Menu items and titles
 */ 

	'pages'  =>  "Sider" , 
	'pages:owner' => "%s's sider",
	'pages:friends' => "Venners sider",
	'pages:all' => "Alle sider",
	'pages:add' => "Tilføj side",
	 
	'pages:group'  =>  "Gruppe sider", 
	'groups:enablepages' => 'Aktiver gruppe sider',

	'pages:edit'  =>  "Rediger denne side" , 
	'pages:delete'  =>  "Slet denne side" , 
	'pages:history'  =>  "Sidehistorik" ,	
	'pages:view'  =>  "Se side" , 
	'pages:revision' => "Revision",

	'pages:navigation'  =>  "Navigation" ,
	'pages:via' => "via sider", 
	'item:object:page_top'  =>  "Top-niveau sider" , 
	'item:object:page'  =>  "Sider" , 
	'pages:nogroup' => 'Denne gruppe har ikke nogen sider endnu',
	'pages:more' => 'Flere sider',
	'pages:none' => 'Der er ikke oprettet sider endnu',
	
	/**
	* River
	**/
	
	'river:create:object:page' => '%s oprettede siden %s',
	'river:create:object:page_top' => '%s oprettede siden %s',
	'river:update:object:page' => '%s opdaterede siden %s',
	'river:update:object:page_top' => '%s opdaterede siden %s',
	'river:comment:object:page' => '%s kommenterede siden %s',
	'river:comment:object:page_top' => '%s kommenterede siden %s',

	/**
	 * Form fields
	 */

	'pages:title' => 'Sidetitler',
	'pages:description' => 'Side indhold',
	'pages:tags' => 'Tags',	
	'pages:access_id' => 'Læseadgang',
	'pages:write_access_id' => 'Skriveadgang',

/**
 * Status and error messages
 */	  
	'pages:noaccess'  =>  "Igen adgang til side" , 
	'pages:cantedit'  =>  "Du kan ikke redigere denne side" , 
	'pages:saved'  =>  "Siden gemt" , 
	'pages:notsaved'  =>  "Siden kunne ikke gemmes" , 
	'pages:error:no_title'  =>  "Din side skal have en titel." , 
	'pages:delete:success'  =>  "Din side er blevet slettet" , 
	'pages:delete:failure'  =>  "Siden kunne ikke slettes." ,

/**
 * Page
 */	 	
	'pages:strapline'  =>  "Sidst opdateret %s af %s" ,
	
/**
 * History
 */	  
	'pages:revision'  =>  "Revision lavet %s af %s" ,
	
/**
 * Widget
 **/
	  
	'pages:num'  =>  "Antal sider, der skal vises" ,
	'pages:widget:description' => "Dette er en liste med dine sider.",
	
/**
 * Submenu items
 */			 
	'pages:label:view'  =>  "Se side" , 
	'pages:label:edit'  =>  "Rediger side" , 
	'pages:label:history'  =>  "Sidehistorik" ,
	
/**
 * Sidebar items
 */	  
	'pages:sidebar:this'  =>  "Denne side" , 
	'pages:sidebar:children'  =>  "Undersider" , 
	'pages:sidebar:parent'  =>  "Forælder" ,
	 
	'pages:newchild'  =>  "Opret en underside" ,	
	'pages:backtoparent'  =>  "Tilbage til '%s'"	  
);

add_translation("da",$danish);

?>