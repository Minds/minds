<?php if (0): ?><style><?php endif; ?>
body {
	width: 100%;
}
.fixed-deck {
	position: fixed;
}
.fixed-deck .elgg-main {
	padding: 10px 0 0;
}
.response-loader {
	background: url(<?php echo elgg_get_site_url() . 'mod/elgg-deck_river/graphics/ajax-loader.gif'; ?>) no-repeat scroll 0 0 transparent;
	height: 16px;
	left: -20px;
	position: relative;
	width: 16px;
	margin-right: -16px;
}

/*.elgg-river > li{
	width:auto;
	height:auto;
	float:none;
}*/
/* the wire-search textarea */
.elgg-form-deck-river-wire-input {
	position: relative;
}
#thewire-header, #search-input {
	background: white;
	border-radius: 6px;
	height: 33px;
	-webkit-box-shadow: inset 0 2px 2px 0 #1F2E3D;
	-moz-box-shadow: inset 0 2px 2px 0 #1F2E3D;
	box-shadow: inset 0 2px 2px 0 #1F2E3D;
}
#thewire-header #thewire-textarea-border {
	display: none;
}
#thewire-textarea, .elgg-search .search-input {
	background: transparent !important;
	resize: none;
	height: 32px !important;
	padding: 10px 2px 0 12px !important;
	margin: 0;
	color: #666;
	font: 130% Arial,Helvetica,sans-serif;
	border: none !important;
	width: 570px;
	line-height: 1em;
	overflow: hidden;
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
}
#thewire-textarea-border {
	background: #4690D6;
	border-radius: 0 0 8px 8px;
	-webkit-box-shadow: 3px 3px 3px 0 rgba(0, 0, 0, 0.3);
	-moz-box-shadow: 3px 3px 3px 0 rgba(0, 0, 0, 0.3);
	box-shadow: 3px 3px 3px 0 rgba(0, 0, 0, 0.3);
	padding-bottom: 3px;
	left: -7px;
	position: absolute;
	top: 35px;
	width: 671px;
	z-index: -1;
}
.reverse-border {
	position: absolute;
	right: 47px;
	top: 0;
	z-index: 7003;
	overflow: hidden;
	width: 40px;
	text-align: right;
	font-weight: bold;
	color: #333333;
}
.reverse-border span {
	color: #00CC00;
	background: white;
	border-radius: 0 6px 6px 0;
	display: block;
	font-size: 1.2em;
	margin-left: -12px;
	padding: 9px 6px 6px 0;
	height: 18px;
	-webkit-box-shadow: inset 0 2px 2px 0 #1F2E3D;
	-moz-box-shadow: inset 0 2px 2px 0 #1F2E3D;
	box-shadow: inset 0 2px 2px 0 #1F2E3D;
}
#thewire-header > .thewire-button, .elgg-search .search-button {
	position: absolute;
	top: 0;
	right: 0;
	border-radius: 6px 6px 6px 6px;
	height: 33px;
	overflow: hidden;
	background: #FFE6E6;
	-webkit-box-shadow: inset 0 2px 2px 0 #1F2E3D;
	-moz-box-shadow: inset 0 2px 2px 0 #1F2E3D;
	box-shadow: inset 0 2px 2px 0 #1F2E3D;
}
#thewire-header > .thewire-button:before, .elgg-search .search-button:before {
	content: "A";
	color: #B40000;
	font-size: 54px;
	position: relative;
	right: 5px;
	top: 9px;
	position: absolute;
}
#thewire-header > .thewire-button:hover {
	background: #FF0000;
}
#thewire-header > .thewire-button:hover:before {
	color: white;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.3);
}
#thewire-submit-button, .elgg-search .elgg-button {
	color: transparent;
	height: 33px;
	text-indent: -9999px;
	background: transparent;
	border: none;
	width: 60px;
}
#thewire-header #submit-loader {
	background-color: white;
	background-position: 50% center;
	padding: 2px 8px;
	left: inherit;
	top: 8px;
	right: 65px;
	z-index: 7004;
	position: absolute;
}
#thewire-header.extended #submit-loader {
	background-color: transparent;
	top: inherit;
	bottom: -26px;
	right: 115px;
}
#thewire-header.extended #thewire-textarea {
	height: 129px !important;
}
#thewire-header.extended {
	border-radius: 6px 6px 0 0;
}
#thewire-header.extended #thewire-textarea-border {
	display: block;
}
#thewire-header.extended #thewire-textarea {
	width: 100%;
	overflow-y: auto;
}
#thewire-header.extended #thewire-characters-remaining {
	bottom: -31px;
	left: 3px;
	top: auto;
}
#thewire-header.extended #thewire-characters-remaining span {
	background: transparent;
	box-shadow: none;
	margin-left: 5px;
	text-align: left;
}
#counters-alert {
	bottom: 50px;
	position: absolute;
}
#thewire-header.extended #counters-alert {
	bottom: -24px;
	left: 45px;
	font-size: 2.5em;
}
#counters-alert li {
	display: inline-block;
	width: 12px;
}
#counters-alert span:before {
	padding: 0;
	color: red;
}
#thewire-header.extended #thewire-textarea-bottom {
	background: #F4F4F4;
	border-radius: 0 0 6px 6px;
	bottom: -31px;
	height: 40px;
	position: absolute;
	width: 100%;
	z-index: -1;
	-webkit-box-shadow: inset 0 2px 2px 0 #1F2E3D;
	-moz-box-shadow: inset 0 2px 2px 0 #1F2E3D;
	box-shadow: inset 0 2px 2px 0 #1F2E3D;
}
#thewire-header.extended .thewire-button {
	background: white;
	border: 1px solid #999999;
	box-shadow: none;
	height: 21px;
	margin: 4px;
	padding: 0 0 1px 24px;
	top: auto;
	bottom: -32px;
	width: 72px;
	-webkit-box-shadow: inset 0px -10px 10px 2px rgba(0, 0, 0, 0.1);
	-moz-box-shadow: inset 0px -10px 10px 2px rgba(0, 0, 0, 0.1);
	box-shadow: inset 0px -10px 10px 2px rgba(0, 0, 0, 0.1);
}
#thewire-header.extended .thewire-buttons {
	display: block;
	position: absolute;
	right: 130px;
	bottom: -28px;
}
#pin-thewire.pinned:before {
	color: #00CC00;
}
#thewire-header.extended > .thewire-button:before {
	font-size: 40px;
	left: 2px;
	right: auto;
	top: 3px;
}
#thewire-header.extended #thewire-submit-button {
	color: #333333;
	float: left;
	height: 22px;
	padding-left: 30px;
	position: absolute;
	right: 0;
	text-indent: 0;
	width: 97px;
}
#thewire-header.extended > .thewire-button:hover {
	background: #FF3019;
	border: 1px solid #CF0404;
	color: white;
}
#thewire-header.extended > .thewire-button:hover #thewire-submit-button {
	color: white;
}
#thewire-header.extended .options {
	display: block;
}
#thewire-header .url-shortener {
	border-top: 1px solid #DEDEDE;
	margin: 0 1px;
	padding: 4px;
	position: relative;
}
#thewire-header .url-shortener .elgg-input-text {
	font-size: 100%;
	padding-right: 70px;
	width: 96%;
	top: -1px;
	position: relative;
}
#thewire-header .url-shortener .elgg-button {
	position: absolute;
	top: 3px;
}
#thewire-header .url-shortener .elgg-button-submit {
	right: 3px;
}
#thewire-header .url-shortener .elgg-button-action {
	right: 71px;
}
#thewire-header .url-shortener .elgg-icon {
	position: absolute;
	top: 7px;
	right: 142px;
}
#thewire-header .responseTo {
	background: #FFC;
	color: #666;
	margin: 0 2px;
	padding: 2px 5px;
	height: 18px;
	overflow: hidden;
}
#thewire-header .responseTo span {
	color: #999;
	font-size: 85%;
	font-style: italic;
}
#thewire-header .responseTo:hover {
	background: #FDD;
	color: red;
	cursor: pointer;
}
#linkbox {
	border-top: 1px solid #DEDEDE;
	margin: 0 1px;
}
#linkbox .elgg-menu {
	margin-right: -5px;
}
#linkbox .image-wrapper {
	height: 80px;
	line-height: 80px;
	overflow: hidden;
	width: 80px;
	background: #DEDEDE;
	float: left;
	margin: 6px;
	position: relative;
}
#linkbox .elgg-image {
	margin: 0 5px 0 -6px;
	background: white;
	position: absolute;
	max-height: 460px;
	overflow: auto;
}
#linkbox .elgg-image:hover {
	z-index: 7004;
	box-shadow: 0 0 4px #CCC;
	cursor: pointer;
}
#linkbox .elgg-image + .elgg-body {
	margin-left: 90px;
	min-height: 87px;
}
#linkbox li.image-wrapper {
	display: none;
}
#linkbox div.image-wrapper:before {
	content: "\2715";
	position: absolute;
	color: transparent;
	z-index: 10000;
	font-size: 6em;
	width: 80px;
	background: transparent;
	left: 0;
}
#linkbox div.image-wrapper:active:before {
	font-size: 7em;
}
#linkbox div.image-wrapper:hover:before, #linkbox div.image-wrapper.noimg:before {
	color: red;
	background: rgba(0, 0, 0, 0.3);
}
#linkbox li.image-wrapper:hover {
	-webkit-box-shadow: 0 0 4px 4px #4690D6;
	box-shadow: 0 0 4px 4px #4690D6;
}
#linkbox .elgg-image:hover li {
	display: block;
}
#linkbox .link_name[contenteditable]:hover, #linkbox .link_description[contenteditable]:hover, #linkbox .link_name[contenteditable]:focus, #linkbox .link_description[contenteditable]:focus {
	background: #e4ecf5;
}
#linkbox .link_description {
	min-height: 18px;
	font-size: .9em;
}

#thewire-network {
	right: -201px;
	top: 0;
	width: 194px;
	position: absolute;
}
#thewire-network img {
	width: 24px;
	height: 24px;
}
#thewire-network .selected-profile {
	background: white;
	border: medium none;
	border-radius: 4px 4px 4px 4px;
	height: 23px;
	width: 100%;
	padding: 6px 0 4px;
	box-shadow: inset 0 2px 2px 0 #1F2E3D;
	-webkit-box-shadow: inset 0 2px 2px 0 #1F2E3D;
	-moz-box-shadow: inset 0 2px 2px 0 #1F2E3D;
}
#thewire-network.extended .selected-profile {
	height: 28px;
}
#thewire-network .net-profiles-wrapper {
	box-shadow: 0 2px 2px 0 #1F2E3D inset;
	background: white;
	margin-top: -10px;
}
#thewire-network .net-profiles {
	float: left;
	min-height: 39px;
	padding: 2px 0 1px;
	width: 194px;
	z-index: -1;
	max-height: 316px;
	overflow-x: hidden;
	overflow-y: auto;
}
.selected-profile.ui-state-highlight, .net-profiles.ui-state-highlight {
	background: #FFFFCC;
}
.selected-profile.ui-state-active, .net-profiles.ui-state-active {
	background: #DDFFDD;
}
.selected-profile.ui-start, .net-profiles.ui-start {
	background: transparent;
}
#thewire-network .net-profile {
	position: relative;
}
#thewire-network .net-profile.ui-sortable-helper{
	background: white;
	box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5) !important;
	-webkit-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	cursor: crosshair;
}
#thewire-network .ui-sortable-placeholder {
	border: 2px dashed #dedede;
	-moz-box-sizing: border-box;
	-webkit-box-sizing: border-box;
	box-sizing: border-box;
	visibility: visible !important;
}
#thewire-network .net-profiles .ui-sortable-placeholder {
	margin: 5px !important;
}
#thewire-network .selected-profile .ui-sortable-placeholder {
	width: 24px;
}
#thewire-network .network {
	background: white;
	border: 1px solid #666666;
	height: 10px;
	left: 17px;
	position: absolute;
	top: -3px;
	width: 10px;
	pointer-events: none;
}
#thewire-network .net-profile.ggouv .network {
	background-image: url(<?php echo elgg_get_site_url() . 'mod/elgg-ggouv_template/graphics/favicon/favicon.png'; ?>);
	background-size: 10px 10px;
}
#thewire-network .net-profile.twitter .network {
	background: #00ACED;
	border: 1px solid #00ACED;
	color: white;
	font-size: 18px;
	line-height: 12px;
	text-indent: -1px;
}
#thewire-network .net-profile.facebook .network {
	background: #3B5998;
	border: 1px solid #3B5998;
	color: white;
	font-size: 23px;
	line-height: 12px;
	text-indent: -2px;
}
#thewire-network .elgg-icon-delete {
	background: rgba(0, 0, 0, 0.3);
	height: 15px;
	left: -2px;
	position: absolute;
	text-indent: 1px;
	width: 15px;
	cursor: pointer;
}
#thewire-network .elgg-icon-delete:active {
	background: rgba(0, 0, 0, 0.6);
}
#thewire-network .elgg-icon-delete:before {
	color: red;
}
#thewire-network .net-profile:hover .elgg-icon {
	display: block;
}
#thewire-network .net-profile.ui-draggable-dragging:hover .elgg-icon-delete {
	display: none;
}
#thewire-network .net-profile:hover .elgg-module-popup {
	display: block;
	left: -77px;
	position: absolute;
	top: 31px;
	background: #1F2E3D;
	color: white;
	min-width: 150px;
	border-radius: 6px;
	border: none;
	box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.5);
	-webkit-box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.5);
}
#thewire-network .selected-profile .net-profile.ui-draggable-dragging:hover .elgg-module-popup {
	display: none;
}
#thewire-network .selected-profile .net-profile.ui-draggable-dragging:hover {
	cursor: move;
}
#thewire-network .triangle {
	border-style: solid;
	border-width: 0 10px 10px;
	border-color: #1F2E3D transparent;
	left: 80px;
	position: absolute;
	top: -6px;
}
#thewire-network .elgg-user-info-popup {
	font-weight: bold;
}
#thewire-network .elgg-module-popup a:hover, #thewire-network .elgg-module-popup span:hover {
	color: #e4ecf5;
}
#thewire-network .more_networks {
	color: #CCCCCC;
	position: absolute;
	right: 0;
	top: 9px;
	font-size: 3em;
	cursor: pointer;
}
#thewire-network .more_networks:hover {
	color: #4690D6;
}
#thewire-network.extended .non-pinned {
	display: block;
	position: absolute;
	top: 33px;
	z-index: 0;
}
#thewire-network .non-pinned {
	background: #4690D6;
	border-radius: 0 0 8px 8px;
	-webkit-box-shadow: 3px 3px 3px 0 rgba(0, 0, 0, 0.3);
	-moz-box-shadow: 3px 3px 3px 0 rgba(0, 0, 0, 0.3);
	box-shadow: 3px 3px 3px 0 rgba(0, 0, 0, 0.3);
	padding: 4px 7px 7px;
	margin-left: -7px;
	float: left;
}
#thewire-network .non-pinned .content {
	padding-top: 22px;
	width: 100%;
}
#thewire-network .helper {
	height: 24px;
	overflow: hidden;
	position: relative;
	margin: -4px 0 -18px;
	width: 194px;
	z-index: 1;
}
#thewire-network .helper div {
	margin-top: -7px;
	padding: 8px 0 3px;
	background: #F0F0F0;
	box-shadow: 0 2px 2px 0 #1F2E3D inset;
	color: #999999;
	font-size: 0.85em;
	text-align: center;
	cursor: default;
}
#thewire-network .helper span {
	font-size: 2em;
	vertical-align: text-bottom;
	color: #CCC;
}
/* hack Chrome / Safari */
@media screen and (-webkit-min-device-pixel-ratio:0) {
	#thewire-network .helper span {
		vertical-align: bottom;
	}
	#thewire-network .helper div {
		padding: 10px 0 3px;
	}
}
#thewire-network .non-pinned .net-profile {
	margin: 2px;
	padding: 3px;
	width: 184px;
	overflow: hidden;
}
#thewire-network .non-pinned .net-profile:hover {
	background: white;
	cursor: move;
	box-shadow: 0 0 1px #CCC;
}
#thewire-network .non-pinned .network {
	left: 20px;
	top: 0;
}
#thewire-network .non-pinned .net-profile .elgg-module-popup {
	background: white;
	border: medium none;
	box-shadow: none;
	display: block;
	float: left;
	font-size: 0.8em;
	height: 0;
	left: 38px;
	padding: 0;
	position: absolute;
	top: -1px;
	width: 155px;
	z-index: 0;
}
#thewire-network .non-pinned .net-profile:hover .elgg-module-popup {
	color: black !important;
}
#thewire-network .non-pinned .triangle, #thewire-network .non-pinned .elgg-icon-delete, #thewire-network .non-pinned .elgg-river-timestamp {
	display: none !important;
}
#thewire-network .non-pinned .elgg-module-popup span {
	pointer-events: none;
}
#thewire-network .non-pinned .elgg-module-popup span:hover {
	color: #555;
}
#thewire-network .non-pinned .elgg-river-summary {
	height: 31px;
	display: table-cell;
	vertical-align: middle;
	line-height: 1.1em;
}
#thewire-network .non-pinned .pin {
	float: right;
	font-size: 0.8em;
	padding: 8px 2px;
}
#thewire-network .footer {
	background: #F0F0F0;
	border-radius: 0 0 6px 6px;
	box-shadow: 0 2px 2px 0 #1F2E3D inset;
	float: left;
	margin-top: -12px;
	padding: 15px 5px 5px;
	position: relative;
	width: 184px;
	z-index: -1;
}
#thewire-network .footer li {
	display: inline-block;
	font-size: 0.8em;
	line-height: 1em;
	margin-top: 3px;
	padding: 0 5px;
	text-align: center;
	width: 74px;
}
#thewire-network .footer li:first-child {
	border-right: 1px solid #CCC;
	padding-right: 15px;
}
#thewire-network .footer a {
	color: #999;
}
.fixed-deck .minds-body-header, .deck .minds-body-header{
	opacity:1;
}
#post-input-box{
	height:36px;
}
.deck-post-preview{
	position:absolute;
	width:100%;
	bottom:46px;
	left:12px;
	display:none;
}
.deck-post-preview .deck-post-preview-title, .deck-post-preview .deck-post-preview-title:focus{
	background:transparent;
	border:0;
	font-weight:bold;
	padding:0;
	font-size:14px;
	width:auto;
	margin:0 8px;
}
.deck-post-preview .deck-post-preview-description, .deck-post-preview .deck-post-preview-description:focus{
	background:transparent;
	border:0;
	font-weight:lighter;
	font-style:italic;
	padding:0;
	overflow:hidden;
	width:80%;
	margin:0 8px;
}
.deck-post-preview .deck-post-preview-icon-img{
	float:left;
	height:36px;
}
.deck-river-accounts{
	position:relative;
	z-index:1;
}
.elgg-input-dragbox{
	right:0;
	top:5px;
	height:auto;
	width:200px;
	position:absolute;
}
.elgg-input-dragbox .selected{
	width:100%;
	height:30px;
	overflow:scroll;
	
	border:1px solid #EEE;
	background:#FFF;
	border-radius:0 0 3px;
}
.elgg-input-dragbox .not-selected{
	display:none;
	position: relative;  
	height:auto;
	border:1px solid #EEE;
	background:#EEE;
	border-radius:0 0 3px;
}
.elgg-input-dragbox:hover .not-selected{
	display:block;
	width:100%;
}
.elgg-input-dragbox .drag-prompt{
	width:100%;
	font-size:11px;
	font-weight:bold;
	text-align:center;
	color:#666;
	heigth:12px;
}
.elgg-input-dragbox li{
	float:left;
	width:auto; 
	height:auto;
	list-style:none;
}
.elgg-input-dragbox img{
	float:none;
	margin:2px;
	width:24px;
	border:1px solid #DDD;
}
.elgg-form-deck-river-post .elgg-button-submit{
	min-width: 100px;
	top: 2px;
}
/**
 * 	Attacher
 */

.deck-attachment-button-override{
	font-family:"fontello";
	background:#EEE;
	padding:0;
	border:1px solid #DDD;
	border-radius:0;
	color:#888;
	content: "\1f4f7"; /* \1f4f7 */
	font-size:22px;
	position:absolute;
	right:242px;
	top:5px;
	cursor:pointer;
	height:29px;
	width:36px;
}
.deck-attachment-button-override:before{
	font-family: "fontello";
	color: #888;
	content: "\1f4f7";
	position: absolute;
	top: 7px;
	left: 6px;
}
.deck-attachment-button-override.attached:before{
	color:#4690D6;
}
.deck-attachment-button{
	position:absolute;
	font-family:"fontello";
	color:#888;
	content: "\1f4f7"; /* \1f4f7 */
	position: absolute;
	height: 100%;
	width:100%;
	top: 0;
	left: 0;
	cursor: pointer;
	opacity: 0;
	filter:alpha(opacity=0);
}


/**
 * 	Scheduler
 */
.deck-scheduler-button{
	font-family:"fontello";
	background:#EEE;
	padding:6px 8px 6px;
	border:1px solid #DDD;
	color:#888;
	font-size:22px;
	position:absolute;
	right:200px;
	top:5px;
	cursor:pointer;
}
.deck-scheduler-content{
	display:none;
	position:absolute;
	right:200px;
	background:#EEE;
	top:32px;
	width:125px;
	height:auto;
	padding:8px;
}
.deck-scheduler-content input, .deck-scheduler-content select{
	text-align:right;
}

/**
 * 	Scheduler views
 */
.deck-scheduler-layout .elgg-body > .avatar{
	width: 32px;
	padding:2px;
}

.elgg-deck-filter-row{
	display: block;
	position: relative;
}
/* deck tabs */
.elgg-menu-deck-river {
	position:absolute;
	top:10px;
	left:0;
	margin: 2px 0 0;
}
.elgg-menu-deck-river > li {
	margin:0 10px;
}
.elgg-menu-deck-river > li > a:first-letter {
	text-transform: uppercase;
}

.elgg-menu-deck-river .elgg-menu-item-refresh-all .elgg-icon:before {
	cursor: pointer;
}
.elgg-menu-deck-river .delete-tab{
	display:none;
}
.elgg-menu-deck-river:hover .delete-tab {
	display:block;
	font-size: 12px;
	padding: 3px 2px 0;
	position:absolute;
	top:0;
	right:0;
}

.elgg-menu-deck-river > .elgg-menu-item-plus-column {
	border-radius: 0 5px 0 0;
	border-style: solid solid none none;
	font-size: 1.5em;
	font-weight: bold;
	line-height: 14px;
	margin: 0;
}
.elgg-menu-deck-river > .elgg-menu-item-plus {
	font-weight: bold;
}
.elgg-menu-deck-river > .elgg-menu-item-refresh-all:hover,
.elgg-menu-deck-river > .elgg-menu-item-plus-column:hover,
.elgg-menu-deck-river > .elgg-menu-item-refresh-all:hover > a,
.elgg-menu-deck-river > .elgg-menu-item-plus-column:hover > a {
	background: #EEE;
}
.column-deletable, .delete-tab {
	float: left;
}
li.elgg-menu-item-arrow-left {
	border: none;
	width: 160px;
}
.elgg-menu-item-arrow-left:hover, li.elgg-menu-item-arrow-left a:hover {
	background: transparent;
}
.deck-river-scroll-arrow.left {
	position: absolute;
	top: -3px;
}
.deck-river-scroll-arrow.right {
	position: absolute;
	right: 20px;
	top: 10px;
}
.deck-river-scroll-arrow .count {
	background: none repeat scroll 0 0 #FFAA33;
	border-radius: 4px 4px 4px 4px;
	color: white;
	font-size: 0.9em;
	font-weight: bold;
	padding: 0 4px;
}
.deck-river-scroll-arrow.right .count {
	float: left;
}
.deck-river-scroll-arrow.left .count {
	float: right;
}

/* deck */
#deck-river-lists {
	overflow-x: scroll;
	overflow-y: hidden;
	width: 100%;
	padding-top:10px;
}
#deck-river-lists .nofeed {
	padding: 30px 37px;
}
#deck-river-lists .nofeed span {
	color: #CCC;
	font-size: 4em;
	-webkit-transform: rotateY(180deg) rotateZ(90deg);
	margin: -15px 10px 0;
}
.column-river .elgg-river {
	height: 100%;
	overflow-y: scroll;
	overflow-x: hidden;
}
.elgg-list {
	margin: 0;
	position: relative;
}
#deck-river-lists .column-river {
	float: left;
	position: relative;
	border: 1px solid #CCC;
	max-width: 645px;
	margin:0 4px;
}
.column-placeholder {
	width: 300px;
	float: left;
	border: 2px dashed #dedede;
}

/* column header */
.column-header {
	height: 30px;
	background: #EEE;
	overflow: hidden;
	position: relative;
	padding:6px;
}
.column-header.facebook{
	background:#3b5998;
}
.column-header.twitter{
	background:#2290bf;
}
.column-header.tumblr{
	background:#2c4762;
}
.column-header.linkedin{
	background:#4875B4;
}
.column-header.minds{
	background:#888;
}
.column-header .title {
	color: #FFF;
	font-size: 17px;
	overflow: hidden;
	margin: -5px 0 -20px;
}
.column-header .subtitle {
	color: #999;
	line-height: 10px;
	height: 11px;
	overflow: hidden;
}
.column-header > li > a {
	float: right;
	margin: 6px 7px 0 0;
}
.column-header .delete-tab{
	color:#999;
}
.column-header  span + .filtered {
	border-left: 1px solid #CCC;
	margin-left: 5px;
	padding-left: 5px;
}
.column-handle {
	margin-right: 85px;
	cursor: move;
}
.column-handle:before {
	font-family: 'fontello';
	float: left;
	color: #FFF;
	font-weight: lighter;
	font-size: 28px;
	padding: 12px 12px 0 4px;
	/* margin: 0 2px; */
	text-shadow: 0 0 3px #888;
}
.facebook .column-handle:before{
	content: '\f30d';
}
.twitter .column-handle:before{
	content: '\f30a';
}
.tumblr .column-handle:before{
	content: '\f316';
}
.linkedin .column-handle:before{
	content: '\f319';
}
.column-river .count, .newRiverItem:after {
	background: #FA3;
	border-radius: 8px;
	color: white;
	float: right;
	font-weight: bold;
	margin: 6px;
	padding: 0 4px;
}
.column-river .elgg-list-item{
	width:90%;
	margin:8px 4px;
	height:auto;
	float:none;
}
.message-box {
	position: absolute;
	top: 30px;
	width: 100%;
	z-index: 2;
	font-size: 0.9em;
}
.deck-popup .message-box {
	top: 64px !important;
	margin: -5px;
}
.column-message, .top-message {
	color: white;
	cursor: pointer;
	font-weight: bold;
	opacity: 0.9;
	padding: 3px 10px;
	text-align: center;
	-webkit-box-shadow: 0 2px 2px #CCCCCC;
	-moz-box-shadow: 0 2px 2px #CCCCCC;
	box-shadow: 0 2px 2px #CCCCCC;
}
.top-message {
	background: #FFFFCC;
	color: #555;
}
.column-river .refresh-gif {
	position: absolute;
	right: 28px;
	top: 8px;
	display: none;
}
.column-river.loadingRefresh .refresh-gif {
	display: block;
}
.column-header .elgg-icon:before {
	cursor: pointer;
	color: #BBB;
}
.column-header .elgg-icon:hover:before {
	color: #4690D6;
}
.column-filter {
	background: #FFC;
	z-index: 1;
	position: relative;
	-webkit-box-shadow: 0 2px 2px #CCCCCC;
	-moz-box-shadow: 0 2px 2px #CCCCCC;
	box-shadow: 0 2px 2px #CCCCCC;
}
.column-filter .elgg-vertical {
	border-bottom: 1px solid #CCC;
	display: inline-block;
	width: 100%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	-ms-box-sizing: border-box;
	box-sizing: border-box;
	min-height: 35px;
}
.column-filter label {
	font-size: 90%;
}
.column-filter .close-filter {
	color: #555;
}
.column-filter .close-filter:hover {
	color: black;
}

.river-to-top {
	height: 17px;
	width: 17px;
	background: rgba(200, 200, 200, 0.5);
	position: absolute;
	bottom: 10px;
	right: 10px;
	border-radius: 5px;
}
.river-to-top:before {
	content: "↑";
	font-size: 2em;
	text-align: center;
	width: 100%;
	color: #555;
}
.river-to-top:hover {
	background: rgba(200, 200, 200, 0.8);
}
.river-to-top:hover:before {
	color: #0054A7;
}

/* river */
.elgg-river-item {
	padding: 0;
}
.elgg-river-item .elgg-river-timestamp a {
	color: #999;
}
.elgg-river-item .elgg-river-timestamp .elgg-icon {
	height: 10px;
}
.elgg-river-item .elgg-river-timestamp .elgg-icon:before {
	font-size: 28px;
	text-indent: -3px;
}
.elgg-river .elgg-module {
	margin-bottom: 0;
}
.elgg-river .elgg-ajax-loader {
	height: 100%;
}
.newRiverItem:after {
	content: " ";
	height: 8px;
	position: absolute;
	right: 1px;
	top: 3px;
}
.elgg-list-item.newRiverItem:hover:after {
	opacity: 0;
}
.elgg-list > li.moreItem {
	background: #EEE;
	cursor: pointer;
	text-align: center;
	position: relative;
	width:100%;
	height:40px;
}
.elgg-list > li.moreItem:hover {
	color: #4690D6;
}
.moreItem .response-loader {
	left: 50%;
	position: absolute;
}
.loadingMore .moreItem {
	color: transparent;
}
.loadingMore .moreItem .response-loader {
	display: block;
}

.column-river .elgg-river td.helper {
	padding: 80px 20px 0;
	text-align: center;
}
.elgg-river-item .elgg-avatar-small > div > img {
	width: 32px;
	height: 32px;
	border-radius: 0;
}
.elgg-river-comments .elgg-avatar-small > div > img {
	width: 24px;
	height: 24px;
}

.elgg-submenu {
	text-transform: none;
	text-align: left;
	font-weight: normal;
	font-size: inherit;
}
.elgg-submenu.elgg-state-active .elgg-module-popup {
	box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
	display: block;
	position: absolute;
	right: 3px;
	top: 15px;
	width: 150px;
	color: #333;
	padding: 0;
	font-size: 0.9em;
}
.elgg-button-dropdown.elgg-state-active .elgg-module-popup {
	right: -1px;
	top: 24px;
}
.elgg-submenu li {
	display: list-item;
}
.elgg-submenu li.section {
	border-top: 1px solid #CCC;
}
.elgg-submenu a {
	padding: 5px;
	display: block;
}
.elgg-submenu li:hover {
	background: #EEE;
}
.elgg-submenu li .elgg-icon {
	vertical-align: text-bottom;
	padding-right: 15px;
}
.elgg-submenu li .elgg-icon:before {
	transition: none;
	-webkit-transition: none;
	-moz-transition: none;
	-o-transition: none;
}
.elgg-submenu li:hover a {
	color: #555;
}
.elgg-submenu a:hover {
	text-decoration: none;
}
.elgg-submenu li:hover .elgg-icon:before {
	color: #555;
}
.elgg-submenu li:hover .elgg-icon-delete:before {
	color: red;
}

.elgg-river-image {
	max-height: 90px;
	overflow: hidden;
	background: #EEE;
	max-width: 590px;
}
.elgg-river-image .elgg-body {
	overflow: hidden;
	border: 6px solid #EEE;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	-ms-box-sizing: border-box;
	box-sizing: border-box;
	max-height: inherit;
}
.elgg-river-image .elgg-image {
	background-position: 50% 50%;
	background-size: auto 90px;
	background-repeat: no-repeat;
	background-color: white;
	width: 90px;
	height: 90px;
	margin: 0;
}
.elgg-list-item .elgg-image > img.fb {
	opacity: 0.3;
	padding: 7px;
}
.elgg-river-image.big {
	max-height: none;
}
.elgg-river-image.big .elgg-image {
	background-position: 50% 50%;
	background-size: 100% auto;
	width: 100%;
	height: 150px;
	max-width: 600px;
	max-height: 600px;
}
.elgg-river-image.big .elgg-body {
	height: auto;
	float: left;
	clear: both;
	padding: 3px;
}
.elgg-river-responses.media-video-popup .elgg-image:before {
	content: "v";
	height: 100%;
	width: 100%;
	z-index: 1;
	background-color: rgba(0, 0, 0, 0.2);
	color: rgba(255, 255, 255, 0.8);
	font-size: 10em;
	line-height: 0.8em;
	display: none;
	text-indent: 9px;
}
.elgg-river-responses.media-video-popup:hover .elgg-image:before {
	display: block;
}
.elgg-river-message {
	white-space: pre-line;
}

.elgg-river-responses a:hover {
	cursor: pointer;
}
.elgg-river-responses .thread .elgg-list-item {
	color: black;
	margin-left: -30px;
	padding-top: 5px;
}
.elgg-river-responses .thread .elgg-avatar img {
	height: 24px;
	width: 24px;
}
.elgg-river-responses .thread .elgg-river-item.elgg-image-block .elgg-body {
	margin-left: 30px;
}
.elgg-river-responses a.thread:before {
	content: "<?php echo elgg_echo('deck_river:thread:show'); ?>";
}
.elgg-river-responses a.thread.elgg-state-active:before {
	content: "<?php echo elgg_echo('deck_river:thread:hide'); ?>";
}
.elgg-list-item.responseAt {
	background: #FFFFCC !important;
}
/* facebook */
a.elgg-river-responses {
	color: #555555;
	line-height: 1.2em;
}
a.elgg-river-responses:hover {
	text-decoration: none;
}
a.elgg-river-responses:hover h4 {
	text-decoration: underline;
}
.facebook-comment-form .comment {
	height: 60px;
	resize: vertical;
}
.elgg-river-responses.linkbox-droppable:hover > div {
	-webkit-box-shadow: 0 0 3px #ccc;
	box-shadow: 0 0 3px #ccc;
}

/* settings */
#column-settings {
	left: 40%;
	position: fixed;
	top: 15%;
	z-index: 99999999;
	width: auto;
	height: auto;
	min-width: 540px;
	max-width: 600px;
	min-height: 300px;
}
#column-settings .elgg-ajax-loader {
	height: 270px;
}
.deck-popup > .elgg-head {
	background: #EEE;
	margin: -5px -5px 5px;
	padding-bottom: 5px;
	cursor: move;
}
.deck-popup > .elgg-head h3 {
	color: #666666;
	float: left;
	padding: 4px 50px 0 5px;
}
.deck-popup > .elgg-head a {
	display: inline-block;
	height: 18px;
	position: absolute;
	right: 10px;
	top: 5px;
	width: 18px;
	cursor: pointer;
}
.deck-popup .elgg-head a.pin {
	right: 25px;
}
#column-settings .tab {
	height: 240px;
	float: left;
}
#column-settings .networks {
	border-bottom: medium none;
	width: auto;
}
#column-settings .networks > li a:before{
	font-family:'fontello';
	font-size:20px;
	vertical-align:middle;
	margin-right:8px;
}
#column-settings .networks > li a.twitter:before{
	content: '\f30a';
}
#column-settings .networks > li a.facebook:before{
	content: '\f30d';
}
#column-settings .networks > li a.tumblr:before{
	content: '\f316';
}
#column-settings .networks > li a.linkedin:before{
	content: '\f319';
}
#column-settings .networks > li {
	content: '';
	clear: both;
	float: right;
	margin: 5px 0 0;
	width: 120px;
	font-size:13px;
	font-weight:bold;
}
#column-settings .networks > li a {
	text-align: left;
}
#column-settings .networks > .elgg-state-selected a {
	top: 0;
	right: -2px;
}
#column-settings .elgg-input-checkboxes label {
	font-weight: normal;
}
#column-settings .tab > * {
	border-left: 2px solid #CCC;
	height: 100%;
	width: 421px;
}
#add-deck-river-tab, .rename-deck-river-tab {
	font-weight: bold;
	float: left;
}
#add-deck-river-tab .elgg-input-text, .rename-deck-river-tab .elgg-input-text {
	width: 200px;
	float: left;
}
#add-deck-river-tab .elgg-button-submit, .rename-deck-river-tab .elgg-button-submit {
	margin-top: 8px;
}
#column-settings .tab .elgg-module-info {
	background: none repeat scroll 0 0 #EEEEEE;
}
#column-settings .tab .elgg-module {
	font-size: 0.9em;
	padding: 5px 5px 0;
	position: relative;
}
#column-settings .tab .elgg-module.multi {
	position: relative;
	padding: 0 5px;
}
#column-settings .add_social_network {
	font-size: 1.4em;
	font-weight: bold;
	cursor: pointer;
}
#column-settings .add_social_network:hover {
	text-decoration: none;
}
#column-settings .elgg-foot {
	float: left;
	width: 100%;
}

/*
 * info popup
 */
.deck-popup {
	width: 600px;
	height: 600px;
	left: 40%;
	position: fixed !important;
	top: 15%;
	z-index: 9990;
}
.deck-popup > .elgg-body > .elgg-body {
	height: 540px;
	overflow-y: auto;
}
.deck-popup .column-river {
	height: 540px;
}
#hashtag-info-popup > .elgg-body {
	height: 573px;
	overflow-y: auto;
}
.deck-popup .elgg-ajax-loader {
	height: 540px;
}
.deck-popup .avatar-wrapper {
	height: 200px;
	line-height: 200px;
	overflow: hidden;
	width: 200px;
	background: #1F2E3D;
}
.deck-popup .elgg-menu-owner-block li a {
	background: #EEE;
}
.deck-popup .elgg-menu-owner-block li a:hover {
	background: #0054A7;
}
.deck-popup .elgg-menu-owner-block, .deck-popup #profile-details {
	clear: both;
	padding: 5px 0 0 0;
}
.deck-popup h1 {
	line-height: 1em;
}
.user-stats {
	background: none repeat scroll 0 0 #EEEEEE;
	border-radius: 5px 5px 5px 5px;
	clear: both;
	display: inline-block;
	width: 100%;
}
.user-stats li {
	font-weight: bold;
	display: block;
	float: left;
	margin: 0 10px 5px;
	min-width: 100px;
	vertical-align: top;
}
.user-stats li a {
	color: #333333;
}
.user-stats li a:hover {
	color: #0054A7;
	text-decoration: none;
}
.user-stats .stats {
	font-size: 200%;
	line-height: 0.9em;
}
.elgg-user-info-popup {
	cursor: pointer;
}
div.info-popup.ui-draggable-dragging {
	-webkit-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	cursor: crosshair;
	line-height: 0.1;
	pointer-events: none;
}
a.info-popup.ui-draggable-dragging {
	text-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	cursor: crosshair;
	pointer-events: none;
}
.info-popup.canDrop:before, .linkbox-droppable.canDrop:before {
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0.2);
	position: absolute;
	top: 0;
	content: '';
}
.info-popup.canDrop:after, .linkbox-droppable.canDrop:after {
	background: #51C600;
	border-radius: 10px;
	color: white;
	content: "+";
	font-weight: bold;
	height: 20px;
	position: absolute;
	right: -10px;
	text-align: center;
	top: -10px;
	width: 20px;
	line-height: 1.1em;
	font-size: 17px;
}
.elgg-module-popup .elgg-avatar a:hover .avatar-wrapper img {
	opacity: 0.3;
}
.elgg-module-popup .elgg-avatar span:before {
	content: "o";
	float: none;
	font-size: 10em;
}
.elgg-module-popup .elgg-avatar a:hover span {
	display: block;
	font-size: 1.4em;
	font-weight: bold;
	line-height: 0;
	position: absolute;
	text-align: center;
	top: 80px;
	width: 200px;
	color: white;
}
.deck-popup .elgg-column-filter-button {
	position: absolute;
	right: 10px;
	top: 35px;
	color: #999;
}
.deck-popup .elgg-column-filter-button:hover {
	color: #4690D6;
}

/* facebook popup */
#facebook-groups-popup li:hover, #facebook-pages-popup li:hover {
	background: #CCC;
}
#video-popup {
	height: 360px;
}
#video-popup .elgg-body {
	height: 346px;
	margin: -6px;
}
.linkbox-droppable.ui-draggable.ui-draggable-dragging {
	-webkit-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	cursor: crosshair;
}

#group-info-popup .cover-block {
	position: relative;
	padding-bottom: 1px;
}
#group-info-popup .cover-wrapper {
	width: 480px;
	height: 200px;
	overflow: hidden;
}
#group-info-popup .cover-wrapper span {
	display: block;
	width: 1000px;
	margin-left: -260px;
	text-align: center;
}
#group-info-popup .cover-wrapper img {
	height: 200px;
	display: inline-block;
}
#group-info-popup .cover-wrapper + .elgg-avatar {
	position: absolute;
	bottom: 0;
	left: -4px;
}
#group-info-popup .cover-wrapper + .elgg-avatar .avatar-wrapper {
	height: 100px;
	line-height: 100px;
	width: 100px;
	border: 4px solid white;
}
#group-info-popup .cover-wrapper + .elgg-avatar span {
	font-size: 0.8em;
	top: 40px;
	width: 108px;
}
#group-info-popup .cover-wrapper + .elgg-avatar img {
	width: 100px;
}
#group-info-popup .cover-wrapper + .elgg-avatar + div > h1 {
	margin-left: 100px;
}

/* single view */
.single-view .elgg-list-item {
	opacity: 0.6;
}
.single-view .elgg-list-item:hover {
	opacity: 1;
}
.single-view .viewed {
	opacity: 1;
	padding: 10px 0 !important;
	background: none;
}
.single-view .elgg-river-responses {
	display: none;
}
.viewed .elgg-image-block {
	box-shadow: 0 0 10px #CCCCCC;
	margin: 10px;
	padding: 10px;
}
.viewed .elgg-avatar-small > div > img {
	height: 40px ;
	width: 40px;
}
.viewed .elgg-image-block .elgg-body {
	margin-left: 50px;
}

#message-river-activity .elgg-river {
	overflow: visible;
}
#message-river-activity li {
	background: none;
}

/* popup links */
.elgg-river-summary .info-popup {
	color: #4690D6;
	font-weight: bold;
}
.elgg-river-summary .info-popup:hover {
	color: #555;
	text-decoration: underline;
	cursor: pointer;
}

/* applications page */
.elgg-module-network.elgg-module-aside > .elgg-head h3 {
	color: #555555;
	font-size: 2em;
	margin-top: 5px;
}
.elgg-module-network .network-icon:before {
	cursor: default;
	font-size: 2.5em;
}

/* popup add_social_network */
#add_social_network {
	height: auto;
}
#add_social_network .elgg-image-block {
	border-top: 1px solid #CCC;
}
#add_social_network .elgg-image-block:first-child {
	border: none;
}
#add_social_network .elgg-image {
	font-size: 10em;
	line-height: 1.3em;
	padding-right: 20px;
}

/* popup choose-twitter-account-popup */
#choose-twitter-account-popup, #choose-twitter-list-popup, #share-account-popup {
	height: auto;
	width: 300px;
}
#share-account-popup {
	height: auto;
	width: 410px;
}
#share-account-popup input[type="checkbox"] {
	display: none;
}
#share-account-popup input[type="checkbox"] + label {
	display: none;
}

.ui-resizable { position: relative;}
.ui-resizable-handle { position: absolute;font-size: 0.1px;z-index: 99999; display: block;}
.ui-resizable-disabled .ui-resizable-handle, .ui-resizable-autohide .ui-resizable-handle { display: none; }
.ui-resizable-n { cursor: n-resize; height: 7px; width: 100%; top: -5px; left: 0px; }
.ui-resizable-s { cursor: s-resize; height: 7px; width: 100%; bottom: -5px; left: 0px; }
.ui-resizable-e { cursor: e-resize; width: 7px; right: -5px; top: 0px; height: 100%; }
.ui-resizable-w { cursor: w-resize; width: 7px; left: -5px; top: 0px; height: 100%; }
.ui-resizable-se { cursor: se-resize; width: 14px; height: 14px; right: 0; bottom: 0; }
.ui-resizable-sw { cursor: sw-resize; width: 9px; height: 9px; left: -5px; bottom: -5px; }
.ui-resizable-nw { cursor: nw-resize; width: 9px; height: 9px; left: -5px; top: -5px; }
.ui-resizable-ne { cursor: ne-resize; width: 9px; height: 9px; right: -5px; top: -5px;}
.resizable-helper {
	border: 2px dotted red;
	background: rgba(255, 0, 0, 0.08);
}
#video-popup:hover .ui-resizable-handle:before {
	content: "\ABFE";
	color: rgba(255, 255, 255, 0.5);
	font-family: ggouv;
	font-size: 40px;
	margin-left: -6px;
}

.bookmarklet-link {
	margin: 30px;
	background: #4690D6;
	font-size: 0;
	padding: 10px 20px;
}
.bookmarklet-link:before {
	content: "<?php echo elgg_echo('bookmarklet:popup:button'); ?>";
	font-size: 20px;
}

#deck-river-lists .elgg-river-image{
	max-height:none;
	max-width:none;
}
#deck-river-lists .elgg-river-image img{
	width:100%;
}
#deck-river-lists .elgg-river-comments li{
	float:none;
	width:auto;
	height:auto;
}
.elgg-form-deck-river-post{
	width:50%;
}
.elgg-form-deck-river-post input{
	
	
}


.minds-preview{
	border-top: 1px solid #EEE;
	padding: 16px 12px;
	background: #FFF;
}
.minds-preview:hover{
}
.minds-preview > a:hover{
	text-decoration:none;
}

.minds-preview > a > .minds-preview-icon{
	height: 84px;
	float: left;
	margin: 0 12px 12px 0;
}

.minds-preview > a > h3{
	font-size: 14px;
	font-weight: bold;
	font-family: helvetica;
	line-height:15px;
}

.minds-preview > a span.minds-preview-url{
	font-weight: bold;
	font-size: 10px;
	margin: 0;
	padding: 0;
	line-height:15px;
}

.minds-preview > a > p{
	color: #333;
	font-size: 11px;
	font-style: italic;
	line-height:13px;
	margin:0;
}

<?php if (0): ?></style><?php endif; ?>
