<?php

require_once('init.php');

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
$pathToFile = '';

$isValidRequiredFields = false;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = cleanVal($_POST['name']) ?? '';
    $email = cleanVal($_POST['email']) ?? '';
    $message = cleanVal($_POST['message']) ?? '';
    $password = cleanVal($_POST['password']) ?? '';


    foreach ($_POST as $field => $value) {

        $value = cleanVal($value);

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
        if (!count($errors)) {

            $ext = getExtensionFromMime($fileType);
            $newFileName = uniqid() . '.' . $ext;
            $pathToFile = UPLOAD_IMG_DIR . $newFileName;
            move_uploaded_file($fileTmpName, $pathToFile);
        }
    }


    // готовим данные для отправки
    $userPass = password_hash(cleanVal($_POST['password']), PASSWORD_DEFAULT);
    $imgUrl = $pathToFile;


    // проверка что такого email в БД нет
    $sqlCheckEmail = "SELECT * FROM users WHERE email = ?";
    $isEmailExists = dbGetData($link, $sqlCheckEmail, [$email]);


    // Если нет ошибок и в бд нет такого email, отправляем данные в БД
    if (!count($errors) && !$isEmailExists) {

        $addUserSql = "INSERT INTO users 
                          (email, name, password, contacts, img_url) 
                        VALUES
                           (?, ?, ?, ?, ?)";

        $newUserId = dbInsertData($link, $addUserSql, [$email, $name, $userPass, $message, $imgUrl]);

        // если все ок - переадресация на форму входа (еще нет)
        if ($newUserId && empty($errors)) {
            header("Location: /");
            die();
        }

    } else {

        $errors['email'] = "Такой email уже зарегистрирован";
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
    'sign-up.php',
    [
        'navCategories' => $navCategories,
        'errors' => $errors,
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'password' => $password,
        'pathToFile' => $pathToFile
    ]);


$layoutContent = renderTemplate(
    'layout.php',
    [
        'content' => $pageContent,
        'navCategories' => $navCategories,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'title' => 'Yeticave | Регистрация'
    ]);

print($layoutContent);











