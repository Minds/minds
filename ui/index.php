<!doctype html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <title>Minds</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <link rel="icon" type="image/png" href="https://www.minds.com/_graphics/icon.png" />
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css' />

    <link rel="stylesheet" href="styles/main.css">
    <!-- endbuild -->
    <link href='//fonts.googleapis.com/css?family=Lato:300,400,700|Roboto+Slab:400,300,100,700' rel='stylesheet' type='text/css'>
  </head>
  <body ng-app="app">
    <!--[if lt IE 7]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Add your site or application content here -->
    <nav role="navigation" class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#/" class="navbar-brand logo">
                    <img src="images/logo-transparent.png"/> <h1>ORG</h1>
                </a>
            </div>
           <div class="navbar-collapse collapse" id="navbar" aria-expanded="false" style="height: 1px;">
                <ul class="nav navbar-nav header-nav">
                    <li class="col-sm-2 col-md-2 col-lg-2"><a href="https://www.minds.com/">Minds.com</a></li>
                    <li class="col-sm-2 col-md-2 col-lg-2"><a href="#/docs">Docs</a></li>
                    <li class="col-sm-2 col-md-2 col-lg-2"><a href="#/code">Code</a></li>
                    <li class="col-sm-2 col-md-2 col-lg-2"><a href="#/governance">Governance</a></li>
                </ul>
            </div><!--/.nav-collapse -->
      </div><!--/.container-fluid -->
    </nav>

    <div class="wrapper">
        <div ng-view></div>
    
        <div class="footer">
          <div class="container">
            
          </div>
        </div>
    </div>

    <script src="ui/vendors/require.js" data-main="ui/scripts/main.js"></script>
  </body>
</html>