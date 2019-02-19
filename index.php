<?php

require_once ('functions.php');
require_once ('data.php');
require_once ('mysql_helper.php');
require_once ('db-connect.php');

// запрос для получения списка новых лотов
$sqlGetLots = "
SELECT  l.id, l.label as name, l.start_price, l.img_url as image, IFNULL(max(b.last_price), l.start_price) as price, c.name as category
FROM lots l
  LEFT JOIN bets b ON b.id_lot = l.id
  JOIN categories c ON c.id = l.id_category
WHERE l.dt_end > CURDATE()
GROUP BY l.id
ORDER BY l.dt_add DESC";

// запрос для получения списка катеорий
$sqlGetCategories = "SELECT * FROM categories";

$items = dbGetData($link, $sqlGetLots);
$categories = dbGetData($link, $sqlGetCategories);

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

