<section class="lot-item container">
    <h2><?= $lot['name']; ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['image']; ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot['category']; ?></span></p>
            <p class="lot-item__description"><?= $lot['description']; ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <div class="lot-item__timer timer">
                    <?= showTimeLeft($lot['dt_end']);?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= formatPrice($lot['price']); ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= $lot['price'] + $lot['bet_step'] ?></span>
                    </div>
                </div>
                <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
                    <p class="lot-item__form-item form__item form__item--invalid">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="<?= $lot['price'] + $lot['bet_step'] ?>">
                        <span class="form__error">Введите наименование лота</span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            </div>
            <?php if (count($bets) > 0) : ?>
                <div class="history">
                    <h3>История ставок (<span><?= count($bets)?></span>)</h3>
                    <table class="history__list">
                        <?php foreach($bets as $bet) :  ?>
                            <tr class="history__item">
                                <td class="history__name"><?=$bet['name'];?></td>
                                <td class="history__price"><?= formatPrice($bet['price']); ?></td>
                                <td class="history__time"><?= customTimeLeft($bet['dt_create']); ?></td>
                            </tr>
                        <?php endforeach;?>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>