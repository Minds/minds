<?php

/**
 * @package External
 * @subpackage Kaltura
 */
class MultiRequestSubResult
{
    function __construct($value)
	{
        $this->value = $value;
	}
	
    function __toString()
	{
        return '{' . $this->value . '}';
	}

    function __get($name)
	{
        return new MultiRequestSubResult($this->value . ':' . $name);
	}
}

/**
 * @package External
 * @subpackage Kaltura
 */
class KalturaClientBase 
{
	const KALTURA_SERVICE_FORMAT_JSON = 1;
	const KALTURA_SERVICE_FORMAT_XML  = 2;
	const KALTURA_SERVICE_FORMAT_PHP  = 3;

	/**
	 * @var string
	 */
	protected $apiVersion = null;

	/**
	 * @var KalturaConfiguration
	 */
	protected $config;
	
	/**
	 * @var string
	 */
	private $ks;
	
	/**
	 * @var boolean
	 */
	private $shouldLog = false;
	
	/**
	 * @var bool
	 */
	private $isMultiRequest = false;
	
	/**
	 * @var unknown_type
	 */
	private $callsQueue = array();

	/**
	 * Array of all plugin services
	 *
	 * @var array<KalturaServiceBase>
	 */
	protected $pluginServices = array();
	
	public function __get($serviceName)
	{
		if(isset($this->pluginServices[$serviceName]))
			return $this->pluginServices[$serviceName];
		
		return null;
	}
	
	/**
	 * Kaltura client constructor
	 *
	 * @param KalturaConfiguration $config
	 */
	public function __construct(KalturaConfiguration $config)
	{
	    $this->config = $config;
	    
	    $logger = $this->config->getLogger();
		if ($logger)
		{
			$this->shouldLog = true;	
		}
		
		// load all plugins
		$pluginsFolder = realpath(dirname(__FILE__)) . '/KalturaPlugins';
		if(is_dir($pluginsFolder))
		{
			$dir = dir($pluginsFolder);
			while (false !== $fileName = $dir->read())
			{
				$matches = null;
				if(preg_match('/^([^.]+).php$/', $fileName, $matches))
				{
					require_once("$pluginsFolder/$fileName");
					
					$pluginClass = $matches[1];
					if(!class_exists($pluginClass) || !in_array('IKalturaClientPlugin', class_implements($pluginClass)))
						continue;
						
					$plugin = call_user_func(array($pluginClass, 'get'), $this);
					if(!($plugin instanceof IKalturaClientPlugin))
						continue;
						
					$pluginName = $plugin->getName();
					$services = $plugin->getServices();
					foreach($services as $serviceName => $service)
					{
						$service->setClient($this);
						$this->pluginServices[$serviceName] = $service;
					}
				}
			}
		}
	}

	public function getServeUrl()
	{
		if (count($this->callsQueue) != 1)
			return null;
			 
		$params = array();
		$files = array();
		$this->log("service url: [" . $this->config->serviceUrl . "]");
		
		// append the basic params
		$this->addParam($params, "apiVersion", $this->apiVersion);
		$this->addParam($params, "format", $this->config->format);
		$this->addParam($params, "clientTag", $this->config->clientTag);
		
		$call = $this->callsQueue[0];
		$this->callsQueue = array();
		$this->isMultiRequest = false; 
		
		$params = array_merge($params, $call->params);
		$signature = $this->signature($params);
		$this->addParam($params, "kalsig", $signature);
		
		$url = $this->config->serviceUrl . "/api_v3/index.php?service={$call->service}&action={$call->action}";
		$url .= '&' . http_build_query($params); 
		$this->log("Returned url [$url]");
		return $url;
	}
	
	public function queueServiceActionCall($service, $action, $params = array(), $files = array())
	{
		// in start session partner id is optional (default -1). if partner id was not set, use the one in the config
		if (!isset($params["partnerId"]) || $params["partnerId"] === -1)
			$params["partnerId"] = $this->config->partnerId;
			
		$this->addParam($params, "ks", $this->ks);
		
		$call = new KalturaServiceActionCall($service, $action, $params, $files);
		$this->callsQueue[] = $call;
	}
	
	/**
	 * Call all API service that are in queue
	 *
	 * @return unknown
	 */
	public function doQueue()
	{
		if (count($this->callsQueue) == 0)
		{
			$this->isMultiRequest = false; 
			return null;
		}
			 
		$startTime = microtime(true);
				
		$params = array();
		$files = array();
		$this->log("service url: [" . $this->config->serviceUrl . "]");
		
		// append the basic params
		$this->addParam($params, "apiVersion", $this->apiVersion);
		$this->addParam($params, "format", $this->config->format);
		$this->addParam($params, "clientTag", $this->config->clientTag);
		
		$url = $this->config->serviceUrl."/api_v3/index.php?service=";
		if ($this->isMultiRequest)
		{
			$url .= "multirequest";
			$i = 1;
			foreach ($this->callsQueue as $call)
			{
				$callParams = $call->getParamsForMultiRequest($i++);
				$params = array_merge($params, $callParams);
				$files = array_merge($files, $call->files);
			}
		}
		else
		{
			$call = $this->callsQueue[0];
			$url .= $call->service."&action=".$call->action;
			$params = array_merge($params, $call->params);
			$files = $call->files;
		}
		
		// reset
		$this->callsQueue = array();
		$this->isMultiRequest = false; 
		
		$signature = $this->signature($params);
		$this->addParam($params, "kalsig", $signature);
		
		list($postResult, $error) = $this->doHttpRequest($url, $params, $files);
		
		if ($error)
		{
			throw new KalturaClientException($error, KalturaClientException::ERROR_GENERIC);
		}
		else 
		{
//			if(strlen($postResult) > 1024)
//				$this->log("result (serialized): " . strlen($postResult) . " bytes");
//			else
				$this->log("result (serialized): " . $postResult);
			
			if ($this->config->format == self::KALTURA_SERVICE_FORMAT_PHP)
			{
				$result = @unserialize($postResult);

				if ($result === false && serialize(false) !== $postResult) 
				{
					throw new KalturaClientException("failed to unserialize server result\n$postResult", KalturaClientException::ERROR_UNSERIALIZE_FAILED);
				}
				$dump = print_r($result, true);
//				if(strlen($dump) < 1024)
					$this->log("result (object dump): " . $dump);
			}
			else
			{
				throw new KalturaClientException("unsupported format: $postResult", KalturaClientException::ERROR_FORMAT_NOT_SUPPORTED);
			}
		}
		
		$endTime = microtime (true);
		
		$this->log("execution time for [".$url."]: [" . ($endTime - $startTime) . "]");
		
		return $result;
	}

	/**
	 * Sign array of parameters
	 *
	 * @param array $params
	 * @return string
	 */
	private function signature($params)
	{
		ksort($params);
		$str = "";
		foreach ($params as $k => $v)
		{
			$str .= $k.$v;
		}
		return md5($str);
	}
	
	/**
	 * Send http request by using curl (if available) or php stream_context
	 *
	 * @param string $url
	 * @param parameters $params
	 * @return array of result and error
	 */
	private function doHttpRequest($url, $params = array(), $files = array())
	{
		if (function_exists('curl_init'))
			return $this->doCurl($url, $params, $files);
		else
			return $this->doPostRequest($url, $params, $files);
	}

	/**
	 * Curl HTTP POST Request
	 *
	 * @param string $url
	 * @param array $params
	 * @return array of result and error
	 */
	private function doCurl($url, $params = array(), $files = array())
	{
		$cookies = array();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		if (count($files) > 0)
		{
			foreach($files as &$file)
				$file = "@".$file; // let curl know its a file
			curl_setopt($ch, CURLOPT_POSTFIELDS, array_merge($params, $files));
		}
		else
		{
			$opt = http_build_query($params, null, "&");
			$this->log("curl: $url&$opt");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $opt);
		}
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->config->userAgent);
		if (count($files) > 0)
			curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		else
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->config->curlTimeout);
			
		if ($this->config->startZendDebuggerSession === true)
		{
			$zendDebuggerParams = $this->getZendDebuggerParams($url);
		 	$cookies = array_merge($cookies, $zendDebuggerParams);
		}
		
		if (count($cookies) > 0) 
		{
			$cookiesStr = http_build_query($cookies, null, '; ');
			curl_setopt($ch, CURLOPT_COOKIE, $cookiesStr);
		} 

		$result = curl_exec($ch);
		$curlError = curl_error($ch);
		curl_close($ch);
		return array($result, $curlError);
	}

	/**
	 * HTTP stream context request 
	 *
	 * @param string $url
	 * @param array $params
	 * @return array of result and error
	 */
	private function doPostRequest($url, $params = array(), $files = array())
	{
		if (count($files) > 0)
			throw new KalturaClientException("Uploading files is not supported with stream context http request, please use curl", KalturaClientException::ERROR_UPLOAD_NOT_SUPPORTED);
			
		$formattedData = http_build_query($params , "", "&");
		$params = array('http' => array(
					"method" => "POST",
					"Accept-language: en\r\n".
					"Content-type: application/x-www-form-urlencoded\r\n",
					"content" => $formattedData
		          ));

		$ctx = stream_context_create($params);
		$fp = @fopen($url, 'rb', false, $ctx);
		if (!$fp) {
			$phpErrorMsg = "";
			throw new KalturaClientException("Problem with $url, $phpErrorMsg", KalturaClientException::ERROR_CONNECTION_FAILED);
		}
		$response = @stream_get_contents($fp);
		if ($response === false) {
		   throw new KalturaClientException("Problem reading data from $url, $phpErrorMsg", KalturaClientException::ERROR_READ_FAILED);
		}
		return array($response, '');
	}

	/**
	 * @return string
	 */
	public function getKs()
	{
		return $this->ks;
	}
	
	/**
	 * @param string $ks
	 */
	public function setKs($ks)
	{
		$this->ks = $ks;
	}
	
	/**
	 * @return KalturaConfiguration
	 */
	public function getConfig()
	{
		return $this->config;
	}
	
	/**
	 * @param KalturaConfiguration $config
	 */
	public function setConfig(KalturaConfiguration $config)
	{
		$this->config = $config;
		
		$logger = $this->config->getLogger();
		if ($logger instanceof IKalturaLogger)
		{
			$this->shouldLog = true;	
		}
	}
	
	/**
	 * Add parameter to array of parameters that is passed by reference
	 *
	 * @param arrat $params
	 * @param string $paramName
	 * @param string $paramValue
	 */
	public function addParam(&$params, $paramName, $paramValue)
	{
		if ($paramValue === null)
			return;
			
		if(is_object($paramValue) && $paramValue instanceof KalturaObjectBase)
		{
			$this->addParam($params, "$paramName:objectType", get_class($paramValue));
		    foreach($paramValue as $prop => $val)
				$this->addParam($params, "$paramName:$prop", $val);
				
			return;
		}	
		
		if(!is_array($paramValue))
		{
			$params[$paramName] = (string)$paramValue;
			return;
		}
		
		if ($paramValue)
		{
			foreach($paramValue as $subParamName => $subParamValue)
				$this->addParam($params, "$paramName:$subParamName", $subParamValue);
		}
		else
		{
			$this->addParam($params, "$paramName:-", "");
		}
	}
	
	/**
	 * Validate the result object and throw exception if its an error
	 *
	 * @param object $resultObject
	 */
	public function throwExceptionIfError($resultObject)
	{
		if ($this->isError($resultObject))
		{
			throw new KalturaException($resultObject["message"], $resultObject["code"]);
		}
	}
	
	/**
	 * Checks whether the result object is an error
	 *
	 * @param object $resultObject
	 */
	public function isError($resultObject)
	{
		return (is_array($resultObject) && isset($resultObject["message"]) && isset($resultObject["code"]));
	}
	
	/**
	 * Validate that the passed object type is of the expected type
	 *
	 * @param unknown_type $resultObject
	 * @param unknown_type $objectType
	 */
	public function validateObjectType($resultObject, $objectType)
	{
		if (is_object($resultObject))
		{
			if (!($resultObject instanceof $objectType))
				throw new KalturaClientException("Invalid object type", KalturaClientException::ERROR_INVALID_OBJECT_TYPE);
		}
		else if (gettype($resultObject) !== "NULL" && gettype($resultObject) !== $objectType)
		{
			throw new KalturaClientException("Invalid object type", KalturaClientException::ERROR_INVALID_OBJECT_TYPE);
		}
	}
	
	public function startMultiRequest()
	{
		$this->isMultiRequest = true;
	}
	
	public function doMultiRequest()
	{
		return $this->doQueue();
	}
	
	public function isMultiRequest()
	{
		return $this->isMultiRequest;	
	}
		
	public function getMultiRequestQueueSize()
	{
		return count($this->callsQueue);	
	}
	
    public function getMultiRequestResult()
	{
        return new MultiRequestSubResult($this->getMultiRequestQueueSize() . ':result');
	}	
	
	/**
	 * @param string $msg
	 */
	protected function log($msg)
	{
		if ($this->shouldLog)
			$this->config->getLogger()->log($msg);
	}
	
	/**
	 * Return a list of parameter used to a new start debug on the destination server api
	 * @link http://kb.zend.com/index.php?View=entry&EntryID=434
	 * @param $url
	 */
	protected function getZendDebuggerParams($url)
	{
		$params = array();
		$passThruParams = array('debug_host',
			'debug_fastfile',
			'debug_port',
			'start_debug',
			'send_debug_header',
			'send_sess_end',
			'debug_jit',
			'debug_stop',
			'use_remote');
		
		foreach($passThruParams as $param)
		{
			if (isset($_COOKIE[$param]))
				$params[$param] = $_COOKIE[$param];
		}
		
		$params['original_url'] = $url;
		$params['debug_session_id'] = microtime(true); // to create a new debug session
		
		return $params;
	}
}

/**
 * @package External
 * @subpackage Kaltura
 */
interface IKalturaClientPlugin
{
	/**
	 * @return KalturaClientPlugin
	 */
	public static function get(KalturaClient $client);
	
	/**
	 * @return array<KalturaServiceBase>
	 */
	public function getServices();
	
	/**
	 * @return string
	 */
	public function getName();
}

/**
 * @package External
 * @subpackage Kaltura
 */
abstract class KalturaClientPlugin implements IKalturaClientPlugin
{
	protected function __construct(KalturaClient $client)
	{
		
	}
}

/**
 * @package External
 * @subpackage Kaltura
 */
class KalturaServiceActionCall
{
	/**
	 * @var string
	 */
	public $service;
	
	/**
	 * @var string
	 */
	public $action;
	
	
	/**
	 * @var array
	 */
	public $params;
	
	/**
	 * @var array
	 */
	public $files;
	
	/**
	 * Contruct new Kaltura service action call, if params array contain sub arrays (for objects), it will be flattened
	 *
	 * @param string $service
	 * @param string $action
	 * @param array $params
	 * @param array $files
	 */
	public function __construct($service, $action, $params = array(), $files = array())
	{
		$this->service = $service;
		$this->action = $action;
		$this->params = $this->parseParams($params);
		$this->files = $files;
	}
	
	/**
	 * Parse params array and sub arrays (for objects)
	 *
	 * @param array $params
	 */
	public function parseParams(array $params)
	{
		$newParams = array();
		foreach($params as $key => $val) 
		{
			if (is_array($val))
			{
				$newParams[$key] = $this->parseParams($val);
			}
			else
			{
				$newParams[$key] = $val;
			}
		}
		return $newParams;
	}
	
	/**
	 * Return the parameters for a multi request
	 *
	 * @param int $multiRequestIndex
	 */
	public function getParamsForMultiRequest($multiRequestIndex)
	{
		$multiRequestParams = array();
		$multiRequestParams[$multiRequestIndex.":service"] = $this->service;
		$multiRequestParams[$multiRequestIndex.":action"] = $this->action;
		foreach($this->params as $key => $val)
		{
			$multiRequestParams[$multiRequestIndex.":".$key] = $val;
		}
		return $multiRequestParams;
	}
}

/**
 * Abstract base class for all client services
 *  
 * @package External
 * @subpackage Kaltura
 */
abstract class KalturaServiceBase
{
	/**
	 * @var KalturaClient
	 */
	protected $client;
	
	/**
	 * Initialize the service keeping reference to the KalturaClient
	 *
	 * @param KalturaClient $client
	 */
	public function __construct(KalturaClient $client = null)
	{
		$this->client = $client;
	}
						
	/**
	 * @param KalturaClient $client
	 */
	public function setClient(KalturaClient $client)
	{
		$this->client = $client;
	}
}

/**
 * Abstract base class for all client objects
 * 
 * @package External
 * @subpackage Kaltura
 */
abstract class KalturaObjectBase
{
	protected function addIfNotNull(&$params, $paramName, $paramValue)
	{
		if ($paramValue !== null)
		{
			if($paramValue instanceof KalturaObjectBase)
			{
				$params[$paramName] = $paramValue->toParams();
			}
			else
			{
				$params[$paramName] = $paramValue;
			}
		}
	}
	
	public function toParams()
	{
		$params = array();
		$params["objectType"] = get_class($this);
	    foreach($this as $prop => $val)
		{
			$this->addIfNotNull($params, $prop, $val);
		}
		return $params;
	}
}

/**
 * @package External
 * @subpackage Kaltura
 */
class KalturaException extends Exception 
{
    public function __construct($message, $code) 
    {
    	$this->code = $code;
		parent::__construct($message);
    }
}

/**
 * @package External
 * @subpackage Kaltura
 */
class KalturaClientException extends Exception 
{
	const ERROR_GENERIC = -1;
	const ERROR_UNSERIALIZE_FAILED = -2;
	const ERROR_FORMAT_NOT_SUPPORTED = -3;
	const ERROR_UPLOAD_NOT_SUPPORTED = -4;
	const ERROR_CONNECTION_FAILED = -5;
	const ERROR_READ_FAILED = -6;
	const ERROR_INVALID_PARTNER_ID = -7;
	const ERROR_INVALID_OBJECT_TYPE = -8;
}

/**
 * @package External
 * @subpackage Kaltura
 */
class KalturaConfiguration
{
	private $logger;

	public $serviceUrl    				= "http://www.kaltura.com/";
	public $partnerId    				= null;
	public $format        				= 3;
	public $clientTag 	  				= "php5";
	public $curlTimeout   				= 10;
	public $userAgent					= '';
	public $startZendDebuggerSession 	= false;
	
	/**
	 * Constructs new Kaltura configuration object
	 *
	 */
	public function __construct($partnerId = -1)
	{
	    if (!is_numeric($partnerId))
	        throw new KalturaClientException("Invalid partner id", KalturaClientException::ERROR_INVALID_PARTNER_ID);
	        
	    $this->partnerId = $partnerId;
	}
	
	/**
	 * Set logger to get kaltura client debug logs
	 *
	 * @param IKalturaLogger $log
	 */
	public function setLogger(IKalturaLogger $log)
	{
		$this->logger = $log;
	}
	
	/**
	 * Gets the logger (Internal client use)
	 *
	 * @return IKalturaLogger
	 */
	public function getLogger()
	{
		return $this->logger;
	}
}

/**
 * Implement to get Kaltura Client logs
 * 
 * @package External
 * @subpackage Kaltura
 */
interface IKalturaLogger 
{
	function log($msg); 
}
