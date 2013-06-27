<?php
/**
 * Elgg Peek a boo theme
 * @package Peek a boo theme
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Web Intelligence
 * @copyright Web Intelligence
 * @link www.webintelligence.ie
 * @version 1.8
 */



    echo '<ul class="top-menu top-menu-site top-menu-site-default clearfix">';
        echo '<li class="top-more" style="vertical-align: baseline; ">';
            $user = elgg_get_logged_in_user_entity();
			 $more = "Hi ".elgg_echo($user->name); 
                         
        if((int)messages_count_unread() > 0){
                global $CONFIG;
            $exl = " <font id='message-notification'><img src='".$CONFIG->url."mod/pab_theme/graphics/mail.png' /></font>";            	
        }                         

            echo "<a title=\"$more\">$more$exl</a>";

            echo elgg_view('navigation/menu/elements/section', array(
                    'class' => 'top-menu top-menu-site top-menu-site-more', 
                    'items' => $vars['menu']['default'],
            ));
            echo elgg_view('navigation/menu/elements/section', array(
                    'class' => 'top-menu top-menu-site top-menu-site-more top-menu-site-more-admin', 
                    'items' => $vars['menu']['admin'],
            ));
            echo elgg_view('navigation/menu/elements/section', array(
                    'class' => 'top-menu top-menu-site top-menu-site-more top-menu-site-more-last', 
                    'items' => $vars['menu']['alt'],
            ));

        echo '</li>';
    echo '</ul>';



?>
