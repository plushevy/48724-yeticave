<main>

    <?= $navCategories; ?>

    <section class="lot-item container">
        <h2>Список моих ставок</h2>
        <div class="lot-item__content">
            <?php if (count($myBets) > 0) : ?>
                <div class="history history--mybets">
                    <h3>История ставок (<span><?= count($myBets); ?></span>)</h3>
                    <table class="history__list">
                        <tr class="history__item history__item--mybets <?php if ($myWin) {
                            echo "history__item--win";
                        } ?>">
                            <th class="history__name">Предмет</th>
                            <th class="history__price">Ставка</th>
                            <th class="history__time">Дата создания</th>
                            <th class="history__time">Владелец лота</th>
                            <th class="history__time">Контакты владельца</th>
                        </tr>
                        <?php foreach ($myBets as $bet) : ?>
                            <?php
                            $myWin = false;
                            if ($bet['id_winner'] == $userId) {
                                $myWin = true;
                            }
                            ?>
                            <tr class="history__item <?php if ($myWin) {
                                echo "history__item--win";
                            } ?>">
                                <td class="history__name"><a
                                        href="/lot.php?id=<?= $bet['lot_id']; ?>"><?= $bet['name']; ?></a></td>
                                <td class="history__price"><?= formatPrice($bet['price']); ?></td>
                                <td class="history__time"><?= customTimeLeft($bet['dt_create']); ?></td>
                                <td class="history__time"><?= $bet['lot_author']; ?></td>
                                <td class="history__time"><?= $bet['lot_author_contacts']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php else: ?>

                <div class="history">
                    <h3>У вас не было ни одной ставки...</h3>
                </div>

            <?php endif; ?>
        </div>
    </section>
</main>
