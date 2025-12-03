<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>
<div class="content-wrapper">

    <div class="container mt-4">

        <h3 class="mb-4"><i class="fas fa-cogs"></i> Configurações do Sistema</h3>

        <ul class="nav nav-tabs" id="configTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-dark" id="admins-tab" data-bs-toggle="tab" data-bs-target="#admins" type="button">Administradores</button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark" id="pagamento-tab" data-bs-toggle="tab" data-bs-target="#pagamento" type="button">Formas de Pagamento</button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark" id="condicao-tab" data-bs-toggle="tab" data-bs-target="#condicao" type="button">Condições de Pagamento</button>
            </li>
        </ul>

        <div class="tab-content mt-4">

            <!-- ============================
              ABA 1 – ADMINISTRADORES
        ============================= -->
            <div class="tab-pane fade show active" id="admins" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Lista de Administradores</h5>
                    </div>

                    <div class="card-body">
                        <a href="<?= base_url('/usuarios') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-users"></i> Abrir Gerenciamento de Administradores
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============================
              ABA 2 – FORMAS DE PAGAMENTO
        ============================= -->
            <div class="tab-pane fade" id="pagamento" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Formas de Pagamento</h5>
                    </div>

                    <div class="card-body">
                        <a href="<?= base_url('pagamentos') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Gerenciar Formas de Pagamento
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============================
              ABA 3 – CONDIÇÕES DE PAGAMENTO
        ============================= -->
            <div class="tab-pane fade" id="condicao" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Condições de Pagamento</h5>
                    </div>

                    <div class="card-body">
                        <a href="<?= base_url('condicao') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Gerenciar Condições de Pagamento
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>