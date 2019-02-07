<?php

require_once ('functions.php');
require_once ('data.php');

$mainPageContent = renderTemplate(
    'index.php',
    [
        'categories' => $categories,
        'items' => $items
    ]);

$layoutContent = renderTemplate(
    'layout.php',
    [
    'content' => $mainPageContent,
    'categories' => $categories,
    'isAuth' => $isAuth,
    'userName' => $userName,
    'title' => 'Yeticave | Главная страница'
    ]);

print($layoutContent);

