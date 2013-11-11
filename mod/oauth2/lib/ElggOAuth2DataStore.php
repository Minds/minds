<?php

/**
 * Elgg OAuth2 storage class
 *
 * @author Billy Gunn (billy@arckinteractive.com)
 * @copyright Minds.com 2013
 * @link http://minds.com
 */
class ElggOAuth2DataStore implements OAuth2_Storage_AuthorizationCodeInterface,
    OAuth2_Storage_AccessTokenInterface, OAuth2_Storage_ClientCredentialsInterface,
    OAuth2_Storage_UserCredentialsInterface, OAuth2_Storage_RefreshTokenInterface, OAuth2_Storage_JWTBearerInterface
{

    public function __construct() 
    { 

    }

    /**
     * Returns options for querying Elgg entities by the client_id metadata
     *
     * @param  string  $client_id
     * @return array
     */
    public function getClientOptions($client_id) 
    {
        $options = array(
            'type'    => 'object',
            'subtype' => 'oauth2_client',
            'attrs' => array('namespace' => 'oauth2_client:'.$client_id),
            'limit' => 1
        );

        return $options;
    }

    /**
     * Returns options for querying Elgg entities by the token metadata
     *
     * @param  string  $client_id
     * @return array
     */
    public function getRefreshTokenOptions($token) 
    {
        $options = array(
            'type'    => 'object',
            'subtype' => 'oauth2_refresh_token',
            'attrs' => array('namespace' => 'oauth2_refresh_token:'.$token),
            'limit' => 1
        );

        return $options;
    }

    /**
     * Returns options for querying Elgg entities by the token metadata
     *
     * @param  string  $client_id
     * @return array
     */
    public function getAccessTokenOptions($token) 
    {
        $options = array(
            'type'    => 'object',
            'subtype' => 'oauth2_access_token',
            'attrs' => array('namespace' => 'oauth2_access_token:'.$token),
            'limit' => 1
        );

        return $options;
    }

    /**
     * Returns options for querying Elgg entities by the auth code metadata
     *
     * @param  string  $client_id
     * @return array
     */
    public function getAuthCodeOptions($code) 
    {
        $options = array(
            'type'    => 'object',
            'subtype' => 'oauth2_auth_code',
            'attrs' => array('namespace' => 'oauth2_auth_code:'.$code),
            'limit' => 1
        );

        return $options;
    }

    /**
     * Make sure that the client credentials is valid.
     *
     * @param $client_id   Client identifier
     * @param $client_secret  (optional) If a secret is required, check that they've given the right one.
     *
     * @return  bool  TRUE if the client credentials are valid, and MUST return FALSE if it isn't.
     * @endcode
     *
     * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-3.1
     *
     * @ingroup oauth2_section_3
     */
    public function checkClientCredentials($client_id, $client_secret = null)
    {
        $client = get_entity($client_id,'object');

        if ($client) {
            return $client->client_secret == $client_secret;
        }

        return false;
    }

    public function getClientDetails($client_id)
    {

        $access = elgg_get_ignore_access();
        elgg_set_ignore_access(true);

        $client = get_entity($client_id,'object');

        elgg_set_ignore_access($access);

        if ($client) {
            return array(
                'client_id'     => $client->client_id,
                'client_secret' => $client->client_secret,
                'entity'        => $client,
            );
        }
    }

    /**
     * Check restricted grant types of corresponding client identifier.
     *
     * @param $client_id   Client identifier
     * @param $grant_type  Grant type
     *
     * @return bool  TRUE if the grant type is supported by this client identifier, FALSE if it isn't.
     *
     * @ingroup oauth2_section_4
     */
    public function checkRestrictedGrantType($client_id, $grant_type)
    {
        $details = $this->getClientDetails($client_id);
        if (isset($details->grant_types)) {
            return in_array($grant_type, unserialize($details->grant_types));
        }

        // If grant_types are not defined, then none are restricted
        return true;
    }

    /**
     * Look up the supplied oauth_token from storage.
     *
     * We need to retrieve access token data as we create and verify tokens.
     *
     * Returns an associative array as below, and return NULL if the supplied oauth_token is invalid:
     *  - client_id: Stored client identifier.
     *  - expires: Stored expiration in unix timestamp.
     *  - scope: (optional) Stored scope values in space-separated string.
     *
     * @param  string $token  Token to check.
     * @return array
     *
     * @ingroup oauth2_section_7
     */
    public function getAccessToken($token)
    {
        $results = elgg_get_entities($this->getAccessTokenOptions($token));

        if (!empty($results)) {
            return array(
                'access_token' => $results[0]->access_token,
                'client_id'    => $results[0]->client_id,
                'user_id'      => $results[0]->owner_guid,
                'expires'      => $results[0]->expires,
                'scope'        => $results[0]->scope,
                'entity'       => $results[0],
            );
        }

        return false;
    }


    /**
     * Store the supplied access token values to storage.
     *
     * We need to store access token data as we create and verify tokens.
     *
     * @param string  $token      Token to be stored.
     * @param string  $client_id  Client identifier to be stored.
     * @param integer $user_id    User identifier to be stored.
     * @param integer $expires    Expiration to be stored.
     * @param string  $scope      (optional) Scopes to be stored in space-separated string.
     *
     * @ingroup oauth2_section_4
     */
    public function setAccessToken($token, $client_id, $user_id, $expires, $scope = null)
    {
        if (!$access_token = $this->getAccessToken($token)) {

            // Create the token entity
            $entity                  = new ElggObject();
            $entity->subtype         = 'oauth2_access_token';
            $entity->owner_guid      = $user_id;
            $entity->container_guid  = $this->getClientDetails($client_id)->guid;
            $entity->access_id       = ACCESS_PRIVATE;

            $entity->save();
			
        } else {
            $entity = $access_token['entity'];
        }

        $entity->access_token = $token;
        $entity->client_id    = $client_id;
        $entity->expires      = $expires;        
        $entity->scope        = $scope;
		
		//add into the indexes
		db_insert('oauth2_access_token:'.$token, array('type'=>'entities_by_time', $entity->guid => $entity->guid));

        return $this->getAccessToken($token);
    }


    /**
     * Fetch authorization code data (probably the most common grant type).
     *
     * Retrieve the stored data for the given authorization code.
     *
     * Required for OAuth2::GRANT_TYPE_AUTH_CODE.
     *
     * @param string  $code Authorization code to be check with.
     *
     * @return
     * An associative array as below, and NULL if the code is invalid:
     * - client_id: Stored client identifier.
     * - redirect_uri: Stored redirect URI.
     * - expires: Stored expiration in unix timestamp.
     * - scope: (optional) Stored scope values in space-separated string.
     *
     * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-4.1
     *
     * @ingroup oauth2_section_4
     */
    public function getAuthorizationCode($code)
    {
        $results = elgg_get_entities($this->getAuthCodeOptions($token));

        if (!empty($results)) {
            return array(
                'authorization_code' => $results[0]->authorization_code,
                'client_id'          => $results[0]->client_id,
                'user_id'            => $results[0]->owner_guid,
                'redirect_uri'       => $results[0]->redirect_uri,
                'expires'            => $results[0]->expires,
                'scope'              => $results[0]->scope,
                'entity'             => $results[0],
            );
        }

        return false;
    }


    /**
     * Take the provided authorization code values and store them somewhere.
     *
     * This function should be the storage counterpart to getAuthCode().
     *
     * If storage fails for some reason, we're not currently checking for
     * any sort of success/failure, so you should bail out of the script
     * and provide a descriptive fail message.
     *
     * Required for OAuth2::GRANT_TYPE_AUTH_CODE.
     *
     * @param $code Authorization code to be stored.
     * @param $client_id Client identifier to be stored.
     * @param $user_id User identifier to be stored.
     * @param $redirect_uri Redirect URI to be stored.
     * @param $expires Expiration to be stored.
     * @param $scope (optional) Scopes to be stored in space-separated string.
     *
     * @ingroup oauth2_section_4
     */
    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null)
    {

        if (!$auth_code = $this->getAuthorizationCode($code)) {

            // Create the token entity
            $entity                  = new ElggObject();
            $entity->subtype         = 'oauth2_auth_code';
            $entity->owner_guid      = $user_id;
            $entity->container_guid  = $this->getClientDetails($client_id)->guid;
            $entity->access_id       = ACCESS_PRIVATE;

            $entity->save();

        } else {
            $entity = $auth_code['entity'];
        }

        $entity->authorization_code = $code;
        $entity->client_id          = $client_id;
        $entity->redirect_uri       = $redirect_uri;
        $entity->expires            = $expires;
        $entity->scope              = $scope;
		
		db_insert('oauth2_auth_code:'.$code, array('type'=>'entities_by_time', $entity->guid => $entity->guid));

        return $this->getAuthorizationCode($code);
    }


    /**
     * once an Authorization Code is used, it must be exipired
     *
     * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-31#section-4.1.2
     *
     *    The client MUST NOT use the authorization code
     *    more than once.  If an authorization code is used more than
     *    once, the authorization server MUST deny the request and SHOULD
     *    revoke (when possible) all tokens previously issued based on
     *    that authorization code
     *
     */
    public function expireAuthorizationCode($code)
    {

        $results = elgg_get_entities($this->getAuthCodeOptions($token));

        if (!empty($results)) {
        	db_remove('oauth2_auth_code:'.$code, 'entities_by_time');
            return $results[0]->delete();
        }

        return false;
    }

    /**
     * Grant access tokens for basic user credentials.
     *
     * Check the supplied username and password for validity.
     *
     * You can also use the $client_id param to do any checks required based
     * on a client, if you need that.
     *
     * Required for OAuth2::GRANT_TYPE_USER_CREDENTIALS.
     *
     * @param $username
     * Username to be check with.
     * @param $password
     * Password to be check with.
     *
     * @return TRUE if the username and password are valid, and FALSE if it isn't.
     * Moreover, if the username and password are valid, and you want to
     * verify the scope of a user's access, return an associative array
     * with the scope values as below. We'll check the scope you provide
     * against the requested scope before providing an access token:
     * @code
     * return array(
     * 'scope' => <stored scope values (space-separated string)>,
     * );
     * @endcode
     *
     * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-4.3
     *
     * @ingroup oauth2_section_4
     */
    public function checkUserCredentials($username, $password)
    {
        $result = elgg_authenticate($username, $password);

        if ($result !== true) {
            return false;
        }

        return true;
    }

    public function getUserDetails($username=null)
    {
        return $this->getUser($username);
    }


    /**
     * Grant refresh access tokens.
     *
     * Retrieve the stored data for the given refresh token.
     *
     * Required for OAuth2::GRANT_TYPE_REFRESH_TOKEN.
     *
     * @param $refresh_token
     * Refresh token to be check with.
     *
     * @return
     * An associative array as below, and NULL if the refresh_token is
     * invalid:
     * - client_id: Stored client identifier.
     * - expires: Stored expiration unix timestamp.
     * - scope: (optional) Stored scope values in space-separated string.
     *
     * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-6
     *
     * @ingroup oauth2_section_6
     */
    public function getRefreshToken($token)
    {
        $results = elgg_get_entities($this->getRefreshTokenOptions($token));

        if (!empty($results)) {
            return array(
                'refresh_token' => $results[0]->refresh_token,
                'client_id'     => $results[0]->client_id,
                'user_id'       => $results[0]->owner_guid,
                'expires'       => $results[0]->expires,
                'scope'         => $results[0]->scope
            );
        }

        return false;
    }

    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
    {

        $client = $this->getClientDetails($client_id);

        // Create the token entity
        $token                  = new ElggObject();
        $token->subtype         = 'oauth2_refresh_token';
        $token->owner_guid      = $user_id;
        $token->container_guid  = $client['entity']->guid;
        $token->access_id       = ACCESS_PRIVATE;

        $token->save();

        $token->refresh_token = $refresh_token;
        $token->client_id     = $client_id;
        $token->expires       = $expires;
        $token->scope         = $scope;

		db_insert('oauth2_refresh_token:'.$refresh_token, array('type'=>'entities_by_time', $token->guid => $token->guid));

        return array(
            'refresh_token' => $token->refresh_token,
            'client_id'     => $token->client_id,
            'user_id'       => $token->owner_guid,
            'expires'       => $token->expires,
            'scope'         => $token->scope
        );
    }

    public function unsetRefreshToken($refresh_token)
    {
        $results = $this->getRefreshToken($refresh_token);

        if (!empty($results)) {
        	db_remove('oauth_refresh_token:'.$refresh_token,'entities_by_time');
            return $results[0]->delete();
        }

        return false;
    }

    public function getUser($username=null)
    {

        if (!$username) {
            $user = elgg_get_logged_in_user_entity();
        } else if (!$user = get_user_by_username($username)) {

            $users = get_user_by_email($username);

            if (empty($users)) {
                return false;
            }
        
            $user = $users[0];
        }

        return array('user_id' => $user->guid, 'username' => $user->username);
    }


    /**
     * NOT IMPLEMENTED
     *
     * Get the public key associated with a client_id
     *
     * @param $client_id Client identifier to be check with.
     *
     * @return STRING Return the public key for the client_id if it exists, and MUST return FALSE if it doesn't.
     * @endcode
     */
    public function getClientKey($client_id, $subject)
    {
        
    }
}
