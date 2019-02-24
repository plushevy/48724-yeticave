<?php

require_once('functions.php');
require_once('data.php');
require_once('mysql_helper.php');
require_once('db-connect.php');

define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2mb
define('UPLOAD_IMG_DIR' , './img/');


$errors = [];

$allowTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

$requiredFields = [
    'lot-name',
    'category',
    'message',
    'lot-rate',
    'lot-step',
    'lot-date'
];
$numericFields = ['lot-rate', 'lot-step'];

$isValidRequiredFields = false;


// запрос для получения списка катеорий
$sqlGetCategories = "SELECT * FROM categories";
$categories = dbGetData($link, $sqlGetCategories);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    foreach ($_POST as $field => $value) {

        $value = trim($value);

        if (in_array($field, $requiredFields) && empty($value)) {
            $errors[$field] = 'Заполните это поле';
        }
        if ($field == 'category' && empty($value)) {
            $errors[$field] = 'Выберите категорию';
        }

        if ($field == "message" && strlen($value) < 10) {
            $errors[$field] = "Описание должно быть не менее 10 символов";
        }

        // проверка на положительные числа
        if (in_array($field, $numericFields) && !empty($value)) {

            $filter_options = array(
                'options' => array( 'min_range' => 0)
            );

            if (!filter_var($value, FILTER_VALIDATE_INT, $filter_options)) {
                $errors[$field] = 'Введите положительное число';
            }
        }

    }

    $isValidRequiredFields = (!count($errors)) ?? false;

    // Если все поля проверены - переходим к загрузке файла
    if ($isValidRequiredFields && isset($_FILES['image'])) {

        $img = $_FILES['image'];

        $fileName = $img['name'];
        $fileSize = $img['size'];
        $fileType = $img['type'];
        $fileTmpName = $img['tmp_name'];

        if (!in_array($fileType, $allowTypes)) {
            $errors['image'] = "Загрузите картинку в формате jpg, jpeg, png, webp";
        }

        if ($fileSize == 0 || $fileSize > MAX_FILE_SIZE || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors['image'] = "Загрузите картинку размером до 2Mb";
        }

        // Если нет ошибок и файл загружен, отправляем данные в БД
        if (!count($errors)) {

            $ext = getExtensionFromMime($fileType);
            $newFileName = uniqid() . '.' . $ext;
            $pathToFile = UPLOAD_IMG_DIR . $newFileName;
            move_uploaded_file($fileTmpName, $pathToFile);

            // готовим данные для отправки
            $dt_end = strip_tags($_POST['lot-date']);
            $label = strip_tags($_POST['lot-name']);
            $desc = strip_tags($_POST['message']);
            $imgUrl = $pathToFile;
            $startPrice = (int) $_POST['lot-rate'];
            $betStep = (int) $_POST['lot-step'];
            $idUser = rand(1, 2);  // временно
            $idCategory = (int) $_POST['category'];


            // проверка на наличие категории
            $sqlCheckCategory = "SELECT * FROM categories WHERE id = ?";
            $isCategoryExists = dbGetData($link, $sqlCheckCategory, [$idCategory]);
            if (!$isCategoryExists) {
                die ('Ошибка. Нет такой категории');
            }


            $sql = " INSERT INTO lots (dt_end, label, description, img_url, start_price, bet_step, id_user, id_category) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $newLotId = dbInsertData($link, $sql, [$dt_end, $label, $desc, $imgUrl, $startPrice, $betStep, $idUser, $idCategory]);

            header("Location: lot.php?id=" . $newLotId);
            die;
        }


    } else {

        $errors['image'] = "Загрузите фотографию лота";
    }
}


$pageContent = renderTemplate(
    'add.php',
    [
        'categories' => $categories,
        'errors' => $errors
    ]);


$layoutContent = renderTemplate(
    'layout.php',
    [
        'content' => $pageContent,
        'categories' => $categories,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'title' => 'Yeticave | Добавление лота'
    ]);

print($layoutContent);











