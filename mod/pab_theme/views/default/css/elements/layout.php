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
?>

/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** DEFAULT LAYOUT ******/
<?php // the width is on the page rather than topbar to handle small viewports ?>
.elgg-page-default {
	min-width: 998px;
        min-height: 100%;
        background: transparent; //#FFF url(<?php echo elgg_get_site_url(); ?>mod/pab_theme/graphics/back_spray.png) no-repeat top center;
        position: relative;
}

.elgg-page-default .elgg-page-topbar > .elgg-inner {
	width: 990px;
	margin: 0 auto;
	height: 50px;
}
.elgg-page-default .elgg-page-header > .elgg-inner {
	width: 990px;
	margin: 0 auto;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
	width: 990px;
	margin: 10px auto;
}
.elgg-page-footer{
        bottom: 0;
        position:absolute;
        background:#666 url(<?php echo elgg_get_site_url(); ?>mod/pab_theme/graphics/patt5.png) top left;
        border-top: 4px solid #83CAFF;
        //margin-top: 50px;
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.4);
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	width: 990px;
	margin: 0 auto;
	padding: 5px 0;
        //border-radius: 10px;
	border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        min-height: 150px;
        //opacity: 0.5;
        margin-bottom: 20px;
        
}


.footer-container {
    background: transparent;
    padding: 10px;
    color: #DDD;
    text-decoration: none;
    
}

.footer-container a {
    font-size: 1.2em;
    font-weight: bold;
}

.footer-container a:hover {
    color: #CEFF16;
    text-decoration: none;
}

#footer-nav {
    width: 200px;
    border-right: 1px solid #777;
    float: left;
}

#footer-subscribe {
    width: 200px;
    padding: 0 10px 0 10px;
    border-right: 1px solid #777;
    float: left;
}

#footer-socnet {
    width: 378px;
    padding: 0 10px 0 10px;
    border-right: 1px solid #777;
    float: left;
    height:76px;
}

.float-left {
    float: left;
}

.socnet-logos {
    float: none;
    margin-right: 50px;
}

#subscribe-button{
    background: #83CAFF;
    border: 1px solid #83CAFF;
    color: white;
    margin: 5px 0 0 0;
}

#subscribe-button:hover{
    background: #CEFF16;
    border: 1px solid #CEFF16;
    color: #666;
}

#bottom-right{
    padding: 0 10px 0 10px;

    float: left;
}

.footer-titles {
    color: #EEE;
    padding: 0 0 5px 0;
}


#main-wrapper{
        background-color: #FFFFFF;
        background-position: top left, top;
        background-repeat: repeat, no-repeat;
        padding-bottom: 50px;
}

/***** TOPBAR ******/
.elgg-page-topbar {
	background: #666 url(<?php echo elgg_get_site_url(); ?>mod/pab_theme/graphics/patt5.png) top left;
	border-bottom: 4px solid #83CAFF;
        box-shadow: inset 0 -2px 10px rgba(0,0,0,0.4);
	position: relative;
	height: 50px;
	z-index: 9000;
}
.elgg-page-topbar > .elgg-inner {
	padding: 0 10px;
        position: relative;
        height: 50px;
}

/***** PAGE MESSAGES ******/
.elgg-system-messages {
	position: fixed;
	top: 50px;
	right: 20px;
	max-width: 500px;
	z-index: 11000;
}
.elgg-system-messages li {
	margin-top: 10px;
}
.elgg-system-messages li p {
	margin: 0;
}

/***** PAGE HEADER ******/
.elgg-page-header {
	position: relative;
        background: transparent;
        margin-bottom:20px;
<!--	background: #DDD url(<?php echo elgg_get_site_url(); ?>_graphics/header_shadow.png) repeat-x bottom left;-->
}
.elgg-page-header > .elgg-inner {
	position: relative;
        padding: 10px 0 17px 0;
}


/***** PAGE BODY LAYOUT ******/

.water-wrapper{
        padding:4px;
        border-radius: 14px;
        //box-shadow: 0 0 10px rgba(0,0,0,0.3);
        //-webkit-box-shadow: 0 0 10px rgba(0,0,0,0.3);
        //-moz-box-shadow: 0 0 10px rgba(0,0,0,0.3);
}
.elgg-layout {
	min-height: 260px;
}
.elgg-layout-one-sidebar {
        //border: 4px solid #DDD;
	background: rgba(255,255,255,0.5);// url(<?php echo elgg_get_site_url(); ?>_graphics/sidebar_background.gif) repeat-y right top;
        border-radius: 10px;
}
.elgg-layout-two-sidebar {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/two_sidebar_background.gif) repeat-y right top;
}

.elgg-layout-one-column{
    background: rgba(255,255,255,0.5);
    border-radius: 10px;
}

.elgg-sidebar {
	position: relative;
	padding: 20px 10px;
	float: right;
	width: 210px;
	margin: 0 0 0 10px;
}
.elgg-sidebar-alt {
	position: relative;
	padding: 20px 10px;
	float: left;
	width: 160px;
	margin: 0 10px 0 0;
}
.elgg-main {
	position: relative;
	min-height: 360px;
	padding: 10px;
}
.elgg-main > .elgg-head {
	padding-bottom: 3px;
	//border-bottom: 1px solid #CCCCCC;
	margin-bottom: 10px;
}

/***** PAGE FOOTER ******/
.elgg-page-footer {
	position: relative;
        bottom: 0;
        text-align: left;
        
}
.elgg-page-footer {
	color: #999;

}




/* ***********  LOGO *************** */

#logo-img{
    margin-left: -140px;
    margin-top: -10px;
    z-index: 99999;
}