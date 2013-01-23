<?php

/**
 * Elgg OAuth2 storage class
 *
 * @author Billy Gunn
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
            'metadata_name_value_pairs' => array(
                'name' => 'client_id',
                'value' => $client_id,
                'operand' => '=' 
            ),  
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
            'metadata_name_value_pairs' => array(
                'name' => 'refresh_token',
                'value' => $token,
                'operand' => '=' 
            ),  
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
            'metadata_name_value_pairs' => array(
                'name' => 'access_token',
                'value' => $token,
                'operand' => '=' 
            ),  
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
            'metadata_name_value_pairs' => array(
                'name' => 'auth_code',
                'value' => $code,
                'operand' => '=' 
            ),  
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
        $results = elgg_get_entities_from_metadata($this->getClientOptions($client_id));

        if (!empty($results)) {
            return $results[0]->client_secret == $client_secret;
        }

        return false;
    }

    public function getClientDetails($client_id)
    {
        $results = elgg_get_entities_from_metadata($this->getClientOptions($client_id));

        if (!empty($results)) {
            return $results[0];
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
        $results = elgg_get_entities_from_metadata($this->getAccessTokenOptions($token));

        if (!empty($results)) {
            return array(
                'access_token' => $results->access_token,
                'client_id'    => $results->client_id,
                'user_id'      => $results->user_id,
                'expires'      => $results->expires,
                'scope'        => $results->scope
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

        if (!$token = $this->getAccessToken($token)) {

            // Create the token entity
            $token                  = new ElggObject();
            $token->owner_guid      = $user_id;
            $token->container_guid  = $this->getClientDetails($client_id)->guid;
            $token->access_id       = ACCESS_PRIVATE;

            $token->save();
        }

        $token->access_token = $token;
        $token->client_id    = $client_id;
        $token->expires      = $expires;        
        $token->scope        = $scope;

        return $token->guid;
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
        $results = elgg_get_entities_from_metadata($this->getAuthCodeOptions($token));

        if (!empty($results)) {
            return array(
                'authorization_code' => $results->authorization_code,
                'client_id'          => $results->client_id,
                'user_id'            => $results->owner_guid,
                'redirect_uri'       => $results->redirect_uri,
                'expires'            => $results->expires,
                'scope'              => $results->scope
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

        if (!$token = $this->getAuthorizationCode($code)) {

            // Create the token entity
            $code                  = new ElggObject();
            $code->owner_guid      = $user_id;
            $code->container_guid  = $this->getClientDetails($client_id)->guid;
            $code->access_id       = ACCESS_PRIVATE;

            $code->save();
        }

        $code->authorization_code = $code;
        $code->client_id          = $client_id;
        $code->redirect_uri       = $redirect_uri;
        $code->expires            = $expires;
        $code->scope              = $scope;

        return $code->guid;
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

        $results = elgg_get_entities_from_metadata($this->getAuthCodeOptions($token));

        if (!empty($results)) {
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

    public function getUserDetails($username)
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
        $results = elgg_get_entities_from_metadata($this->getRefreshTokenOptions($token));

        if (!empty($results)) {
            return array(
                'refresh_token' => $results->refresh_token,
                'client_id'     => $results->client_id,
                'user_id'       => $results->user_id,
                'expires'       => $results->expires,
                'scope'         => $results->scope
            );
        }

        return false;
    }

    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
    {
        // convert expires to datestring
        $expires = date('Y-m-d H:i:s', $expires);

        $stmt = $this->db->prepare(sprintf('INSERT INTO %s (refresh_token, client_id, user_id, expires, scope) VALUES (:refresh_token, :client_id, :user_id, :expires, :scope)', $this->config['refresh_token_table']));

        return $stmt->execute(compact('refresh_token', 'client_id', 'user_id', 'expires', 'scope'));
    }

    public function unsetRefreshToken($refresh_token)
    {
        $stmt = $this->db->prepare(sprintf('DELETE FROM %s WHERE refresh_token = :refresh_token', $this->config['refresh_token_table']));

        return $stmt->execute(compact('refresh_token'));
    }

    // plaintext passwords are bad!  Override this for your application
    protected function checkPassword($user, $password)
    {
        return $user['password'] == $password;
    }

    public function getUser($username)
    {
        $stmt = $this->db->prepare($sql = sprintf('SELECT * from %s where username=:username', $this->config['user_table']));
        $stmt->execute(array('username' => $username));
        return $stmt->fetch();
    }

    public function setUser($username, $password, $firstName = null, $lastName = null)
    {
        // if it exists, update it.
        if ($this->getUser($username)) {
            $stmt = $this->db->prepare($sql = sprintf('UPDATE %s SET username=:username, password=:password, first_name=:firstName, last_name=:lastName where username=:username', $this->config['user_table']));
        } else {
            $stmt = $this->db->prepare(sprintf('INSERT INTO %s (username, password, first_name, last_name) VALUES (:username, :password, :firstName, :lastName)', $this->config['user_table']));
        }
        return $stmt->execute(compact('username', 'password', 'firstName', 'lastName'));
    }

    public function getClientKey($client_id, $subject)
    {
        $stmt = $this->db->prepare($sql = sprintf('SELECT public_key from %s where client_id=:client_id AND subject=:subject', $this->config['jwt_table']));

        $stmt->execute(array('client_id' => $client_id, 'subject' => $subject));
        return $stmt->fetch();
    }
}
