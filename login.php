<?php

require_once('init.php');

$errors = [];

$email = '';
$password = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['email']) && isset($_POST['password'])) {

        foreach ($_POST as $field => $value) {

            $value = cleanVal($value);

            if ($field == "password" && empty($value)) {
                $errors[$field] = "Введите пароль";
            }

            if ($field == "email" && ((empty($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)))) {
                $errors[$field] = "Введите валидный email";
            }

        }

        // если поля заполнены и нет ошибок
        if (!count($errors)) {

            // готовим данные для отправки
            $formEmail = cleanVal($_POST['email']);
            $formPass = cleanVal($_POST['password']);

            // проверка что такой  email в БД есть
            $sqlCheckEmail = "SELECT * FROM users WHERE email = ?";
            $user = dbGetData($link, $sqlCheckEmail, [$formEmail]);


            if ($user) {

                $user = $user[0];

                // проверка хэша введенного пароля с паролем из БД
                if (password_verify($formPass, $user['password'])) {
                    // сохраняем в сессию данные пользователя. авторизация пройдена
                    $_SESSION['user'] = $user;
                    header("Location: /index.php?success=true");
                    die();
                } else {
                    $errors['password'] = 'Неверный пароль';
                }

            } else {
                $errors['email'] = 'Такой email не зарегистрирован';
            }


        }

    } else {
        // если нет поля email или password в POST
        $errors['email'] = 'Введите email';
        $errors['password'] = 'Введите пароль';
    }


    // данные для передачи в шаблон
    $email = ($_POST['email']) ? cleanVal($_POST['email']) : '';
    $password = ($_POST['password']) ? cleanVal($_POST['password']) : '';

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











