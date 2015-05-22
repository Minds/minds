<?php
/**
 * Blog CSS
 *
 * @package Blog
*/
?>

@media screen and (min-width: 1300px) {

	#yt_video{
		height: 720px;
	}

}

@media screen and (max-width:800px){

	iframe{
		width:100% !important;
	}

}

/*.blog-sidebar li{
	width:125px;
	height:75px;
}*/

.scrapers .elgg-list{
	width:auto;
}

.scrapers .elgg-item .elgg-menu{
	display:block;
}

.scrapers .elgg-item{
	width:auto;
	height:auto;
	float:none;
	margin:10px 0;
}

.blog-post img[style*="left"]{
    padding:8px 8px 8px 0;
}
.blog-post img[style*="right"]{
    padding:8px 0px 8px 8px;
}

.blog-post-edit img{
    padding:8px;
}

/* Blog Plugin */

/* force tinymce input height for a more useful editing / blog creation area */
form#blog-post-edit #description_parent #description_ifr {
	height:400px !important;
}
