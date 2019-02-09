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
