<?php
/**
 * Fancybox2 Consolidated CSS
 *
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$fb_path = elgg_get_site_url() . "mod/archive/vendors/jquery-fancybox2/";
$fb_helpers_path = elgg_get_site_url() . "mod/archive/vendors/jquery-fancybox2/helpers/";
?>
/*! fancyBox v2.1.3 fancyapps.com | fancyapps.com/fancybox/#license */
.fancybox2-wrap,
.fancybox2-skin,
.fancybox2-outer,
.fancybox2-inner,
.fancybox2-image,
.fancybox2-wrap iframe,
.fancybox2-wrap object,
.fancybox2-nav,
.fancybox2-nav span,
.fancybox2-tmp
{
	padding: 0;
	margin: 0;
	border: 0;
	outline: none;
	vertical-align: top;
}

.fancybox2-wrap {
	position: absolute;
	top: 0;
	left: 0;
	z-index: 8020;
}

.fancybox2-skin {
	position: relative;
	background: #f9f9f9;
	color: #444;
	text-shadow: none;
	-webkit-border-radius: 4px;
	   -moz-border-radius: 4px;
	        border-radius: 4px;
}

.fancybox2-opened {
	z-index: 8030;
}

.fancybox2-opened .fancybox2-skin {
	-webkit-box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
	   -moz-box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
	        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
}

.fancybox2-outer, .fancybox2-inner {
	position: relative;
}

.fancybox2-inner {
	overflow: hidden;
}

.fancybox2-type-iframe .fancybox2-inner {
	-webkit-overflow-scrolling: touch;
}

.fancybox2-error {
	color: #444;
	font: 14px/20px "Helvetica Neue",Helvetica,Arial,sans-serif;
	margin: 0;
	padding: 15px;
	white-space: nowrap;
}

.fancybox2-image, .fancybox2-iframe {
	display: block;
	width: 100%;
	height: 100%;
}

.fancybox2-image {
	max-width: 100%;
	max-height: 100%;
}

#fancybox2-loading, .fancybox2-close, .fancybox2-prev span, .fancybox2-next span {
	background-image: url('<?php echo $fb_path; ?>fancybox_sprite.png');
}

#fancybox2-loading {
	position: fixed;
	top: 50%;
	left: 50%;
	margin-top: -22px;
	margin-left: -22px;
	background-position: 0 -108px;
	opacity: 0.8;
	cursor: pointer;
	z-index: 8060;
}

#fancybox2-loading div {
	width: 44px;
	height: 44px;
	background: url('<?php echo $fb_path; ?>fancybox_loading.gif') center center no-repeat;
}

.fancybox2-close {
	position: absolute;
	top: -18px;
	right: -18px;
	width: 36px;
	height: 36px;
	cursor: pointer;
	z-index: 8040;
}

.fancybox2-nav {
	position: absolute;
	top: 0;
	width: 40%;
	height: 100%;
	cursor: pointer;
	text-decoration: none;
	background: transparent url('<?php echo $fb_path; ?>blank.gif'); /* helps IE */
	-webkit-tap-highlight-color: rgba(0,0,0,0);
	z-index: 8040;
}

.fancybox2-prev {
	left: 0;
}

.fancybox2-next {
	right: 0;
}

.fancybox2-nav span {
	position: absolute;
	top: 50%;
	width: 36px;
	height: 34px;
	margin-top: -18px;
	cursor: pointer;
	z-index: 8040;
	visibility: hidden;
}

.fancybox2-prev span {
	left: 10px;
	background-position: 0 -36px;
}

.fancybox2-next span {
	right: 10px;
	background-position: 0 -72px;
}

.fancybox2-nav:hover span {
	visibility: visible;
}

.fancybox2-tmp {
	position: absolute;
	top: -99999px;
	left: -99999px;
	visibility: hidden;
	max-width: 99999px;
	max-height: 99999px;
	overflow: visible !important;
}

/* Overlay helper */

.fancybox2-lock {
	overflow: hidden;
}

.fancybox2-overlay {
	position: absolute;
	top: 0;
	left: 0;
	overflow: hidden;
	display: none;
	z-index: 9001;
	background: url('<?php echo $fb_path; ?>fancybox_overlay.png');
}

.fancybox2-overlay-fixed {
	position: fixed;
	bottom: 0;
	right: 0;
}

.fancybox2-lock .fancybox2-overlay {
	overflow: auto;
	overflow-y: scroll;
}

/* Title helper */

.fancybox2-title {
	visibility: hidden;
	font: normal 13px/20px "Helvetica Neue",Helvetica,Arial,sans-serif;
	position: relative;
	text-shadow: none;
	z-index: 8050;
}

.fancybox2-opened .fancybox2-title {
	visibility: visible;
}

.fancybox2-title-float-wrap {
	position: absolute;
	bottom: 0;
	right: 50%;
	margin-bottom: -35px;
	z-index: 8050;
	text-align: center;
}

.fancybox2-title-float-wrap .child {
	display: inline-block;
	margin-right: -100%;
	padding: 2px 20px;
	background: transparent; /* Fallback for web browsers that doesn't support RGBa */
	background: rgba(0, 0, 0, 0.8);
	-webkit-border-radius: 15px;
	   -moz-border-radius: 15px;
	        border-radius: 15px;
	text-shadow: 0 1px 2px #222;
	color: #FFF;
	font-weight: bold;
	line-height: 24px;
	white-space: nowrap;
}

.fancybox2-title-outside-wrap {
	position: relative;
	margin-top: 10px;
	color: #fff;
}

.fancybox2-title-inside-wrap {
	padding-top: 10px;
}

.fancybox2-title-over-wrap {
	position: absolute;
	bottom: 0;
	left: 0;
	color: #fff;
	padding: 10px;
	background: #000;
	background: rgba(0, 0, 0, .8);
}

/* Buttons helper */
#fancybox2-buttons {
	position: relative; /* Relative in tidypics-lightbox-footer */
	left: 0;
	width: 200px;
	margin-left: auto;
	margin-right: auto;
	z-index: 9001;
}

#fancybox2-buttons.top {
	top: 10px;
}

#fancybox2-buttons.bottom {
	bottom: 100px;
}

#fancybox2-buttons ul {
	display: block;
	width: 92px;
	height: 30px;
	margin: 0 auto;
	padding: 0;
	list-style: none;
	border: 1px solid #111;
	border-radius: 3px;
	-webkit-box-shadow: inset 0 0 0 1px rgba(255,255,255,.05);
	   -moz-box-shadow: inset 0 0 0 1px rgba(255,255,255,.05);
	        box-shadow: inset 0 0 0 1px rgba(255,255,255,.05);
	background: rgb(50,50,50);
	background: -moz-linear-gradient(top, rgb(68,68,68) 0%, rgb(52,52,52) 50%, rgb(41,41,41) 50%, rgb(51,51,51) 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgb(68,68,68)), color-stop(50%,rgb(52,52,52)), color-stop(50%,rgb(41,41,41)), color-stop(100%,rgb(51,51,51)));
	background: -webkit-linear-gradient(top, rgb(68,68,68) 0%,rgb(52,52,52) 50%,rgb(41,41,41) 50%,rgb(51,51,51) 100%);
	background: -o-linear-gradient(top, rgb(68,68,68) 0%,rgb(52,52,52) 50%,rgb(41,41,41) 50%,rgb(51,51,51) 100%);
	background: -ms-linear-gradient(top, rgb(68,68,68) 0%,rgb(52,52,52) 50%,rgb(41,41,41) 50%,rgb(51,51,51) 100%);
	background: linear-gradient(top, rgb(68,68,68) 0%,rgb(52,52,52) 50%,rgb(41,41,41) 50%,rgb(51,51,51) 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#444444', endColorstr='#222222',GradientType=0 );
}

#fancybox2-buttons ul li {
	float: left;
	margin: 0;
	padding: 0;
}

#fancybox2-buttons a {
	display: block;
	width: 30px;
	height: 30px;
	text-indent: -9999px;
	background-image: url('<?php echo $fb_helpers_path; ?>fancybox_buttons.png');
	background-repeat: no-repeat;
	outline: none;
	opacity: 0.8;
}

#fancybox2-buttons a:hover {
	opacity: 1;
}

#fancybox2-buttons a.btnPrev {
	background-position: 5px 0;
}

#fancybox2-buttons a.btnNext {
	background-position: -33px 0;
	border-right: 1px solid #3e3e3e;
}

#fancybox2-buttons a.btnPlay {
	background-position: 0 -30px;
}

#fancybox2-buttons a.btnPlayOn {
	background-position: -30px -30px;
}

#fancybox2-buttons a.btnToggle {
	background-position: 3px -60px;
	border-left: 1px solid #111;
	border-right: 1px solid #3e3e3e;
	width: 35px
}

#fancybox2-buttons a.btnToggleOn {
	background-position: -27px -60px;
}

#fancybox2-buttons a.btnClose {
	border-left: 1px solid #111;
	width: 35px;
	background-position: -56px 0px;
}

#fancybox2-buttons a.btnDisabled {
	opacity : 0.4;
	cursor: default;
}

/* Thumbs Helper */
#fancybox2-thumbs {
	position: relative; /* Relative in tidypics-lightbox-footer */
	left: 0;
	width: 100%;
	overflow: hidden;
	z-index: 9001;
}

#fancybox2-thumbs.bottom {
	bottom: 2px;
}

#fancybox2-thumbs.top {
	top: 2px;
}

#fancybox2-thumbs ul {
	position: relative;
	list-style: none;
	margin: 0;
	padding: 0;
}

#fancybox2-thumbs ul li {
	float: left;
	padding: 1px;
	opacity: 0.5;
}

#fancybox2-thumbs ul li.active {
	opacity: 0.75;
	padding: 0;
	border: 1px solid #fff;
}

#fancybox2-thumbs ul li:hover {
	opacity: 1;
}

#fancybox2-thumbs ul li a {
	display: block;
	position: relative;
	overflow: hidden;
	border: 1px solid #222;
	background: #111;
	outline: none;
}

#fancybox2-thumbs ul li img {
	display: block;
	position: relative;
	border: 0;
	padding: 0;
}