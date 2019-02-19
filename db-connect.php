<?php

$link = mysqli_connect("localhost", "root", "root", "yeticave_db");

if ($link == false){

    $error = mysqli_connect_error();
    print("Ошибка: Невозможно подключиться к MySQL " .  $error );
    die;
}
