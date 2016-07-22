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
    'minds:archive' => 'Archivo',
    
    /**
     * Navigation
     */
    'minds:archive:upload' => 'Subir',
    'minds:archive:all' => 'Todo el contenido',
    'minds:archive:featured' => 'Contenido destacado',
    'minds:archive:top' => 'Top comentario',
    'minds:archive:mine' => 'Mi contenido',
    'minds:archive:network' => 'Mi red de contenido',
    'minds:archive:owner' => '%s\'s contenido',
    'minds:archive:owner:network' => '%s\'s contenido de la red',
    
    'minds:archive:upload:videoaudio' => 'Vídeo y audio',
    
    'minds:archive:file:replace' => 'Reemplace el archivo',
    
    'minds:archive:download' => 'Descargar',
    
    'minds:archive:upload:videoaudio' => 'Subir vídeo/audio',
    'minds:archive:album:create' => 'Crear un álbum',
    'minds:archive:upload:others' => 'Cargar imágenes, archivos + más',

    'minds:archive:delete:success' => 'El archivo se ha eliminado de su archivo',
    'minds:archive:delete:error' => 'Ha habido un problema. No se puede eliminar el archivo en el momento',

    
    //title of the menu, put whatever you want, for example 'Kaltura videos'
    'archive' => "Archivo",
    
     
    /*
     * Archive Menus
     */
    'archive:all' => 'Archivo: Todo',
    'archive:owner' => 'Archivo: %s',
    'archive:top' => 'Archivo: superior',
    'archive:network' => 'Archivo: red',
    
    'archive:upload:videoaudio' => 'Vídeo y audio',
    'archive:upload:others' => 'Imágenes y Archivos',
    
    /*
     * Archive featured, sponsored& trending/popular
     */
    'archive:popular:title' => 'Popular',
    'archive:featured:title' => 'Destacado',
    'archive:featured:action' => 'Característica',
    'archive:featured:un-action' => 'Un-función',
    'archive:morefromuser:title' => 'Más de %s',
    
    'archive:monetized:action' => 'Monetizar',
    'archive:monetized:un-action' => 'Un-monetizar',
    
    'archive:owner_tag' => 'Por ',

    /*
     * Other strings
     */
    'archive:close' => 'Cerrar',
);

add_translation("es", $translations);
