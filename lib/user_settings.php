<?php
/**
 * Elgg user settings functions.
 * Functions for adding and manipulating options on the user settings panel.
 *
 * @package Elgg.Core
 * @subpackage Settings.User
 */

/**
 * Saves user settings.
 *
 * @todo this assumes settings are coming in on a GET/POST request
 *
 * @note This is a handler for the 'usersettings:save', 'user' plugin hook
 *
 * @return void
 * @access private
 */
function users_settings_save() {
	elgg_set_user_language();
	elgg_set_user_password();
	elgg_set_user_default_access();
	elgg_set_user_name();
	elgg_set_user_email();
}

/**
 * Set a user's password
 *
 * @return bool
 * @since 1.8.0
 * @access private
 */
function elgg_set_user_password() {
	$current_password = get_input('current_password', null, false);
	$password = get_input('password', null, false);
	$password2 = get_input('password2', null, false);
	$user_guid = get_input('guid');

	if (!$user_guid) {
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_entity($user_guid,'user');
	}

    if($user && !$user->canEdit()){
        return false;
    }

	if ($user && $password) {
		// let admin user change anyone's password without knowing it except his own.
		if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid == elgg_get_logged_in_user_guid()) {
			$credentials = array(
				'username' => $user->username,
				'password' => $current_password
			);

			try {
				pam_auth_userpass($credentials);
			} catch (LoginException $e) {
				register_error(elgg_echo('LoginException:ChangePasswordFailure'));
				return false;
			}
		}

		try {
			$result = validate_password($password);
		} catch (RegistrationException $e) {
			register_error($e->getMessage());
			return false;
		}

		if ($result) {
			if ($password == $password2) {
				$user->salt = generate_random_cleartext_password(); // Reset the salt

				$algo = 'sha256';
				if(strlen($user->password) == 32)
					$algo = 'md5';

                $user->password = generate_user_password($user, $password, $algo);
                $user->override_password = true;
                error_log('commence and override');
				if ($user->save()) {
					system_message(elgg_echo('user:password:success'));
					return true;
				} else {
					register_error(elgg_echo('user:password:fail'));
				}
			} else {
				register_error(elgg_echo('user:password:fail:notsame'));
			}
		} else {
			register_error(elgg_echo('user:password:fail:tooshort'));
		}
	} else {
		// no change
		return null;
	}

	return false;
}

/**
 * Set a user's default access level
 *
 * @return bool
 * @since 1.8.0
 * @access private
 */
function elgg_set_user_default_access() {

	if (!elgg_get_config('allow_user_default_access')) {
		return false;
	}

	$default_access = get_input('default_access');
	$user_id = get_input('guid');

	if (!$user_id) {
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_entity($user_id,'user');
	}

	if ($user) {
		$current_default_access = $user->getPrivateSetting('elgg_default_access');
		if ($default_access !== $current_default_access) {
			if ($user->setPrivateSetting('elgg_default_access', $default_access)) {
				system_message(elgg_echo('user:default_access:success'));
				return true;
			} else {
				register_error(elgg_echo('user:default_access:fail'));
			}
		} else {
			// no change
			return null;
		}
	} else {
		register_error(elgg_echo('user:default_access:fail'));
	}

	return false;
}
