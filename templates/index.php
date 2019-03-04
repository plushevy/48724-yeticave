<main class="container">

    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
            снаряжение.</p>
        <ul class="promo__list">
            <!-- этот список из массива категорий-->
            <?php foreach ($categories as $category) : ?>
                <li class="promo__item promo__item--boards">
                    <a class="promo__link"
                       href="all-lots.php?id=<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">

            <!--этот список из массива с товарами-->
            <?php foreach ($items as $key => $item) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $item['image']; ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($item['category']); ?></span>
                        <h3 class="lot__title"><a class="text-link"
                                                  href="/lot.php?id=<?= $item['id']; ?>"><?= htmlspecialchars($item['name']); ?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= htmlspecialchars(formatPrice($item['price'])); ?></span>
                            </div>
                            <div class="lot__timer timer">
                                <?= showTimeLeft($item['dt_end']); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>

        </ul>
    </section>
</main>>

