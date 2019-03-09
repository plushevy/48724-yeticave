<?php

require_once('init.php');

$search = cleanVal($_GET['search'] ?? '');
$currentPage = (int)($_GET['page'] ?? 1);
$items = [];
$pages = [1];
$pageItems = 9;
$pagesCount = 0;
$offset = 0;
$linkParam = "search.php?search={$search}&";

if ($search) {

    // пагинация. узнаем кол-во лотов в ответе
    $sqlGetCount = "
        SELECT COUNT(*) as cnt
        FROM lots l
        WHERE l.dt_end > CURDATE() AND  MATCH (label, description) AGAINST(? IN BOOLEAN MODE)";

    $itemsCount = dbGetData($link, $sqlGetCount, [$search]);
    $itemsCount = (int)$itemsCount[0]['cnt'];

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
                    WHERE l.dt_end > CURDATE() AND  MATCH (label, description) AGAINST(? IN BOOLEAN MODE)
                    GROUP BY l.id
                    ORDER BY l.dt_add DESC 
                    LIMIT {$pageItems} OFFSET {$offset}";

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
    'search.php',
    [
        'navCategories' => $navCategories,
        'items' => $items,
        'pagesCount' => $pagesCount,
        'pagination' => $pagination,
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

