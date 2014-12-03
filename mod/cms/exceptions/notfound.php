<?php
/**
 * 404 exception - not found
 */
 
namespace minds\plugin\cms\exceptions;
 
class notfound extends \Exception{
	
	  protected $message = '404 - not found';
	  protected $code = 404;
	  
}
