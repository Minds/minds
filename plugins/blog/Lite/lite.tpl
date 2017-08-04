<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.1.2/material.blue_grey-amber.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:400,700'>
    <script src="//storage.googleapis.com/code.getmdl.io/1.1.2/material.min.js"></script>
    <!-- inject:css -->
    <link rel="stylesheet" href="/stylesheets/main.css?v=1500394555111">

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

      <a href="https://www.minds.com/login" style="text-decoration:none;">
        <button class="mdl-button mdl-button--raised mdl-color--blue-grey-600 mdl-color-text--white">Login</button>
      </a>
    </div>

    <div class="m-lite--body">
      <?php echo $vars['body'] ?>
    </div>

  </body>

</html>
