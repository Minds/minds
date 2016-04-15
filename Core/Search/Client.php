<?php
/**
 * Search events listeners
 */
namespace Minds\Core\Search;

use Minds\Core\Config;

class Client extends \Elasticsearch\Client
{
  public function __construct(array $opts = [])
  {
    $hosts = Config::_()->elasticsearch_hosts ?: 'localhost';

    if (!is_array($hosts)) {
      $hosts = [ $hosts ];
    }

    $opts = array_merge([
      'hosts' => $hosts
    ], $opts);

    parent::__construct($opts);
  }
}
