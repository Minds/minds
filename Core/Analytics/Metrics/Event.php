<?php
namespace Minds\Core\Analytics\Metrics;

use Minds\Helpers;
use Minds\Core;
use Minds\Core\Analytics\Timestamps;
use Minds\Interfaces\AnalyticsMetric;

/**
 * Active Metric
 */
class Event
{
    private $elatic;
    private $index = "minds-metrics-";
    private $data;

    public function __construct($elastic = null)
    {
        $this->elastic = $elastic ?: $this->db = Core\Di\Di::_()->get('Database\ElasticSearch');
        $this->index = "minds-metrics-" . date('m-Y', time());
    }

    public function setUserGuid($guid)
    {
        $this->data['user_guid'] = $guid;
        return $this;
    }

    public function push()
    {
        $this->data['@timestamp'] = (int) time() * 1000;

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
        return $this->elastic->request($prepared);
    }

    /**
     * Magic method for getter and setters.
     */
     public function __call($name, array $args = [])
     {
         if (strpos($name, 'set', 0) === 0) {
             $attribute = str_replace('set', '', $name);
             $attribute = lcfirst($attribute);
             $this->data[$attribute] = $args[0];
             return $this;
         }
         if (strpos($name, 'get', 0) === 0) {
             $attribute = str_replace('get', '', $name);
             $attribute = lcfirst($attribute);
             return $this->data[$attribute];
         }
         return $this;
     }

}
