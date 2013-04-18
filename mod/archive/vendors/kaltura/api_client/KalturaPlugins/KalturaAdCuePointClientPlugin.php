<?php
require_once(dirname(__FILE__) . "/../KalturaClientBase.php");
require_once(dirname(__FILE__) . "/../KalturaEnums.php");
require_once(dirname(__FILE__) . "/../KalturaTypes.php");
require_once(dirname(__FILE__) . "/KalturaCuePointClientPlugin.php");

class KalturaAdCuePointOrderBy
{
	const END_TIME_ASC = "+endTime";
	const END_TIME_DESC = "-endTime";
	const DURATION_ASC = "+duration";
	const DURATION_DESC = "-duration";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const START_TIME_ASC = "+startTime";
	const START_TIME_DESC = "-startTime";
	const PARTNER_SORT_VALUE_ASC = "+partnerSortValue";
	const PARTNER_SORT_VALUE_DESC = "-partnerSortValue";
}

class KalturaAdProtocolType
{
	const CUSTOM = "0";
	const VAST = "1";
	const VAST_2_0 = "2";
	const VPAID = "3";
}

class KalturaAdType
{
	const VIDEO = "1";
	const OVERLAY = "2";
}

abstract class KalturaAdCuePointBaseFilter extends KalturaCuePointFilter
{
	/**
	 * 
	 *
	 * @var KalturaAdProtocolType
	 */
	public $protocolTypeEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $protocolTypeIn = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $titleLike = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $titleMultiLikeOr = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $titleMultiLikeAnd = null;

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

	/**
	 * 
	 *
	 * @var int
	 */
	public $durationGreaterThanOrEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $durationLessThanOrEqual = null;


}

class KalturaAdCuePointFilter extends KalturaAdCuePointBaseFilter
{

}

class KalturaAdCuePoint extends KalturaCuePoint
{
	/**
	 * 
	 *
	 * @var KalturaAdProtocolType
	 * @insertonly
	 */
	public $protocolType = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $sourceUrl = null;

	/**
	 * 
	 *
	 * @var KalturaAdType
	 */
	public $adType = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $title = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $endTime = null;

	/**
	 * Duration in milliseconds
	 *
	 * @var int
	 * @readonly
	 */
	public $duration = null;


}

class KalturaAdCuePointClientPlugin extends KalturaClientPlugin
{
	/**
	 * @var KalturaAdCuePointClientPlugin
	 */
	protected static $instance;

	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
	}

	/**
	 * @return KalturaAdCuePointClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		if(!self::$instance)
			self::$instance = new KalturaAdCuePointClientPlugin($client);
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
		return 'adCuePoint';
	}
}

