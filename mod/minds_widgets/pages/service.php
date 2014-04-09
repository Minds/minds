<?php
    $tab = get_input('tab', 'remind');

    // Logged in, display login box and remember where to go
    if (((!elgg_is_logged_in()) && ($tab != "voting")) || (get_input('fl') == 'y' )) { 

        $_SESSION['last_forward_from'] = current_page_url();
        
        $content = elgg_view_form('login', null, array('returntoreferer' => true));
        
        // Hack
        if ($tab == 'voting' && elgg_is_logged_in()) {
            $content .= "
                <script>
                    window.opener.location.reload();  

                    window.close();
                </script>
                ";
        }
    }
    else
    {
        $content = elgg_view('minds_widgets/service/service' , array('tab' => $tab, 'user' => elgg_get_logged_in_user_entity()));
    }
    
    if (get_input('embed')!='yes') {
        $params = array(
                'title' => elgg_echo('minds_widgets:tab:'.$tab),
                'content' => $content,
                'sidebar' => ''
        );
        $body = elgg_view_layout('default', $params);


        echo elgg_view_page(elgg_echo('minds_widgets:tab:'.$tab), $body);
    } else {
        echo elgg_view_page(elgg_echo('minds_widgets:tab:'.$tab), $content, 'widget_embed');
    }