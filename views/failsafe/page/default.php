<?php
/**
 * Elgg failsafe pageshell
 * Special viewtype for rendering exceptions. Includes minimal code so as not to
 * create a "Exception thrown without a stack frame in Unknown on line 0" error
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 */

// we won't trust server configuration but specify utf-8
header('Content-type: text/html; charset=utf-8');
?>
<html>
	<head>
		<title><?php echo $vars['title']; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<style type="text/css">

		body {
			text-align:left;
			margin:0;
			padding:0;
			background: #fff;
			font: 80%/1.5  "Lucida Grande", Verdana, sans-serif;
			color: #333333;
		}
		p {
			margin: 0px 0px 15px 0;
		}
		#elgg-wrapper {
			background:white;
			width:570px;
			margin:auto;
			padding:10px 40px;
			margin-bottom:40px;
			margin-top:20px;
			border: 1px solid #666666;
			
		}
		.elgg-messages-exception {
			background:#FDFFC3;
			display:block;
			padding:10px;
		}
                #minds-logo {
                    margin-left: auto;
                    margin-right: auto;
                    margin-bottom: 50px;
                    margin-top: 20px;
                    width: 200px;
                }
		</style>

	</head>
	<body>
        
            <div id="minds-logo"><img src="<?php echo elgg_get_site_url(); ?>mod/minds/graphics/minds_logo.png" /></div>
	<div id="elgg-wrapper">
            
		<h1>Sorry.... we're doing some work</h1>
		Please check back later
	</div>
	</body>
</html>
