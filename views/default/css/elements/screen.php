<?php
/**
 * Multiple screen layout
 */
if(0){ ?><style><?php } ?>

@media all 
and (min-width : 0px)
and (max-width : 720px) {

	.hero, .elgg-page-default{
		min-width:320px;
	}
	
	.hero > .topbar{
		min-width:320px;
	}
	
	.hero > .topbar > .inner .global-menu{
		margin-top:14px;
	}
	
	.hero > .topbar .logo > img{
		width: 100%;
		height: auto;
		max-width: 100px;
	}
	.hero > .topbar .right .elgg-button{
		margin:2px;
		width:52px;
	}
	
	
	
	.minds-body-header > .inner > .elgg-head{
		min-width:0;
	}

	.hero > .body > .inner, .elgg-page-default .elgg-page-body > .elgg-inner{
		width:100%;
	}

	.hero > .topbar .logo {
		height:40px;
	}

	.hero > .topbar > .inner .menu-toggle {
		margin-top:8px;
	}

	.hero > .topbar .search {
		display:none;
	}

	.hero > .topbar .owner_block {
		margin-top:0;
	}

	.hero > .topbar .actions {
		margin: 8px 6px;
	}

	.hero > .topbar .owner_block > a > .text{
		display:none;
	}

	.homepage{
		padding-top:64px;
	}

	.heading-main, .elgg-heading-main {
		font-size:24px;
	}

	.carousel, .carousel .item{
		height:180px;
	}
	.carousel-inner > .item > .carousel-caption{
		top:32px;
		left:0px;
	}
	
	.carousel-inner > .item > .carousel-caption h3{
		font-size:24px;
		line-height:24px;
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
