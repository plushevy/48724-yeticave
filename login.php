<?php

require_once('init.php');

$errors = [];

$email = '';
$password = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // данные для передачи в шаблон
    $email = cleanVal($_POST['email'] ?? '');
    $password = cleanVal($_POST['password'] ?? '');


    if (empty($password)) {
        $errors['password'] = "Введите пароль";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Введите валидный email";
    }

    // если поля заполнены и нет ошибок
    if (!count($errors)) {

        // проверка что такой  email в БД есть
        $sqlCheckEmail = "SELECT * FROM users WHERE email = ?";
        $user = dbGetData($link, $sqlCheckEmail, [$email]);


        if ($user) {

            $user = $user[0];

            // проверка хэша введенного пароля с паролем из БД
            if (password_verify($password, $user['password'])) {
                // сохраняем в сессию данные пользователя. авторизация пройдена
                $_SESSION['user'] = $user;
                header("Location: /index.php?success=true");
                die();
            } else {
                $errors['password'] = 'Неверный пароль';
            }

        } else {
            // если юзера в БД не нашли
            $errors['email'] = 'Такой email не зарегистрирован';
        }


    }


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

$pageContent = renderTemplate(
    'login.php',
    [
        'navCategories' => $navCategories,
        'errors' => $errors,
        'email' => $email,
        'password' => $password

    ]);


$layoutContent = renderTemplate(
    'layout.php',
    [
        'content' => $pageContent,
        'navCategories' => $navCategories,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'title' => 'Yeticave | Вход на сайт'
    ]);

print($layoutContent);











