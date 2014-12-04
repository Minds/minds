/**
 * Directory lists
 */
.nodes-directory-list{

}
.nodes-directory-list > li{
	width:300px;
	background:#FFF;
	padding-top:16px;
}
.nodes-directory-list > li .node_logo{
	width:100%;
}
.nodes-directory-list > li h3{
	font-size: 16px;
	text-align: center;
	padding: 16px;
}

/**
 * Other 
 */
div.node-signup {
    margin-top: 30px;

}

div.node-signup div.email,
div.node-signup div.full-domain,
div.node-signup div.node {
    margin-top: 25px;
    margin-bottom: 25px;
}

div.node-signup div.blurb-or {
    font-weight: bold;
    text-align: center;
    font-size: 1.2em;
}

div.node-signup div.node input {
    width: 85%;
}

.minds-tiers-buttons{
	margin:0 5% 12px; 
}


.loading-bar{
	margin:32px auto;
	width:600px;
	height:32px;
	display:block;
	border-radius:3px;
	background:#eee;
}
.loading-bar .progress{
	width:1%;
	height:100%;
	display:block;
	background:#4690D6;
}

/** Tier selection */
#tiers{
	border:1px solid #eee;
	background:#FFF;
	width: 990px;
	display: table;
	margin: auto;
}
#tiers.admin{
	width:100%;
}
#tiers .row{
	display: table-row;
}
#tiers .row.thead, #tiers .row.tfoot{
	display: table-header-group;
	background: #f9f9f9;
}
#tiers .row.thead .cell{
	font-weight:bold;
	color:#4690D6;
}
#tiers .cell{
	float: none;
	width: 25%;
	display: table-cell;
	vertical-align: middle;
	position: relative;
	padding: 15px 20px;
	font-size: 15px;
	color: #555;
	text-align: center;
	border-bottom: 1px solid #eeeeee;
}
#tiers .cell.feature{
	text-align: left;
	color:#4690D6;
}

.elgg-form-select-tier,.tiers, .elgg-form-node {
    width: 100%!important;
    max-width: 100% !important;
}
.elgg-item.tier {
	background:#FFF;
	height:auto;
	padding:32px 11px;
}

.elgg-item.tier .elgg-button{
	display:block;
	min-width:0;
	width:100px;
	padding:8px;
	text-align:center;
}

.elgg-item.tier .tier-buttons {
    margin: auto;
    width: 260px;
}


.elgg-item.tier .tier-buttons .elgg-button {
    margin: 5px;
    float: left;
}

.elgg-item.tier h2{
	font-size:42px;
	text-align:center;
}
.elgg-item.tier .tier-description{
	margin:24px 0;
	padding:0 24px;
}
.elgg-item.tier .tier-description p{
	font-size:14px;
}


.elgg-item.tier .pay.buynow {
	font-size:42px;
	display: block;
	padding: 5px;
	background: transparent;
	color: #4690D6;
	font-weight: bold;
}
div.register-popup {
    width:700px;
    margin-left: 30px;
    margin-right: auto;
}

/**
 * Domain select
 */
.nodes-table{
	border:1px solid #eee;
	background:#FFF;
	width: 990px;
	display: table;
	margin: 16px auto;
}
.nodes-table.admin{
	width:100%;
}
.nodes-table .row{
	display: table-row;
}
.nodes-table .row.thead, #tiers .row.tfoot{
	display: table-header-group;
	background: #f9f9f9;
}
.nodes-table .row.thead .cell{
	font-weight:bold;
	color:#4690D6;
}
.nodes-table .cell{
	float: none;
	width: 25%;
	display: table-cell;
	vertical-align: middle;
	position: relative;
	padding: 15px 20px;
	font-size: 15px;
	color: #555;
	text-align: center;
	border-bottom: 1px solid #eeeeee;
}
.nodes-table .cell.feature{
	text-align: left;
	color:#4690D6;
}

.nodes-table.hide .row.thead .cell{
	font-weight:bold;
	color:#CCC;
}

.domain input{
	padding:16px;
	margin:16px;
	width:600px;
}
.domain .paid{
	display:none;
	padding:16px;
}
.domain .free{
	width:600px;
	text-align:left;
}
.domain .free input{
	margin:0;
	width:200px;
}

.domain .availability{
	width:100%;
	text-align:left;
}


.account .cell.custom{
	width:100px;
	text-align:right;
}
.account .cell{
	width:800px;
	text-align:left;
}
.account .cell input{
	width:800px;
	padding:16px;
}
.account.hide .elgg-button{
	border:1px solid #DDD;
	background:#EEE;
}
.account .elgg-button{
	min-width:80px;
}
.payment.hide{

}
.payment .row{

}
.payment input{
	padding:16px;
}
.payment input[name=number]{
	
}
.payment input[name=sec]{
	width:60px;
}
.payment input[name=month]{
	width:160px;
}
.payment .type a{
	padding-right:16px;
}
.payment .type a:hover{
		cursor:pointer;
		text-decoration:none;
	}
.payment select {
   background-color:#FFF;
   width: auto;
   padding: 5px;
   font-size: 13px;
   border: 1px solid #ccc;
   height: 34px;
}
.payment .helper{
	clear: both;
	width: 100%;
	float: left;
	font-size: 11px;
	color: #888;
	padding: 16px 0;
}

.response .reason, .response .reason2{
	text-align:left;
}

.launch .response{
	display:none;
}


.node-button{
	min-width:0;
}

.elgg-form-nodes-upgrade .card-input{
	width:990px;
	margin:auto;
}
