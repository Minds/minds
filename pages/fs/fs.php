<?php
/**
 * Minds FS - pseudo router
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace minds\pages\fs;

use Minds\Core;
use Minds\Interfaces;
use Minds\Fs\Factory;

class fs implements Interfaces\fs{

	public function get($pages){

      return Factory::build($pages);

	}

}
