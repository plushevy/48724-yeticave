<?php

require_once('init.php');

if (!$isAuth) {
    // незалогиненным вход запрещен
    // http_response_code(403);
    header("Location: login.php");
    die;
}


$sqlGetMyBets = "
                SELECT l.id as lot_id, l.label as name, b.last_price as price, b.dt_create, u.name as lot_author, u.contacts as lot_author_contacts, l.id_winner
                FROM bets b
                JOIN lots l ON b.id_lot = l.id
                JOIN users u ON l.id_user = u.id
                WHERE b.id_user = ?
                ORDER BY b.dt_create DESC";

$myBets = dbGetData($link, $sqlGetMyBets, [$userId]);


// запрос для получения списка катеорий
$sqlGetCategories = "SELECT * FROM categories";
$categories = dbGetData($link, $sqlGetCategories);

// список категорий
$navCategories = renderTemplate(
    'nav.php',
    [
        'categories' => $categories
    ]);


$pageContent = renderTemplate(
    'my-bets.php',
    [
        'navCategories' => $navCategories,
        'categories' => $categories,
        'myBets' => $myBets,
        'userId' => $userId
    ]);


$layoutContent = renderTemplate(
    'layout.php',
    [
        'content' => $pageContent,
        'navCategories' => $navCategories,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'title' => 'Yeticave | Мои ставки'
    ]);

print($layoutContent);











