<?php
/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
    <ul class="pagination">
        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a class="btn btn-success" href="<?= $pager->getFirst() ?>" aria-label="Primeira Página">
                    <!-- Ícone de duplo para início -->
                    &#171;
                </a>
            </li>&nbsp;
            <li>
                <a class="btn btn-success" href="<?= $pager->getPrevious() ?>" aria-label="Página Anterior">
                    <!-- Ícone de seta para esquerda -->
                    &#8249;
                </a>
            </li>&nbsp;
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="btn <?= $link['active'] ? 'btn-primary' : 'btn-success' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>&nbsp;
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a class="btn btn-success" href="<?= $pager->getNext() ?>" aria-label="Próxima Página">
                    <!-- Ícone de seta para direita -->
                    &#8250;
                </a>
            </li>&nbsp;
            <li>
                <a class="btn btn-success" href="<?= $pager->getLast() ?>" aria-label="Última Página">
                    <!-- Ícone de duplo para final -->
                    &#187;
                </a>
            </li>&nbsp;
        <?php endif ?>
    </ul>
</nav>
