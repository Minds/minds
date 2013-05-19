<?php

echo '<div class="sidebar-footer">';

echo '<div class="logo"> &copy; Minds 2012-2013 <!--[if lte IE 8]><span style="filter: FlipH; -ms-filter: "FlipH"; display: inline-block;"><![endif]-->
<span style="-moz-transform: scaleX(-1); -o-transform: scaleX(-1); -webkit-transform: scaleX(-1); transform: scaleX(-1); display: inline-block;">
    &copy;
</span> <!--[if lte IE 8]></span><![endif]--> <br/> Content created and shared here is <a href="' . elgg_get_site_url() . 'licenses">free for the world</a></div>';

echo elgg_view_menu('footer', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));

echo '</div>';
