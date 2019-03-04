
<main>
    <?=$navCategories; ?>

    <div class="container">
        <section class="lots">
            <h2>Все лоты в категории <span>«<?=$categoryName?>»</span></h2>

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

        <?=$pagination;?>

    </div>
</main>
