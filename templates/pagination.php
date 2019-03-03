
<?php if ($pagesCount > 1): ?>
    <ul class="pagination-list">
        <!-- назад появится только со 2 страницы-->
        <?php if ($currentPage > 1): ?>
            <li class="pagination-item pagination-item-prev"><a href="/<?=$linkParam;?>page=<?=$currentPage - 1;?>">Назад</a></li>
        <?php endif; ?>

        <?php foreach ($pages as $page): ?>
            <li class="pagination__item <?php if ($page == $currentPage): ?>pagination-item-active<?php endif; ?>">
                <a href="/<?=$linkParam;?>page=<?=$page;?>"><?=$page;?></a>
            </li>
        <?php endforeach; ?>

        <?php if ($currentPage <  end($pages)): ?>
            <li class="pagination-item pagination-item-next"><a href="/<?=$linkParam;?>page=<?=$currentPage + 1;?>">Вперед</a></li>
        <?php endif; ?>
    </ul>
<?php endif; ?>


