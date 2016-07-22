<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

$translations = array(
    'minds:archive' => 'Archiv',
    
    /**
     * Navigation
     */
    'minds:archive:upload' => 'Hochladen',
    'minds:archive:all' => 'Alle Inhalte',
    'minds:archive:featured' => 'Empfohlene Inhalte',
    'minds:archive:top' => 'Top-Inhalte',
    'minds:archive:mine' => 'Meine Inhalte',
    'minds:archive:network' => 'Mein Netzwerk Inhalt',
    'minds:archive:owner' => '%s\'s inhalt',
    'minds:archive:owner:network' => '%s\'s Netzwerk Inhalt',
    
    'minds:archive:upload:videoaudio' => 'Video & Audio',
    
    'minds:archive:file:replace' => 'Datei ersetzen',
    
    'minds:archive:download' => 'Herunterladen',
    
    'minds:archive:upload:videoaudio' => 'Hochladen Video / Audio',
    'minds:archive:album:create' => 'Erstellen eines Albums',
    'minds:archive:upload:others' => 'Laden Sie Bilder, Dateien + mehr',

    'minds:archive:delete:success' => 'Die Datei wurde aus dem Archiv entfernt worden',
    'minds:archive:delete:error' => 'Es gab ein Problem. Wir können diese Datei nicht löschen im Moment',

    
    //title of the menu, put whatever you want, for example 'Kaltura videos'
    'archive' => "Archiv",
    
     
    /*
     * Archive Menus
     */
    'archive:all' => 'Archiv: Alle',
    'archive:owner' => 'Archiv: %s',
    'archive:top' => 'Archiv: Spitze',
    'archive:network' => 'Archiv: Netzwerk',
    
    'archive:upload:videoaudio' => 'Video & Audio',
    'archive:upload:others' => 'Bilder & Filme',
    
    /*
     * Archive featured, sponsored& trending/popular
     */
    'archive:popular:title' => 'Beliebte',
    'archive:featured:title' => 'Vorgestellt',
    'archive:featured:action' => 'Eigenschaft',
    'archive:featured:un-action' => 'Un-feature',
    'archive:morefromuser:title' => 'Mehr von %s',
    
    'archive:monetized:action' => 'Geld verdienen',
    'archive:monetized:un-action' => 'Un-Geld verdienen',
    
    'archive:owner_tag' => 'Von ',

    /*
     * Other strings
     */
    'archive:close' => 'Schließen',
);

add_translation("de", $translations);
