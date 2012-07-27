<?php
$base_url = elgg_get_site_url();
$graphics_url = $base_url . 'mod/hypeAlive/graphics/';
?>

.elgg-module-livesearch {
        font-size:0.8em;
        line-height:1.4em;
        width:260px;
        margin-bottom:0;
}

.elgg-module-livesearch > .elgg-head {
	background: #666;
	padding: 3px;
	margin-bottom: 3px;
}

.elgg-module-livesearch > .elgg-head h3 {
	color: white;
}

.elgg-module-livesearch > .elgg-body > .elgg-list {
        margin:0;
}

.elgg-module-livesearch .elgg-image-block {
        padding:0;
}
.ui-autocomplete {
        overflow-y:auto;
}

.elgg-search input[type="text"]:focus.ui-autocomplete-loading {
    background:white url(<?php echo $base_url ?>mod/hypeFramework/graphics/loader/indicator.gif) no-repeat; 
    background-position:2px 50%;
}

.ac_results li {
font-size:0.9em;
line-height:12px;
padding:7px;
cursor:pointer;
}

.ac_res_subtype {
padding:3px;
}
