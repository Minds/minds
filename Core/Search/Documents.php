<?php
/**
 * Search events listeners
 */
namespace Minds\Core\Search;

use Minds\Core;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Entities;

class Documents
{
    /** @var Core\Data\ElasticSearch\Client $client */
    protected $client;

    public function __construct($client = null, $index = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
        $this->index = $index ?: Config::_()->elasticsearch['index'];
    }

  /**
   * Get GUIDs that match the provided query
   * @deprecated
   * @param  string $query
   * @param  array  $opts
   * @return array
   */
  public function query($query, array $opts = [])
  {
      $opts = array_merge([
        'limit' => 12,
        'type' => null,
        'offset' => '',
        'flags' => [ ],
        'mature' => null,
        'container' => ''
      ], $opts);

      $query = preg_replace('/[^A-Za-z0-9_\-#"+]/', ' ', $query);
      $flags = '';

      // Passed flags (type, subtype, ~, etc.)
      if (!is_array($opts['flags'])) {
          $opts['flags'] = [ $opts['flags'] ];
      }

      foreach ($opts['flags'] as $flag) {
          if ($flag != '~') {
              $flags .= ' ';
          }

          $flags .= $flag;
      }

      // Transform hashtags to `field:"value"` form and put into $flags
      // Then remove the hashtags from main query
      $htRe = '/(^|\s)#(\w*[a-zA-Z_]+\w*)/';
      $matches = [];

      $hashtags = false;
      preg_match_all($htRe, $query, $matches);

      if (isset($matches[2]) && $matches[2]) {
          $matches[2] = array_unique($matches[2]);
          $hashtags = true;

          foreach ($matches[2] as $match) {
              $flags .= " +tags:\"{$match}\"";
          }

          $query = preg_replace($htRe, '', $query);
      }

      $hasContainer = false;
      if ($opts['container']) {
          $container = Entities\Factory::build($opts['container']);

          if (Core\Security\ACL::_()->read($container)) {
              $flags .= " +container_guid:\"{$container->guid}\"";
              $hasContainer = true;
          }
      }

      if (!$hasContainer) {
          $flags .= " +public:true";
      }

      if (isset($opts['mature'])) {
          $flags .= " +mature:" . ($opts['mature'] ? 'true' : 'false');
      }

      // Setup parameters
      $params = [
        'size' => $opts['limit'],
        'index' => $this->index,
        'body' => [
          'query' => [
            'query_string' => [
              'query' => $query . $flags,
              'default_operator' => 'AND',
              'minimum_should_match' => '75%',
              'fields' => [ '_all', 'name^6', 'title^8', 'username^8', 'tags^12' ],
             ]
           ]
         ]
      ];

      if ($hashtags) {
          $params['body']['sort'] = [
            [ '_uid' => 'desc' ]
          ];
      }

      if ($opts['type']) {
          $params['type'] = $opts['type'];
      }

      if ($opts['offset']) {
          $params['from'] = $opts['offset'];
      }

      $guids = [];

      try {
          $prepared = new Prepared\Search();
          $prepared->query($params);

          $results = $this->client->request($prepared);

          foreach ($results['hits']['hits'] as $result) {
              $guids[] = $result['_id'];
          }    
      } catch (\Exception $e) {
          var_dump($e);
          exit;
      }

      return $guids;
  }

  public function suggestQuery($query, array $opts = [])
  {
      $params = [
        'index' => $this->index,
        'body' => [
          'suggest' => [
            'text' => $query,
            'autocomplete' => [
              'completion' => [
                'field' => 'suggest'
              ]
            ]
          ]
        ]
      ];

      try {
          $prepared = new Prepared\Search();
          $prepared->query($params);

          $suggestions = $this->client->request($prepared);
          return $suggestions;
      } catch (\Exception $e) {
        echo 2;
          var_dump($e);
          exit;
      }

  }

  public function customQuery($opts = [])
  {

      if (!$opts || empty($opts)) {
          return [];
      }

      try {
          $prepared = new Prepared\Search();
          $prepared->query($opts);

          return $this->client->request($prepared);
      } catch (\Exception $e) {
          return [];
      }
  }

  public static function escapeQuery($query = '')
  {
    $query = (string) $query;
    $query = str_replace(['<', '>'], '', $query);

    $reservedChars = str_split('\+-=&|><!(){}[]^"~*?:/'); // backslash always first
    foreach ($reservedChars as $reservedChar) {
        $query = str_replace($reservedChar, '\\' . $reservedChar, $query);
    }

    return $query;
  }
}
