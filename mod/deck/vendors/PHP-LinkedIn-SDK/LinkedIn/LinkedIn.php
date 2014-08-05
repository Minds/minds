<?php

    //namespace LinkedIn;
    
    class LinkedIn {

        private $_config = array();
        private $_state = null;
        private $_access_token = null;
        private $_access_token_expires = null;
        private $_debug_info = null;
        private $_curl_handle = null;
        
        const API_BASE = 'https://api.linkedin.com/v1';
        const OAUTH_BASE = 'https://www.linkedin.com/uas/oauth2';
        
        const SCOPE_BASIC_PROFILE = 'r_basicprofile'; // Name, photo, headline, and current positions
        const SCOPE_FULL_PROFILE = 'r_fullprofile'; // Full profile including experience, education, skills, and recommendations
        const SCOPE_EMAIL_ADDRESS = 'r_emailaddress'; // The primary email address you use for your LinkedIn account
        const SCOPE_NETWORK = 'r_network'; // Your 1st and 2nd degree connections
        const SCOPE_CONTACT_INFO = 'r_contactinfo'; // Address, phone number, and bound accounts
        const SCOPE_READ_WRITE_UPDATES = 'rw_nus'; // Retrieve and post updates to LinkedIn as you
        const SCOPE_READ_WRITE_GROUPS = 'rw_groups'; // Retrieve and post group discussions as you
        const SCOPE_WRITE_MESSAGES = 'w_messages'; // Send messages and invitations to connect as you

        const HTTP_METHOD_GET = 'GET';
        const HTTP_METHOD_POST = 'POST';
        const HTTP_METHOD_PUT = 'PUT';
        const HTTP_METHOD_DELETE = 'DELETE';
        
        /**
         * @param array $config (api_key, api_secret, callback_url)
         * @throws \InvalidArgumentException
         * @throws \RuntimeException
         */
        public function __construct(array $config){
            
            if (!isset($config['api_key']) || empty($config['api_key'])){
                throw new \InvalidArgumentException('Invalid api key - make sure api_key is defined in the config array');
            }
            
            if (!isset($config['api_secret']) || empty($config['api_secret'])){
                throw new \InvalidArgumentException('Invalid api secret - make sure api_secret is defined in the config array');
            }
            
            if (!isset($config['callback_url']) || empty($config['callback_url'])){
                throw new \InvalidArgumentException('Invalid callback url - make sure callback_url is defined in the config array');
            }
            
            if (!extension_loaded('curl')){
                throw new \RuntimeException('PHP CURL extension does not seem to be loaded');
            }

            $this->_config = $config;
 
        }
        
        /**
         * Get the login url, pass scope to request specific permissions
         * 
         * @param array $scope - an array of requested permissions (can use scope constants defined in this class)
         * @param string $state - a unique identifier for this user, if none is passed, one is generated via uniqid
         * @return string $url
         */
        public function getLoginUrl(array $scope = array(), $state = null){
            
            if (!empty($scope)){
                $scope = implode('%20', $scope);
            }
            
            if (empty($state)){
                $state = uniqid('', true);
            }
            $this->setState($state);
            
            $url = self::OAUTH_BASE . "/authorization?response_type=code&client_id={$this->_config['api_key']}&scope={$scope}&state={$state}&redirect_uri=" . urlencode($this->_config['callback_url']);

            return $url;

        }
        
        /**
         * Exchange the authorization code for an access token
         * 
         * @param string $authorization_code
         * @throws \InvalidArgumentException
         * @throws \RuntimeException
         * @return string $access_token
         */
        public function getAccessToken($authorization_code = null){
            
            if (!empty($this->_access_token)){
                return $this->_access_token;
            }

            if (empty($authorization_code)){
                throw new \InvalidArgumentException('Invalid authorization code. Pass in the "code" parameter from your callback url');
            }

            $params = array(
                'grant_type' => 'authorization_code',
                'code' => $authorization_code,
                'client_id' => $this->_config['api_key'],
                'client_secret' => $this->_config['api_secret'],
                'redirect_uri' => $this->_config['callback_url']
            );
            
            /** Temp bug fix as per https://developer.linkedin.com/comment/28938#comment-28938 **/
            $tmp_params = http_build_query($params);

            $data = $this->_makeRequest(self::OAUTH_BASE . '/accessToken?' . $tmp_params, array(), self::HTTP_METHOD_POST, array('x-li-format: json'));
            if (isset($data['error']) && !empty($data['error'])){
                throw new \RuntimeException('Access Token Request Error: ' . $data['error'] . ' -- ' . $data['error_description']);
            }
                    
            $this->_access_token = $data['access_token'];
            $this->_access_token_expires = $data['expires_in'];
            
            return $this->_access_token;

        }
        
        /**
         * This timestamp is "expires in". In other words, the token will expire in now() + expires_in
         * 
         * @return int access token expiration time - 
         */
        public function getAccessTokenExpiration(){
            
            return $this->_access_token_expires;
            
        }
        
        /**
         * Set the access token manually
         * 
         * @param string $token
         * @throws \InvalidArgumentException
         * @return \LinkedIn\LinkedIn
         */
        public function setAccessToken($token){
            
            $token = trim($token);
            if (empty($token)){
                throw new \InvalidArgumentException('Invalid access token');
            }
            
            $this->_access_token = $token;
            
            return $this;
            
        }
        
        /**
         * Set the state manually. State is a unique identifier for the user
         * 
         * @param string $state
         * @throws \InvalidArgumentException
         * @return \LinkedIn\LinkedIn
         */
        public function setState($state){
            
            $state = trim($state);
            if (empty($state)){
                throw new \InvalidArgumentException('Invalid state. State should be a unique identifier for this user');
            }
            
            $this->_state = $state;
            
            return $this;
            
        }
        
        /**
         * Get state
         * 
         * @return string
         */
        public function getState(){
            
            return $this->_state;
            
        }
        
        /**
         * POST to an authenciated API endpoint w/ payload
         *
         * @param string $endpoint
         * @param array $payload
         * @return array
         */
        public function post($endpoint, array $payload = array()){
             
            return $this->fetch($endpoint, $payload, self::HTTP_METHOD_POST);
             
        }
         
        /**
         * GET an authenticated API endpoind w/ payload
         *
         * @param string $endpoint
         * @param array $payload
         * @return array
         */
        public function get($endpoint, array $payload = array()){
             
            return $this->fetch($endpoint, $payload);
             
        }
         
        /**
         * PUT to an authenciated API endpoint w/ payload
         *
         * @param string $endpoint
         * @param array $payload
         * @return array
         */
        public function put($endpoint, array $payload = array()){
             
            return $this->fetch($endpoint, $payload, self::HTTP_METHOD_PUT);
             
        }
        
        /**
         * Make an authenticated API request to the specified endpoint
         * Headers are for additional headers to be sent along with the request. 
         * Curl options are additional curl options that may need to be set
         * 
         * @param string $endpoint
         * @param array $payload
         * @param string $method
         * @param array $headers
         * @param array $curl_options
         * @return array 
         */
        public function fetch($endpoint, array $payload = array(), $method = 'GET', array $headers = array(), array $curl_options = array()){
            
            $endpoint = self::API_BASE . '/' . trim($endpoint, '/\\') . '?oauth2_access_token=' . $this->getAccessToken();
            $headers[] = 'x-li-format: json';
            
            return $this->_makeRequest($endpoint, $payload, $method, $headers, $curl_options);
            
        }
        
        /**
         * Get debug info from the CURL request
         * 
         * @return array
         */
        public function getDebugInfo(){
            
            return $this->_debug_info;
   
        }
        
        /**
         * Make a CURL request
         * 
         * @param string $url
         * @param array $payload
         * @param string $method
         * @param array $headers
         * @param array $curl_options
         * @throws \RuntimeException
         * @return array
         */
        protected function _makeRequest($url, array $payload = array(), $method = 'GET', array $headers = array(), array $curl_options = array()){

            $ch = $this->_getCurlHandle();

            $options = array(
                CURLOPT_CUSTOMREQUEST => strtoupper($method),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_FOLLOWLOCATION => true
            );

            if (!empty($payload)){
                if ($options[CURLOPT_CUSTOMREQUEST] == self::HTTP_METHOD_POST || $options[CURLOPT_CUSTOMREQUEST] == self::HTTP_METHOD_PUT){
                    $options[CURLOPT_POST] = true;
                    $options[CURLOPT_POSTFIELDS] = json_encode($payload); // Json encode payload
                    $headers[] = 'Content-Length: ' . strlen($options[CURLOPT_POSTFIELDS]);
                    $headers[] = 'Content-Type: application/json'; //Set Content-Type header to application/json
                    $options[CURLOPT_HTTPHEADER] = $headers;
                }else{
                    $options[CURLOPT_URL] .= '&' . http_build_query($payload); // we assume there is already a ? in the request url
                    $options[CURLOPT_URL] = preg_replace('/%26/simU', '&', $options[CURLOPT_URL]); // fix because linkedIn use non standard array eg: type=SHAR&type=VIRL and not type[]=SHAR&type[]=VIRL
                    $options[CURLOPT_URL] = preg_replace('/%3D/simU', '=', $options[CURLOPT_URL]);
                }
            }

            if (!empty($curl_options)){
                $options = array_merge($options, $curl_options);
            }

            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            $this->_debug_info = curl_getinfo($ch);
            
            if ($response === false){
                throw new \RuntimeException('Request Error: ' . curl_error($ch));
            }
            
            $response = json_decode($response, true);
            if (isset($response['status']) && ($response['status'] < 200 || $response['status'] > 300)){
                throw new \RuntimeException('Request Error: ' . $response['message'] . '. Raw Response: ' . print_r($response, true));
            }

            return $response;
        }
        
        protected function _getCurlHandle(){
        
            if (!$this->_curl_handle){
                $this->_curl_handle = curl_init();
            }
                
            return $this->_curl_handle;
        
        }
        
        public function __destruct(){
        
            if ($this->_curl_handle){
                curl_close($this->_curl_handle);
            }
        
        }
        
    }
