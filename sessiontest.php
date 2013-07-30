<?php

session_start(); 

var_dump($_SESSION['testing']);
$_SESSION['testing'] = 'hello';
