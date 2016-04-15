<?php
/**
 * Search events listeners
 */
namespace Minds\Core\Search;

use Minds\Core\Config;
use Minds\Core\Search\Client;

class Documents
{
  protected $client;

  public function __construct($client = null, $index = null)
  {
    $this->client = $client ?: new Client();
    $this->index = $index ?: Config::_()->cassandra->keyspace;
  }

  /**
   * Creates or updates a document
   * @param  mixed   $data
   * @return boolean
   */
  public function index($data = null)
  {
    if (is_object($data)) {
      if (method_exists($data, 'export')) {
        $data = $data->export();
      } else {
        $data = (array) $data;
      }
    }

    if (!is_array($data)) {
      throw new \Exception('Invalid data');
    }

    if (!isset($data['type']) || !$data['type']) {
      throw new \Exception('Missing data type');
    }

    if (!isset($data['guid']) || !$data['guid']) {
      throw new \Exception('Missing data guid');
    }

    $body = $this->formatDocumentBody($data);
    $fullTextBody = $this->getFullTextBody($data);

    if (!$body) {
      throw new \Exception('Empty data body');
    }

    // Get hashtags to put them into a field
    $htRe = '/(^|\s)#(\w*[a-zA-Z_]+\w*)/';
    $matches = [];

    preg_match_all($htRe, $fullTextBody, $matches);

    if (isset($matches[2]) && $matches[2]) {
        $body['hashtags'] = array_unique($matches[2]);
    }

    $params = [
      'body' => $body,
      'index' => $this->index,
      'type' => $data['type'],
      'id' => $data['guid'],
    ];

    // error_log("indexing for search: {$this->index}/{$data['type']}/{$data['guid']}");
    // error_log(print_r($body, 1));

    // Document path: index/type/guid
    $result = $this->client->index($params);
  }

  /**
   * Get GUIDs that match the provided query
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
      'flags' => [ ]
    ], $opts);

    $query = preg_replace('/[^A-Za-z0-9_\-#"]/', ' ', $query);
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

    preg_match_all($htRe, $query, $matches);

    if (isset($matches[2]) && $matches[2]) {
        $matches[2] = array_unique($matches[2]);

        foreach ($matches[2] as $match) {
            $flags .= " +hashtags:\"{$match}\"";
        }

        $query = preg_replace($htRe, '', $query);
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
            'fields' => [ '_all', 'name^6', 'title^8', 'username^8', 'hashtags^8' ],
          ]
        ]
      ]
    ];

    if ($opts['type']) {
      $params['type'] = $opts['type'];
    }

    if ($opts['offset']) {
      $params['from'] = $opts['offset'];
    }

    $guids = [];

    try {
      $results = $this->client->search($params);

      foreach ($results['hits']['hits'] as $result) {
        $guids[] = $result['_id'];
      }
    } catch (\Exception $e) { }

    return $guids;
  }

  /**
   * Formats a document for storing
   * @param  array $data
   * @return array
   */
  public function formatDocumentBody(array $data = [])
  {
    $body = [];

    foreach ($data as $item => $value) {
      if (is_bool($value)) {
        continue;
      } elseif (is_numeric($value)) {
        $value = (string) $value;
      } elseif (is_object($value) && method_exists($value, 'export')) {
        $value = $value->export();
      }

      $body[$item] = $value;
    }

    return $body;
  }

  /**
   * Gets the full text format for a set of data
   * @param  array $data
   * @return array
   */
  public function getFullTextBody(array $data = [])
  {
    $body = [];

    foreach ($data as $item => $value) {
      if (!is_string($value)) {
          continue;
      }

      $body[] = $value;
    }

    return implode(' ', $body);
  }
}
