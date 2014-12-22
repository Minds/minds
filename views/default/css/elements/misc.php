/* ***************************************
	MISC
*************************************** */
#login-dropdown {
	position: absolute;
	top:10px;
	right:0;
	z-index: 100;
}
.index{
margin-bottom:-115px;
}
/* ***************************************
	AVATAR UPLOADING & CROPPING
*************************************** */

#current-user-avatar {
	border-right:1px solid #ccc;
}
#avatar-croppingtool {
	border-top: 1px solid #ccc;
}
#user-avatar-cropper {
	float: left;
}
#user-avatar-preview {
	float: left;
	position: relative;
	overflow: hidden;
	width: 100px;
	height: 100px;
}

/**
 * Minds stuff
 * @todo move this to relevant sub files
 */
<?php
/**
 * CSS Extensions for Minds Theme
 */
?>

/**************
 **** BODY ****
 **************/

body{
	background:#FFF;
}
body.news{
	background:#D2D9DF;
}

/* Top Menu
 */


.index .elgg-main{
	overflow: visible !important;
	border:0;
	box-shadow:none !important;
	-webkit-box-shadow:none !important;
	-moz-box-shadow:none !important;
}


.elgg-menu-comments li{
	padding:0 3px;
}
.elgg-menu-comments li a{
	color:#CCC;
}

/* More Drop Down
 */
.elgg-menu.elgg-menu-site.elgg-menu-site-more{
	position:absolute;
	color:#333;
}

.elgg-search-header{
	margin-top:1px;
}
@media screen and (-webkit-min-device-pixel-ratio:0) {
	.elgg-search-header{
        	box-sizing: initial;
       		margin-top:-1px;
	}
}

/**
 * Homepage specific
 */


/**
 * Register
 */
input[name=terms]{
	display:none;
}
/* Login button 
 */
#login-dropdown{
	top:3px;
}
#login-dropdown:hover #login-dropdown-box{
	display:block;
}
.login-button{
	color:#333;
}
.login-button:hover{
	background:transparent;
    color:#4690D6;
    -webkit-border-radius-bottomright: 0px; 
	-moz-border-radius-bottomright: 0px;
    border-bottom-right-radius:0px;
    -webkit-border-radius-bottomleft: 0px; 
	-moz-border-radius-bottomleft: 0px;
    border-bottom-left-radius:0px;
}
/**
 * Content
 */
.minds-body-header{
	width: 100%;
	height:auto;
	background: transparent;
	padding: 25px 0;
	margin-bottom: 10px;
	/*opacity: .90;*/
	display:inline-block;
}
.minds-body-header > .inner{
	width:90%;
	margin:0 auto;
}
.minds-body-header > .inner > .elgg-head{
	min-width:998px;
}
.minds-body-header h2{
	text-align:center;
	font-size:56px;
	font-weight:lighter;
	float:none;
	margin:auto;
}
.minds-body-header h3{
	text-align:center;
	font-size:16px;
	font-weight:lighter;
}
.minds-body-header .elgg-menu-entity, .minds-body-header .elgg-menu-title{
	margin:10px;
	clear:right;
	position:absolute;
	right:2%;
	top:36px;
}
.elgg-form-wall-add{
	float:right;
	width:50%;
}
.elgg-form-wall-add textarea{
	margin:0;
	width:100%;
}
.elgg-form-wall-add:hover textarea, .minds-body-header .elgg-form-wall-add textarea:focus{
	width:82%
}
.elgg-form-wall-add .elgg-button-submit{
	float:right;
	display:none;
	min-width:15%;
	text-align:right;
}
.elgg-form-wall-add:hover .elgg-button-submit{
	display:block;
}
.elgg-form-wall-add .elgg-foot{
	display:none;
}
.elgg-form-wall-add:hover .elgg-foot{
	display:block;
}
/**
 * Footer
 */
.static-footer{
	z-index:9999;
	position:fixed;
	bottom:0;
	left:50%;
	width:450px;
	margin-left:-225px;
	background:#FAFAFA;
	border:1px solid #DDD;
	padding:0;
	border-radius: 0;
}
.static-footer .footer-social-links{
	float:right;
	line-height:33px;
}
.static-footer .footer-social-links > a{
	font-size:26px;
	color:#888;
	padding:0 4px;
}
.static-footer .copyright{
	float: right;
	color: #888;
	padding: 0 8px;
	font-size:10px;
}
.elgg-menu-footer-default{
	margin:0;
	float:left;
}
.minds-static-footer:hover .elgg-menu-footer-default{
	display:block;
}
.static-footer .elgg-menu-footer-default li{
	border-right:1px solid #DDD;
	padding:8px 16px;;
}
.static-footer .elgg-menu-footer-default li:after{
	content:none;
}
.static-footer .elgg-menu-footer-default li a{
	
	font-size:13px;
	color:#666;
	
}

/**
 * Minds Tiles
 */
.tiles .elgg-list{
	width:100%;
	height:auto;
	display:block;
}
.tiles .elgg-list li.elgg-item{
	border:0;
	float:left;
	display:block;
	width:25%;
	height:170px;
	overflow:hidden;
	position:relative;
}
li .rich-image{
	width:110%;
	margin:0 -6px;
}
li .blog-rich-image-holder{
	position: relative;
	width: 110%;
	height: auto;
	display: block;
	overflow: hidden;
	margin: 0 -10px;
}
li .blog-rich-image-holder .rich-image{
	top:-45px;
	left:0;
	position:absolute;
}
/*MASON OVERRIDE*/
.mason .blog-rich-image-holder .rich-image{
	width:100%;
	position:relative;
	top:0;
	margin-bottom:-5px;
}
.mason .blog-rich-image-holder .rich-image.youtube{
	margin:-45px 0;
}
li .excerpt{
	background: rgb(0, 0, 0); /* The Fallback */
	background: rgba(0, 0,0, 0.5); 
	position: absolute;
	width:auto;
	height:auto;
	top: 0;
	left: 0;
	font-size:12px;
	line-height:14px;
	padding: 15px;
	color: #FFF;
	display:none;
}
li:hover .excerpt{
        display:block;
}
li .excerpt a{
	color:#FFF;
}
.tiles .elgg-list li.elgg-item .info{
	left:0;
	bottom:-50px;
	padding:25px 10px 0;
	width:100%;
	
	position:absolute;
	
	
	
	background-color: transparent; 
   	/*background-image: url(images/fallback-gradient.png); */
   	background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(transparent), to(#333));
   	background-image: -webkit-linear-gradient(top, transparent, #333); 
   	background-image:    -moz-linear-gradient(top, transparent, #333);
   	background-image:     -ms-linear-gradient(top, transparent, #333);
   	background-image:      -o-linear-gradient(top, transparent, #333);
}
.tiles .elgg-list li.elgg-item .info .extras{
	width:100%;
	height:50px;
	display:block;
} 
.tiles .elgg-list li.elgg-item:hover .info{
	bottom:0;
}
.tiles .elgg-list li.elgg-item .info h2{
	font-size:14px;
	color:#FFF;
	padding:10px 0 3px;
}
.tiles .elgg-list li.elgg-item .info h2 a{
	text-decoration:none;
}
.tiles .elgg-list li.elgg-item:hover .info .time{
	color:#EEE;
	font-style:italic;
	margin:0;
	font-size:11px;
}
.tiles .elgg-list li.elgg-item .info .excerpt a{
	display:none;
}
.tiles .elgg-list li.elgg-item:hover .info .excerpt a{
	display:block;
	color:#FFF;
	text-decoration:none;
	margin:0;
	width:100%;
	max-height:70px;
	display:block;
	overflow:hidden;
}
@media (min-width: 1280px){
	.tiles .elgg-list li.elgg-item{
		width:20%;
	}
}
.tiles .elgg-list li.elgg-item .elgg-menu{
	margin:0 0 5px 0;
	float:left;
}
.tiles .elgg-list li.elgg-item .elgg-menu li{
	margin:0 15px 0 0;
}

/**
 * Register page
 */
.elgg-form-account .social{
}

.social-login{
	margin:16px 6px 0;
	width:100%;
	height:50px;
	clear:both;
}
.social-login > .social_login{
	float:left;
	margin:0 10px 0 0;
	width:auto;
	clear:none;
}
.social .facebook{
	padding-top:1px;
}
	
/***************
 **CUSTOMINDEX**
 **************/

.front-page-buttons{
	margin:30px 0 0;
	float:left;
	z-index:1;
}
.carousel-fat .elgg-menu-right-filter{
	margin:50vh 0 0 0;
}
.elgg-menu-right-filter{
	float:right;
	margin:36px 0 0 0;
	z-index:1;
}
.elgg-menu-right-filter li{
	font-size:14px;
	margin:0 10px;
}
.elgg-menu-right-filter li a{
	color:#666;
}
.elgg-menu-right-filter li.elgg-state-selected a{
	color:#4690D6;
}
.front-page-buttons a{
	margin-right:20px;
}
.minds_index h3 a{
	color:#4690D6;
	font-weight:normal;
}
.minds_index > .logo{
	margin:auto;
    width:200px;
} 
.minds_index > .search{
	width:600px;
	margin:auto;
}
.minds_index > .search input{
	float:left;
    width:525px;
}

.minds_index > .search form .elgg-button-submit{
	margin-left:5px;
    padding:4px;

}
.minds_index > .earlyAccess{
	width:575px;
	margin:0 auto 15px auto;
}
.minds_index > .earlyAccess .elgg-input-text{
	float:left;
	width:175px;
	margin:0 5px;
}
.minds_index > .earlyAccess .elgg-button{
	float:left;
	margin:0 5px;
	padding:5px;
}
.minds_index > .splash{
	width:100%;
	font-size:54px;
	font-weight: normal;
	text-align:center;
	letter-spacing:3px;
	line-height:75px;
	color:#666;
	font-family: 'Ubuntu Light', 'Ubuntu', 'Ubuntu Beta', UbuntuBeta, Ubuntu, 'Bitstream Vera Sans', 'DejaVu Sans', Tahoma, sans-serif;
	margin-top:10px;
}
.minds_index > .featured_wall{
	clear:both;
	width:100%;
	padding-top:25px;
}
.minds_index > .featured_wall h3{
	font-family: 'Ubuntu Bold', 'Ubuntu', 'Ubuntu Beta', UbuntuBeta, Ubuntu, 'Bitstream Vera Sans', 'DejaVu Sans', Tahoma, sans-serif;
	margin: 0 10px;
}
.minds_index .block{
	margin:25px 0;
	display:block;
	width:100%;
	height:auto;
	position:relative;
}
.minds_index .news-block{
	float:left;
	width:680px;
}
.minds_index .side-block{
	float:right;
	width:250px;
	background:#EDEDED;
	border: 1px #CCC solid;
	margin:30px 0;
	padding:10px;
	border-radius:2px;
	-webkit-border-radius: 2px;
    -moz-border-radius: 2px;
}
.minds_index .side-block p {
	font-size: 14px;
}

/**
 * Sidebar Footer
 */
.sidebar-footer{
	width:225px;
	position:absolute;
	margin:25px 0;
	clear:both;
	color:#888;
	text-shadow: 0px 0px 1px #DDD;
}

.sidebar-footer .elgg-menu-footer-default{
	text-align:left;
}
.elgg-menu-footer > li, .elgg-menu-footer > li > a {
	display: inline-block;
	color: #CCC;
	font-size: 11px;
}

/******************
 ** CUSTOM PAGES **
 *****************/
.elgg-sidebar .elgg-owner-block{
	-webkit-border-radius: 3px 3px 0 0;
	-moz-border-radius: 3px 3px 0 0;
 	border-radius:3px 3px 0 0;
}
.elgg-sidebar .elgg-owner-block .elgg-image-block{
	padding:0;
}
.elgg-sidebar .elgg-owner-block .elgg-image-block .elgg-body{
	padding:5px;
}

.elgg-sidebar li.elgg-item{
	margin: 5px;
	width: 306px;
	padding: 0;
	height: auto;
	background: #FFF; 
	border: 1px solid #DDD;
	box-shadow: 0 0 0;
}
.elgg-gallery-users li.elgg-item{
	margin:1px;
	width:auto;
	padding:0;
	height:auto;
}
.elgg-sidebar li.elgg-item img{
}
.elgg-sidebar li.elgg-item a{
}
.elgg-sidebar li.elgg-item h3, .elgg-sidebar li .stamp{
	padding: 8px;
	color: #000;
	font-size: 14px;
	font-weight: 800;
	line-height: 1.4em;
}
.blog-sidebar li a.title{
	font-weight: bold;
	/* bottom: 0; */
	/* left: 0; */
	background: rgba(0,0,0,0.75);
	z-index: 999;
	color: #FFF;
	/* position: absolute; */
}
.blog-sidebar li img.rich-image{
	margin:0;
/*	height:360px ; */
}
.elgg-sidebar li a h3{
	color:#333;
	font-size:16px;
}
.elgg-sidebar .elgg-module-aside{
	background:#FFF;
	padding:10px;
	margin:0;
}

.load-more{
	clear:both;
	width:200px;
    padding:8px;
    margin:auto;
    text-align:center;
    background:#EFEFEF;
    border:1px solid #DDD;
  
    color:#999;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    cursor:pointer;
}
.elgg-list-river{
	border-top:0;
}

.load-more .dots{
	font-size:48px;
	font-weight:900;
	padding-bottom:8px;
}
.load-more p{
	margin:0;
	padding:0;
}

/**
 * Homepage news
 */
.news-block .elgg-river li.elgg-item{
	background:#f9f9f9;
	border:1px #CCC solid;
	padding:10px;
	margin:5px 0;
	border-radius:2px;
	-webkit-border-radius: 2px;
    -moz-border-radius: 2px;
}
.elgg-river-attachments .elgg-photo.large{
	width:98.5%;
}
/* ***************************************
	ANNOUNCEMENT
**************************************** */
#announcement {
   margin: 5px auto;
   padding: 7px;
   background: #ceffcc;
   border: 2px solid #18ba18;
   border-radius: 5px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
}
#announcement h2 {
  color: #000;
  font-size:14px;
  font-weight: bold;
}


/* ***************************************
	RIVER
**************************************** */
.elgg-river-item form{
	-moz-border-radius: 0px;
    -webkit-border-radius: 0px;
    border-radius: 0px;
}
.elgg-river-responses {
}
input.comments.inline{
	height:25px;
    font-size:11px;
}


/* Comments 
 */
 

.hj-annotations-bar {
     -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
}
form.elgg-form.hj-ajaxed-comment-save {
	padding:5px;
}
form.elgg-form.hj-ajaxed-comment-save input{
	margin:0;
    height:25px;
    width:100%;
    font-size:12px;
 	padding:0 8px;
}

/** 
 *
 * Online Status
 *
 */
 
.minds_online_status_small{
    margin: -13px 0 0;
    right:0;
    position: absolute;
}
.minds_online_status_tiny{
    margin: -9px 0 0;
    right:0;
    position: absolute;
}
.minds_online_status_medium{
    margin: -16px 0 0;
    right:0;
    position: absolute;
}
.minds_online_status_large{
    margin: -20px 0 0;
    right:0;
    position: absolute;
}
.minds_online_status_full{
    margin: -64px 0 0;
    right:0;
    position: absolute;
}

/**
 * Hover menu left instead of right
 */
.elgg-avatar > .elgg-icon-hover-menu {
	left:0;
}
.elgg-menu.elgg-menu-hover{
	margin-left:-25px;
    }
/**
 * Hide RSS
 */
li.elgg-menu-item-rss{
	display:none;
}

/** 
 * Upload Form
 */
.elgg-form-minds-upload .progressbox {
    border: 1px solid #0099CC;
    padding: 1px;
    position:relative;
    width:400px;
    border-radius: 3px;
    margin: 10px;
    display:none;
    text-align:left;
}
.elgg-form-minds-upload .progressbar {
    height:20px;
    border-radius: 3px;
    background-color: #003333;
    width:1%;
}
.elgg-form-minds-upload .statustxt {
    float:left;

    display:inline-block;
    color: #000000;
}
.thumbnail-tile{
	width:300px;
	height:185px;
	margin:10px;
	background:#EEE;
	display:block;
	float:left;
	overflow:hidden;
	position:relative;
}
.thumbnail-tile img{
	width:100%;
	
}
.thumbnail-tile .hover{
	width:300px;
	height:50px;
	/*background:url(<?php echo elgg_get_site_url();?>mod/minds/graphics/transparent_white.png);*/
	background-color:#EEE;
	position:absolute;
	margin:135px 0;
	display:none;
}
.thumbnail-tile > .hover > .inner{
	padding:8px;
}
.thumbnail-tile > .hover > .inner > .title{
	font-size:12px;
	font-weight:bold;
	padding:0;
	margin:0;
}
.thumbnail-tile > .hover > .inner > .owner{
	padding:0;
	margin:0;
	font-size:10px;
}
.thumbnail-tile .elgg-menu{
	margin:-25px 5px;
}
.thumbnail-tile .elgg-menu li{
	float:right;
}
.elgg-river-attachments .elgg-photo.large{
	width:98.5%;
}

.minds-licenses .license{
	float:left;
	width:400px;
	margin:10px;
}
.minds-licenses .license h3{
	padding:5px 0;
}
.minds-licenses .license img{
	float:left;
	margin:10px;
}


/**
 * rich content
 */
.rich-content{

}
.rich-content .elgg-image {
	float:none;
}

.rich-content .readmore{
	font-weight:bold;
}
.rich-content .rich-image-container{
	
}
.elgg-widget-content .rich-content .rich-image-container{
	width:200px;
	height:85px;
}
.rich-content .rich-image{

}
.rich-content.sidebar h3{
	font-size:14px;
	line-height:14px;
}
.rich-content.sidebar h3 a:hover{
	color:#4690D6;
	text-decoration:none;
}
.rich-content.sidebar  .rich-image-container{
	margin:0;
	width:100px;
	height:60px;
}
.rich-content.sidebar .rich-image{
	margin:0;
	width:100%;
}

.rich-content.carousel .rich-image-container{
	margin:0 15px 0 0;
	width:100%;
	height:100%;
}

.homepage{
	padding-top:260px;
}
.homepage > h2{
	margin-top:-160px;
}
/**
 * Carousel
 */
.carousel-admin-wrapper{
	width:100%;
	height:250px;
	display:block;
	margin:8px 0 72px;
	position:relative;
	background-color:#222;
	filter: alpha(opacity=0);
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover !important;
	background-position: center !important;
}
.carousel-admin-wrapper .drag.entypo{
	font-size: 37px;
	padding: 16px;
	z-index: 999999;
	position: relative;
	/* color: #FFF; */
	text-shadow: 0 0 3px #FFF;
	width: 25px;
	margin: 0;
	cursor: pointer;
}
.carousel-admin-wrapper .bg_wrapper{
	overflow:hidden;
	width:100%;
	height:250px;
	position:absolute;
}
.carousel-admin-wrapper img{
	width:100%;
	min-width:997px;
	height:auto;
	position:relative;
}
.carousel-admin-wrapper .remove{
	float: right;
	margin: 8px;
	width: 34px;
	min-width: 0;
}
.carousel-admin-wrapper .actions{
	display:table;
	width:100%;
	position:absolute;
	bottom:-72px;
}
.carousel-admin-wrapper .actions > div{
	display:table-cell;
	padding:8px;
}
/*.carousel-admin-wrapper .bg-input{
	padding: 8px;
	background: #FFF;
	width: 200px;
	height: 40px;
	margin: 8px auto;
	display: block;
	position: absolute;
	bottom: 0;
	right: 8px;	
}
.carousel-admin-wrapper .carousel-colorpicker{
	position: absolute;
	padding: 8px;
	margin: 8px auto;
	right:206px;
	bottom:0;
	width: 100px;
	height: 40px;
}
.carousel-admin-wrapper .shadow-color{
	right:282px;
}
.carousel-admin-wrapper .carousel-href{
	position: absolute;
	bottom: 6px;
	width: 200px;
	padding: 12px;
	right: 372px;
}
*/
.carousel-admin-wrapper > textarea{
	display:block;
	margin:32px;
	padding:0;
	background: transparent;
	border: 0;
	font-size: 60px;
	color: #FFF;
	font-weight: 100;
	text-align: left;
	width:800px;
	position:absolute;
	border:1px dotted #DDD;
}
.carousel-admin-wrapper > input[type=text]{
	position:absolute;
	top:160px;
	margin:32px;
	border:1px dotted #DDD;
	background:transparent;
	width:800px;
}

#carousel_wrapper{
	z-index:0;
	background-color: #222;
	width: 100%;
	height: 380px;
	overflow: hidden;
	position: absolute;
	top: 0;
	left: 0;
}
#carousel > div{
	float:left;
	display:block;
	
	width:2800px;
	height:380px;
	padding:0;
	
	background-color:#222;
	filter: alpha(opacity=0);
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover !important;
	background-position: center !important;
}
#carousel > div > h2{
	width:990px;
	color:#FFF;
	/*color:#222;*/
	font-weight:lighter;
	margin:128px auto 0;
}
#carousel > div > h3, #carousel > div > h3 a{
	color:#F8F8F8;
	/*color:#222;*/
}

.carousel{
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height:380px;
	overlflow:hidden;
}
.carousel .item{
	top:0; 
	left:0;
	width:100%;
	height: 380px;
	opacity:0;
	overflow:hidden;
	-moz-transition: opacity ease-in-out .7s;
        -o-transition: opacity ease-in-out .7s;
        -webkit-transition: opacity ease-in-out .7s;
        transition: opacity ease-in-out .7s;
	position: absolute;
	display:block;
}

.carousel-fat .minds-body-header{
	height:100vh;
}
.carousel-fat .carousel{
	height:100vh;
}
.carousel-fat .carousel .item{
	height:100vh;
}
.carousel-fat .carousel .item > .carousel-caption{
	top: 30vh;
}
.carousel-fat .carousel-inner > .item > img {
	/*top:-20vh;*/
	height:auto;
	min-height	100%;
	width:auto;
	min-width:100%;
}

.carousel-fat .elgg-layout{
	min-height:0;
}
.carousel-fat .minds-body-header{
	margin:0;
	padding:0;
}

.carousel .item.active{
	opacity: 1;
}
.carousel-inner > .item > img {
	position: relative;
	top: 0;
	left: 0;
	width:auto;
	min-width:100%;
	height:auto;
	min-height:380px;

}
.carousel-inner > .item > .carousel-caption{
	position: absolute;
	top: 120px;
	left: 0;
	width:100%;
	height: auto;
	font-weight:lighter;
	z-index:2;
}
.carousel-inner > .item > .carousel-caption > .inner{
	width: auto;
	margin:auto;
	padding: 10px 13px;
	/*background: rgba(000,000,000,0.4);*/
	position: relative;
	display:table;
	max-width:90%;	
}
.carousel-inner > .item > .carousel-caption h3{
	color:#FFF;
	line-height:64px;
	font-size:64px;
	text-shadow:0 0 2px #BBB;
	font-family:"Lato", 'Helvetica', Arial, Sans-Serif;
}
.carousel-inner > .item > .carousel-caption p{
	color:#FFF;
	text-align:center;
	font-family:"Lato", 'Helvetica', Arial, Sans-Serif;
	padding-top:12px;
	font-size:20px;
}

.elgg-widget-more{
	width: 100%;
	height: auto;
	display: block;
	clear: both;
}

.com-ui{
	display:block;
	margin:auto;
	width:990px;
}
.com-ui .frontpage-signup{
	width:400px;
	box-shadow:0 0 3px #FFF;
}
.com-ui .node{
	float:left;
	background: #FFF;
	padding: 8px;
	width:380px;
}
.com-ui .node .domain-input{
	position:relative;
	width:260px;
	float:left;
}
.com-ui .node .domain-input input{
	width:100%;
	float:left;
	border:1px solid #DDD;
}
.com-ui .node .domain-input .url-domain{
	position: absolute;
	right: 0;
	top: 0;
	padding:12px;
	font-weight:bold;
}
.com-ui input[type=submit]{
	width:auto;
	float:left;
	margin-left:12px;
}

.com-ui .user{
	float:right;
	background: #FFF;
	padding: 8px;
} 
.com-ui .user .user-input{
	position:relative;
	width:255px;
	float:left;
}
.com-ui .user .user-input input{
	width:100%;
	float:left;
	border:1px solid #DDD;
	padding-left:120px;
}
.com-ui .user .user-input .url-domain{
	position: absolute;
	left: 0;
	top: 0;
	padding:12px;
	font-weight:bold;
}

.frontpage-signup{
	position: relative;
	width: 600px;
	height: 40px;
	margin: auto;
	z-index: 1;
	float: none;
}
.carousel-fat .frontpage-signup{
	margin-top:20vh;
}
	
.frontpage-signup input{
	width: 136px;
	margin: 4px;
	box-shadow: 0 0 6px #FFF;
}

div.signup-options {

    margin-top: 120px;
}

div.signup-options div.signup-button-row {
    text-align: center;
    margin-bottom: 35px;
}

div.signup-options div.signup-button  {
    margin: 110px;
    padding: 15px;
    margin-left: auto;
    margin-right: auto;
}

div.signup-options div.video {
    height: 200px;
    width: 300px;
    margin: 10px;
    margin-left: auto;
    margin-right: auto;
    border: 1px solid #000;
}


div.signup-options div.option {
    width: 400px;
}

div.signup-options .left {
    float: left;
}

div.signup-options .right {
    float: right;
}

/**
 * Ad specific
 */
.content-block-ratator{
	height:720px;
	margin:18px;
	width:95%;
}
.banner-ad{
	height: auto;
    margin: 16px auto;
    position: relative;
    width: 730px;
}
.banner-ad .inner{
	width:auto;
	height:auto;
	margin:auto;
}
.contentad-side{
	width: 300px;
	height: 600px;
	margin: 18px;
	background: #AAA;
}

.toobla-side{
        margin: 18px;
}
.adblade iframe{
	margin: 10px auto;
	display:block;
}

div.tier_details {
border: 1px solid #000;
}

div.register-popup {
    width:700px;
    margin-left: 30px;
    margin-right: auto;
}

/**
 * Ad specific
 */
.contentad{
	width:100%;
	height:620px;
}
.adblade iframe{
	margin: 10px auto;
	display:block;
}

/**
 * TEMP DONATIONS
 */
.donations-box{
	position:absolute;
	top:80vh;
	left:50%;
	margin:auto;
	margin-left:-300px;
	width:700px;
	overflow:visible;
}

.donations-button{
	background:#4690D6;
	padding:12px 24px;
	margin:4px;
	border-radius:5px;
	color:#FFF;
}
.donations-button:hover{
	box-shadow:0 0 3px #FFF;
	color:#FFF;
	text-decoration:none;
}
.donations-button .entypo{
	padding-right:12px;
}

#taboola-header-thumbnails{
	border: 1px solid #EEE;
	padding: 8px;
	margin: 8px auto;
	width:60%;
	display:block;
}


.global-sidebar .elgg-menu-page a{
	border:0;
}
