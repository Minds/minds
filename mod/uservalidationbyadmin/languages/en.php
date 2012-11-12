<?php
/**
 * Email user validation plugin language pack.
 *
 * @package Elgg.Core.Plugin
 * @subpackage Elgguservalidationbyadmin
 */

$english = array(
	'admin:users:unvalidated' => 'Unvalidated',
	
	'email:validate:subject' => "%s is requesting validation of account for %s!",
	'email:validate:body' => "Hello %s,

A user named %s is requesting validation of their account by you. 

Geolocation details of the user is
IP address: %s
Probable location: %s

You can validate their account by clicking on the link below:

%s

If you can't click on the link, copy and paste it to your browser manually.

%s
%s
",

	'user:validate:subject' => "Welcome to Minds!",
	'user:validate:body' => "Hello %s,

Welcome to Minds!  Your account is now activated.  We hope you will setup your channel and create some media to share with the world.  Post your ideas to get voted into action!

You can now access the site with:

Username : %s
Password : the one you provided during registration

%s
%s
",

	'email:confirm:success' => "The user account is now validated",
	'email:confirm:fail' => "The user account could not be validated...",

	'uservalidationbyadmin:registerok' => "Welcome!  You will receive an email in the near future enabling your account.",
	'uservalidationbyadmin:login:fail' => "Your account is not validated so the log in attempt failed. Please be patient until we can validate your account.",

	'uservalidationbyadmin:admin:no_unvalidated_users' => 'No unvalidated users.',

	'uservalidationbyadmin:admin:unvalidated' => 'Unvalidated',
	'uservalidationbyadmin:admin:user_created' => 'Registered %s',
	'uservalidationbyadmin:admin:resend_validation' => 'Resend validation',
	'uservalidationbyadmin:admin:validate' => 'Validate',
	'uservalidationbyadmin:admin:delete' => 'Delete',
	'uservalidationbyadmin:confirm_validate_user' => 'Validate %s?',
	'uservalidationbyadmin:confirm_resend_validation' => 'Resend validation email to %s?',
	'uservalidationbyadmin:confirm_delete' => 'Delete %s?',
	'uservalidationbyadmin:confirm_validate_checked' => 'Validate checked users?',
	'uservalidationbyadmin:confirm_resend_validation_checked' => 'Resend validation to checked users?',
	'uservalidationbyadmin:confirm_delete_checked' => 'Delete checked users?',
	'uservalidationbyadmin:check_all' => 'All',

	'uservalidationbyadmin:errors:unknown_users' => 'Unknown users',
	'uservalidationbyadmin:errors:could_not_validate_user' => 'Could not validate user.',
	'uservalidationbyadmin:errors:could_not_validate_users' => 'Could not validate all checked users.',
	'uservalidationbyadmin:errors:could_not_delete_user' => 'Could not delete user.',
	'uservalidationbyadmin:errors:could_not_delete_users' => 'Could not delete all checked users.',
	'uservalidationbyadmin:errors:could_not_resend_validation' => 'Could not resend validation request.',
	'uservalidationbyadmin:errors:could_not_resend_validations' => 'Could not resend all validation requests to checked users.',

	'uservalidationbyadmin:messages:validated_user' => 'User validated.',
	'uservalidationbyadmin:messages:validated_users' => 'All checked users validated.',
	'uservalidationbyadmin:messages:deleted_user' => 'User deleted.',
	'uservalidationbyadmin:messages:deleted_users' => 'All checked users deleted.',
	'uservalidationbyadmin:messages:resent_validation' => 'Validation request resent.',
	'uservalidationbyadmin:messages:resent_validations' => 'Validation requests resent to all checked users.'

);

add_translation("en", $english);