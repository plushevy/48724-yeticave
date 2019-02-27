<?php

session_start();
// разлогиниваем пользователя
session_destroy();
header("Location: /index.php");
die;

