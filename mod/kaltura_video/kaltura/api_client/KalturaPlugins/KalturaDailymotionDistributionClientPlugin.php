<?php
require_once(dirname(__FILE__) . "/../KalturaClientBase.php");
require_once(dirname(__FILE__) . "/../KalturaEnums.php");
require_once(dirname(__FILE__) . "/../KalturaTypes.php");
require_once(dirname(__FILE__) . "/KalturaContentDistributionClientPlugin.php");

class KalturaDailymotionDistributionProfileOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaDailymotionDistributionProviderOrderBy
{
}

abstract class KalturaDailymotionDistributionProfileBaseFilter extends KalturaConfigurableDistributionProfileFilter
{

}

abstract class KalturaDailymotionDistributionProviderBaseFilter extends KalturaDistributionProviderFilter
{

}

class KalturaDailymotionDistributionProfileFilter extends KalturaDailymotionDistributionProfileBaseFilter
{

}

class KalturaDailymotionDistributionProviderFilter extends KalturaDailymotionDistributionProviderBaseFilter
{

}

class KalturaDailymotionDistributionProfile extends KalturaConfigurableDistributionProfile
{
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
	public $password = null;


}

class KalturaDailymotionDistributionProvider extends KalturaDistributionProvider
{

}

class KalturaDailymotionDistributionClientPlugin extends KalturaClientPlugin
{
	/**
	 * @var KalturaDailymotionDistributionClientPlugin
	 */
	protected static $instance;

	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
	}

	/**
	 * @return KalturaDailymotionDistributionClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		if(!self::$instance)
			self::$instance = new KalturaDailymotionDistributionClientPlugin($client);
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
		return 'dailymotionDistribution';
	}
}

