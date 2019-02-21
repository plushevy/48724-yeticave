<?php

require_once ('functions.php');
require_once ('data.php');
require_once ('mysql_helper.php');
require_once ('db-connect.php');

if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

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

    $lot = dbGetData($link, $sqlGetLot, [$id])[0];
    if (!$lot) {
        showError404();
    }
    $bets = dbGetData($link, $sqlGetBets, [$id]);
    $categories = dbGetData($link, $sqlGetCategories);


    $lotPageContent = renderTemplate(
        'lot.php',
        [
            'categories' => $categories,
            'lot' => $lot,
            'bets' => $bets
        ]);

    $layoutContent = renderTemplate(
        'layout.php',
        [
            'content' => $lotPageContent,
            'categories' => $categories,
            'isAuth' => $isAuth,
            'userName' => $userName,
            'title' => 'Yeticave | ' . $lot['name']
        ]);

    print($layoutContent);

} else {

    showError404();
}

