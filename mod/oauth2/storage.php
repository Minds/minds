<?php
/**
 * OAuth2 custom storage object
 */
namespace minds\plugin\oauth2;

use OAuth2;
use Minds\Core\data;

class storage implements OAuth2\Storage\AccessTokenInterface, OAuth2\Storage\RefreshTokenInterface,
OAuth2\Storage\ClientCredentialsInterface, OAuth2\Storage\UserCredentialsInterface, OAuth2\Storage\AuthorizationCodeInterface {

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
     * 
     * DATA GET/SET Method
     */
    public function set($key, $values, $expires = NULL){
        $db = new Data\Call('entities');
        foreach($values as $k => $v)
            if($v === NULL)
                unset($values[$k]);

        return $db->insert($key, $values, $expires > 0 ? $expires - time(): NULL);
    }
    
    public function get($key){
        $db = new Data\Call('entities');
        return $db->getRow($key);
    }
    
    public function remove($key){
        $db = new Data\Call('entities');
        return $db->removeRow($key);
    }


    /**
     * 
     * CLIENT CREDENTIALS
     * 
     */
     
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
    public function checkClientCredentials($client_id, $client_secret = null){

        $client = new entities\client($client_id);
        
        if ($client) {
            return $client->client_secret == $client_secret;
        }

        return false;
    }
    
    public function getClientDetails($client_id){

        $access = elgg_get_ignore_access();
        elgg_set_ignore_access(true);

        $client = new entities\client($client_id);

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
     * Determine if the client is a "public" client, and therefore
     * does not require passing credentials for certain grant types
     *
     * @param $client_id
     * Client identifier to be check with.
     *
     * @return
     * TRUE if the client is public, and FALSE if it isn't.
     * @endcode
     *
     * @see http://tools.ietf.org/html/rfc6749#section-2.3
     * @see https://github.com/bshaffer/oauth2-server-php/issues/257
     *
     * @ingroup oauth2_section_2
     */
    public function isPublicClient($client_id){
        return true;   
    }


    /**
     * Get the scope associated with this client
     *
     * @return
     * STRING the space-delineated scope list for the specified client_id
     */
    public function getClientScope($client_id){
        return "";
    }

    /**
     * 
     * ACCESS TOKENS
     * 
     */
     
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
    public function getAccessToken($token){
        return $this->get($token);
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
    public function setAccessToken($token, $client_id, $user_id, $expires, $scope = null){
        return $this->set($token, array('client_id'=>$client_id, 'user_id' => $user_id, 'scope'=>$scope, 'expires'=>$expires), $expires > 0 ? $expires : NULL);
    }

    /**
     * 
     * REFRESH TOKENS
     * 
     */

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
     * - refresh_token: Refresh token identifier.
     * - client_id: Client identifier.
     * - user_id: User identifier.
     * - expires: Expiration unix timestamp, or 0 if the token doesn't expire.
     * - scope: (optional) Scope values in space-separated string.
     *
     * @see http://tools.ietf.org/html/rfc6749#section-6
     *
     * @ingroup oauth2_section_6
     */
    public function getRefreshToken($refresh_token){
        return $this->get($refresh_token);
    }
    
    /**
     * Take the provided refresh token values and store them somewhere.
     *
     * This function should be the storage counterpart to getRefreshToken().
     *
     * If storage fails for some reason, we're not currently checking for
     * any sort of success/failure, so you should bail out of the script
     * and provide a descriptive fail message.
     *
     * Required for OAuth2::GRANT_TYPE_REFRESH_TOKEN.
     *
     * @param $refresh_token
     * Refresh token to be stored.
     * @param $client_id
     * Client identifier to be stored.
     * @param $user_id
     * User identifier to be stored.
     * @param $expires
     * Expiration timestamp to be stored. 0 if the token doesn't expire.
     * @param $scope
     * (optional) Scopes to be stored in space-separated string.
     *
     * @ingroup oauth2_section_6
     */
    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null){
        return $this->set($refresh_token, array('client_id'=>$client_id, 'user_id'=>$user_id, 'expires'=>$expires, 'scope'=>$scope), $expires > 0 ? $expires : NULL);
    }
    
    /**
     * Expire a used refresh token.
     *
     * This is not explicitly required in the spec, but is almost implied.
     * After granting a new refresh token, the old one is no longer useful and
     * so should be forcibly expired in the data store so it can't be used again.
     *
     * If storage fails for some reason, we're not currently checking for
     * any sort of success/failure, so you should bail out of the script
     * and provide a descriptive fail message.
     *
     * @param $refresh_token
     * Refresh token to be expirse.
     *
     * @ingroup oauth2_section_6
     */
    public function unsetRefreshToken($refresh_token){
        return $this->remove($refresh_token);
    }

    /**
     * 
     * AUTHORIZATION CODES
     * 
     */

    /**
     * Fetch authorization code data (probably the most common grant type).
     *
     * Retrieve the stored data for the given authorization code.
     *
     * Required for OAuth2::GRANT_TYPE_AUTH_CODE.
     *
     * @param $code
     * Authorization code to be check with.
     *
     * @return
     * An associative array as below, and NULL if the code is invalid
     * @code
     * return array(
     *     "client_id"    => CLIENT_ID,      // REQUIRED Stored client identifier
     *     "user_id"      => USER_ID,        // REQUIRED Stored user identifier
     *     "expires"      => EXPIRES,        // REQUIRED Stored expiration in unix timestamp
     *     "redirect_uri" => REDIRECT_URI,   // REQUIRED Stored redirect URI
     *     "scope"        => SCOPE,          // OPTIONAL Stored scope values in space-separated string
     * );
     * @endcode
     *
     * @see http://tools.ietf.org/html/rfc6749#section-4.1
     *
     * @ingroup oauth2_section_4
     */
    public function getAuthorizationCode($code){
        return $this->get($code);
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
     * @param string $code         Authorization code to be stored.
     * @param mixed  $client_id    Client identifier to be stored.
     * @param mixed  $user_id      User identifier to be stored.
     * @param string $redirect_uri Redirect URI(s) to be stored in a space-separated string.
     * @param int    $expires      Expiration to be stored as a Unix timestamp.
     * @param string $scope        OPTIONAL Scopes to be stored in space-separated string.
     *
     * @ingroup oauth2_section_4
     */
    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null){
        return $this->set($code, array('client_id'=>$client_id, 'user_id'=>$user_id, 'redirect_uri'=>$redirect_uri, 'expires'=>$expires, 'scope'=>$scope), $expires > 0 ? $expires : NULL);
    }
    /**
     * once an Authorization Code is used, it must be exipired
     *
     * @see http://tools.ietf.org/html/rfc6749#section-4.1.2
     *
     *    The client MUST NOT use the authorization code
     *    more than once.  If an authorization code is used more than
     *    once, the authorization server MUST deny the request and SHOULD
     *    revoke (when possible) all tokens previously issued based on
     *    that authorization code
     *
     */
    public function expireAuthorizationCode($code){
        return $this->remove($code);
    }

    /****
     * 
     * USER CREDENTIALS
     * 
     **/

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
    public function checkUserCredentials($username, $password){
	$username = strtolower($username);
        $result = elgg_authenticate($username, $password);

        if ($result !== true) {
            return false;
        }

        return true;
    }

    /**
     * @return
     * ARRAY the associated "user_id" and optional "scope" values
     * This function MUST return FALSE if the requested user does not exist or is
     * invalid. "scope" is a space-separated list of restricted scopes.
     * @code
     * return array(
     *     "user_id"  => USER_ID,    // REQUIRED user_id to be stored with the authorization code or access token
     *     "scope"    => SCOPE       // OPTIONAL space-separated list of restricted scopes
     * );
     * @endcode
     */
    public function getUserDetails($username=null){
	$username = strtolower($username);
        $user = new \minds\entities\user($username);
        if($user->guid)
            return array(
                'user_id' => (string) $user->guid,
                'scope' => ''
            );
        return false;
    }


}
