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

    <base href="/" />
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">

    <?php
      $meta = Minds\Core\SEO\Manager::get();
      foreach($meta as $name => $content){
        $name = strip_tags($name);
        $content = strip_tags($content);
        switch($name){
          case "title":
            echo "<title>$content | Minds</title>\n";
            break;
          case strpos($name, ":") !== FALSE:
            echo "<meta property=\"$name\" content=\"$content\">\n";
            break;
          default:
            echo "<meta name=\"$name\" content=\"$content\">\n";
        }
      }
    ?>

    <!-- temporary design -->
     <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.blue_grey-amber.min.css" />
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.min.js"></script>
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
        <?php
            $minds = array(
                "LoggedIn" => Minds\Core\Session::isLoggedIn() ? true : false,
                "cdn_url" => Minds\Core\Config::get('cdn_url'),
                "navigation" => Minds\Core\Navigation\Manager::export()
              );
            if(Minds\Core\Session::isLoggedIn()){
                $minds['user'] = Minds\Core\Session::getLoggedinUser()->export();
                $minds['user']['chat'] = (bool) elgg_get_plugin_user_setting('option', Minds\Core\Session::getLoggedinUser()->guid, 'gatherings') == 1 ? true : false;
            }
        ?>
        window.Minds = <?= json_encode($minds, JSON_PRETTY_PRINT) ?>;

        System.import('app')
          .catch(function(){console.error(e,
            'Report this error at https://github.com/mgechev/angular2-seed/issues')});

    </script>
  </body>
</html>
