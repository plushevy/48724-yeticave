<?php

require_once('init.php');

$search = cleanVal($_GET['search'] ?? '');
$items = [];

if ($search) {
    // запрос для получения списка новых лотов
    $sqlGetLots = "SELECT * FROM lots
                   WHERE MATCH (label, description) AGAINST( ? IN BOOLEAN MODE)";
    $items = dbGetData($link, $sqlGetLots, [$search]);

    debug($items);
}



// запрос для получения списка катеорий
$sqlGetCategories = "SELECT * FROM categories";
$categories = dbGetData($link, $sqlGetCategories);

// список категорий
$navCategories = renderTemplate(
    'nav.php',
    [
        'categories' => $categories
    ]);


$mainPageContent = renderTemplate(
    'search.php',
    [
        'navCategories' => $navCategories,
        'items' => $items,
        'search' => $search
    ]);

$layoutContent = renderTemplate(
    'layout.php',
    [
        'content' => $mainPageContent,
        'navCategories' => $navCategories,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'title' => 'Yeticave | Поиск'
    ]);

print($layoutContent);

