<?php
	/**
	 * Elgg CSS page
	 * 
	 * @package Elgg Membership
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgg.in/
	 */ 
?>
.margin {
	background-color: #CCCCCC; color: black;
}
td.datacelltwo {
	background-color: #9999CC; color: black;
}
#new_category {
	padding:15px;	
}
#member_paypal_details {
	border:1px solid #D1D2CD;
	-moz-border-radius-bottomleft:8px;
	-moz-border-radius-bottomright:8px;
	-moz-border-radius-topleft:8px;
	-moz-border-radius-topright:8px;
	margin:0 10px 10px;
	padding:10px;	
}
#trial_details {
	border:1px solid #D1D2CD;
	-moz-border-radius-bottomleft:8px;
	-moz-border-radius-bottomright:8px;
	-moz-border-radius-topleft:8px;
	-moz-border-radius-topright:8px;
	margin:0 10px 10px;
	padding:10px;	
}
#recurring_details {
	border:1px solid #D1D2CD;
	-moz-border-radius-bottomleft:8px;
	-moz-border-radius-bottomright:8px;
	-moz-border-radius-topleft:8px;
	-moz-border-radius-topright:8px;
	margin:0 10px 10px;
	padding:10px;	
}
#subscr_details {
	border:1px solid #D1D2CD;
	-moz-border-radius-bottomleft:8px;
	-moz-border-radius-bottomright:8px;
	-moz-border-radius-topleft:8px;
	-moz-border-radius-topright:8px;
	margin:0 10px 10px;
	padding:10px;	
}
.duration_text {	
	width:40px;
}
.duration_div {
	display:inline-block;
	padding-bottom:10px;
}
input[type="radio"]{
	border:none;
}
.small_textbox {
	width:40px;
}
#trial_details div{
	margin:0px;
	padding-top:6px;
}
.block_class {
	display:inline-block;
}
.left_class {
	float:left;
	width:250px;
}
.clear {
	clear:both;
}



/* Addtional css for Authorize.net */

.member_authorizenet_back {
	background:#EEE;
	border:1px solid #4690D6;
	margin-bottom:10px;
	margin-top:10px;
	padding:10px;
        overflow: hidden;
}
.member_authorizenet_back p strong {
        display: inline-block;
        margin-top: 6px;
        text-align: right;
        width: 270px;
}
.member_authorizenet_head {
	background:#CCC;
	border:1px solid #9DD3D8;
	margin-bottom:10px;
	margin-top:10px;
	padding:5px;
        text-align: center;
}
.input_auth {
        padding: 3px;
        width: 160px;
}

.permission .sub_permission {
    padding: 5px 0 10px 15px;
}
.perm_hide {
    display:none;
}
    
/*-------------- Coupon ----------------*/
.mem_edit_coupon {
	color:#4E4F4F;
	font-size:11px;
	margin-top:5px;
	padding:4px;
	width:100%;
	border-spacing:2px;
}
.mem_edit_coupon .label {
	color:#4E4F4F;
	font-family:Tahoma;
	font-size:11px;
	padding:6px 10px 0;
	text-decoration:none;
	vertical-align:top;
	width:170px;
	font-weight:bold;
}
.mem_edit_coupon .input-text {
	-webkit-border-radius: 0; 
	-moz-border-radius: 0;
	padding:2px;
	width:200px;
}
.category_select_box {
	border:1px solid #CCCCCC;
	width:200px;
	height:140px;
	overflow-y:scroll;
	
}
.category_select_box ul{
	padding-left:5px;
	margin:5px 0 5px;
}
.mem_list_coupons table{
	width:100%;
	border: 1px solid #4690D6;
	border-collapse:collapse;
	font-size:11px;
}
.mem_list_coupons th {
	padding: 5px 8px;
	background-color: #4690D6;
	color:#EEEEEE;
	font-weight:bold;
}
.mem_list_coupons td {
	padding: 5px 8px;
}
a.mem_coupon_edit {
	background:transparent url(<?php echo $vars['url']; ?>mod/cubet_membership/graphics/tag-pencil.png) no-repeat scroll left 0;
	display:block;
	height:16px;
	width:16px;
	float:left;
	padding: 0 !important;
	margin: 0 5px !important;
	cursor: pointer;
}
a.mem_coupon_edit:hover {
	background:transparent url(<?php echo $vars['url']; ?>mod/cubet_membership/graphics/tag-pencil.png) no-repeat scroll left 0;
}
a.mem_coupon_delete{
	background:transparent url(<?php echo $vars['url']; ?>mod/cubet_membership/graphics/delete.png) no-repeat scroll left 0;
	display:block;
	height:16px;
	width:16px;
	float:left;
	padding: 0 !important;
	margin: 0 5px !important;
	cursor: pointer;
}
a.mem_coupon_delete:hover {
	background:transparent url(<?php echo $vars['url']; ?>mod/cubet_membership/graphics/delete.png) no-repeat scroll left 0;
}
.mem_coupon_back {
	background:#EAFDFF;
	border:1px solid #9DD3D8;
	margin-bottom:10px;
	margin-top:10px;
	padding:10px;
	width:300px;
	float:right;
}
.mem_cancel {
        width: 100%;
        border: 1px solid #CCCCCC;
        margin: 10px auto;
}
.mem_cancel_inner {
        padding: 10px;
}
#elgg_horizontal_tabbed_nav {
        border-bottom:2px solid #CCCCCC;
        display:table;
        margin-bottom:5px;
        width:100%;
}
#elgg_horizontal_tabbed_nav li {
        -moz-border-radius-bottomleft:0;
        -moz-border-radius-bottomright:0;
        -moz-border-radius-topleft:5px;
        -moz-border-radius-topright:5px;
        background:#EEEEEE none repeat scroll 0 0;
        border-color:#CCCCCC;
        border-style:solid solid none;
        border-width:2px 2px 0;
        float:left;
        margin:0 0 0 10px;
}
#elgg_horizontal_tabbed_nav  li  a {
        color:#999999;
        display:block;
        height:21px;
        padding:3px 10px 0;
        text-align:center;
        text-decoration:none;
}
#elgg_horizontal_tabbed_nav  li a:hover {
        background:#DEDEDE none repeat scroll 0 0;
        color:#4690D6;
}
#elgg_horizontal_tabbed_nav .selected {
        background:white none repeat scroll 0 0;
        border-color:#CCCCCC;
}
#elgg_horizontal_tabbed_nav .selected a{
        top:2px;
        background:white none repeat scroll 0 0;
        position:relative;
}
.list_coupon_membership {
    margin-bottom:6px;
}
.premium_membership_desc {
    max-width:350px;
}
.premium_membership_row {
    border-bottom:1px dotted #CCC;
}
#toggleText {
    margin-left:10px;
}
.input_payment_method label {
    font-weight:normal;
}
.mem_report {
    margin-top:15px;
    border: 1px solid #CCCCCC;
}
.mem_report td {
    padding: 2px;
}
.mem_report th {
    padding: 5px;
}

.mem_report .mem_disabled td {
    background: #f9e6e7;
}
.mem_report .mem_disabled td span.mem_content {
    text-decoration: line-through;
}
