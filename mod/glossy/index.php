<?php
if (elgg_is_logged_in()) forward('activity');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>Oneafghan.com | Advance Social Networking</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="description" content="A social networking site where you can find millions of new friends With much features and functions like using wire, creating pages and much more" />
        <meta name="keywords" content="Social Network, Afghan social network, Afghan girls chat, Afghan boys chat, Afghanistan flirt, Afghanistan chat, Afghanistan social network, Afghan friends, afghanistan friends"/>
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon"/>
        <link rel="stylesheet" type="text/css" href="mod/glossy/css/style.css" />
		<script src="mod/glossy/js/cufon-yui.js" type="text/javascript"></script>
		<script src="mod/glossy/js/ChunkFive_400.font.js" type="text/javascript"></script>
		<script type="text/javascript">
			Cufon.replace('h1',{ textShadow: '1px 1px #fff'});
			Cufon.replace('h2',{ textShadow: '1px 1px #fff'});
			Cufon.replace('h3',{ textShadow: '1px 1px #000'});
			Cufon.replace('.back');
		</script>
    </head>
	
	
	

<?php 
echo elgg_view('page/elements/messages', array('object' => $_SESSION['msg']));
unset($_SESSION['msg']);

////////////////////////////////////////////////////////////////////////////////////////////////
?>
    <body>
		<div class="wrapper">
			<h1 style="color: white;"> Welcome</h1>
                                 <div class="content">
				<div id="form_wrapper" class="form_wrapper">
				
					
						<h3>Login</h3>
						<div>
							<?php
									$form_body = 
									"
										
										<label style='color:#333;'>"
										.elgg_echo('username')
										."<br />"
										.elgg_view
										(
											'input/text'
											,array
											(
												'internalname' => 'username'
												,'class' => 'login-textarea'
											)
										)
										."</label><br />
									";
									$form_body .=
										"<label style='color:#333;'>"
										.elgg_echo('password')
										."<br />" 
										.elgg_view
										(
											'input/password'
											,array
											(
												'internalname' => 'password'
												,'class' => 'login-textarea'
											)
										)
										."</label><br />
									";
									$form_body .=
										elgg_view
										(
											 'input/submit'
											,array
											(
												'value' => elgg_echo('login')
											)
										)
										."</p>
									";
									echo elgg_view
									(
										'input/form'
										,array
										(
											 'body' => $form_body
											,'action' => ""
											.$vars['url']
											."action/login"
										)
									);
									?>
										 
						</div>
						<div class="bottom">
							
						
							<a href="register" rel="register" class="linkform"  style="color: white;" >Wanna join? Get an account here | Register!</a>
							<div class="clear"></div>
						</div>
					
						</div>
				<div class="clear"></div>
			</div>
<br>
<br>
<br>
<h3 style="color: :#333;"> Oneafghan Welcomes you, Be the first of your friends to join us Join us today to reach to thousands, So we can choose friends from millions, For instant support please call us 0093(0)787565611 or email us info@oneafghan.com</h3>
<br>
<script type="text/javascript"><!--
google_ad_client = "(your adsense client id";
/* samlast */
google_ad_slot = "(your adsenes slot id";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
			<a class="back"  style="color: white;" href="http://www.oneafghan.com/members">Search Members</a>
                       
                        
		</div>
		

		<!-- The JavaScript -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript">
			 
        </script>
		
		
    </body>
</html>