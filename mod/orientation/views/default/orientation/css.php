/**
 * Bootcamp renamed orientation
 */
.orientation{}
.orientation h2{

}

.orientation .title{
	font-size: 80px;
	position: relative;
}
.orientation .blurb{
	font-size: 20px;
	padding: 100px 10px;
}

.orientation .progress{
 	//visibility: hidden;
 	float:right;
 	text-align:right;
 	postion: absolute;
 	top: 100px;
}	
.orientation .progress h3{
 	font-size:80px;
 	font-weight:bold;
  	color:#4690D6;
 	margin-top:100px;
}
.orientation .progress p{
	margin-top:20px;
}
 

.orientation .elgg-list{
	
}
.orientation li{
	border: outset;
	text decoration: none;
}

.orientation .step{
	display: block;
	position:left;
	clear:left;
	margin:0px 0px;
	padding:70px;
}
.orientation a .step{
	color:#333;
}

.orientation a:hover .step{
	text-decoration: none;
	color: #4690D6;
}
.orientation a:hover .elgg-item{
	text-decoration: none;
	color: #4690D6;
}

.orientation .inner{
	text-align: center;
	font-size: 25px;
	padding: 20px 0px 0px 0px;
}
.orientation .content{
	visibility: hidden;
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
	color:#4690D6;
	text-decoration:none;
}
.orientation .step.completed > .number{
	color:#2C6700;
}
.orientation .entypo{
	font-size: 125px;
	text-align: left;
	text-decoration:none;
}

/**
 * Sidebar specifics
 */
.orientation.sidebar .step{
	padding:10px;
}
.orientation.sidebar .step > .number{
	height:45px;
	padding:0;
	width:100%;
	font-size:64px;
	text-align:center;
}
.orientation.sidebar .step .entypo{
	font-size:70px;
}


