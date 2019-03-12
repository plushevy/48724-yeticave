<?php

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param resource $link mysqli Ресурс соединения
 * @param resource $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function prepareStmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    if (!$stmt) {
        showError($link);
    }

    return $stmt;
}

/**
 * Получение данных из БД
 * @param resource $link Ресурс соединения mysqli
 * @param resource $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 * @return array|null
 */
function dbGetData($link, $sql, $data = [])
{
    $result = [];
    $stmt = prepareStmt($link, $sql, $data); // подготавливаем выражение
    mysqli_stmt_execute($stmt);
    $res = mysqliGetResult($link, $stmt);
    $result = mysqli_fetch_all($res, MYSQLI_ASSOC);

    return $result;
}

/**
 * Добавление записи в БД и получение Id последней записи
 * @param resource $link Ресурс соединения mysqli
 * @param resource $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 * @return int|null|string
 */
function dbInsertData($link, $sql, $data = [])
{
    $stmt = prepareStmt($link, $sql, $data); // подготавливаем выражение
    mysqliExecuteStmt($link, $stmt);
    $id = mysqli_insert_id($link);

    return $id;
}


/**
 * Показ ошибки и остановка скрипта
 * @param resource $link Ресурс соединения mysqli
 */
function showError($link)
{

    $error = mysqli_error($link);
    print("Ошибка: Невозможно выполнить запрос к БД. " . $error);
    die;

}


/**
 * Обертка mysqli_stmt_get_result с выводом ошибки
 * @param resource $link Ресурс соединения mysqli
 * @param resource $stmt Подготовленное SQL выражение
 * @return mysqli_result
 */
function mysqliGetResult($link, $stmt)
{
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        showError($link);
    }
    return $res;
}

/**
 * Обертка mysqli_stmt_execute с выводом ошибки
 * @param resource $link Ресурс соединения mysqli
 * @param resource $stmt Подготовленное SQL выражение
 * @return mysqli_result
 */
function mysqliExecuteStmt($link, $stmt)
{
    $res = mysqli_stmt_execute($stmt);

    if (!$res) {
        showError($link);
    }
    return $res;
}
