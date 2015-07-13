<?php
/**
 * Minds frontend
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');
error_reporting(E_ALL); 
ini_set( 'display_errors','1');

?>
<html>
  <head>
    <title>Minds <?= "" ?></title>
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">

    <!-- temporary design -->
     <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.blue_grey-amber.min.css" /> 
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:400,700'>
    <link rel="stylesheet" href="stylesheets/main.css"/>

  </head>
  <body>

  
    <!-- The app component created in app.ts -->
    <minds-app  class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header  mdl-layout--overlay-drawer-button">
        <div id="p2" class="mdl-progress mdl-js-progress mdl-progress__indeterminate initial-loading"></div>
    </minds-app>
    
     <!-- inject:js -->
  	 <!-- endinject -->
    
    <script>
        window.LoggedIn = <?= Minds\Core\session::isLoggedIn() ? "true" : "false" ?>;
        
        <?php
            $minds = array(
                "LoggedIn" => Minds\Core\session::isLoggedIn() ? "true" : "false",
                "navigation" => array(
                    array(
                        "name" => "newsfeed",
                        "path" => "/newsfeed",
                        "title" => "Newsfeed",
                        "text" => "Newsfeed",
                        "icon" => "home",
                        "class" => ""
                        ),
                     array(
                        "name" => "capture",
                        "path" => "/capture",
                        "title" => "Capture",
                        "text" => "Capture",
                        "icon" => "videocam",
                        "class" => ""
                        ),
                     array(
                        "name" => "discovery",
                        "path" => "/discovery",
                        "title" => "Discovery",
                        "text" => "Discovery",
                        "icon" => "search",
                        "class" => ""
                        ),
                     array(
                        "name" => "messenger",
                        "path" => "/messenger",
                        "title" => "Messenger",
                        "text" => "Messenger",
                        "icon" => "chat_bubble",
                        "class" => ""
                        ),
                     array(
                        "name" => "notifications",
                        "path" => "/notifications",
                        "title" => "Notifications",
                        "text" => "Notifications",
                        "icon" => "notifications",
                        "class" => ""
                        ),
                     array(
                        "name" => "groups",
                        "path" => "/groups",
                        "title" => "Groups",
                        "text" => "Groups",
                        "icon" => "group_work",
                        "class" => ""
                        )
                )
            );
            if(Minds\Core\session::isLoggedIn()){
                $minds['user'] = Minds\Core\session::getLoggedinUser()->export();
            }
        ?>
        window.Minds = <?= json_encode($minds) ?>;
        
        System.config({
          baseURL: './',
          paths: {
            '*': '*.js'
          }
        });
        
        System.import('app');
    </script>
  </body>
</html>