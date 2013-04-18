<?php
require_once(dirname(__FILE__) . "/../KalturaClientBase.php");
require_once(dirname(__FILE__) . "/../KalturaEnums.php");
require_once(dirname(__FILE__) . "/../KalturaTypes.php");

class KalturaFileSyncOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const READY_AT_ASC = "+readyAt";
	const READY_AT_DESC = "-readyAt";
	const SYNC_TIME_ASC = "+syncTime";
	const SYNC_TIME_DESC = "-syncTime";
	const FILE_SIZE_ASC = "+fileSize";
	const FILE_SIZE_DESC = "-fileSize";
}

class KalturaFileSyncStatus
{
	const ERROR = -1;
	const PENDING = 1;
	const READY = 2;
	const DELETED = 3;
	const PURGED = 4;
}

class KalturaFileSyncType
{
	const FILE = 1;
	const LINK = 2;
	const URL = 3;
}

abstract class KalturaFileSyncBaseFilter extends KalturaFilter
{
	/**
	 * 
	 *
	 * @var int
	 */
	public $partnerIdEqual = null;

	/**
	 * 
	 *
	 * @var KalturaFileSyncObjectType
	 */
	public $fileObjectTypeEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $fileObjectTypeIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $objectIdEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $objectIdIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $versionEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $versionIn = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $objectSubTypeEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $objectSubTypeIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $dcEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $dcIn = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $originalEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $createdAtGreaterThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $createdAtLessThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $updatedAtGreaterThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $updatedAtLessThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $readyAtGreaterThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $readyAtLessThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $syncTimeGreaterThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $syncTimeLessThanOrEqual = null;

	/**
	 * 
	 *
	 * @var KalturaFileSyncStatus
	 */
	public $statusEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $statusIn = null;

	/**
	 * 
	 *
	 * @var KalturaFileSyncType
	 */
	public $fileTypeEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $fileTypeIn = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $linkedIdEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $linkCountGreaterThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $linkCountLessThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $fileSizeGreaterThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $fileSizeLessThanOrEqual = null;


}

class KalturaFileSyncFilter extends KalturaFileSyncBaseFilter
{

}

class KalturaFileSyncClientPlugin extends KalturaClientPlugin
{
	/**
	 * @var KalturaFileSyncClientPlugin
	 */
	protected static $instance;

	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
	}

	/**
	 * @return KalturaFileSyncClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		if(!self::$instance)
			self::$instance = new KalturaFileSyncClientPlugin($client);
		return self::$instance;
	}

	/**
	 * @return array<KalturaServiceBase>
	 */
	public function getServices()
	{
		$services = array(
		);
		return $services;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'fileSync';
	}
}

