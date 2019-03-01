<?php

require_once('init.php');


if (!isset($_GET['id'])) {
    showError404();
}

$id = (int) $_GET['id'];
$errors = [];
$cost = '';
$minBet = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!$isAuth) {
        // незалогиненным нельзя сделать ставку
        // http_response_code(403);
        header("Location: login.php");
        die;
    }

    $cost = cleanVal($_POST['cost']) ?? '';
    $id = cleanVal($_POST['id']) ?? '';

}


// запрос для получения лота по id
$sqlGetLot = "
        SELECT
          l.id,
          l.label as name,
          l.dt_end,
          l.description,
          l.bet_step,
          l.img_url as image,
          c.name as category,
          (SELECT IFNULL(MAX(b.last_price), l.start_price) FROM bets b WHERE b.id_lot = l.id) AS price
        FROM lots l
          JOIN categories c ON c.id = l.id_category
        WHERE l.id = ?
        GROUP BY l.id;
        ";

// запрос для получения списка катеорий
$sqlGetCategories = "SELECT * FROM categories";

// запрос для получения ставок у лота
$sqlGetBets = "
    SELECT
      u.name,
      b.last_price as price,
      b.dt_create
    FROM bets b
      JOIN lots l ON l.id = b.id_lot
      JOIN users u ON u.id = b.id_user
    WHERE l.id = ?
    ORDER BY dt_create DESC";

$lot = dbGetData($link, $sqlGetLot, [$id]);
if (!$lot) {
    showError404();
}
$lot = $lot[0]; // массив $lot состоит из 1 элемента
$bets = dbGetData($link, $sqlGetBets, [$id]);
$categories = dbGetData($link, $sqlGetCategories);
$minBet = $lot['price'] + $lot['bet_step'];






// список категорий
$navCategories = renderTemplate(
    'nav.php',
    [
        'categories' => $categories
    ]);


$lotPageContent = renderTemplate(
    'lot.php',
    [
        'navCategories' => $navCategories,
        'lot' => $lot,
        'id' => $id,
        'minBet' => $minBet,
        'bets' => $bets,
        'cost' => $cost
    ]);

$layoutContent = renderTemplate(
    'layout.php',
    [
        'content' => $lotPageContent,
        'navCategories' => $navCategories,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'title' => 'Yeticave | ' . $lot['name']
    ]);

print($layoutContent);



