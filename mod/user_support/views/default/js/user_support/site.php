<?php
?>
//<script>
elgg.provide("elgg.user_support");

elgg.user_support.init = function() {
	$('#user-support-help-center-search').live({
		focus: function() {
			if ($(this).val() === $(this).attr("title")) {
				$(this).val("");
			}
		},
		blur: function() {
			if ($(this).val() == "") {
				$(this).val($(this).attr("title"));
			}
		},
		keypress: function(event) {
			if (event.which == 13) {
				$('#user_support_help_search_result_wrapper').hide();
				
				elgg.ajax("user_support/search/?q=" + $(this).val(), function(data) {
					$('#user_support_help_search_result_wrapper').html(data).show();
				});
			}
		}
	});
}

elgg.register_hook_handler('init', 'system', elgg.user_support.init);