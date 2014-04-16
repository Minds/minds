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

.blog-sidebar li{
	width:125px;
	height:75px;
}

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

/* Blog Plugin */

/* force tinymce input height for a more useful editing / blog creation area */
form#blog-post-edit #description_parent #description_ifr {
	height:400px !important;
}
