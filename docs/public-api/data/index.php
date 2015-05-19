<?php
header('Content-Type: application/json');
readfile(__DIR__.'/api-docs.json');