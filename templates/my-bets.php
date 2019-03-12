
<main>
    <?= $navCategories; ?>

    <section class="rates container">

            <h2>Мои ставки</h2>
            <table class="rates__list">

                <?php foreach ($myBets as $bet) : ?>
                    <?php $myWin = ($bet['id_winner'] === $userId); ?>

                    <tr class="rates__item <?php if ($myWin) { echo "rates__item--win"; } ?>">
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src="<?= $bet['image']; ?>" width="54" height="40" alt="<?= $bet['name']; ?>">
                            </div>
                            <h3 class="rates__title">
                                <a href="/lot.php?id=<?= $bet['lot_id']; ?>"><?= $bet['name']; ?></a>
                                <p><?= $bet['lot_author_contacts']; ?></p>
                            </h3>
                        </td>
                        <td class="rates__category"><?= $bet['category']; ?><td>
                        <td class="rates__timer">

                            <?php if ($myWin) : ?>
                                <div class="timer timer--win">Ставка выиграла</div>
                            <? else : ?>
                                <div class="timer timer--finishing"> <?= showTimeLeft($bet['dt_end']); ?></div>
                            <?php endif; ?>

                        </td>

                        <td class="rates__price"><?= formatPrice($bet['price']); ?></td>
                        <td class="rates__time"><?= customTimeLeft($bet['dt_create']) ?></td>
                    </tr>

                <?php endforeach; ?>

            </table>

    </section>
</main>