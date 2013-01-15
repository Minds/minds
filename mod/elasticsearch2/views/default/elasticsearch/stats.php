<?php
/**
 * Elastic Search 
 *
 * @package elasticsearch
 */

$stats = $vars['stats'];

echo $stats['hits']['total'] . ' results in ' . round($stats['took']/60, 3) . ' seconds'; //@make this multi lingual
