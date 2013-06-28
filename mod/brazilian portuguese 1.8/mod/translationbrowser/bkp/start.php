<?php
/**
 * Elgg translation browser.
 *
 * @package translationbrowser
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Mariusz Bulkowski
 * @author v2 Pedro Prez
 * @copyright 2009
 * @link http://www.pedroprez.com.ar/
 * 
 * @author v3 Renato Cerceau
 * @copyright 2011  (Upgrade to elgg 1.8)
 */

/**
 * Initialise the log browser and set up the menus.
*/

//require_once(dirname(__FILE__) . '/lib/functions_translatebrowser.php');

require_once(dirname(__FILE__) . '/functions_translatebrowser.php');

function translationbrowser_init()
{
    global $CONFIG;

    // Register a page handler, so we can have nice URLs
    elgg_register_page_handler('translationbrowser','translationbrowser_page_handler');


    // Extend CSS
    elgg_extend_view('css','translationbrowser/css');

}


/**
 * Log browser page handler
 *
 * @param array $page Array of page elements,
 *        forwarded by the page handling mechanism
 */
function translationbrowser_page_handler($page) 
{
    global $CONFIG;

    if (isset($page[0]) && !empty($page[0]))
    {
        switch($page[0])
        {
            case 'translate':
                if (isset($page[1]) && !empty($page[1]))
                {
                    set_input('translationbrowser_session',$page[1]);
                }
                include dirname(__FILE__) . "/pages/translationbrowser/translate.php";
                break;
            default:
                include dirname(__FILE__) . "/pages/translationbrowser/index.php";
                break;
       }
    }
    else
    {
        include dirname(__FILE__) . "/pages/translationbrowser/index.php";
    }
   
}


/**
 * Adding the log browser to the admin menu
 *
 * @author v2 Renato Cerceau
 * @copyright 2011  (Upgrade to elgg 1.8)
 *
 */
function translationbrowser_pagesetup()
{
    if (get_context() == 'admin' && elgg_is_admin_logged_in()) {

        global $CONFIG;
	    $item =  array('name' =>  elgg_echo('translationbrowser'), 
		'text' =>  elgg_echo('translationbrowser'), 
		'href' =>  $config->wwwroot . 'translationbrowser/',
		'context' => elgg_get_context(), 
		'section' => 'develop',);
	   elgg_register_menu_item('page', $item);
    }
}


elgg_register_event_handler('init','system','translationbrowser_init');
elgg_register_event_handler('pagesetup','system','translationbrowser_pagesetup');

?>