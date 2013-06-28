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

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");
require_once(dirname(dirname(dirname(__FILE__))) ."/lib/countries.php");

// Ensure that only logged-in users can see this page
gatekeeper();
admin_gatekeeper();
set_context('admin');

// Set context and title
elgg_set_context('translationbrowser');

// Set admin user for user block
//// set_page_owner(get_loggedin_userid);
elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

//clean session
unset($_SESSION['translationbrowser']);

// my output stringl
$body = "<BR><BR>";
$body .= "<h1>" . elgg_echo('translationbrowser'). "</h1>" ;
$body .= "<BR><BR>";
// small caption 
$body .= elgg_echo("translationbrowser:selectlanguage");

//why the discrimination against english?
//if(isset($iso_639_1['en'])) unset($iso_639_1['en']);


////// >>>>>>>> ATUALIZAR FUNÇÃO TITLE <<<<<<
$title = elgg_view_title(elgg_echo('translationbrowser'));

//why the discrimination against english?
if(isset($iso_639_1['en'])) unset($iso_639_1['en']);

$title = elgg_view_title(elgg_echo('translationbrowser'));

// read to tab_out list translation files in elgg
//// $tab_out = translationbrowser_scandir(dirname(dirname(dirname(dirname(dirname(__FILE__))))),$tab_out);
$tab_out = translationbrowser_get_language_files_for_active_plugins();

//Order the files
asort($tab_out);

//print results
//Get the form	
$body .= elgg_view(
            "translationbrowser/forms/select_module", array(
            'languages' => $iso_639_1, 
            'modules' => $tab_out
         ));
// Draw the page
////page_draw(elgg_echo('translationbrowser'), elgg_view_layout("two_column_left_sidebar", '', $title . $body)

echo elgg_view_page($title, $body);


?>


