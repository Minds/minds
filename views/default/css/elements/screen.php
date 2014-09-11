<?php
/**
 * Multiple screen layout
 */
if(0){ ?><style><?php } ?>

@media all 
and (min-width : 0px)
and (max-width : 1260px) {
		
	.minds-topbar-icon{
		display:none;
	}
	
}

@media all 
and (min-width : 0px)
and (max-width : 720px) {


	.elgg-list > li:hover .excerpt, .elgg-list > li:hover .elgg-menu{
		display:none;
	}

	.hero, .elgg-page-default{
		min-width:320px;
	}
	
	.hero > .topbar{
		min-width:320px;
	}
	
	.hero > .topbar > .inner .global-menu{
		margin-top:14px;
	}
	
	.hero > .topbar .logo img{
		width: 100%;
		height: auto;
		max-width: 100px;
	}
	.hero > .topbar .right .elgg-button{
		margin: 1px;
		/* width: 52px; */
		font-size: 60%;
		padding: 2px 5px;
		width: auto;
		font-weight: bold;
	}
	
	.hero > .body, .elgg-page-body {	
		margin-top:60px;
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

	.hero > .topbar .owner_block > a > img {
		padding:0;
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
	
	.carousel-fat .minds-body-header{
		height:400px;
	}
	.carousel-fat .carousel{
		height:400px;
	}
	.carousel-fat .carousel .item{
		height:400px;
	}
	.carousel-fat .carousel .item > .carousel-caption{
		top:  100px;
	}
	.carousel-fat .carousel-inner > .item > img {
		position: absolute;
		top: 0;
		left: 0;
		min-width:100%;
		height: 100%;
	}
	
	.carousel-fat .elgg-layout{
		min-height:0;
	}
	.carousel-fat .minds-body-header{
		margin:0;
		padding:0;
	}
	
	.donations-box{
		position:absolute;
		margin-left:0;
		top:200px;
		left:0;
		width:100%;
	}
	.donations-button{
		font-size:11px;
		float:left;
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
