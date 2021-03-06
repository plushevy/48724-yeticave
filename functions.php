<?php

/**
 * renderTemplate - получение html из шаблона
 * @param string $filename
 * @param array $data
 * @return string
 */
function renderTemplate($filename, $data)
{
    $filename = 'templates/' . $filename;
    $result = '';

    if (!is_readable($filename)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $filename;

    $result = ob_get_clean();

    return $result;
}


/**
 * formatPrice - Форматирование цены
 * @param string $num
 * @return string
 */
function formatPrice($num)
{

    $currency = '₽';
    $num = ceil($num);

    if ($num >= 1000) {
        // форматирование числа вида 99 999
        $num = number_format($num, 0, ".", " ");
    }

    return "{$num} {$currency}";
}

/**
 * showTimeLeft - сколько времени осталось до .. (чч-мм)
 * @param string $endTime
 * @return string
 */
function showTimeLeft($endTime = 'tomorrow')
{

    date_default_timezone_set('Europe/Moscow');

    $timestamp1 = strtotime('now');
    $timestamp2 = strtotime($endTime);
    $timeLeft = $timestamp2 - $timestamp1;
    $result = '00:00';

    if ($timeLeft > 0) {
        $hours = floor($timeLeft / 3600);
        $mins = floor(($timeLeft - ($hours * 3600)) / 60);
        $result = addZeroLeft($hours) . ':' . addZeroLeft($mins);
    }

    return $result;
}


/**
 * Добавление нуля у чисел до 10
 * @param string $num
 * @return string
 */
function addZeroLeft($num)
{
    return str_pad($num, 2, '0', STR_PAD_LEFT);
}


/**
 *  Ф-ция склонения окончаний
 * @param integer $number
 * @param array $variants - Массив словоформ
 * @return string
 */
function setEnding($number, array $variants)
{

    $num1 = $number % 100;
    $num2 = $number % 10;
    $result = ' ';

    if ($num1 >= 11 && $num1 <= 14) {

        $result .= $variants[2];

    } else {

        switch ($num2) {
            case '1':
                $result .= $variants[0];
            case '2':
            case '3':
            case '4':
                $result .= $variants[1];
            default:
                $result .= $variants[2];
        }
    }

    return $result;
}


/**
 * Форматирует полученную из БД метку timestamp в склоняемые 'Осталось минут и секунд
 * @param string $str - timestamp из БД в виде строки
 * @return string
 */
function customTimeLeft($str)
{
    $minEnds = ['минуту', 'минуты', 'минут'];
    $hourEnds = ['час', 'часа', 'часов'];
    $now = time();
    $sec = strtotime($str);
    $timeLeft = $now - $sec;
    $days = floor($timeLeft / 86400);
    $hours = floor($timeLeft / 3600);
    $mins = floor($timeLeft / 60);
    $result = '';

    if ($hours > 23) {
        $result = gmdate('d.m.y \в H:i', $sec);
    } else {

        if ($mins > 59) {
            $result = $hours . setEnding($hours, $hourEnds) . ' назад';
        } else {
            $result = $mins . setEnding($mins, $minEnds) . ' назад';
        }
    }

    return $result;
}

;


/**
 * Вывод ошибки 404 с завершением скрипта
 */
function showError404()
{
    header("HTTP/1.1 404 Not Found");
    die("Такой страницы не существует. Ошибка - " . http_response_code());
}

;


/**
 * Получаем расширение файла
 * @param string $str
 * @return string
 */
function getExtensionFromMime($str)
{
    preg_match('/.*\/(\w{1,4})$/i', $str, $matches);
    $mime = (isset($matches[1])) ? $matches[1] : '';
    return $mime;
}


/**
 * Проверка даты окончания лота, должна приходить в формате дд.мм.гггг
 * @param string $str
 * @return bool
 */
function checkEndDate($str)
{

    date_default_timezone_set('Europe/Moscow');

    $pattern = '/^\d{2}\.\d{2}\.\d{4}$/';
    $isValid = false;

    if (preg_match($pattern, $str)) {

        $now = strtotime('now');
        $endDt = strtotime($str . ' 23:59:59');
        $secsinMin = 60;
        $secsInHour = $secsinMin * 60;
        $secsInDay = $secsInHour * 24;

        $diff = $endDt - $now;
        $day = floor($diff / $secsInDay);

        $isValid = ($day >= 1);

    }

    return $isValid;
}


/**
 * Возвращиет строку даты виде гггг-мм-дд 23:59:59
 * @param string $str
 * @return string
 */
function dateToTimestamp($str)
{
    $dt = date_create($str);
    return date_format($dt, "Y-m-d 23:59:59");
}


/**
 * Экранирование и очистка от пробелов
 * @param string $str
 * @return string
 */
function cleanVal($str)
{
    return strip_tags(trim($str));
}


/**
 * Проверка конечной даты на > текущей
 * @param string $str
 * @return bool
 */
function validateEndDate($str)
{
    $now = strtotime('now');
    $endDt = strtotime($str);
    $diff = $endDt - $now;
    return $diff > 0;
}


/**
 * Проверка файла для загрузки
 * Возвращает или false или путь до файла
 * @param array $file
 * @param array $errors
 * @param string $errName
 * @param array $allowTypes
 * @return bool|string
 */
function validateFile($file, &$errors, $errName = 'image', $allowTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp']){

    define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2mb
    define('UPLOAD_IMG_DIR', './img/');

    $fileSize = $file['size'];
    $fileTmpName = $file['tmp_name'];
    $fileType = ($fileTmpName) ? mime_content_type($fileTmpName) : '';
    $pathToFile = false;

    if (!in_array($fileType, $allowTypes)) {
        $errors[$errName] = "Загрузите картинку в формате jpg, jpeg, png, webp";
    }

    if ($fileSize > MAX_FILE_SIZE || $file['error'] !== UPLOAD_ERR_OK) {
        $errors[$errName] = "Загрузите картинку размером до 2Mb";
    }

    if (!count($errors)) {
        $ext = getExtensionFromMime($fileType);
        $newFileName = uniqid() . '.' . $ext;
        $pathToFile = UPLOAD_IMG_DIR . $newFileName;
        move_uploaded_file($fileTmpName, $pathToFile);
    }

    return $pathToFile;
}