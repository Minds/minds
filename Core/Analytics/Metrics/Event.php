<?php
namespace Minds\Core\Analytics\Metrics;

use Minds\Helpers;
use Minds\Core;
use Minds\Core\Analytics\Timestamps;
use Minds\Interfaces\AnalyticsMetric;

/**
 * Class Event
 * @package Minds\Core\Analytics\Metrics
 * @method Event setType($value)
 * @method Event setAction($value)
 * @method Event setProduct($value)
 * @method Event setUserPhoneNumberHash($value)
 * @method Event setEntityGuid($value)
 * @method Event setEntityContainerGuid($value)
 * @method Event setEntityAccessId($value)
 * @method Event setEntityType($value)
 * @method Event setEntitySubtype($value)
 * @method Event setEntityOwnerGuid($value)
 * @method Event setCommentGuid($value)
 */
class Event
{
    private $elatic;
    private $index = "minds-metrics-";
    protected $data;

    public function __construct($elastic = null)
    {
        $this->elastic = $elastic ?: Core\Di\Di::_()->get('Database\ElasticSearch');
        $this->index = "minds-metrics-" . date('m-Y', time());
    }

    public function setUserGuid($guid)
    {
        $this->data['user_guid'] = (string) $guid;
        return $this;
    }

    public function push()
    {
        $this->data['@timestamp'] = (int) microtime(true) * 1000;

        if (!isset($this->data['platform'])) {
            $platform = isset($_REQUEST['cb']) ? 'mobile' : 'browser';
            if (isset($_REQUEST['platform'])) { //will be the sole method once mobile supports
                $platform = $_REQUEST['platform'];
            }
            $this->data['platform'] = $platform;
        }

        $prepared = new Core\Data\ElasticSearch\Prepared\Index();
        $prepared->query([
            'body' => $this->data,
            'index' => $this->index,
            'type' => $this->data['type'],
            //'id' => $data['guid'],
            'client' => [
                'timeout' => 2,
                'connect_timeout' => 1
            ] 
        ]);

        try {
            return $this->elastic->request($prepared);
        } catch (\Exception $e) { }
    }

    /**
     * Magic method for getter and setters.
     */
     public function __call($name, array $args = [])
     {
        if (strpos($name, 'set', 0) === 0) {
            $attribute = str_replace('set', '', $name);
            $attribute = implode('_', preg_split('/([\s])?(?=[A-Z])/', $attribute, -1, PREG_SPLIT_NO_EMPTY));
            $attribute = strtolower($attribute);
            $this->data[$attribute] = $args[0];
            return $this;
        }
     }

     public function getData()
     {
         return $this->data;
     }

}
