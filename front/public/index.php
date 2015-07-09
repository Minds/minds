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

    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:400,700'>
    <link rel="stylesheet" href="https://gitcdn.xyz/repo/angular/bower-material/master/angular-material.css">
    <link rel="stylesheet" href="stylesheets/main.css"/>
  </head>
  <body>

  
    <!-- The app component created in app.ts -->
    <minds-app>Loading...</minds-app>
    
     <!-- inject:js -->
  	 <script src="/lib/traceur-runtime.js?v=0.0.1"></script>
  	 <script src="/lib/es6-module-loader-sans-promises.js?v=0.0.1"></script>
  	 <script src="/lib/Reflect.js?v=0.0.1"></script>
  	 <script src="/lib/system.src.js?v=0.0.1"></script>
  	 <script src="/lib/zone.js?v=0.0.1"></script>
  	 <script src="/lib/angular2.js?v=0.0.1"></script>
  	 <script src="/lib/router.js?v=0.0.1"></script>
  	 <!-- endinject -->
    
    <script>
        window.LoggedIn = <?= Minds\Core\session::isLoggedIn() ? "true" : "false" ?>;
        
        <?php
            $minds = array(
                "LoggedIn" => Minds\Core\session::isLoggedIn() ? "true" : "false"
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