<?php

namespace minds\plugin\thumbs;

use Minds\Components;
use Minds\Core;
use Minds\Api;

class start extends Components\Plugin{

	public function init(){
		Api\Routes::add('v1/thumbs', "\\minds\\plugin\\thumbs\\api\\v1\\thumbs");
	}

}
