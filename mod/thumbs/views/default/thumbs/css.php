<?php
/**
 * Thumbs CSS
 */
?>

.thumbs-button{
	text-decoration:none !important;
	vertical-align:middle;
}
.thumbs-button .count{
	font-size:12px;
	color:#888;
	vertical-align:middle;
}
.thumbs-button:hover .count{
	color:#FFF;
}

.thumbs-list{
	width:260px;
	margin-top:0;
}
.thumbs-list h3{ 
	font-size: 14px;
	font-weight: 600;
	padding: 6px 12px;
	/* text-align: center; */
	color: #888;
	/* background: #666; */
}
.thumbs-list h3 span{
	padding-right:6px;
} 
.thumbs-list li{
	width:252px !important;
} 
.thumbs-list li .blog-rich-image-holder{
	height:120px;
}


/* ***************************************
	Likes
*************************************** */
.elgg-likes {
	width: 345px;
	position: absolute;
}

.elgg-menu.elgg-menu-thumbs li {
	margin-left: 5px;
}

.elgg-menu .elgg-menu-item-likes-count {
	margin-left: 3px;
}
.elgg-menu-item-thumbs-count{
	padding-top:2px;
	font-size:14px;
}

.thumbs-button-down.selected, .thumbs-button-up.selected{
	color:#4690D6;
}
.thumbs-button-down:hover, .thumbs-button-up:hover{
	color:#999;
}
