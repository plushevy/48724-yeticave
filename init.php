<?php

require_once('functions.php');
require_once('mysql_helper.php');
require_once('db-connect.php');

session_start();

$isAuth = isset($_SESSION['user']);
$userName = ($isAuth) ? $_SESSION['user']['name'] : '';
$userId = ($isAuth) ? $_SESSION['user']['id'] : '';


