<style>
    .main-sidebar {
        bottom: 0 !important;
        float: none;
        left: 0 !important;
        position: fixed;
        top: 0;
    }
</style>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="position:fixed;">
    <!-- Brand Logo -->
    <a href="<?= base_url() ?>" class="brand-link d-flex justify-content-center align-items-center">
        <span class="brand-text font-weight-light">Painel</span>

    </a>
    <span class="brand-link d-flex justify-content-center align-items-center">Bem vindo, <?= session()->get('name'); ?></span>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="<?= base_url('/home') ?>" class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/painel/dashboard') !== false) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-poll"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>
                            Cadastros
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/clientes') ?>" class="nav-link">
                                <i class="fa-solid fa-user"></i>
                                <p>Clientes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/categorias') ?>" class="nav-link">
                                <i class="fa-solid fa-shop"></i>
                                <p>Categorias</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/produtos') ?>" class="nav-link">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <p>Produtos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/locacoes') ?>" class="nav-link">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <p>Locações</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('#') ?>" class="nav-link">
                        <i class="fa-solid fa-calendar-days"></i>
                        <p>Calendario</p>
                    </a>
                </li>



            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>