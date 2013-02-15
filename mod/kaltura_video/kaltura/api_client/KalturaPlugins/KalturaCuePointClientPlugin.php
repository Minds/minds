<?php
require_once(dirname(__FILE__) . "/../KalturaClientBase.php");
require_once(dirname(__FILE__) . "/../KalturaEnums.php");
require_once(dirname(__FILE__) . "/../KalturaTypes.php");

class KalturaCuePointOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const START_TIME_ASC = "+startTime";
	const START_TIME_DESC = "-startTime";
	const PARTNER_SORT_VALUE_ASC = "+partnerSortValue";
	const PARTNER_SORT_VALUE_DESC = "-partnerSortValue";
}

class KalturaCuePointStatus
{
	const READY = 1;
	const DELETED = 2;
}

class KalturaCuePointType
{
	const AD = "adCuePoint.Ad";
	const CODE = "codeCuePoint.Code";
}

abstract class KalturaCuePoint extends KalturaObjectBase
{
	/**
	 * 
	 *
	 * @var string
	 * @readonly
	 */
	public $id = null;

	/**
	 * 
	 *
	 * @var KalturaCuePointType
	 * @readonly
	 */
	public $cuePointType = null;

	/**
	 * 
	 *
	 * @var KalturaCuePointStatus
	 * @readonly
	 */
	public $status = null;

	/**
	 * 
	 *
	 * @var string
	 * @insertonly
	 */
	public $entryId = null;

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
	 * @var int
	 * @readonly
	 */
	public $createdAt = null;

	/**
	 * 
	 *
	 * @var int
	 * @readonly
	 */
	public $updatedAt = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $tags = null;

	/**
	 * Start tim ein milliseconds
	 *
	 * @var int
	 */
	public $startTime = null;

	/**
	 * 
	 *
	 * @var string
	 * @readonly
	 */
	public $userId = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $partnerData = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $partnerSortValue = null;

	/**
	 * 
	 *
	 * @var KalturaNullableBoolean
	 */
	public $forceStop = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $thumbOffset = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $systemName = null;


}

class KalturaCuePointListResponse extends KalturaObjectBase
{
	/**
	 * 
	 *
	 * @var array of KalturaCuePoint
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

abstract class KalturaCuePointBaseFilter extends KalturaFilter
{
	/**
	 * 
	 *
	 * @var string
	 */
	public $idEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $idIn = null;

	/**
	 * 
	 *
	 * @var KalturaCuePointType
	 */
	public $cuePointTypeEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $cuePointTypeIn = null;

	/**
	 * 
	 *
	 * @var KalturaCuePointStatus
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
	 * @var string
	 */
	public $tagsLike = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $tagsMultiLikeOr = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $tagsMultiLikeAnd = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $startTimeGreaterThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $startTimeLessThanOrEqual = null;

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
	 * @var int
	 */
	public $partnerSortValueEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $partnerSortValueIn = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $partnerSortValueGreaterThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $partnerSortValueLessThanOrEqual = null;

	/**
	 * 
	 *
	 * @var KalturaNullableBoolean
	 */
	public $forceStopEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $systemNameEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $systemNameIn = null;


}

class KalturaCuePointFilter extends KalturaCuePointBaseFilter
{

}


class KalturaCuePointService extends KalturaServiceBase
{
	function __construct(KalturaClient $client = null)
	{
		parent::__construct($client);
	}

	function add(KalturaCuePoint $cuePoint)
	{
		$kparams = array();
		$this->client->addParam($kparams, "cuePoint", $cuePoint->toParams());
		$this->client->queueServiceActionCall("cuepoint_cuepoint", "add", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "KalturaCuePoint");
		return $resultObject;
	}

	function addFromBulk($fileData)
	{
		$kparams = array();
		$kfiles = array();
		$this->client->addParam($kfiles, "fileData", $fileData);
		$this->client->queueServiceActionCall("cuepoint_cuepoint", "addFromBulk", $kparams, $kfiles);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "KalturaCuePointListResponse");
		return $resultObject;
	}

	function serveBulk(KalturaCuePointFilter $filter = null, KalturaFilterPager $pager = null)
	{
		$kparams = array();
		if ($filter !== null)
			$this->client->addParam($kparams, "filter", $filter->toParams());
		if ($pager !== null)
			$this->client->addParam($kparams, "pager", $pager->toParams());
		$this->client->queueServiceActionCall('cuepoint_cuepoint', 'serveBulk', $kparams);
		$resultObject = $this->client->getServeUrl();
		return $resultObject;
	}

	function get($id)
	{
		$kparams = array();
		$this->client->addParam($kparams, "id", $id);
		$this->client->queueServiceActionCall("cuepoint_cuepoint", "get", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "KalturaCuePoint");
		return $resultObject;
	}

	function listAction(KalturaCuePointFilter $filter = null, KalturaFilterPager $pager = null)
	{
		$kparams = array();
		if ($filter !== null)
			$this->client->addParam($kparams, "filter", $filter->toParams());
		if ($pager !== null)
			$this->client->addParam($kparams, "pager", $pager->toParams());
		$this->client->queueServiceActionCall("cuepoint_cuepoint", "list", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "KalturaCuePointListResponse");
		return $resultObject;
	}

	function count(KalturaCuePointFilter $filter = null)
	{
		$kparams = array();
		if ($filter !== null)
			$this->client->addParam($kparams, "filter", $filter->toParams());
		$this->client->queueServiceActionCall("cuepoint_cuepoint", "count", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "integer");
		return $resultObject;
	}

	function update($id, KalturaCuePoint $cuePoint)
	{
		$kparams = array();
		$this->client->addParam($kparams, "id", $id);
		$this->client->addParam($kparams, "cuePoint", $cuePoint->toParams());
		$this->client->queueServiceActionCall("cuepoint_cuepoint", "update", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "KalturaCuePoint");
		return $resultObject;
	}

	function delete($id)
	{
		$kparams = array();
		$this->client->addParam($kparams, "id", $id);
		$this->client->queueServiceActionCall("cuepoint_cuepoint", "delete", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "null");
		return $resultObject;
	}
}
class KalturaCuePointClientPlugin extends KalturaClientPlugin
{
	/**
	 * @var KalturaCuePointClientPlugin
	 */
	protected static $instance;

	/**
	 * @var KalturaCuePointService
	 */
	public $cuePoint = null;

	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
		$this->cuePoint = new KalturaCuePointService($client);
	}

	/**
	 * @return KalturaCuePointClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		if(!self::$instance)
			self::$instance = new KalturaCuePointClientPlugin($client);
		return self::$instance;
	}

	/**
	 * @return array<KalturaServiceBase>
	 */
	public function getServices()
	{
		$services = array(
			'cuePoint' => $this->cuePoint,
		);
		return $services;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'cuePoint';
	}
}

