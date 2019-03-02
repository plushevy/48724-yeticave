<?php

require_once('init.php');

$search = cleanVal($_GET['search'] ?? '');
$items = [];

if ($search) {
    // запрос для получения списка новых лотов
    $sqlGetLots = "
                    SELECT l.id,
                           l.label as name,
                           l.start_price,
                           l.img_url as image,
                           IFNULL(max(b.last_price),
                                  l.start_price) as price,
                           l.dt_end,
                           c.name as category
                    FROM lots l
                           LEFT JOIN bets b ON b.id_lot = l.id
                           JOIN categories c ON c.id = l.id_category
                    WHERE l.dt_end > CURDATE() AND  MATCH (label, description) AGAINST(? IN BOOLEAN MODE)
                    GROUP BY l.id
                    ORDER BY l.dt_add DESC";

    $items = dbGetData($link, $sqlGetLots, [$search]);

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

