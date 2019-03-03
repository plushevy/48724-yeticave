<?php

require_once('init.php');

$categoryId = (int) ($_GET['id'] ?? '');

if (!isset($categoryId)) {
    showError404();
}


$currentPage = (int) ($_GET['page'] ?? 1);
$items = [];
$pages = [1];
$pageItems = 3;
$pagesCount = 0;
$offset = 0;
$linkParam = "all-lots.php?id={$categoryId}&";


// пагинация. узнаем кол-во лотов в ответе
$sqlGetCount = "
                SELECT  count(l.id) as cnt
                FROM lots l
                       JOIN categories c ON c.id = l.id_category
                WHERE l.dt_end > CURDATE() AND c.id = ?
                ORDER BY l.dt_add DESC";

$itemsCount = dbGetData($link, $sqlGetCount, [$categoryId]);
$itemsCount = $itemsCount[0]['cnt'] ?? 0;

//вычисляем смещение и кол-во страниц
$pagesCount = ceil($itemsCount / $pageItems);
$offset = ($currentPage - 1) * $pageItems;

$pages = range(1, $pagesCount);



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
                WHERE l.dt_end > CURDATE() AND  c.id =  ?
                GROUP BY l.id
                ORDER BY l.dt_add DESC 
                LIMIT {$pageItems} OFFSET {$offset}";

$items = dbGetData($link, $sqlGetLots, [$categoryId]);




// запрос для получения списка катеорий
$sqlGetCategories = "SELECT * FROM categories";
$categories = dbGetData($link, $sqlGetCategories);
$categoryName = dbGetData($link, "SELECT name FROM categories WHERE id = ? ", [$categoryId]);
$categoryName = $categoryName[0]['name'] ?? '';

// список категорий
$navCategories = renderTemplate(
    'nav.php',
    [
        'categories' => $categories
    ]);

// пагинация
$pagination = renderTemplate(
    'pagination.php',
    [
        'pages' => $pages,
        'pagesCount' => $pagesCount,
        'currentPage' => $currentPage,
        'linkParam' => $linkParam ?? '?'
    ]);


$mainPageContent = renderTemplate(
    'all-lots.php',
    [
        'navCategories' => $navCategories,
        'items' => $items,
        'categoryName' => $categoryName,
        'pagesCount' => $pagesCount,
        'pagination' => $pagination
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

