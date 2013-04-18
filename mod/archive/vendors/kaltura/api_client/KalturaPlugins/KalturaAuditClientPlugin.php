<?php
require_once(dirname(__FILE__) . "/../KalturaClientBase.php");
require_once(dirname(__FILE__) . "/../KalturaEnums.php");
require_once(dirname(__FILE__) . "/../KalturaTypes.php");

class KalturaAuditTrailAction
{
	const CREATED = "CREATED";
	const COPIED = "COPIED";
	const CHANGED = "CHANGED";
	const DELETED = "DELETED";
	const VIEWED = "VIEWED";
	const CONTENT_VIEWED = "CONTENT_VIEWED";
	const FILE_SYNC_CREATED = "FILE_SYNC_CREATED";
	const RELATION_ADDED = "RELATION_ADDED";
	const RELATION_REMOVED = "RELATION_REMOVED";
}

class KalturaAuditTrailContext
{
	const CLIENT = -1;
	const SCRIPT = 0;
	const PS2 = 1;
	const API_V3 = 2;
}

class KalturaAuditTrailFileSyncType
{
	const FILE = 1;
	const LINK = 2;
	const URL = 3;
}

class KalturaAuditTrailObjectType
{
	const ACCESS_CONTROL = "accessControl";
	const ADMIN_KUSER = "adminKuser";
	const BATCH_JOB = "BatchJob";
	const CATEGORY = "category";
	const CONVERSION_PROFILE_2 = "conversionProfile2";
	const EMAIL_INGESTION_PROFILE = "EmailIngestionProfile";
	const ENTRY = "entry";
	const FILE_SYNC = "FileSync";
	const FLAVOR_ASSET = "flavorAsset";
	const THUMBNAIL_ASSET = "thumbAsset";
	const FLAVOR_PARAMS = "flavorParams";
	const THUMBNAIL_PARAMS = "thumbParams";
	const FLAVOR_PARAMS_CONVERSION_PROFILE = "flavorParamsConversionProfile";
	const FLAVOR_PARAMS_OUTPUT = "flavorParamsOutput";
	const THUMBNAIL_PARAMS_OUTPUT = "thumbParamsOutput";
	const KSHOW = "kshow";
	const KSHOW_KUSER = "KshowKuser";
	const KUSER = "kuser";
	const MEDIA_INFO = "mediaInfo";
	const MODERATION = "moderation";
	const PARTNER = "Partner";
	const ROUGHCUT = "roughcutEntry";
	const SYNDICATION = "syndicationFeed";
	const UI_CONF = "uiConf";
	const UPLOAD_TOKEN = "UploadToken";
	const WIDGET = "widget";
	const METADATA = "Metadata";
	const METADATA_PROFILE = "MetadataProfile";
	const USER_LOGIN_DATA = "UserLoginData";
	const USER_ROLE = "UserRole";
	const PERMISSION = "Permission";
}

class KalturaAuditTrailOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const PARSED_AT_ASC = "+parsedAt";
	const PARSED_AT_DESC = "-parsedAt";
}

class KalturaAuditTrailStatus
{
	const PENDING = 1;
	const READY = 2;
	const FAILED = 3;
}

abstract class KalturaAuditTrailInfo extends KalturaObjectBase
{

}

class KalturaAuditTrail extends KalturaObjectBase
{
	/**
	 * 
	 *
	 * @var int
	 * @readonly
	 */
	public $id = null;

	/**
	 * 
	 *
	 * @var int
	 * @readonly
	 */
	public $createdAt = null;

	/**
	 * Indicates when the data was parsed
	 *
	 * @var int
	 * @readonly
	 */
	public $parsedAt = null;

	/**
	 * 
	 *
	 * @var KalturaAuditTrailStatus
	 * @readonly
	 */
	public $status = null;

	/**
	 * 
	 *
	 * @var KalturaAuditTrailObjectType
	 */
	public $auditObjectType = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $objectId = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $relatedObjectId = null;

	/**
	 * 
	 *
	 * @var KalturaAuditTrailObjectType
	 */
	public $relatedObjectType = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $entryId = null;

	/**
	 * 
	 *
	 * @var int
	 * @readonly
	 */
	public $masterPartnerId = null;

	/**
	 * 
	 *
	 * @var int
	 * @readonly
	 */
	public $partnerId = null;

	/**
	 * 
	 *
	 * @var string
	 * @readonly
	 */
	public $requestId = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $userId = null;

	/**
	 * 
	 *
	 * @var KalturaAuditTrailAction
	 */
	public $action = null;

	/**
	 * 
	 *
	 * @var KalturaAuditTrailInfo
	 */
	public $data;

	/**
	 * 
	 *
	 * @var string
	 * @readonly
	 */
	public $ks = null;

	/**
	 * 
	 *
	 * @var KalturaAuditTrailContext
	 * @readonly
	 */
	public $context = null;

	/**
	 * The API service and action that called and caused this audit
	 *
	 * @var string
	 * @readonly
	 */
	public $entryPoint = null;

	/**
	 * 
	 *
	 * @var string
	 * @readonly
	 */
	public $serverName = null;

	/**
	 * 
	 *
	 * @var string
	 * @readonly
	 */
	public $ipAddress = null;

	/**
	 * 
	 *
	 * @var string
	 * @readonly
	 */
	public $userAgent = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $clientTag = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $description = null;

	/**
	 * 
	 *
	 * @var string
	 * @readonly
	 */
	public $errorDescription = null;


}

abstract class KalturaAuditTrailBaseFilter extends KalturaFilter
{
	/**
	 * 
	 *
	 * @var int
	 */
	public $idEqual = null;

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
	public $parsedAtGreaterThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $parsedAtLessThanOrEqual = null;

	/**
	 * 
	 *
	 * @var KalturaAuditTrailStatus
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
	 * @var KalturaAuditTrailObjectType
	 */
	public $auditObjectTypeEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $auditObjectTypeIn = null;

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
	public $relatedObjectIdEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $relatedObjectIdIn = null;

	/**
	 * 
	 *
	 * @var KalturaAuditTrailObjectType
	 */
	public $relatedObjectTypeEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $relatedObjectTypeIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $entryIdEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $entryIdIn = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $masterPartnerIdEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $masterPartnerIdIn = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $partnerIdEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $partnerIdIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $requestIdEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $requestIdIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $userIdEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $userIdIn = null;

	/**
	 * 
	 *
	 * @var KalturaAuditTrailAction
	 */
	public $actionEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $actionIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $ksEqual = null;

	/**
	 * 
	 *
	 * @var KalturaAuditTrailContext
	 */
	public $contextEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $contextIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $entryPointEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $entryPointIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $serverNameEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $serverNameIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $ipAddressEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $ipAddressIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $clientTagEqual = null;


}

class KalturaAuditTrailFilter extends KalturaAuditTrailBaseFilter
{

}

class KalturaAuditTrailListResponse extends KalturaObjectBase
{
	/**
	 * 
	 *
	 * @var array of KalturaAuditTrail
	 * @readonly
	 */
	public $objects;

	/**
	 * 
	 *
	 * @var int
	 * @readonly
	 */
	public $totalCount = null;


}

class KalturaAuditTrailChangeItem extends KalturaObjectBase
{
	/**
	 * 
	 *
	 * @var string
	 */
	public $descriptor = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $oldValue = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $newValue = null;


}

class KalturaAuditTrailChangeInfo extends KalturaAuditTrailInfo
{
	/**
	 * 
	 *
	 * @var array of KalturaAuditTrailChangeItem
	 */
	public $changedItems;


}

class KalturaAuditTrailFileSyncCreateInfo extends KalturaAuditTrailInfo
{
	/**
	 * 
	 *
	 * @var string
	 */
	public $version = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $objectSubType = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $dc = null;

	/**
	 * 
	 *
	 * @var bool
	 */
	public $original = null;

	/**
	 * 
	 *
	 * @var KalturaAuditTrailFileSyncType
	 */
	public $fileType = null;


}

class KalturaAuditTrailTextInfo extends KalturaAuditTrailInfo
{
	/**
	 * 
	 *
	 * @var string
	 */
	public $info = null;


}


class KalturaAuditTrailService extends KalturaServiceBase
{
	function __construct(KalturaClient $client = null)
	{
		parent::__construct($client);
	}

	function add(KalturaAuditTrail $auditTrail)
	{
		$kparams = array();
		$this->client->addParam($kparams, "auditTrail", $auditTrail->toParams());
		$this->client->queueServiceActionCall("audit_audittrail", "add", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "KalturaAuditTrail");
		return $resultObject;
	}

	function get($id)
	{
		$kparams = array();
		$this->client->addParam($kparams, "id", $id);
		$this->client->queueServiceActionCall("audit_audittrail", "get", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "KalturaAuditTrail");
		return $resultObject;
	}

	function listAction(KalturaAuditTrailFilter $filter = null, KalturaFilterPager $pager = null)
	{
		$kparams = array();
		if ($filter !== null)
			$this->client->addParam($kparams, "filter", $filter->toParams());
		if ($pager !== null)
			$this->client->addParam($kparams, "pager", $pager->toParams());
		$this->client->queueServiceActionCall("audit_audittrail", "list", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "KalturaAuditTrailListResponse");
		return $resultObject;
	}
}
class KalturaAuditClientPlugin extends KalturaClientPlugin
{
	/**
	 * @var KalturaAuditClientPlugin
	 */
	protected static $instance;

	/**
	 * @var KalturaAuditTrailService
	 */
	public $auditTrail = null;

	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
		$this->auditTrail = new KalturaAuditTrailService($client);
	}

	/**
	 * @return KalturaAuditClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		if(!self::$instance)
			self::$instance = new KalturaAuditClientPlugin($client);
		return self::$instance;
	}

	/**
	 * @return array<KalturaServiceBase>
	 */
	public function getServices()
	{
		$services = array(
			'auditTrail' => $this->auditTrail,
		);
		return $services;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'audit';
	}
}

