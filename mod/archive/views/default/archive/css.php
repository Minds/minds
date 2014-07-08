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
.archive-player{
	width:100%;
}
.archive-plays{
	float: left;
	padding: 8px 24px;
	color: #333;
	font-weight: bold;
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
.uiVideoInline.archive.entity{
	width:110%;
	padding-bottom:60%;
	margin-left:-10px;
}
.uiVideoInline.archive.entity span{
	height:90%;
	width:95%;
}
.uiVideoInline.archive{
	width:100%;
	height:0;
	display:block;
	position:relative;
	overflow:hidden;
	padding-bottom: 56.25%;
}
/* If anyone has a better way of getting this centred, please left me know!! /MH */
.uiVideoInline.archive span {
	position:absolute;
	display:inline-block;
	cursor:pointer;
	margin:auto;
	height:100%;
	width:100%;
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/embed_extender/graphics/play_button.png) no-repeat center center;
	z-index:2;
}
.uiVideoInline.archive img{
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
