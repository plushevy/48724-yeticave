<?php

$link = mysqli_connect("localhost", "root", "", "yeticave_db");
mysqli_set_charset($link, "utf8");

if ($link === false) {

    $error = mysqli_connect_error();
    print("Ошибка: Невозможно подключиться к MySQL " . $error);
    die;
}
