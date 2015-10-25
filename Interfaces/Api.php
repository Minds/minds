<?php
/**
 * Minds API request interface.
 */
namespace minds\Interfaces;

interface Api{

	public function get($pages);

	public function post($pages);

	public function put($pages);

	public function delete($pages);

}
