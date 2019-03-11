<?php


$sqlWinners = "
                SELECT l.id as lot_id,
                       l.label as name,
                       l.img_url as image,
                       l.dt_end,
                       b.last_price as final_price,
                       b.dt_create as dt_bet,
                       c.name as category,
                       u.id as winner_id,
                       u.name as winner_name,
                       u.email as winner_email
                FROM lots l
                       JOIN bets b ON b.id_lot = l.id
                       JOIN categories c ON c.id = l.id_category
                       JOIN users u ON u.id = b.id_user
                WHERE l.id_winner IS NULL AND l.dt_end <= CURDATE()rfrj
                ORDER BY b.dt_create DESC";

$winnersData = dbGetData($link, $sqlWinners, []);


// отправляем email победителю
$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

foreach ($winnersData as $data) {

    $userEmail = $data['winner_email'];

    $mailer = new Swift_Mailer($transport);

    // созздаем сообщение
    $message = new Swift_Message();
    $message->setSubject("Ваша ставка победила");
    $message->setFrom(['keks@phpdemo.ru' => 'Проект YetiCave']);
    $message->setTo($userEmail);

    $msg_content = renderTemplate('email.php', ['data' => $data]);
    $message->setBody($msg_content, 'text/html');


    // отправляем
    $result = $mailer->send($message);


    if ($result) {

        //добавляем запиcь в lot
        $sql = " UPDATE lots SET id_winner = ? WHERE id = ?";

        $idWinner = $data['winner_id'];
        $idLot = $data['lot_id'];

        dbInsertData($link, $sql, [$idWinner, $idLot]);
    }

}
