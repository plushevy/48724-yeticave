<?php

require_once('functions.php');
require_once('data.php');
require_once('mysql_helper.php');
require_once('db-connect.php');

define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2mb
define('UPLOAD_IMG_DIR', './img/');


$errors = [];

$allowTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

$requiredFields = [
    'name',
    'email',
    'message',
    'password'
];

$name = '';
$email = '';
$message = '';
$password = '';
$pathToFile = 'img/avatar.jpg';

$isValidRequiredFields = false;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    foreach ($_POST as $field => $value) {

        $value = trim($value);

        if (in_array($field, $requiredFields) && empty($value)) {
            $errors[$field] = 'Заполните это поле';
        }

        if ($field == "password" && strlen($value) < 8) {
            $errors[$field] = "Пароль должен быть не меньше 8 символов";
        }

        if ($field == "message" && strlen($value) < 10) {
            $errors[$field] = "Напишите как с вами связаться. Не менее 10 символов";
        }

        if ($field == "email" && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$field] = "Введите валидный email";
        }

    }

    $isValidRequiredFields = empty($errors);

    // Если все поля проверены - переходим к проверка файла, если он есть, иначе  - img/avatar.jpg
    if ($isValidRequiredFields && isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {

        $img = $_FILES['avatar'];

        $fileName = $img['name'];
        $fileSize = $img['size'];
        $fileType = $img['type'];
        $fileTmpName = $img['tmp_name'];

        if (!in_array($fileType, $allowTypes)) {
            $errors['avatar'] = "Загрузите картинку в формате jpg, jpeg, png, webp";
        }

        if ($fileSize > MAX_FILE_SIZE || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $errors['avatar'] = "Загрузите картинку размером до 2Mb";
        }

        // если картинка подходит - загружаем и переопределяем $pathToFile
        if (!strlen($errors['avatar'])) {

            $ext = getExtensionFromMime($fileType);
            $newFileName = uniqid() . '.' . $ext;
            $pathToFile = UPLOAD_IMG_DIR . $newFileName;
            move_uploaded_file($fileTmpName, $pathToFile);
        }
    }


    // готовим данные для отправки
    $userEmail = strip_tags($_POST['email']);
    $userName = strip_tags($_POST['name']);
    $userPass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userContacts = strip_tags($_POST['message']);
    $imgUrl = $pathToFile;


    // проверка что такого email в БД нет
    $sqlCheckEmail = "SELECT * FROM users WHERE email = ?";
    $isEmailExists = dbGetData($link, $sqlCheckEmail, [$userEmail]);

    if ($isEmailExists) {
        $errors['email'] = "Такой email уже зарегистрирован";
    }

    // Если нет ошибок, отправляем данные в БД
    if (!count($errors)) {

        $addUserSql = "INSERT INTO users 
                          (email, name, password, contacts, img_url) 
                        VALUES
                           (?, ?, ?, ?, ?)";

        $newUserId = dbInsertData($link, $addUserSql, [$userEmail, $userName, $userPass, $userContacts, $imgUrl]);

        // если все ок - переадресация на форму входа (еще нет)
        if ($newUserId && empty($errors)) {
            header("Location: /");
            die();
        }

    }

    // данные для передачи в шаблон
    $name = ($_POST['name']) ? strip_tags($_POST['name']) : '';
    $email = ($_POST['email']) ? strip_tags($_POST['email']) : '';
    $message = ($_POST['message']) ? strip_tags($_POST['message']) : '';
    $password = ($_POST['password']) ? strip_tags($_POST['password']) : '';

}

$pageContent = renderTemplate(
    'sign-up.php',
    [
        'errors' => $errors,
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'password' => $password
    ]);

// запрос для получения списка катеорий
$sqlGetCategories = "SELECT * FROM categories";
$categories = dbGetData($link, $sqlGetCategories);


$layoutContent = renderTemplate(
    'layout.php',
    [
        'content' => $pageContent,
        'categories' => $categories,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'title' => 'Yeticave | Регистрация'
    ]);

print($layoutContent);











