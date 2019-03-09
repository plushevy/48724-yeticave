<?php

require_once('init.php');

$errors = [];
$cost = '';
$showAddBet = false;

// в POST проверяем авторизацию и id , в GET - id
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!$isAuth) {
        // незалогиненным нельзя сделать ставку
        // http_response_code(403);
        header("Location: login.php");
        die;
    }

    $lotId = (int)($_POST['id'] ?? '');

} else {

    $lotId = (int)($_GET['id'] ?? '');
}

if (!isset($lotId)) {
    showError404();
}


// запрос для получения лота по id
$sqlGetLot = "
        SELECT
          l.id,
          l.id_user,
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
      u.id as id_user,
      b.last_price as price,
      b.dt_create
    FROM bets b
      JOIN lots l ON l.id = b.id_lot
      JOIN users u ON u.id = b.id_user
    WHERE l.id = ?
    ORDER BY dt_create DESC";

$lot = dbGetData($link, $sqlGetLot, [$lotId]);
if (!$lot) {
    showError404();
}

$lot = $lot[0]; // массив $lot состоит из 1 элемента
$bets = dbGetData($link, $sqlGetBets, [$lotId]);
$categories = dbGetData($link, $sqlGetCategories);
$minBet = $lot['price'] + $lot['bet_step'];
$lotAuthor = $lot['id_user'];
$lotEndDt = $lot['dt_end'];
$isBetAuthor = false;
foreach ($bets as $bet) {
    if ($bet['id_user'] == $userId) {
        $isBetAuthor = true;
        break;
    }
}

// если авторизован, лот не кончился, не создал лот и ставку - то Блок добавления ставки показывать
if (validateEndDate($lotEndDt) && $isAuth && $userId != $lotAuthor && !$isBetAuthor) {
    $showAddBet = true;
}

// Если POST проверяем новую ставку
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $cost = cleanVal($_POST['cost'] ?? '');

    // Ставка должна быть больше $minBet
    $filter_options = [
        'options' => array('min_range' => $minBet)
    ];

    if (!filter_var($cost, FILTER_VALIDATE_INT, $filter_options)) {
        $errors['cost'] = 'Введите число больше мин. ставки';
    }

    // если нет ошибко - добавляем в БД
    if (empty($errors)) {

        $addBetSql = "INSERT INTO bets 
                          (last_price, id_user, id_lot)
                        VALUES
                          (?, ?, ? )";

        $newBetId = dbInsertData($link, $addBetSql, [$cost, $userId, $lotId]);
        header("Location: /lot.php?id=" . $lotId);
        die();
    }

}


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
        'errors' => $errors,
        'lotId' => $lotId,
        'minBet' => $minBet,
        'bets' => $bets,
        'cost' => $cost,
        'showAddBet' => $showAddBet
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



