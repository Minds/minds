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



function pab_theme_init() {
    
	elgg_extend_view ('css/elgg','pab_theme/meta');
	elgg_unregister_menu_item('topbar', 'elgg_logo');
    
    
    //some javascript
    elgg_extend_view('metatags', 'js/roller'); 
    
    $user = elgg_get_logged_in_user_entity();

    if ($user) {

        elgg_register_menu_item('topmenu', array(
                    'name' => 'profile',
                    'href' =>  $user->getURL(),
                    'text' => elgg_echo("pab_theme:myprof"),
                    'priority' => 110,
                    //'link_class' => 'topmenu-roldown',
            ));
        
        
            elgg_register_menu_item('topmenu', array(
                    'name' => 'friends',
                    'href' => "friends/{$user->username}",
                    'text' => elgg_echo('pabtheme:myfriends'),
                    'title' => elgg_echo('friends'),
                    'priority' => 300,
            ));        
        

        elgg_register_menu_item('topmenu', array(
                    'name' => 'logout',
                    'href' => "action/logout",
                    'text' => elgg_echo('logout'),
                    'is_action' => TRUE,
                    'priority' => 1000,
                    'section' => 'alt',
        ));
        elgg_register_menu_item('topmenu', array(
            'name' => 'usersettings',
            'href' => "settings/user/{$user->username}",
            'text' => elgg_echo('pab_theme:mysets'),
            'priority' => 500,
            //'section' => 'alt',
            ));


        if(elgg_is_active_plugin ('messages')){
            $num_messages = (int)messages_count_unread();
            $text = elgg_echo("pab_theme:mymess");
            if ($num_messages != 0) {
                $text .= ' <div id="myaccount-messages">('.$num_messages.')</div>';
            }

            elgg_register_menu_item('topmenu', array(
                        'name' => 'messages',
                        'text' => $text,          //elgg_echo('messages:inbox'),
                        'href' => "messages/inbox/" . elgg_get_logged_in_user_entity()->username,
                        'priority' => 300,
                ));
        }
        
        if(elgg_is_active_plugin ('dashboard')){
                elgg_register_menu_item('topmenu', array(
                    'name' => 'dashboard',
                    'href' => 'dashboard',
                    'text' => elgg_echo('pabtheme:mydashboard'),
                    'priority' => 450,
                    //'section' => 'alt',
            ));
        }        
        
        if (elgg_is_admin_logged_in()) {
            elgg_register_menu_item('topmenu', array(
            'name' => 'administration',
            'href' => 'admin',
            'text' => elgg_echo('admin'),
            'priority' => 400,
            'section' => 'admin',
            ));

        }
    }
    
      elgg_unregister_widget_type('river_widget');
        

}

elgg_register_event_handler('init', 'system', 'pab_theme_init');


