<?php
/**
 * Search events listeners
 */
namespace Minds\Core\Search;

class Client extends \Elasticsearch\Client
{
  public function __construct(array $opts = [])
  {
    $hosts = \elgg_get_plugin_setting('server_addr','search') ?: 'localhost';

    if (!is_array($hosts)) {
      $hosts = [ $hosts ];
    }

    $opts = array_merge([
      'hosts' => $hosts
    ], $opts);

    parent::__construct($opts);
  }
}
