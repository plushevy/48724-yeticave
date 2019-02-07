<?php

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


function formatPrice($num){

    $currency = '₽';
    $num = ceil($num);

    if ($num >= 1000) {
        // форматирование числа вида 99 999
        $num = number_format($num , 0 , "." , " " );
    }

    return "{$num} {$currency}";
}
