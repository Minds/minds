/**
 * 
 */
elgg.provide('elgg.autocomplete');

elgg.autocomplete.init = function() {
	$('.elgg-input-autocomplete').autocomplete({
		source: elgg.autocomplete.url, //gets set by input/autocomplete view
		minLength: 2,
		html: "html",
		select: function( event, ui ) { 
           window.location.href = ui.item.url;
       	},
	})
};

elgg.register_hook_handler('init', 'system', elgg.autocomplete.init);