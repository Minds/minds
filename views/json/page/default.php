<?php
/**
 * Elgg JSON output pageshell
 *
 * @package Elgg
 * @subpackage Core
 *
 */

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

global $jsonexport;
echo json_encode($jsonexport);
