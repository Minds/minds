<?php
/**
 * Simplecache view for fancybox2 JS
 *
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$path = elgg_get_config('path');
$f_path = "{$path}mod/archive/vendors/jquery-fancybox2/jquery.fancybox.pack.js";
$b_path = "{$path}mod/archive/vendors/jquery-fancybox2/helpers/jquery.fancybox-buttons.js";
$t_path = "{$path}mod/archive/vendors/jquery-fancybox2/helpers/jquery.fancybox-thumbs.js";

include $f_path;
include $b_path;
include $t_path;