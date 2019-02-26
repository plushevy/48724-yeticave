<?php

$nameErr = $errors['name'] ?? '';
$emailErr = $errors['email'] ?? '';
$messageErr = $errors['message'] ?? '';
$passwordErr = $errors['password'] ?? '';
$fileErr = $errors['avatar'] ?? '';

$formErrClass = (!empty($errors)) ? 'form--invalid' : '';
$itemErrClass = 'form__item--invalid';
$pathToFile = $pathToFile ?? 'img/avatar.jpg';

?>
<form class="form container <?= $formErrClass;?>" action="sign-up.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?php if ($emailErr) {echo $itemErrClass;} ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$email?>" required>
        <span class="form__error"><?=$emailErr?></span>
    </div>
    <div class="form__item <?php if ($passwordErr) {echo $itemErrClass;} ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль" value="<?=$password?>" required>
        <span class="form__error"><?=$passwordErr?></span>
    </div>
    <div class="form__item <?php if ($nameErr) {echo $itemErrClass;} ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=$name?>" required>
        <span class="form__error"><?=$nameErr?></span>
    </div>
    <div class="form__item <?php if ($messageErr) {echo $itemErrClass;} ?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться" required><?=$message?></textarea>
        <span class="form__error"><?=$messageErr?></span>
    </div>
    <div class="form__item form__item--file form__item--last <?php if ($fileErr) {echo $itemErrClass;} ?>">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="<?= $pathToFile?>" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="avatar" id="photo2" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
        <span class="form__error"><?=$fileErr?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
