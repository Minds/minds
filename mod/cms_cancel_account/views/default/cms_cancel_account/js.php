
elgg.provide('elgg.cms_cancel_account');

elgg.cms_cancel_account.init = function() {
	$('#cms_cancel_account-checkall').click(function() {
		var checked = $(this).attr('checked') == 'checked';
		$('#cms_cancel_account-form .elgg-body').find('input[type=checkbox]').attr('checked', checked);
	});

	$('.cms_cancel_account-submit').click(function(event) {
		var $form = $('#cms_cancel_account-form');
		event.preventDefault();

		// check if there are selected users
		if ($('#cms_cancel_account-form .elgg-body').find('input[type=checkbox]:checked').length < 1) {
			return false;
		}

		// confirmation
		if (!confirm($(this).attr('title'))) {
			return false;
		}

		$form.attr('action', $(this).attr('href')).submit();
	});
};

elgg.register_hook_handler('init', 'system', elgg.cms_cancel_account.init);
