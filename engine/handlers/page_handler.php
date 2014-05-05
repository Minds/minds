<?php
/**
 * 
 */


require_once(dirname(dirname(__FILE__)) . "/start.php");

$router = new minds\core\router();
$router->route();
exit;

$handler = get_input('handler');
$page = get_input('page');

if (!page_handler($handler, $page)) {
	//try a profile then
	if(!page_handler('channel', "$handler/$page")){
		//forward('', '404');
		header("HTTP/1.0 404 Not Found");
		$buttons = elgg_view('output/url', array('onclick'=>'window.history.back()', 'text'=>'Go back...', 'class'=>'elgg-button elgg-button-action'));
		$header = <<<HTML
<div class="elgg-head clearfix">
	<h2>404</h2>
	<h3>Ooooopppsss.... we couldn't find the page you where looking for! </h3>
	<div class="front-page-buttons">
		$buttons
	</div>
</div>
HTML;
		$body = elgg_view_layout( "one_column", array(
								'content' => null, 
								'header'=>$header
								));
		echo elgg_view_page('404', $body);
	}
}
