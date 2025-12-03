<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>

<div class="content-wrapper">
    <div class="container mt-4">
        <h1>Condições de pagamento</h1>

        <div class="d-flex justify-content-between mb-3">

            <!-- Botão Voltar (esquerda) -->
            <a href="<?= base_url('/configuracoes') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>

            <!-- Botão Cadastrar (direita) -->
            <a href="<?= base_url('/condicao/cadastrar') ?>" class="btn btn-success">
                Cadastrar <i class="fa-solid fa-pencil"></i>
            </a>

        </div>


        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                  
                    <?php
                    if (!empty($condicoes)){
                        foreach ($condicoes as $condicao): ?>
                            <tr>
                                <td><?= $condicao['id'] ?></td>
                                <td><?= $condicao['nome'] ?></td>
                                <td>
                                    <a href="<?= base_url('condicao/edita/') . $condicao['id'] ?>">
                                        <button class="btn btn-warning btn-sm">Editar</button>
                                    </a>
                                    <a href="<?= base_url('condicao/excluir/') . $condicao['id'] ?>">
                                        <button class="btn btn-danger btn-sm">Excluir</button>
                                    </a>
                                </td>
                            </tr>
                    <?php endforeach;} ?>
                    <!-- Outras linhas podem ser adicionadas aqui -->
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            <?php echo $paginacao->links('default', 'custom_pager') ?>
        </div>

    </div>
</div>


<?= $this->endSection(); ?>