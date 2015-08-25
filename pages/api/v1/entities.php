<?php
/**
 * Minds Entities API
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use minds\interfaces;
use Minds\Api\Factory;

class entities implements interfaces\api{

    /**
     * Returns the entities
     * @param array $pages
     *
     * @SWG\GET(
     *     tags={"entities"},
     *     summary="Returns basic entities",
     *     path="/v1/entities",
     *     @SWG\Parameter(
     *      name="type",
     *      in="query",
     *      description="Type (eg. object, user, activity)",
     *      required=false,
     *      type="string"
     *     ),
     *     @SWG\Parameter(
     *      name="subtype",
     *      in="query",
     *      description="Subtype (eg. video, image, blog)",
     *      required=false,
     *      type="string"
     *     ),
     *     @SWG\Parameter(
     *      name="owner_guid",
     *      in="query",
     *      description="The owner of the content to return",
     *      required=false,
     *      type="integer"
     *     ),
     *     @SWG\Parameter(
     *      name="limit",
     *      in="query",
     *      description="Limit the number of returned entities",
     *      required=false,
     *      type="integer"
     *     ),
     *     @SWG\Parameter(
     *      name="offset",
     *      in="query",
     *      description="Pagination. Include the entity guid to start the list from",
     *      required=false,
     *      type="integer"
     *     ),
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function get($pages){

        $type = "object";
        $subtype = NULL;
        switch($pages[0]){
          case "all":
            $type="user";
        }

        //the allowed, plus default, options
        $options = array(
            'type' => $type,
            'subtype' => $subtype,
            'owner_guid'=> NULL,
            'limit'=>12,
            'offset'=>''
            );

        foreach($options as $key => $value){
            if(isset($_GET[$key]))
                $options[$key] = $_GET[$key];
        }


        $entities = core\entities::get($options);

        if($entities){
            $response['entities'] = factory::exportable($entities);
            $response['load-next'] = (string) end($entities)->guid;
            $response['load-previous'] = (string) key($entities)->guid;
        }

        return Factory::response($response);

    }

    public function post($pages){}

    public function put($pages){}

    public function delete($pages){}

}
