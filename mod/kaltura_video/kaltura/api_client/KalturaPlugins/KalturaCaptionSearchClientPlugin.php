<?php
require_once(dirname(__FILE__) . "/../KalturaClientBase.php");
require_once(dirname(__FILE__) . "/../KalturaEnums.php");
require_once(dirname(__FILE__) . "/../KalturaTypes.php");
require_once(dirname(__FILE__) . "/KalturaCaptionClientPlugin.php");

class KalturaCaptionAssetItemFilter extends KalturaCaptionAssetFilter
{
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
	 * @var string
	 */
	public $contentLike = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $contentMultiLikeOr = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $contentMultiLikeAnd = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $partnerDescriptionLike = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $partnerDescriptionMultiLikeOr = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $partnerDescriptionMultiLikeAnd = null;

	/**
	 * 
	 *
	 * @var KalturaLanguage
	 */
	public $languageEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $languageIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $labelEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $labelIn = null;

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
	 * @var int
	 */
	public $endTimeGreaterThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $endTimeLessThanOrEqual = null;


}

class KalturaCaptionAssetItem extends KalturaObjectBase
{
	/**
	 * The Caption Asset object
	 * 
	 *
	 * @var KalturaCaptionAsset
	 */
	public $asset;

	/**
	 * The entry object
	 * 
	 *
	 * @var KalturaBaseEntry
	 */
	public $entry;

	/**
	 * 
	 *
	 * @var int
	 */
	public $startTime = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $endTime = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $content = null;


}

class KalturaCaptionAssetItemListResponse extends KalturaObjectBase
{
	/**
	 * 
	 *
	 * @var array of KalturaCaptionAssetItem
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


class KalturaCaptionAssetItemService extends KalturaServiceBase
{
	function __construct(KalturaClient $client = null)
	{
		parent::__construct($client);
	}

	function search(KalturaBaseEntryFilter $entryFilter = null, KalturaCaptionAssetItemFilter $captionAssetItemFilter = null, KalturaFilterPager $captionAssetItemPager = null)
	{
		$kparams = array();
		if ($entryFilter !== null)
			$this->client->addParam($kparams, "entryFilter", $entryFilter->toParams());
		if ($captionAssetItemFilter !== null)
			$this->client->addParam($kparams, "captionAssetItemFilter", $captionAssetItemFilter->toParams());
		if ($captionAssetItemPager !== null)
			$this->client->addParam($kparams, "captionAssetItemPager", $captionAssetItemPager->toParams());
		$this->client->queueServiceActionCall("captionsearch_captionassetitem", "search", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "KalturaCaptionAssetItemListResponse");
		return $resultObject;
	}
}
class KalturaCaptionSearchClientPlugin extends KalturaClientPlugin
{
	/**
	 * @var KalturaCaptionSearchClientPlugin
	 */
	protected static $instance;

	/**
	 * @var KalturaCaptionAssetItemService
	 */
	public $captionAssetItem = null;

	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
		$this->captionAssetItem = new KalturaCaptionAssetItemService($client);
	}

	/**
	 * @return KalturaCaptionSearchClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		if(!self::$instance)
			self::$instance = new KalturaCaptionSearchClientPlugin($client);
		return self::$instance;
	}

	/**
	 * @return array<KalturaServiceBase>
	 */
	public function getServices()
	{
		$services = array(
			'captionAssetItem' => $this->captionAssetItem,
		);
		return $services;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'captionSearch';
	}
}

