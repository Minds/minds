<?php
/**
 * Elgg Peek a boo theme
 * @package Peek a boo theme
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Web Intelligence
 * @copyright Web Intelligence
 * @link www.webintelligence.ie
 * @version 1.8
 */

echo '<div class="footer-container">';

//navigation
echo '<div id="footer-nav">';
echo elgg_view ('output/titletext', array(
        'text' => 'Navigate',
	'class' => 'footer-titles',
    ));
echo '<div><a href="'.$vars["url"].'">Home</a></div>';


// ********************** activate when we decide to include blog **************
if (elgg_is_active_plugin('blog')) {
        echo '<div><a href="'.$vars["url"].'blog/all">Blog</a></div>';
}
//*********************** activate when groups *********************************
if (elgg_is_active_plugin('groups')) {
        echo '<div><a href="'.$vars["url"].'groups/all">Groups</a></div>';
}
if (elgg_is_logged_in()) {

        echo '<div style="border-bottom: 0px solid #777; margin: 10px 0 2px 0;"></div>';
  
        echo '<div><a href="'.$vars["url"].'profile/'.elgg_get_logged_in_user_entity()->username.'">My Profile</a></div>';
}
//end of navigation
echo '</div>';



$url1 = elgg_get_site_url()."mod/pab_theme/graphics/facebook_logo_small.png";
$url2 = elgg_get_site_url()."mod/pab_theme/graphics/twitter_logo_small.png";
$url3 = elgg_get_site_url()."mod/pab_theme/graphics/google_small.png";

$fb_href = elgg_get_plugin_setting('footer_facebook', 'pab_theme');

$tw_href = elgg_get_plugin_setting('footer_twitter', 'pab_theme');

$gp_href = elgg_get_plugin_setting('footer_googleplus', 'pab_theme');

//social network connection logos
echo '<div id="footer-socnet">';

if(!empty($fb_href) || !empty($tw_href) || !empty($gp_href)){
    
    
        echo elgg_view ('output/titletext', array(
                'text' => 'Follow Us',
                'class' => 'footer-titles',
            ));    

        echo elgg_view('output/url', array(
                'href' => "$fb_href",
                'text' => '<img src="'.$url1.'" alt=\"Facebook\"/>',
                'class' => 'socnet-logos',
        ));
        echo elgg_view('output/url', array(
                'href' => "$tw_href",
                'text' => '<img src="'.$url2.'" alt=\"Twitter\"/>',
                'class' => 'socnet-logos',
        ));
        echo elgg_view('output/url', array(
                'href' => "$gp_href",
                'text' => '<img src="'.$url3.'" alt=\"Google plus\"/>',
                'class' => 'socnet-logos',
        ));
}
else{
    echo "&nbsp;";
}

//end social network connection logos
echo '</div>';



$about = elgg_get_plugin_setting('footer_about_us', 'pab_theme');
if(!empty($about)){
    //bottom right cont
echo '<div id="bottom-right">';
    echo elgg_view ('output/titletext', array(
            'text' => 'About Us',
            'class' => 'footer-titles',
        ));

   
  
     echo '<div style="width: 350px; font-size:0.8em">';
     echo $about;
     echo '</div>'; 
 


    //end bottom right cont
    echo '</div>';

//end footer-container
echo '</div>';
}

$powered_url = elgg_get_site_url()."mod/pab_theme/graphics/powered_by_elgg_badge_drk_bckgnd.png";

echo '<div class="mts clearfloat right float-alt">';
echo elgg_view('output/url', array(
	'href' => 'http://elgg.org',
	'text' => "<img src=\"$powered_url\" alt=\"Powered by Elgg\" width=\"106\" height=\"15\" />",
	'class' => '',
));
echo '</div>';
echo '<div class="clearfix"></div>';


$site = elgg_get_site_entity();
?>
<div style="margin-top:20px; text-align: center; border-top: 1px #777 solid; padding-top: 10px;">
<p><?php echo elgg_view("output/url", array(
                            "text" => $site->name,
                            "href" => $site->getURL()    
        )); ?>© 2012</p>
</div>


