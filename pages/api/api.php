<?php
/**
 * Minds API - pseudo router
 * 
 * @version 1
 * @author Mark Harding
 * 
 * @SWG\Swagger(
 *     schemes={"https"},
 *     host="www.minds.com",
 *     basePath="/api/v1",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Swagger Petstore",
 *         description="This is a sample server Petstore server.  You can find out more about Swagger at <a href=""http://swagger.io"">http://swagger.io</a> or on irc.freenode.net, #swagger.  For this sample, you can use the api key ""special-key"" to test the authorization filters",
 *         termsOfService="http://helloreverb.com/terms/",
 *         @SWG\Contact(
 *             email="apiteam@wordnik.com"
 *         ),
 *         @SWG\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about Swagger",
 *         url="http://swagger.io"
 *     )
 * )
 * @SWG\SecurityScheme(
 *   securityDefinition="minds_oauth2",
 *   type="oauth2",
 *   authorizationUrl="https://www.minds.com/oauth2/authorize",
 *   flow="implicit",
 *   scopes={
 *   }
 * )
 * @SWG\Info(title="Minds Public API", version="1.0")
 */
namespace minds\pages\api;

use Minds\Core;
use minds\interfaces;
use Minds\Api\Factory;

class api implements interfaces\api{

	public function get($pages){
        
        return Factory::build($pages);
        
	}
	
	public function post($pages){
	    
        return Factory::build($pages);
        
	}
	
	public function put($pages){
	    
        return Factory::build($pages);
        
	}
	
	public function delete($pages){
	    
        return Factory::build($pages);
        
	}
	
}
