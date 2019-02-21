<?php

/**
 * renderTemplate - получение html из шаблона
 * @param $filename
 * @param $data
 * @return false|string
 */
function renderTemplate($filename, $data) {
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
 * @param $num
 * @return string
 */
function formatPrice($num){

    $currency = '₽';
    $num = ceil($num);

    if ($num >= 1000) {
        // форматирование числа вида 99 999
        $num = number_format($num , 0 , "." , " " );
    }

    return "{$num} {$currency}";
}

/**
 * showTimeLeft - сколько времени осталось до .. (чч-мм)
 * @param string $endTime
 * @return string
 */
function showTimeLeft($endTime = 'tomorrow') {

    date_default_timezone_set('Europe/Moscow');

    $dtNow = date_create('now');
    $dtEnd = date_create($endTime);
    $interval = date_diff($dtNow, $dtEnd);
    $timeLeft = date_interval_format($interval, '%H:%I');

    return $timeLeft;
}


/**
 *  Ф-ция склонения окончаний
 * @param $number
 * @param $variants - Массив
 * @return mixed
 */
function setEnding($number, $variants) {

    $num1 = $number % 100;
    $num2 = $number % 10;

    if ($num1 >= 11 && $num1 <= 14) {
        return ' ' . $variants[2];
    }

    switch ($num2) {
        case '1':
            return ' ' . $variants[0];
        case '2':
        case '3':
        case '4':
            return ' ' . $variants[1];
        default:
            return ' ' . $variants[2];
    }

    return $variants[2];

}


/**
 * Форматирует полученную из БД метку timestamp в склоняемые 'Осталось минут и секунд
 * @param $str - timestamp из БД в виде строки
 * @return string
 */
function customTimeLeft ($str) {
    $minEnds = ['минуту', 'минуты', 'минут'];
    $hourEnds = ['час', 'часа', 'часов'];
    $now = time();
    $sec = strtotime($str);
    $timeLeft =  $now - $sec;
    $days = floor($timeLeft / 86400);
    $hours = floor($timeLeft / 3600);
    $mins = floor($timeLeft / 60 );
    $result = '';

    if ($hours > 23) {
        $result = gmdate('d.m.y \в H:i', $sec);
    } else {

        if ($mins > 59) {
            $result = $hours . setEnding($hours, $hourEnds) . ' назад';
        } else {
            $result = $mins. setEnding($mins, $minEnds) . ' назад';
        }
    }

    return $result;
};

/**
 * Вывод ошибки 404 с завершением скрипта
 */
function showError404() {
    header("HTTP/1.1 404 Not Found");
    die("Такой страницы не существует. Ошибка - " . http_response_code());
};
