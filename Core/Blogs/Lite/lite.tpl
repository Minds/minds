<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.1.2/material.blue_grey-amber.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:400,700'>
    <script src="//storage.googleapis.com/code.getmdl.io/1.1.2/material.min.js"></script>
    <!-- inject:css -->
    <link rel="stylesheet" href="/stylesheets/main.css?v=1500394555112">

    <?php
      foreach($vars['meta'] as $name => $content){
        $name = strip_tags($name);
        $content = str_replace(['"'], '\'', $content);
        switch($name){
          case "title":
            echo "<title>$content</title>\n";
            break;
          case strpos($name, ":") !== FALSE:
            echo "<meta property=\"$name\" content=\"$content\">\n";
            break;
          default:
            echo "<meta name=\"$name\" content=\"$content\">\n";
        }
      }
    ?>
  </head>
  <body>

    <div class="m-lite--header">
      <a href="https://www.minds.com/" style="text-decoration:none;" class="m-lite--logo">
        <img src="https://www.minds.com/assets/logos/medium-production.png" alt="Minds"/>
      </a>

      <a href="https://www.minds.com/login" style="text-decoration:none; padding-right:8px">
        <button class="mdl-button mdl-button--raised mdl-color--blue-grey-600 mdl-color-text--white">Signup</button>
      </a>
      <a href="https://www.minds.com/login" style="text-decoration:none;">
        <button class="mdl-button mdl-button--raised mdl-color--blue-grey-600 mdl-color-text--white">Login</button>
      </a>
    </div>

    <div class="m-lite--body">
      <?php echo $vars['body'] ?>
    </div>

    <!-- Google Analytics -->
      <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-35146796-1', 'auto');
          ga('send', 'pageview');

      </script>      
    <!-- End Google Analytics -->

  </body>

</html>
