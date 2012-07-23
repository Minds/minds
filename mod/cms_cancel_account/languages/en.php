<?php
/**
 * cms_cancel_account English language file
 */

$english = array(
	'cms_cancel_account:cancelaccount' => 'Cancel account',
	'cms_cancel_account:askreason' => 'Explain the reason of your account cancellation:',
	'cms_cancel_account:button:request' => 'Request cancellation',
	'cms_cancel_account:invalidrequest' => 'Another pending cancellation request already exists for this account',
	'cms_cancel_account:successfulrequestsubject' => 'Request cancellation successfully sent',
	'cms_cancel_account:successfulrequestmessage' => '%s,
	
Your request cancellation has been successfully sent.
	
As soon as your account is cancelled, you will receive a notification by email.',
	'admin:users:cancellations' => 'Cancellation Requests',
	'cms_cancel_account:reason' => 'Reason: ',
	'cms_cancel_account:check_all' => 'All',
	'cms_cancel_account:admin:delete' => 'Delete',
	'cms_cancel_account:confirm_delete_checked' => 'Delete checked users?',
	'cms_cancel_account:confirm_delete' => 'Delete %s?',
	'cms_cancel_account:errors:unknown_users' => 'Unknown users',

	'cms_cancel_account:messages:deleted_user' => 'User deleted.',
	'cms_cancel_account:messages:deleted_users' => 'All checked users deleted.',
	'cms_cancel_account:errors:could_not_delete_user' => 'Could not delete user.',
	'cms_cancel_account:errors:could_not_delete_users' => 'Could not delete all checked users.',
	'cms_cancel_account:admin:no_requests' => 'No cancellation requests.',

	'cms_cancel_account:mail:failedcancellationsubject' => 'Failed cancellation account',
	'cms_cancel_account:mail:failedcancellationmessage' => '%s,
A problem occurred while cancelling your account.

Please contact with the webmaster.',
	'cms_cancel_account:mail:successfulcancellationsubject' => 'Successful cancellation account',
	'cms_cancel_account:mail:successfulcancellationmessage' => '%s,
Your account has been successfully cancelled.

Thank you for having been part of our social network.

Whenever you want to sign up again, you will find us in %s.',

);

add_translation("en", $english);