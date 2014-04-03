/**
 * Bootcamp renamed orientation
 */
.orientation{}
.orientation h2{

}

.orientation .title{
	position: relative;
}
.orientation .blurb{
	visibility: hidden;
	font-size: 20px;
	padding: 100px 10px;
}

.orientation .progress{
 	//visibility: hidden;
 	float:right;
 	text-align:right;
 	font-size:18px;
 	postion: absolute;
 	padding-right: 10%;
 	margin: -6%;
}	
.orientation .progress h3{
	//visibility: hidden;
 	font-size:80px;
 	font-weight:bold;
  	color:#4690D6;
 	margin-top:16px;
}
.orientation .progress p{
	//visibility: hidden;
	margin-top:20px;
	font-weight:bold;
	padding-right: 10px;
	padding-top: 10px
}

.orientation .step{
	display: block;
	position:relative;
	margin:0px 0px;
	padding:14% 20% 0% 25%;
}
.orientation a .step{
	color:#333;
}
.orientation .entypo{
	font-size: 125px;
	text-align: center;
	text-decoration:none;
}
.orientation h3{
	padding-top:24px;
}

/**
 * Sidebar Specifc
 */
.orientation.sidebar{
	position:relative;
	width:100%;
	height:auto;
	clear:both;
	padding:0 8px;
}
.orientation.sidebar .step {
	padding:0;
}
.orientation.sidebar .entypo{
		font-size:48px;
}
.orientation.sidebar h3{
	color:#333;
}

.orientation a:hover, .orientation a:hover .step{
	text-decoration: none;
	color: #4690D6;
}

.orientation a:hover .elgg-item{
	text-decoration: none;
	color: #4690D6;
}

.orientation .inner{
	text-align: center;
	font-size: 18px;
	margin: 0px 0px;
	padding: 30px 0px 0px 0px;
	line-height: 100%;
}
.orientation .content{
 	visibility: hidden;
}

.orientation .step > .tick{
	position: absolute;
	right: 16px;
	top: 16px;
	color: green;
	font-size: 42px;
}

.orientation .step > .number{
	visibility: hidden;
	font-family: 'Ubuntu Light', 'Ubuntu', 'Ubuntu Beta', UbuntuBeta, Ubuntu, 'Bitstream Vera Sans', 'DejaVu Sans', Tahoma, sans-serif;
	font-size:72px;
	font-weight:bold;
	float:left;
	color:#999;
	padding:10px;
	display:inline-block;
	width:75px;
}
.orientation a:hover .step > .number{
	text-decoration:none;
}
.orientation .step.completed > .number{
	color:#2C6700;
}
.orientation .step.completed{
}

/**
 * Register pages
 */
.orientation-register-wrapper{
	width: 800px;
	margin: auto;
	height: auto;
	border: 1px solid #EEE;
	border-radius: 3px;
	padding: 16px;
	background: #FFF;
	position: relative;
	display:table;
}
.orientation-register-wrapper > .orientation-menu{
	display:table-cell;
	width: 100px;
	border-right: 1px solid #EEE;
	position: relative;
}
.orientation-register-wrapper > .orientation-menu > ul{
}
.orientation-register-wrapper > .orientation-menu > ul > li{
	text-align:right;
	padding: 8px 20px 8px 0;
}
.orientation-register-wrapper > .orientation-menu > ul > li a{
	display:table-cell;
	width:80%;
}
.orientation-register-wrapper > .orientation-menu > ul > li.active a{
	color:#333;
}
.orientation-register-wrapper > .orientation-menu > ul > li:after{
	font-family:"fontello";
	width:15px;
	font-size:14px;
	padding:0 0 0 8px;
	display:table-cell;
	vertical-align:middle;
}
.orientation-menu > ul > li.avatar:after{
	content: '\e806';
}
.orientation-menu > ul > li.channel:after{
	content: '\1f30e';
}
.orientation-menu > ul > li.group:after{
	content: '\e805';
}
.orientation-menu > ul > li.deck:after{
	content: '\e73a';
}
.orientation-menu > ul > li.import:after{
	content: '\e804';
}
.orientation-menu > ul > li.revenue:after{
	content: '\e73d';
}
.orientation-menu > ul > li.multisite:after{
	content: '\e776';
}
.orientation-menu > ul > li.subscribe:after{
	content: '\1f4f6';
}
.orientation-menu > ul > li.post:after{
	content: '\e718';
}
.orientation-menu > ul > li.complete:after{
	content: '\1f44d';
}
.orientation-register-wrapper > .orientation-menu > ul > li > a{
	font-weight:bold;
}
.orientation-register-wrapper > .orientation-content{
	display:table-cell;
	width: 680px;
	height: 100%;
	position: relative;
	padding: 16px;
}
.orientation-register-wrapper > .orientation-content .orientation-action-buttons-wrapper{
	float:right;
	margin:16px 0 0;
}

.orientation-register-wrapper > .orientation-content .blurb{
	padding: 16px 8px;
	font-style: italic;
}
.orientation-register-wrapper #tiers{
	width:680px;
}
.disabled{
	background:#EEE;
	border:1px solid #DDD;
	color:#333;
	
	cursor: not-allowed;
	/*pointer-events: none;*/
}

.orientation-channel input{
	width:100%;
	margin-right:12px;
}
.orientation-channel select{
	-webkit-appearance: none;
	padding: 8px;
	border: 1px solid #DDD;
	background: #FAFAFA;
	margin-right:12px;
}

.orientation-content #user-avatar-cropper{
	width:400px;
}
.orientation-content .elgg-foot .elgg-button-submit, .orientation-content .deck-river-accounts .elgg-button-submits{
	display:none;
}

.orientation-table{
	display:table;
	width:100%;
}
.orientation-table-row{
	display:table-row;
}
.orientation-table-cell{
	display:table-cell;
	padding:8px;
}
.orientation-table-cell.label{
	width: 96px;
	font-weight: bold;
}

.orientation-table-cell span.entypo{
	font-size: 29px;
	vertical-align: middle;
	padding-right:4px;
}

.orientation-content .elgg-item{
	width: 310px;
margin: 8px !important;
padding: 0;
background: #FFF;
border: 0;
box-shadow: 0 0 0;
}

.orientation-content .elgg-item .elgg-avatar-medium > a > img{
	width:75px;
	height:75px;
}


.orientation-subscribe-list{
	height:400px !important;
	overflow:scroll;
}
