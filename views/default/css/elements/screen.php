<?php
/**
 * Multiple screen layout
 */
if(0){ ?><style><?php } ?>

@media all 
and (min-width : 320px)
and (max-width : 680px) {

	.hero, .elgg-page-default{
		min-width:320px;
	}
	
	.hero > .topbar{
		min-width:320px;
	}
	
	.hero > .topbar .right .elgg-button{
		margin:2px;
		width:52px;
	}
	
	.minds-body-header > .inner > .elgg-head{
		min-width:0;
	}
	.homepage{
		padding-top:64px;
	}
	.carousel, .carousel .item{
		height:180px;
	}
	.carousel-inner > .item > .carousel-caption{
		top:32px;
		left:0px;
	}
	.carousel-inner > .item > .carousel-caption > h3{
		font-size:36px;
	}
	.frontpage-signup, .front-page-buttons{
		display:none;
	}
	
	/**
	 * 	Hide search for now
	 */
	.search{
		display:none;
	}
	
	/**
	 * 	General fixed widths
	 */
	.elgg-form-login{
		width:70%;
	}
	
	/**
	 * 	Listings and tiles
	 */
	.elgg-list.mason > li{
		width:90%;
	}
	
	/**
	 * 	Pages
	 */
	.sidebar{
		display:none;
	}
}