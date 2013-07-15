<?php
//If not logged in, show frontpage

if (elgg_is_logged_in()) forward('activity');

//Get some variables
$front_title =elgg_get_plugin_setting('mytitle', 'ac-130'); 
$front_text =elgg_get_plugin_setting('front', 'ac-130');
$front_twitter =elgg_get_plugin_setting('mytwitter', 'ac-130');  


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" media="screen" href="mod/ac-130/css/reset.css">
    <link rel="stylesheet" type="text/css" media="screen" href="mod/ac-130/css/style.css">
    <link rel="stylesheet" type="text/css" media="screen" href="mod/ac-130/css/grid_12.css">
    <link rel="stylesheet" type="text/css" media="screen" href="mod/ac-130/css/slider.css">
    <link rel="stylesheet" type="text/css" media="screen" href="mod/ac-130/css/tabs.css">
    <script src="mod/ac-130/js/jquery-1.7.min.js"></script>
    <script src="mod/ac-130/js/jquery.easing.1.3.js"></script>
    <script src="mod/ac-130/js/tms-0.3.js"></script>
	<script src="mod/ac-130/js/tms_presets.js"></script>
    <script src="mod/ac-130/js/cufon-yui.js"></script>
    <script src="mod/ac-130/js/Vegur-L_300.font.js"></script>
    <script src="mod/ac-130/js/Vegur-M_500.font.js"></script>
    <script src="mod/ac-130/js/Vegur-R_400.font.js"></script>
    <script src="mod/ac-130/js/cufon-replace.js"></script>
    <script src="mod/ac-130/js/tabs.js"></script>
    <script src="mod/ac-130/js/FF-cash.js"></script>
    <script>
		$(window).load(function(){
			$('.slider')._TMS({
			prevBu:'.prev',
			nextBu:'.next',
			pauseOnHover:true,
			pagNums:false,
			duration:800,
			easing:'easeOutQuad',
			preset:'Fade',
			slideshow:7000,
			pagination:'.pagination',
			waitBannerAnimation:false,
			banners:'fromLeft'
			})
		}) 	
    </script>
	<!--[if lt IE 8]>
       <div style=' clear: both; text-align:center; position: relative;'>
         <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
           <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
        </a>
      </div>
    <![endif]-->
    <!--[if lt IE 9]>
   		<script type="text/javascript" src="mod/ac-130/js/html5.js"></script>
    	<link rel="stylesheet" type="text/css" media="screen" href="mod/ac-130/css/ie.css">
	<![endif]-->
</head>
<body>
<!--==============================header=================================-->
<header>
  		<?php 

echo elgg_view('page/elements/messages', array('object' => $_SESSION['msg']));
unset($_SESSION['msg']);

?>
</header>   
<section id="header-content">
  <div class="main">
    <div class="slider">
      <ul class="items">
         <li><img src="mod/ac-130/images/slider-1.jpg" alt="">
         	<div class="banner">
            	<p>
				
				<strong class="font-1">Login</strong>
				</br>
				</br>
				</br>
				<strong class="font-2">
				
				<?php
									$form_body = 
									"
										
										<label>"
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
										."</label><br /></br>
									";
									$form_body .=
										"<label>"
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
										."</label><br /><br />
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
</br>
</br>
</br>
<a href="forgotpassword/"style="color:white; font-size:28px;">Forgot Password?</a>
</strong>

         	</div>
        </li>
         <li><img src="mod/ac-130/images/slider-2.jpg" alt="">
         	<div class="banner">
            	<p><strong class="font-1">Create an account</strong>
				</br>
				<strong class="font-33"><?php
								
								$form_body  = "<p><label>" . elgg_echo('name') . "<br />" . elgg_view('input/text' , array('internalname' => 'name', 'class' => "general-textarea", 'value' => $name)) . "</label><br />";
								$form_body .= "<label>" . elgg_echo('email') . "<br />" . elgg_view('input/text' , array('internalname' => 'email', 'class' => "general-textarea", 'value' => $email)) . "</label><br />";
								$form_body .= "<label>" . elgg_echo('username') . "<br />" . elgg_view('input/text' , array('internalname' => 'username', 'class' => "general-textarea", 'value' => $username)) . "</label><br />";
								$form_body .= "<label>" . elgg_echo('password') . "<br />" . elgg_view('input/password' , array('internalname' => 'password', 'class' => "general-textarea")) . "</label><br />";
								$form_body .= "<label>" . elgg_echo('passwordagain') . "<br />" . elgg_view('input/password' , array('internalname' => 'password2', 'class' => "general-textarea")) . "</label><br /><br />";
								$form_body .= elgg_view('register/extend');
								$form_body .= elgg_view('input/captcha');
								if ($admin_option) {
									$form_body .= elgg_view('input/checkboxes', array('internalname' => "admin", 'options' => array(elgg_echo('admin_option'))));
								}
								$form_body .= elgg_view('input/hidden', array('internalname' => 'friend_guid', 'value' => $vars['friend_guid']));
								$form_body .= elgg_view('input/hidden', array('internalname' => 'invitecode', 'value' => $vars['invitecode']));
								$form_body .= elgg_view('input/hidden', array('internalname' => 'action', 'value' => 'register'));
								$form_body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('register'))) . "</p>";
								?>
								
								<h2>
								<?php
								//echo elgg_echo('register');
								?>
								</h2>
								<?php
								// REGISTER FORM
								echo elgg_view
								(
									'input/form'
									,array
									(
										'body' => $form_body
										,'action' => "{$vars['url']}action/register"
									)
								);
								?></strong>         	</div>
        </li>
        
      </ul>
      <div class="pagination">
          <ul>
            <li><a href="#"><img src="mod/ac-130/images/slider-1-small.jpg" alt=""></a></li>
            <li><a href="#"><img src="mod/ac-130/images/slider-2-small.jpg" alt=""></a></li>
          
          </ul>
      </div>  
    </div>
  </div> 
</section>
<!--==============================content================================-->
<section id="content" class="border"><div class="ic"><div class="inner_copy">All <a href="http://www.magentothemesworld.com" title="Best Magento Templates">premium Magento themes</a> at magentothemesworld.com!</div></div>
	<div class="container_12">
     
      <div class="grid_8">
      	<div class="left-1 page1-col1">
		</br>
		</br>
            <h2 class="h2">
			<?php 
			if ($front_title == null)
		{
			
			?>
			
			Welcome to my network!
			
			 <?php
		 
		 }
		 
		 else 
		 
		 echo $front_title;
		 
		 ?> </h2>
        
</br>
  <p class="color-1 p2">
<?php 
			if ($front_text == null)
		{
		
  ?>
  This is a DEMO test. To edit the text here, go to the plugin settings and change it!
  <?php
		 
		 }
		 
		 else 
		 
		 echo $front_text;
		 
		 ?> 


  </p>
            <div class="block-2 wrap">
                
            </div>
            <p></p>
            <div class="page1-img1"></div>
        </div>
      </div>
      <div class="grid_4">
        
        <div class="tabs">
            <ul class="nav">
               <li class="selected"><a href="#tab-1">Tweets</a></li>
           
            </ul>
            <div id="tab-1" class="tab-content">
               <div class="inner">
			   
			   <?php 
			if ($front_twitter == null)
			$front_twitter = "swsocialweb";
		else
		$front_twitter =elgg_get_plugin_setting('twitter', 'ac-130');  

		
  ?>
                    <script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 7,
  interval: 30000,
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#333333',
      color: '#ffffff'
    },
    tweets: {
      background: '#474047',
      color: '#ffffff',
      links: '#ffffff'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: false,
    behavior: 'all'
  }
}).render().setUser('<?php echo $front_twitter; ?>').start();
</script>
               </div>  
            </div>
         
		 
              
            </div>
        </div>                        
      </div>
      <div class="clear"></div>
    </div>
</section> 
<!--==============================footer=================================-->
<footer>
    <p>© 2012 SW Social Web</p>
   
	
</footer>	
<script>
	Cufon.now();
</script>
</body>
</html>