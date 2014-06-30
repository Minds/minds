<?php
$base_url = elgg_get_site_url();
$graphics_url = $base_url . 'mod/hypeAlive/graphics/';
?>
/** Minds Comments **/
.minds-comments{
	margin:0;
	background: #EFEFEF;
	border: 1px solid #CCC;
}
.minds-comments .minds-comment{
	background: #f9f9f9;
	padding: 8px;
	margin: 8px;
	border: 1px solid #DDD;
	border-radius: 4px;
}	
.minds-comments .minds-comments-timestamp{
	clear:both;
	font-size:11px;
	font-style:italic;
}
.minds-comments-bar .show-more{
	margin: -16px 0;
	width: 10%;
	height: auto;
	position: absolute;
	text-align: center;
	background: #333;
	padding: 2px 8px;
	border-radius: 3px;
	color: #FFF;
	font-size: 11px;
	right: 45%;
	left: 45%;
	cursor: pointer;
}
.elgg-menu-comments-default{
	float:right;
}
.minds-comments-form{
	border:1px solid #CCC;
	background:#CCC;
	margin:16px 0 0;
	margin-top:0;
	padding:8px;
}
.minds-comments-form fieldset > div{
	margin-bottom:0;
}
/** Legacy CSS **/
.elgg-menu-comments {
font-size:11px;
}

.elgg-menu-comments li.elgg-menu-item-comment {
padding:0 5px;
}

.elgg-menu-comments li a {
}

li.elgg-menu-item-showallcomments {
font-weight:bold;
}
li.elgg-menu-item-time {
font-size:10px;
font-style:italic;
font-color:#666;
}

.hj-comments-bubble,
.hj-comments-list .elgg-list > li
{
background:#f4f4f4;
border-bottom:1px solid #ddd;
padding:2px 5px;
margin-bottom:2px;
}
.hj-comments-bubble {
font-size:10px;
}
.hj-annotations-list .hj-annotations-list .hj-comments-bubble {
border-bottom:0;
}

.hj-annotations-list .hj-annotations-list .hj-comments-list .elgg-list > li {
border-bottom:0;
}

.hj-comments-list .elgg-list {
border:0px;
margin:0;
}

.hj-annotations-bar form {
background:0;
border:#ddd;
height:auto;
overflow:hidden;
padding:0;
}

.hj-comments-input, .comments-input {
font-size:12px;
height:25px;
overflow: hidden;
padding:4px;
}

.hj-comments-bubble-pointer {
background:transparent url(<?php echo $graphics_url . 'pointer.png' ?>) no-repeat 8px 0;
height:8px;
display:block;
}

.elgg-menu-comments li a.hidden {
display:none;
}


#comments-signup > form{
	float:left;
	width:360px;
	margin-left:12px;
}
#comments-signup > form input{
	padding:12px;
}

