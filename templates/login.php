<?php

$emailErr = $errors['email'] ?? '';
$passwordErr = $errors['password'] ?? '';

$formErrClass = (!empty($errors)) ? 'form--invalid' : '';
$itemErrClass = 'form__item--invalid';

?>
<main>
    <?= $navCategories; ?>

    <form class="form container <?= $formErrClass; ?>" action="login.php" method="post"> <!-- form--invalid -->
        <h2>Вход</h2>
        <div class="form__item <?php if ($emailErr) {echo $itemErrClass;} ?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$email;?>" required>
            <span class="form__error"><?= $emailErr ?></span>
        </div>
        <div class="form__item form__item--last <?php if ($passwordErr) {echo $itemErrClass;} ?>"">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="password" placeholder="Введите пароль" value="<?=$password;?>"required>
            <span class="form__error"><?= $passwordErr ?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>
