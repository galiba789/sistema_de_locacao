<!DOCTYPE html>
<html lang="pt-br">
<?= $this->include('dashboard/partials/header') ?>

<body class="hold-transition sidebar-mini">

    <div class="wrapper">
        <!-- Navbar -->
        <?php //echo $this->include('dashboard/partials/navbar') ?>
        <!-- /.navbar -->

        <?= $this->include('dashboard/partials/menu') ?>

        <?= $this->renderSection('content-wrapper') ?>

        <!-- <aside class="control-sidebar control-sidebar-dark">
        </aside> -->
        <?= $this->include('dashboard/partials/footer') ?>
    </div>
    <?= $this->include('dashboard/partials/scripts'); ?>
</body>

</html>