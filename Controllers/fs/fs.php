<?php
/**
 * Minds FS - pseudo router
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\fs;

use Minds\Core;
use Minds\Interfaces;
use Minds\Fs\Factory;

class fs implements Interfaces\Fs{

	public function get($pages){

      return Factory::build($pages);

	}

}
