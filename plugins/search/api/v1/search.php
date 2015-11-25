<?php
/**
 * Minds Search API
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\search\api\v1;

use Minds\Core;
use Minds\Interfaces;
use Minds\Api\Factory;

class search implements Interfaces\Api{

    /**
     * Searches
     * @param array $pages
     *
     * API:: /v1/search
     */
    public function get($pages){

        global $CONFIG;
        $client = new \Elasticsearch\Client(array(
          'hosts'=>array(\elgg_get_plugin_setting('server_addr','search')?:'localhost')
        ));
        $params = array();

        if(!isset($_GET['q']) || !$_GET['q'])
          return Factory::response(array('status'=>'error', 'message'=>"?q= was not provided"));

        $query = preg_replace("/[^A-Za-z0-9_]/", ' ', $_GET['q']);

        $query = "$query";

        //$category = isset($_GET['category']) ? $_GET['category'] : NULL;
        //if($category)
        //  $query = "query AND $category";

        $params['index'] = $CONFIG->cassandra->keyspace; //we use the keyspace as this is unique to each site. why complicate things?

        if(isset($pages[0]))
          $params['type'] = $pages[0];

        if(isset($_GET['type']))
          $params['type'] = $_GET['type'];

        if($params['type']){
          switch($params['type']){
            case "channels":
              $query .= "~";
              $params['type'] = 'user';
              break;
            case "videos":
              $params['type'] = 'object';
              $query .= ' +subtype:"video"';
              break;
            case "images":
              $params['type'] = 'object';
              $query .= ' +subtype:"image"';
              break;
            case "blogs":
              $params['type'] = 'object';
              $query .= ' +subtype:"blog"';
              break;
          }
        }

        $params['size'] = $_GET['limit'] ?: 12;
        if(isset($_GET['offset']) && $_GET['offset'])
          $params['from'] = $_GET['offset'];

        $body['query']['query_string']['query'] = $query;
        $body['query']['query_string']['default_operator'] = "AND";
        $body['query']['query_string']['minimum_should_match'] = "75%";
        $body['query']['query_string']['fields'] = array('_all', 'name^6', 'title^8', 'username^8');

        $params['body']  = $body;

        try{
          $results = $client->search($params);
          $guids = array();
          foreach($results['hits']['hits'] as $raw){
            array_push($guids, $raw['_id']);
          }
        }catch(\Exception $e){
            $guids = array();
        }

        $response = array();
        if($guids)
          $response['entities'] = Factory::exportable(Core\Entities::get(array('guids'=>$guids)));
        
        if($_GET['access_token']){
            $response[$params['type']][] = $response['entities'];
        }

       // $response['hits'] = $results['hits']['hits'];
        $response['load-next'] = (int) $_GET['offset'] + $_GET['limit'] + 1;

        return Factory::response($response);
    }

    public function post($pages){
      return Factory::response(array());
    }

    public function put($pages){
      return Factory::response(array());
    }

    public function delete($pages){
      return Factory::response(array());
    }

}
