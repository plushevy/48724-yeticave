<?php

$nameErr = $errors['lot-name'] ?? '';
$selectedCategoryErr = $errors['category'] ?? '';
$messageErr = $errors['message'] ?? '';
$rateErr = $errors['lot-rate'] ?? '';
$stepErr = $errors['lot-step'] ?? '';
$dateErr = $errors['lot-date'] ?? '';
$fileErr = $errors['image'] ?? '';

$formErrClass = (!empty($errors)) ? 'form--invalid' : '';
$itemErrClass = 'form__item--invalid';

?>

<form class="form form--add-lot container <?= $formErrClass;?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?php if ($nameErr) {echo $itemErrClass;} ?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=$name?>" >
            <span class="form__error"><?=$nameErr?></span>
        </div>
        <div class="form__item <?php if ($selectedCategoryErr) {echo $itemErrClass;} ?>">
            <label for="category">Категория</label>
            <select id="category" name="category" required>
                <option value="">Выберите категорию</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['id']; ?>" <?php if($selectedCategory && $selectedCategory == $category['id']) echo "selected"; ?> >
                        <?= $category['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?=$selectedCategoryErr?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?php if ($messageErr) {echo $itemErrClass;} ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="message" placeholder="Напишите описание лота" required><?=$message?></textarea>
        <span class="form__error"><?=$messageErr?></span>
    </div>
    <div class="form__item form__item--file <?php if ($fileErr) {echo $itemErrClass;} ?>"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <!-- max 2 mb-->
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
            <input class="visually-hidden" type="file" name="image" id="photo2" value="" >
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
        <span class="form__error"><?=$fileErr?></span>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?php if ($rateErr) {echo $itemErrClass;} ?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot-rate" placeholder="0" value="<?=$rate?>" >
            <span class="form__error"><?=$rateErr?></span>
        </div>
        <div class="form__item form__item--small <?php if ($stepErr) {echo $itemErrClass;} ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot-step" placeholder="0" value="<?=$step?>" >
            <span class="form__error"><?=$stepErr?></span>
        </div>
        <div class="form__item <?php if ($dateErr) {echo $itemErrClass;} ?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input class="form__input-date" id="lot-date" type="date" name="lot-date" value="<?=$date?>" >
            <span class="form__error"><?=$dateErr?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
