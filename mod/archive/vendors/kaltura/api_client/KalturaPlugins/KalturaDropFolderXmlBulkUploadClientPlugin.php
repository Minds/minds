<?php
require_once(dirname(__FILE__) . "/../KalturaClientBase.php");
require_once(dirname(__FILE__) . "/../KalturaEnums.php");
require_once(dirname(__FILE__) . "/../KalturaTypes.php");
require_once(dirname(__FILE__) . "/KalturaDropFolderClientPlugin.php");

class KalturaDropFolderXmlBulkUploadFileHandlerConfig extends KalturaDropFolderFileHandlerConfig
{

}

class KalturaDropFolderXmlBulkUploadClientPlugin extends KalturaClientPlugin
{
	/**
	 * @var KalturaDropFolderXmlBulkUploadClientPlugin
	 */
	protected static $instance;

	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
	}

	/**
	 * @return KalturaDropFolderXmlBulkUploadClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		if(!self::$instance)
			self::$instance = new KalturaDropFolderXmlBulkUploadClientPlugin($client);
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
		return 'dropFolderXmlBulkUpload';
	}
}

