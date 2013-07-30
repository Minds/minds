<?php
/**
 * Bookmarks Danish language file
 */

$danish = array(

	/**
	 * Menu items and titles
	 */	
	'bookmarks' =>  "Bogmærker", 
	'bookmarks:add' =>  "Tilføj bogmærke",
	'bookmarks:owner' => "%s's bogmærker", 
	'bookmarks:read' =>  "Mine bogmærker", 
	'bookmarks:friends' =>  "Venners bogmærker", 
	'bookmarks:everyone' =>  "Alle bogmærker", 
	'bookmarks:this' =>  "Bogmærk denne side",
	'bookmarks:this:group'=> "Bogmærk i %s", 
	'bookmarks:bookmarklet' =>  "Hent \"Bookmarklet\"", 
	'bookmarks:bookmarklet:group'=> "Hent gruppe \"Bookmarklet\"",
	'bookmarks:inbox' =>  "Bogmærke indbakke",
	'bookmarks:morebookmarks' => "Flere bogmærker", 
	'bookmarks:more' =>  "Flere bogmærker",
	'bookmarks:with' => "Del med", 
	'bookmarks:new' => "Et nyt bogmærke",
	'bookmarks:via' => "via bogmærker",
	'bookmarks:address' =>  "Adresse på ressourcen der skal bogmærkes",
	'bookmarks:none' => 'Ingen bogmærker',
		 
	'bookmarks:delete:confirm' =>  "Er du sikker på, at du vil slette denne ressource?",
	 
	'bookmarks:numbertodisplay' =>  "Antal bogmærker, der skal vises",
	
	'bookmarks:shared' =>  "Bogmærket", 
	'bookmarks:visit' =>  "Besøg ressource" , 
	'bookmarks:recent' =>  "Seneste bogmærker", 

	'river:create:object:bookmarks' => '%s bookmærkede %s',
	'river:comment:object:bookmarks' => '%s kommenterede bookmærket %s',	
	'bookmarks:river:annotate' =>  "%s kommenterede på", 
	'bookmarks:river:item' =>  "noget",
	
	'item:object:bookmarks' =>  "Bogmærker",
	 
	'bookmarks:group' => 'Gruppe bogmærker',
	'bookmarks:enablebookmarks' => 'Aktiver gruppe bogmærker',
	'bookmarks:nogroup' => 'Denne gruppe har ingen bogmærker endnu',
	'bookmarks:more' => 'Flere bogmærker',

	'bookmarks:no_title' => 'Ingen titel',
			
	/**
	 * Widget and bookmarklet
	 */		
	'bookmarks:widget:description'  =>  "Vis dine nyeste bogmærker.",
	
	'bookmarks:bookmarklet:description'  =>  
			"\"Bookmarklet\" bogmærket gør dig i stand til at dele enhver ressource, du finder på nettet med dine venner eller bare lave et privat bogmærke. For at bruge det skal du blot trække knappen herunder op i din browsers linkbar:",
	
	'bookmarks:bookmarklet:descriptionie'  =>  
			"Hvis du bruger Internet Explorer, er du nødt til at højreklikke på \"Bookmarklet\" ikonet og vælge \"Føj til Favoritter\" og derefter \"Linkbar\".",
	
	'bookmarks:bookmarklet:description:conclusion'  =>  
			"Du kan så gemme enhver side, du besøger ved at klikke på ikonet.",
	
/**
* Status messages
*/
  
	'bookmarks:save:success' => "Bogmærket er gemt.",	  
	'bookmarks:delete:success'  =>  "Bogmærket er slettet.", 
  
/**
 * Error messages
 */
	 
	'bookmarks:save:failed' => "Bogmærket kunne ikke gemmes, prøv venligst igen.", 
	'bookmarks:delete:failed'  =>  "Bogmærket kunne ikke slettes, prøv venligst igen."

);

add_translation("da",$danish);

?>