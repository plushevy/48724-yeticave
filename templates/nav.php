<nav class="nav">
    <ul class="nav__list container">
        <!-- этот список из массива категорий-->
        <?php foreach ($categories as $category) : ?>
            <li class="nav__item">
                <a href="pages/all-lots.html?id=<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
