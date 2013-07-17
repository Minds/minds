/**
 * Bootcamp renamed orientation
 */
.orientation{}
.orientation h2{
	
}
.orientation .progress{
	float:right;
	text-align:right;
}
.orientation .progress h3{
	font-size:65px;
	font-weight:bold;
	color:#4690D6;
}
.orientation .progress p{
	margin-top:30px;
}


.orientation .step{
	display:block;
	position:left;
	clear:both;
	margin:25px 0;
	padding:25px 0;
	border-bottom:1px solid #EEE;
}
.orientation a .step{
	color:#333;
}
.orientation a:hover .step{
	text-decoration:none;
	background:#EEE;
}

.orientation .step > .number{
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
}
.orientation .step.completed > .number{
	color:#2C6700;
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


