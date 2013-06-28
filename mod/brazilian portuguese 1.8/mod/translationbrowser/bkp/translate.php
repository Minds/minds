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
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

admin_gatekeeper();

set_context('admin');
// Set admin user for user block
set_page_owner(get_loggedin_userid());

// my output stringl
$body = "";
// small caption 
$body .= elgg_echo("translationbrowser:translate");

$title = elgg_view_title(elgg_echo('translationbrowser:translate'));

$session_translationbrowser = get_input('translationbrowser_session');
$data = $_SESSION['translationbrowser'][$session_translationbrowser];

$body .= elgg_view(
            "translationbrowser/forms/translate", array(
                    'data' => $data,
                    'session_translate' => $session_translationbrowser
         )); //Get the form

// Display main admin menu
page_draw(
        elgg_echo('translationbrowser'),
        elgg_view_layout("two_column_left_sidebar", '', $title . $body)
);

?>

