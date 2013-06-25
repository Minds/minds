elgg.provide('elgg.topbar');

elgg.topbar.init = function() {
	// clicking the menu item always toggles the associated dropdown menu
	$('.elgg-menu-topbar .elgg-menu-parent').live('click', function(event) {
		$(this).toggleClass('elgg-menu-opened elgg-menu-closed').siblings().toggleClass('elgg-state-active');
		return false;
	});
	
	// also deactivate dropdown menus + menu items by clicking away
	$('body').live('click', function(event) {
		$('.elgg-menu-topbar .elgg-child-menu.elgg-state-active').each(function() {
			if ($(this).has(event.target).length == 0 && !$(this).is(event.target)) {
				$(this).removeClass('elgg-state-active')
					.siblings().removeClass('elgg-menu-opened').addClass('elgg-menu-closed');
			}
		});
	});
};

elgg.register_hook_handler('init', 'system', elgg.topbar.init);