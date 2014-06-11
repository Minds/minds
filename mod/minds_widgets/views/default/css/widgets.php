<?php
/**
 * CSS Extensions for Minds Theme
 */
// echo elgg_view('css/elgg'); exit;
?>
@CHARSET "UTF-8";

@font-face {
    font-family: 'entypo';
    src: url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.eot?') format('eot'),
         url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.woff') format('woff'),
         url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.ttf') format('truetype'),
         url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.svg') format('svg');
    font-weight: normal;
    font-style: normal;
}
@font-face {
  font-family: 'fontello';
  src: url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.eot?17546205');
  src: url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.eot?17546205#iefix') format('embedded-opentype'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.woff?17546205') format('woff'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.ttf?17546205') format('truetype'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.svg?17546205#fontello') format('svg');
  font-weight: normal;
  font-style: normal;
}
.entypo{
	font-family:'fontello', 'Ubuntu', Tahoma, sans-serif;
	font-size:17px;
	font-weight:normal;
	text-decoration:none;
}
.elgg-heading-site, .elgg-heading-site:hover {
	font-size: 2em;
	line-height: 1.4em;
	color: white;
	font-style: italic;
	font-family: Georgia, times, serif;
	text-shadow: 1px 2px 4px #333333;
	text-decoration: none;
}
.owner_block .text h3{
	font-size:18px;
}

.minds-post{
	    border: 0 none;
    font-family: inherit;
    font-size: 100%;
    font-style: inherit;
    font-weight: inherit;
    margin: 0;
    outline: 0 none;
    padding: 0;
    vertical-align: baseline;
}
/**
 * Lists
 */
.minds-post .elgg-list{
	list-style:none;
	margin:0;
	padding:0;
}
.minds-post .elgg-list > li{
	list-style:none;
	padding:0;
	margin:0;
}

.minds-post .elgg-list > li {
    background: none repeat scroll 0 0 #F8F8F8;
    border: 1px solid #DDDDDD;
    box-shadow: 0 0 1px #DDDDDD;
    display: block;
    float: left;
    height: auto;
    padding: 10px 10px 0 11px;
    position: relative;
    width: 300px;
}

.minds-post .elgg-image-block{

}

.minds-post .elgg-image-block .elgg-image {
    float: left;
    margin-right: 10px;
}
.minds-post .elgg-avatar {
	position: relative;
	display: inline-block;
}
.minds-post .elgg-avatar > .elgg-icon-hover-menu {
	display: none;
	position: absolute;
	right: 0;
	bottom: 0;
	margin: 0;
	cursor: pointer;
}
.minds-post .elgg-menu-hover {
	display: none;
	position: absolute;
	z-index: 10000;
	overflow: hidden;
	min-width: 165px;
	max-width: 250px;
	border: solid 1px;
	border-color: #E5E5E5 #999 #999 #E5E5E5;
	background-color: #FFF;
	-webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	-moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
}
.minds-post .elgg-body, .elgg-col-last {
    display: block;
    overflow: hidden;
    width: auto;
    word-wrap: break-word;
}

.minds-post .minds-river-attachments, .minds-post .minds-river-message, .minds-post .minds-river-content {
    line-height: 1.5em;
    padding: 0;
}

<?php

