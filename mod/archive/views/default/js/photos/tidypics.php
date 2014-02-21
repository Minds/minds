<?php
/**
 * Tidypics General JS
 * @todo rename this
 *
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

?>
//<script>
elgg.provide('elgg.tidypics');

// General tidypics init
elgg.tidypics.init = function() {
	// @todo move sort stuff
	// $("#tidypics-sort").sortable({
	// 	opacity: 0.7,
	// 	revert: true,
	// 	scroll: true
	// });
};

elgg.register_hook_handler('init', 'system', elgg.tidypics.init);