<!DOCTYPE html>
<html lang="pt-br">
<?= $this->include('dashboard/partials/header') ?>

<body class="tt-smooth-scroll">

    <div class="wrapper pt-80">
        <!-- Navbar -->
         <?= $this->include('dashboard/partials/navbar') ?> 
        <!-- /.navbar -->

        <?= $this->renderSection('content-wrapper') ?>

        <!-- <aside class="control-sidebar control-sidebar-dark">
        </aside> -->
         <?= $this->include('dashboard/partials/footer') ?> 
    </div>
    <?= $this->include('dashboard/partials/scripts'); ?>
</body>

</html>