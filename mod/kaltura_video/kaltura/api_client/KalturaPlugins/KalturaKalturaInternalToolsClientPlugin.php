<?php
require_once(dirname(__FILE__) . "/../KalturaClientBase.php");
require_once(dirname(__FILE__) . "/../KalturaEnums.php");
require_once(dirname(__FILE__) . "/../KalturaTypes.php");

class KalturaInternalToolsSession extends KalturaObjectBase
{
	/**
	 * 
	 *
	 * @var int
	 */
	public $partner_id = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $valid_until = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $partner_pattern = null;

	/**
	 * 
	 *
	 * @var KalturaSessionType
	 */
	public $type = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $error = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $rand = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $user = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $privileges = null;


}


class KalturaKalturaInternalToolsService extends KalturaServiceBase
{
	function __construct(KalturaClient $client = null)
	{
		parent::__construct($client);
	}
}

class KalturaKalturaInternalToolsSystemHelperService extends KalturaServiceBase
{
	function __construct(KalturaClient $client = null)
	{
		parent::__construct($client);
	}

	function fromSecureString($str)
	{
		$kparams = array();
		$this->client->addParam($kparams, "str", $str);
		$this->client->queueServiceActionCall("kalturainternaltools_kalturainternaltoolssystemhelper", "fromSecureString", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "KalturaInternalToolsSession");
		return $resultObject;
	}

	function iptocountry($remote_addr)
	{
		$kparams = array();
		$this->client->addParam($kparams, "remote_addr", $remote_addr);
		$this->client->queueServiceActionCall("kalturainternaltools_kalturainternaltoolssystemhelper", "iptocountry", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "string");
		return $resultObject;
	}

	function getRemoteAddress()
	{
		$kparams = array();
		$this->client->queueServiceActionCall("kalturainternaltools_kalturainternaltoolssystemhelper", "getRemoteAddress", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "string");
		return $resultObject;
	}
}
class KalturaKalturaInternalToolsClientPlugin extends KalturaClientPlugin
{
	/**
	 * @var KalturaKalturaInternalToolsClientPlugin
	 */
	protected static $instance;

	/**
	 * @var KalturaKalturaInternalToolsService
	 */
	public $KalturaInternalTools = null;

	/**
	 * @var KalturaKalturaInternalToolsSystemHelperService
	 */
	public $KalturaInternalToolsSystemHelper = null;

	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
		$this->KalturaInternalTools = new KalturaKalturaInternalToolsService($client);
		$this->KalturaInternalToolsSystemHelper = new KalturaKalturaInternalToolsSystemHelperService($client);
	}

	/**
	 * @return KalturaKalturaInternalToolsClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		if(!self::$instance)
			self::$instance = new KalturaKalturaInternalToolsClientPlugin($client);
		return self::$instance;
	}

	/**
	 * @return array<KalturaServiceBase>
	 */
	public function getServices()
	{
		$services = array(
			'KalturaInternalTools' => $this->KalturaInternalTools,
			'KalturaInternalToolsSystemHelper' => $this->KalturaInternalToolsSystemHelper,
		);
		return $services;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'KalturaInternalTools';
	}
}

