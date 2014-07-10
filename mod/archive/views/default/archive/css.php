<?php
/**
 * Archive CSS
 */
?>
.archive-upload{
	width:750px;
	margin:auto;
}
.archive-upload > .column {
	float:left;
}
.archive-upload > .column > a >img{
	margin:25px 40px;
}
.archive-upload > .column > a > h3{
	width:100%;
	text-align:center;
}
.archive-wall-title{
	margin:0 10px;
}
.archive-footer{
	width: 100%;
	height: 26px;
	margin: 10px;
	display: block;
}
.archive-footer .license{
	float:left;
}
.player-container{
	width:100%;
	position:relative;
}
/* Position the button */
.vjs-res-button {
float: right;
line-height: 3em;
}

/* Don't show hover effects on title */
ul li.vjs-menu-title.vjs-res-menu-title:hover {
cursor: default;
background-color: transparent;
color: #CCC;

-moz-box-shadow: none;
-webkit-box-shadow: none;
box-shadow: none;
}

.player-container .vjs-default-skin .vjs-control-bar { 
	font-size: 125%; 
}
.player-container .vjs-default-skin .vjs-big-play-button{
	left:45%;
	top:45%;
	border-radius:0;
}
.archive-plays{
	float: left;
	padding: 8px 24px;
	color: #333;
	font-weight: bold;
}
.archive-note{
	background: #F3F3F3;
	padding: 16px 32px;
	font-weight: bolder;
}
.archive-description{
	background: #F8F8F8;
	padding: 10px;
	margin: 0 0 10px;
}

.archive-button-right{
	float:right;
	margin:0 2px;
}
.archive-video-wrapper{
	position:relative;
	padding-bottom:55%;
	height:0;
}
.archive-video-wrapper .archive-large-widget{
	clear:both;
	width:100%;
	margin: 0;
	height:100%;
	position:absolute;
}
/*.archive.archive-video{
	width:110%;
	height:0;
	padding-bottom:60%;
	margin-left:-10px;
	position:relative;
	overflow:hidden;
}*/
.archive.archive-video span{
	height:90%;
	width:95%;
}
.archive-video{
	width:110%;
	margin-left:-10px;
	height:0;
	display:block;
	position:relative;
	overflow:hidden;
	padding-bottom: 56.25%;
}
/* If anyone has a better way of getting this centred, please left me know!! /MH */
.archive.archive-video span {
	position:absolute;
	display:inline-block;
	cursor:pointer;
	margin:auto;
	height:100%;
	width:100%;
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/embed_extender/graphics/play_button.png) no-repeat center center;
	z-index:2;
}
.archive-video img{
	position:absolute;
	width:100%;
}
.uiVideoInline.archive object, .uiVideoInline.archive embed{
	position:absolute;
	width:100%;
	height:100%;
	top:0;
	left:0;
	margin:0;
}
/**
 * Right sidebar modules
 */
.elgg-module.sidebar .kalturavideoitem{
	/*padding:10px;*/
}
.elgg-module.sidebar .kalturavideoitem h3 a{
	font-size:13px;
	color:#4690D6;
}
.elgg-module.sidebar .kalturavideoitem p{
	font-size:11px;
	display:block;
}

.elgg-form-archive-save{
	width: 90%;
	background: #F8F8F8;
	padding: 16px;
	margin: auto;
}

.minds-river-attachments .elgg-photo.large{
	border:0;
	padding:0;
	width:100%;
}
