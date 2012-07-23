<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

// Get engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the specified market post
$marketguid = (int) get_input('marketguid');

$marketpost = get_entity($marketguid);
if (!$marketpost || $marketpost->getSubtype() != "market") {
	exit;
}

$market_img = elgg_view('output/url', array(
			'href' => "market/view/{$marketpost->guid}/" . elgg_get_friendly_title($marketpost->title),
			'text' => elgg_view('market/thumbnail', array(
								'marketguid' => $marketpost->guid,
								'size' => 'master',
								'class' => 'market-image-popup',
								)),
			));
			
echo "<p style='width: 600px;'>";
echo "<h3>{$marketpost->title}</h3>";
echo $market_img;
echo "</p><br>";

