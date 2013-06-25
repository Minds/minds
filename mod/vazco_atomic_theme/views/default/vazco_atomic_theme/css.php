<?php //ł
$gfx_src = $vars['url']."mod/vazco_atomic_theme/graphics/"; 
?>
/*
CSS
*/

*{margin:0;padding:0;}
:focus,:active {outline:0}
ul,ol{list-style:none}
h1{font-size:1.4em;}
a img{border:0}

body { font: normal small Arial, Helvetica, sans-serif;
	color: #999999; background-color:#fff;
        }

a { text-decoration: none; color: #EA672E; }
a:hover { color: #6796ce; }

h1, h2, h3, h4, h5, h6{color:#11A6D4 !important;}
label{font-size:90% !important;}

/*############################
########### LOG IN ###########
############################*/

#login-dropdown {
	position:absolute;
	top:10px;
	right:5px;
	z-index: 100;
  
}
.elgg-button-dropdown {
	padding:3px 6px;
	text-decoration:none;
	display:block;
	font-weight:bold;
	position:relative;
	margin-left:0;
	color: white;
	border:1px solid #fff;
	background-color:transparent;
	-webkit-border-radius:4px;
	-moz-border-radius:4px;
	border-radius:4px;
	
	-webkit-box-shadow: 0 0 0;
	-moz-box-shadow: 0 0 0;
	box-shadow: 0 0 0;
	
	
}
.elgg-button-dropdown:hover{
    background-color:#fff;
}

/*############################
###########HEADER###########
############################*/
.elgg-page
.elgg-page-header{
 background: #fff url(<?php echo $gfx_src; ?>img01.gif) repeat-x 0px 0px;

	margin: 0px auto 0px auto;
      
	height:170px;
	
}
.elgg-page
.elgg-page-header .elgg-inner{
     background: transparent url(<?php echo $gfx_src; ?>img02.jpg) no-repeat 0px -1px;
height:170px;

clear:both;

}
.elgg-page
.elgg-page-header .elgg-inner h1{
   display:inline;
   float:left;
   margin:60px 0px 0px 5px;
}

.elgg-page
.elgg-page-header .elgg-inner h1 a{
 font-size: 1.6em;
 margin: 0;
 text-shadow: 1px 1px 1px #000; 
   font-style:normal;
  
 
}



.elgg-page
.elgg-page-header .elgg-menu-site-default {

    height:40px;

left:0px;
bottom:0px;

 -webkit-border-radius: 10px 10px 0 0;
-moz-border-radius: 10px 10px 0 0;
border-radius: 10px 10px 0 0;  


}

.elgg-page
.elgg-page-header .elgg-menu-site-default li{


   

}
.elgg-page
.elgg-page-header .elgg-menu-site-default li a{
float:left;

font-size: 15px;
   color: #EA672E;
    text-transform:uppercase;
  height:20px; 
padding:10px;

-webkit-box-shadow:0px 0px 0px rgba(0, 0, 0, 0.25);
-moz-box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.25);
box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.25);

-webkit-border-radius: 0 0 0 0;
-moz-border-radius: 0 0 0 0;
border-radius: 0 0 0 0;
font-weight:bold;
 background: transparent url(<?php echo $gfx_src; ?>img06.gif) no-repeat top right;

}


.elgg-page
.elgg-page-header .elgg-menu .elgg-more a{
    background:none;
}


.elgg-page
.elgg-page-header .elgg-menu-site-default li a:hover,
.elgg-page
.elgg-page-header .elgg-menu-site-default li:hover a
{
    text-decoration:none;

    color:#11A6D4;
}

.elgg-page
.elgg-page-header .elgg-menu-site-default li.elgg-state-selected a{
    


text-decoration:underline;


letter-spacing:0px;
}

.elgg-menu-site > .elgg-state-selected > a,
.elgg-menu-site > li:hover > a{

  text-decoration:none;
  background:none;
}

.elgg-page
.elgg-page-header .elgg-menu .elgg-more ul.elgg-menu-site-more{
padding:5px;
width:auto !important;
background:none;
clear:both;
position:absolute;
top:40px;
border:none;
left:-2px;
border:1px solid #d9d9d9;
border-width: 1px 1px 1px 1px;  
background-color: #f1f1f1;

}


.elgg-page
.elgg-page-header .elgg-menu .elgg-more ul.elgg-menu-site-more li{
  
   height:26px;
    line-height:16px;
   
}
.elgg-page
.elgg-page-header .elgg-menu .elgg-more ul.elgg-menu-site-more li a{
    height:auto;
   color:#EA672E;
    background:none;
padding:5px !important;
}
.elgg-page
.elgg-page-header .elgg-menu .elgg-more ul.elgg-menu-site-more li a:hover{

   color:#11A6D4 ;

}

.elgg-search-header{
right:5px;
bottom:50px;
}
.elgg-search fieldset{
     /*background: #fff url(<?php echo $gfx_src; ?>input_bg.png) repeat-x 0px 0px;*/

}

.elgg-search input[type="text"]{
    border: 1px solid #fff;
    color: #eee;
    width:198px;

}
.elgg-search input[type="text"]:focus,
.elgg-search input[type="text"]:active{
  border: 1px solid #ccc;
    color: #fff;
  background:url(<?php echo $gfx_src; ?>elgg_sprites.png) no-repeat scroll 2px -934px transparent;
}

input {
 /*background: #fff url(<?php echo $gfx_src; ?>input_bg.png) repeat-x 0px 0px;*/
 

}

/*############################
###########BODY###########
############################*/
.elgg-main > .elgg-head{
    border-bottom:0px dashed #ccc ;
    margin-bottom: 5px;
       -webkit-border-radius: 0px;
-moz-border-radius: 0px;
border-radius: 0px;
}
.elgg-layout-one-sidebar{
    background:none;
   
}

.sidebar .elgg-menu-page li.elgg-state-selected > a{
 
}

.elgg-menu-page li.elgg-state-selected > a{
   background-color:#11A6D4;



}
.elgg-menu-page li.elgg-state-selected > a:hover{
   color:#fff;
}
.elgg-menu-page a{

margin-bottom:0px;
}

.elgg-menu-page a:hover{
background-color:#ccc;
   color:#fff;
}

.elgg-menu > li{
    margin:2px 0px;  
}

.elgg-menu > .elgg-state-selected > a,
.elgg-menu > li:hover > a{
    color:#333;
  text-decoration:underline;

}

.elgg-page
.elgg-page-body {
 background: #fff url(<?php echo $gfx_src; ?>img01.gif) repeat-x 0px -170px;
 padding-top:15px;

}
.elgg-page
.elgg-page-body > .elgg-inner{
    
    background: transparent;

/*-webkit-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
	box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);*/
border:none;

/* -webkit-border-radius: 8px;*/
/*-moz-border-radius: 8px;*/
/*border-radius: 8px;*/

}




.elgg-page
.elgg-page-body .elgg-inner .elgg-sidebar{
padding:20px 0px 20px 0px;
}

.elgg-page
.elgg-page-body .elgg-inner .elgg-module{
    margin-bottom:25px;
   padding-bottom:5px;
      background: #f7f7f7 url(<?php echo $gfx_src; ?>box_grad.gif) repeat-x 0px 40px;
       border:1px solid #ccc;
}

.elgg-page
.elgg-page-body .elgg-inner .elgg-module .elgg-body{
padding:5px;
}
.elgg-menu > li > a{
    background-color:transparent;
}


.elgg-page
.elgg-page-body .elgg-inner .elgg-module .elgg-head{
  background: #f7f7f7 url(<?php echo $gfx_src; ?>img07.gif) repeat-x 0px 0px;


 border-bottom:none;
height:40px;
line-height:40px;
   -webkit-border-radius: 0px;
-moz-border-radius: 0px;
border-radius: 0px;
margin-bottom:5px;
padding-bottom:0px;
}



.elgg-page
.elgg-page-body .elgg-inner .elgg-module .elgg-head a,
.elgg-page
.elgg-page-body .elgg-inner .elgg-module .elgg-head h3
{
    color:#fff !important;
    font-weight:normal; 
}


.elgg-module-info > .elgg-head{
    padding:0px 5px;
}

.elgg-page
.elgg-page-body > .elgg-inner h2,
 .elgg-heading-main{

   position:relative;
 
   
   margin:0px;
  margin-bottom:15px;
  
   font-size:2em;
 

}

.elgg-page .elgg-list{

  padding:5px;
  border: 1px solid #CDE1F5;
  margin-top:0px;
}

.elgg-page .elgg-list li{
    padding:3px;
}
.elgg-page
.elgg-page-body .elgg-menu-filter,
.elgg-page
.elgg-page-body .elgg-tabs{
    margin-bottom:-1px;
   border-bottom: 2px solid #CDE1F5;
}
.elgg-page
.elgg-page-body .elgg-menu-filter > li,
.elgg-page
.elgg-page-body .elgg-tabs > li{
  border: 2px solid #CDE1F5;
  border-bottom:none;
}
.elgg-image-block .elgg-image img{
    border: 1px solid #CDE1F5;  
}

.elgg-page
.elgg-page-body .elgg-river-layout .elgg-input-dropdown{
    position:absolute;
    top:25px;
    left:75%;
}
    
.elgg-page
.elgg-page-body .elgg-inner .elgg-module .elgg-head h3{
font-size:15px;
   background: transparent url(<?php echo $gfx_src; ?>img08.gif) no-repeat 3px 14px;
   padding-left:20px;
}



.elgg-module-widget > .elgg-head h3 {
	float: left;
	padding: 4px 45px 0 20px;
	
}


.elgg-page
.elgg-page-body .elgg-head .elgg-image-block
 {
 height:28px !important;
padding:7px 0px 0px 10px;
}
.elgg-page
.elgg-page-body .elgg-head .elgg-image-block .elgg-body h3{
    height:22px !important;
    float:left;
    line-height:16px;
    background:none;
    padding-left:2px;
}

.elgg-page
.elgg-page-body .elgg-head .elgg-image-block .elgg-body h3 a{
    color:#fff;
    float:left;
      height:28px !important;
      line-height:28px;
      overflow:hidden;
      margin-top:-6px;
}

.elgg-page
.elgg-page-body .elgg-head .elgg-image-block .elgg-body h3 {
    padding-top:2px;
}
.elgg-page
.elgg-page-body .elgg-head .elgg-image-block .elgg-body {
   background:none;
   border:none;
}


.elgg-subtext {
	color: #bbb;
	font-size: 85%;
	line-height: 1.2em;
	font-style: italic;
        display:block;
       padding:0px 5px;
       text-align:left;
       float:left;
}

/*############################
###########Footer###########
############################*/
.elgg-page
.elgg-page-footer > .elgg-inner{
   
   
   height: 40px;
	


/*-webkit-box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.5);
	box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.5);*/
border: none;
}

.elgg-page
.elgg-page-footer .elgg-inner .sign{
text-align:center;
    margin:13px auto 0 auto;
}


/*############################
###########Buttons###########
############################*/

.elgg-button-submit {
	color: white;
	text-shadow: 1px 1px 0px black;
	text-decoration: none;
	border: 1px solid  #ccc;
	background: #F58D35 url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left 0px;
}

.elgg-button-submit:hover {
	border-color: #ccc;
	text-decoration: none;
	color: #eee;
	background: #F58D35 url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left -5px;
}
.elgg-button-cancel{
	color: #fff;
	background: #ccc url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left -1px;
    
}
.elgg-button-cancel:hover{
	color: #333;
	background: #ccc url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left -5px;
    
}


.elgg-button-submit.elgg-state-disabled {
	background: #999;
	border-color: #999;
	cursor: default;
}

.elgg-button-action{
   text-shadow:none;
background: #F58D35 url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left -2px !important;
color:#eee;

}
.elgg-button-action:hover{
 	background: #F58D35 url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left -7px !important;
color:#fff !important;
text-decoration:none !important;
}

.elgg-button-delete
{
 	background: #FF2D2D url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left -3px;
color:#eee !important;
}
.elgg-button-delete:hover
{
 	background: #ff2d2d url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left -7px;
color:#fff !important;
}
/* ***************************************
	PAGINATION
*************************************** */
.elgg-pagination a, .elgg-pagination span {
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	padding: 2px 6px;
	color: #F58D35;
	border: 1px solid #EA672E;
	font-size: 12px;
}
.elgg-pagination a:hover {
	background: #EA672E;
	color: white;
	text-decoration: none;
}
.elgg-pagination .elgg-state-disabled span {
	color: #CCCCCC;
	border-color: #CCCCCC;
}
.elgg-pagination .elgg-state-selected span {
	color: #11A6D4;
	border-color: #11A6D4;
}


/* ***************************************
	BREADCRUMBS
*************************************** */

.elgg-main .elgg-breadcrumbs {
	position: relative;
	
	left: 0;
        margin:10px 0px;
}

/* ***************************************
	OWNER BLOCK
*************************************** */

.elgg-menu-owner-block li a:hover {
	background-color: #ccc;
	color: white;
	text-decoration: none;
}
.elgg-menu-owner-block li.elgg-state-selected > a {
	background-color: #11A6D4;
	color: white;
}

/* ***************************************
	Featured
*************************************** */
.elgg-module-featured {
	border: 1px solid #ccc;
	
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	border-radius: 0px;
        -webkit-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
}
.elgg-module-featured > .elgg-head {
	padding: 0px 5px;
	background-color: #4690D6;
      
}

/* ***************************************
	popup
*************************************** */
.elgg-module-popup {
	background-color: white;
	border: 1px solid #ccc;
	
	z-index: 9999;
	margin-bottom: 0;
	padding: 0px;
	
	
	-webkit-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
}
.elgg-module-popup > .elgg-head {
	margin-bottom: 5px;
}
.elgg-module-popup > .elgg-head * {
	
}
.elgg-module-popup .elgg-body{
    padding:5px;
}
/* ***************************************
	widget
*************************************** */
.elgg-module-widget {
/*    -webkit-border-radius: 12px 12px 0px 0px;
	-moz-border-radius: 12px 12px 0px 0px;
	border-radius: 12px 12px 0px 0px;
	background-color: #dedede;
	padding: 2px;
	margin: 0 5px 15px;
	position: relative;*/
    background: #f7f7f7 url(<?php echo $gfx_src; ?>box_grad.gif) repeat-x 0px 40px;
    padding:0px 0px !important;
}
.elgg-module-widget > .elgg-body{
    background:transparent !important;
    border-top:0px solid #fff;
}

.elgg-module-widget:hover {
/*	background-color: #ccc;*/
}

.elgg-module-widget .elgg-head{
margin-bottom:0px !important;

}
.elgg-module-widget .elgg-body .elgg-widget-content{
 padding:0px;   
}
.elgg-module-widget .elgg-body .elgg-widget-content .elgg-list{
border:0px;
}

.elgg-module-widget .elgg-body{
width:auto !important;
padding:0px;
}
.elgg-module-widget .elgg-head h3{
    line-height:35px !important;
background:none !important;
}
.elgg-module-widget > .elgg-head a{
    top:-5px;
    padding:0px;
}
.groups-widget-viewall {
    /*padding-top:3px;  */
}
.groups-widget-viewall a{
color: #ddd !important;
  
}
.elgg-page
.elgg-page-body .elgg-head .elgg-image-block .elgg-body .elgg-subtext{
    display:none;
}

/* ***************************************
	forms and forms elements
*************************************** */

input:focus, textarea:focus {
	border: solid 1px #777;

	color:#222;
      background: #fff url(<?php echo $gfx_src; ?>input_bg.png) repeat-x 0px 0px;
}

/* ***************************************
	top bar
*************************************** */

.elgg-page-topbar {
	background:  #8C0209 url(<?php echo elgg_get_site_url(); ?>_graphics/toptoolbar_background.gif) repeat-x top left;
	border-bottom: 1px solid #777;
	position: relative;
	height: 24px;
	z-index: 9000;
}

.elgg-menu-topbar > li > a {
	padding: 2px 15px 0;
	color: #eee;
	margin-top: 1px;
}

.elgg-menu-topbar > li > a:hover {
	color: #bbb !important;
	text-decoration: none;
}
.elgg-state-success{
    background-color:#19AF62;
}

.groups-profile-fields .odd, .groups-profile-fields .even,
.groups-stats{

}

