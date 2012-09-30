<?php
/**
 * CSS Extensions for Minds Theme
 */
?>
/**************
 **** BODY ****
 **************/
 
body{
	background:#FEFEFE;
 }

/***************
 *** TOP BAR ***
 **************/
/* Top Menu
 */
.elgg-page-topbar > .elgg-inner{
	
 }
.elgg-menu.elgg-menu-site.elgg-menu-site-default{
	position:relative;
	float:left;   	
}
.elgg-page-topbar .elgg-menu-item-minds-logo{
	margin-top:-8px;
 }
.elgg-menu-topbar .elgg-menu-item-minds-logo > a{
	padding:0; 
	margin:0 15px 0 0;
}
.elgg-page-topbar .elgg-menu-item-profile{
	margin:0px 5px 0 0;
 }

/* More Drop Down
 */
.elgg-menu.elgg-menu-site.elgg-menu-site-more{
	position:absolute;
}

/*Search modifications 
 */
li.elgg-menu-item-search > a, li.elgg-menu-item-login > a  {
	padding:0;
}

/* Login button 
 */
#login-dropdown{
	position:relative;
    top:0;
}
.elgg-button.elgg-button-dropdown{
	font-size:12px;
	width:65px;
    border:0;
    color:#333;
}
.elgg-button.elgg-button-dropdown:hover{
	background:transparent;
    color:#4690D6;
    -webkit-border-radius-bottomright: 0px; 
	-moz-border-radius-bottomright: 0px;
    border-bottom-right-radius:0px;
    -webkit-border-radius-bottomleft: 0px; 
	-moz-border-radius-bottomleft: 0px;
    border-bottom-left-radius:0px;
}
/***************
 **CUSTOMINDEX**
 **************/
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
    width:500px;
}

.minds_index > .search form .elgg-button-submit{
	margin-left:5px;
    padding:4px;

}
.minds_index > object{
	margin-top:10px;
	background:#000;
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
/******************
 ** CUSTOM RIVER **
 *****************/
.is_riverdash_left {
	width:210px;
	margin:15px 0 20px 0px;
	min-height:360px;
	float:left;
	padding:0 0 15px 0;
	background: rgb(255, 255, 255); /* The Fallback */
    background: rgba(255, 255, 255, 0.75); 
	-webkit-border-radius: 5px;
    -moz-border-radius: 5px;
}
.is_riverdash_middle {
	float: right;
	width:585px !important;
	margin:15px;
	min-height:360px;
	float:left;
	padding:0;
	background: rgb(255, 255, 255); /* The Fallback */
    background: rgba(255, 255, 255, 0.75); 
	/*border-left:2px solid #cccccc;*/
	-webkit-border-radius: 5px;
    -moz-border-radius: 5px;
}
.is_riverdash_middle .elgg-module-wall {
	padding:10px;
}
.is_riverdash_middle .elgg-menu-filter {
	padding:0 10px;
	margin:0;
}
.is_riverdash_middle .elgg-list-river{
	margin:0;
	background:#FFF;
	padding:0 10px;
}
.is_riverdash_middle #elgg-river-selector{
	margin:-25px 10px;
	width:100px;
}
.is_riverdash_right {
	width:140px;
	margin:15px 0;
	padding: 10px;
	min-height:360px;
	float:left;
	background: rgb(255, 255, 255); /* The Fallback */
    background: rgba(255, 255, 255, 0.75); 
	-webkit-border-radius: 5px;
    -moz-border-radius: 5px;
}

#dashboard1 {
	padding:5px;
}
.is_riverdash_left .elgg-module{
	border-top:2px solid #CCC;
	background:#FFF;
	-webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    margin:0;
}
.is_riverdash_left .elgg-module .elgg-head{
	background:transparent;
}

.is-groups-element li.elgg-menu-item-membership, .is-groups-element li.elgg-menu-item-feature{
  display: none;
}

}
#river_avatar {
	text-align:center;
	margin-bottom:20px;
	padding: 0;
	padding:3px;
	text-align:center;
}
#river_avatar img{
	width: 200px;
}
#lastloggedin {
	color:#777777;
    font-size:12px;
    font-weight:bold;
    text-align:center;
}
#dashboard_navigation {
	margin-top:20px;
    width:100%;
    font-size:12px;
}
#dashboard_navigation ul {
	margin:0 0 15px 0;
    padding:0;
}
#dashboard_navigation ul li {
	list-style:none;
	float:none;
	padding:5px;
    font-weight:bold;
    border-bottom:1px solid #cccccc;
    text-decoration:none;
    display:block;
}

#dashboard_navigation ul li a {
    font-weight:bold;
    text-decoration:none;
}

.news-show-more{
	width:100%;
    padding:15px 0;
    text-align:center;
    background:#EFEFEF;
    color:#999;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    cursor:pointer;
}
.elgg-list-river{
	border-top:0;
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
	padding-bottom: 20px;
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
    z-index:2;
}
.minds_online_status_tiny{
    margin: -9px 0 0;
    right:0;
    position: absolute;
     z-index:2;
}
.minds_online_status_medium{
    margin: -16px 0 0;
    right:0;
    position: absolute;
     z-index:2;
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
}
 */