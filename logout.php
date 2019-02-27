<?php

session_start();
// разлогиниваем пользователя
unset($_SESSION['user']);
header("Location: /index.php");

