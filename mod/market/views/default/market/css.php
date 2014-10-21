<?php
?>
.market-sidebar-section{
	background: #EEE;
	border: 1px solid #DDD;
}
.market-sidebar-section h3{
	padding: 8px 16px;
	font-size: 16px;
	background: #333;
	color: #FFF;
}

.minds-menu-market{
	
}
.minds-menu-market li{
	list-style:none;
}
.minds-menu-market li a, .market-menu-item{
	display:block;
	color:#333;
	background:#EEE;
	padding:12px 16px;
	border-bottom:1px solid #EEE;
	font-weight:bold;
}
.minds-menu-market li a:hover, .market-menu-item:hover{
	background:#DDD;
	text-decoration:none;
}
.minds-menu-market li a.active{
	background:#DDD;
}
.market-menu-item-add{
	background:#4690D6;
	color:#FFF;
}



/**
 * Full view 
 */
.minds-market-full{
	padding:0 16px;
}
.minds-market-full > ul{
	margin:32px 8px;
}
.minds-market-full h1{
	font-size:64px;
	line-height:86px;
}
.minds-market-description{
	margin-top:20px;
}

.minds-market-full .add-to-basket{
	float:left;
	margin-top:2px;
}
.market-owner-block{
	float:left;
	margin:0 12px;
}
.market-owner-block img{
	vertical-align:middle;
}
.market-owner-block a{
	font-weight:bold;
}
.minds-market-subbanner{
	display:block;
	width:auto;
	height:63px;
	vertical-align:middle;
	clear:both;
}

/**
 * Brief view
 */
.minds-market-item{
	
}
.minds-market-item h3{
	padding:8px;
	font-size:18px;
	width:auto;
}
.minds-market-item h3 .price{
	font-style:italic;
	color:#999;
}
.minds-market-thumbnail{
	width:110%;
	margin-left:-5%;
	display:block;
}
.minds-market-thumbnail img{
	width:100%
}


/**
 * Basket views
 */
.minds-market-basket{
	display:table;
	width:100%;
}
.minds-market-basket > .row{
	display:table-row;
}
.minds-market-basket > .row.labels .cell{
	font-weight:bold;
}
.minds-market-basket > .row > .cell{
	display:table-cell;
	padding:8px;
}
.minds-market-basket > .row > .cell.item{
	font-weight:bold;
}

.minds-market-button-checkout{
	background:#4690D6;
	color:#FFF;
	padding:16px;
	margin:8px 0;
	font-weight:bold;
	border-radius:3px;
	display:block;
	text-align:center;
}
