<?php

	$graphics_folder = $vars["url"] . "mod/user_support/_graphics/";

?>
/* User support button */
#user-support-button {
	background: transparent url(<?php echo $graphics_folder; ?>button_bg.png) top right repeat-y;
	border-color: #B6B6B6;
	border-style: solid;
	border-width: 1px 1px 1px 0px;
	
	font-size: 16px;
    font-weight: bold;
    position: fixed;
    padding: 4px 2px 4px 4px;
    line-height: 18px;
    text-align: left;
    width: 18px;
    z-index: 10000;
}

#user-support-button a {
	color: #FFFFFF;
	text-decoration: none;
	display: block;
	width: 16px;
	padding-bottom: 20px;
	text-align: center;
}

#user-support-button a:hover {
	color: #000;
}

.user-support-button-help-center {
	background: transparent url(<?php echo $graphics_folder; ?>help_center/helpcenter16_gray.png) no-repeat scroll right bottom;
}

.user-support-button-help-center.elgg-state-active {
	background-image: url(<?php echo $graphics_folder; ?>help_center/helpcenter16.png)
}

/* Help Center */
.user-support-help-center-popup {
	width: 650px;
	margin: 0px;
}

#user_support_help_center_help {
	max-height: 250px;
	overflow-x: hidden;
}
