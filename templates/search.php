<main>

    <?=$navCategories; ?>

    <div class="container">

        <?php if ($search) : ?>
            <section class="lots">
            <h2>Результаты поиска по запросу «<span><?=$search;?></span>»</h2>
            <ul class="lots__list">

                <!-- если что-то найдено-->
                <?php if ($items || !empty($items)) : ?>

                    <!--этот список из массива с лотами-->
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

                <?php else: ?>
                    <h3> К сожалению, ничего не найдено..</h3>
                <?php endif; ?>

            </ul>
        </section>

            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
                <li class="pagination-item pagination-item-active"><a>1</a></li>
                <li class="pagination-item"><a href="#">2</a></li>
                <li class="pagination-item"><a href="#">3</a></li>
                <li class="pagination-item"><a href="#">4</a></li>
                <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
            </ul>
        <?php else : ?>

            <section class="lots">
                <h2>Введите параметры для поиска...</h2>
            </section>


        <?php endif; ?>


    </div>
</main>
