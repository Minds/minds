/* <style>
/* ***************************************
	Modules
*************************************** */
/* Info */
.elgg-module-info > .elgg-head {
	background: #e8e8e8;
	border-top: 1px solid #94A3C4;
	margin-bottom: 5px;
	padding: 5px 8px;
}

/* Aside */
.elgg-module-aside {
	margin-bottom: 15px;
}

.elgg-module-aside > .elgg-head {
	background-color: #F2F2F2;
	border-bottom: none;
	border-top: solid 1px #E2E2E2;
	padding: 4px 5px 5px;
	margin-bottom: 5px;
}

.elgg-module-aside > .elgg-head > h3 {
	font-size: 1em;
}

.elgg-module-aside > .elgg-body {
	padding: 0 5px;
}

/* Popup */
.elgg-module-popup {
	background-color: white;

	z-index: 9999;
	margin-bottom: 0;

	box-shadow: 0 0 0 10px rgba(82, 82, 82, 0.7);
	border-radius: 8px;
}

.elgg-module-popup > .elgg-head {
	background: #767676;
	border: 1px solid #484848;
	border-bottom: none;
	font-size: 14px;
	font-weight: bold;
	margin: 0;
	padding: 5px 10px;
}

.elgg-module-popup > .elgg-head > h3 {
	color: white;
}

.elgg-module-popup > .elgg-body {
	background: white;
	border: 1px solid #171717;
	border-top: 0;
	padding: 10px;
}

.elgg-module-popup > .elgg-foot {
	margin-top: -1px;
	border: 1px solid #4b4b4b;
	border-top-color: #525252;
	background: #F2F2F2;
	padding: 8px 10px;
	text-align:right;
}

/* Dropdown */
.elgg-module-dropdown {
	background-color: white;
	border: 1px solid #333333;
	border-bottom: 2px solid #353535;

	z-index:100;
}

.elgg-module-dropdown > .elgg-body {
	padding: 8px;
}

.elgg-module-dropdown > .elgg-head {
	margin: 7px 8px 0;
	border-bottom: 1px solid #4f4f4f;
	padding-bottom: .5em;
}

.elgg-module-dropdown > .elgg-foot {
	text-align: center;
}

/* Featured */
.elgg-module-featured {
	background-color: #F2F2F2;
	border: 1px solid #4e4e4e;
	padding: 10px;
	margin-bottom: 20px;
}

.elgg-module-featured > .elgg-head {
	margin-bottom: 10px;
}

/* ***************************************
	Widgets
*************************************** */
.elgg-widgets {
	float: right;
	min-height: 30px;
}
.elgg-widget-add-control {
	text-align: right;
	margin: 5px 5px 15px;
}
.elgg-widgets-add-panel {
	padding: 10px;
	margin: 0 5px 15px;
	background: #dedede;
	border: 2px solid #ccc;
}
<?php //@todo location-dependent style: make an extension of elgg-gallery ?>
.elgg-widgets-add-panel li {
	float: left;
	margin: 2px 10px;
	width: 200px;
	padding: 4px;
	background-color: #ccc;
	border: 2px solid #b0b0b0;
	font-weight: bold;
}
.elgg-widgets-add-panel li a {
	display: block;
}
.elgg-widgets-add-panel .elgg-state-available {
	color: #333;
	cursor: pointer;
}
.elgg-widgets-add-panel .elgg-state-available:hover {
	background-color: #bcbcbc;
}
.elgg-widgets-add-panel .elgg-state-unavailable {
	color: #888;
}

.elgg-module-widget {
	background-color: #c6c6c6;
	padding: 0px;
	margin: 0 5px 15px;
	position: relative;
}
.elgg-module-widget:hover {
	background-color: #6f6f6f;
}
.elgg-module-widget > .elgg-head {
	background-color: #eeeeee;
	height: 20px;
	overflow: hidden;
	border:1px #06F;
}
.elgg-module-widget > .elgg-head h3 {
	float: left;
	padding: 4px 5px 0 5px;
	color: #2f2f2f;
}
.elgg-module-widget.elgg-state-draggable .elgg-widget-handle {
	cursor: move;
}
a.elgg-widget-collapse-button {
	color: #c5c5c5;
}
a.elgg-widget-collapse-button:hover,
a.elgg-widget-collapsed:hover {
	color: #9d9d9d;
	text-decoration: none;
}
a.elgg-widget-collapse-button:before {
	content: "\25BC";
}
a.elgg-widget-collapsed:before {
	content: "\25BA";
}
.elgg-module-widget > .elgg-body {
	background-color: white;
	width: 100%;
	overflow: hidden;
	border-top: 0px solid #dedede;
}
.elgg-widget-edit {
	display: none;
	width: 96%;
	padding: 2%;
	border-bottom: 0px solid #dedede;
	background-color: #f9f9f9;
}
.elgg-widget-content {
	padding: 10px;
}
.elgg-widget-placeholder {
	border: 0px dashed #dedede;
	margin-bottom: 15px;
}