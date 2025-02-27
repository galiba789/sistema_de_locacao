<?= $this->extend('dashboard/layout');?>
<?= $this->section('content-wrapper');?>
<div class="content-wrapper">
<div class="container mt-4">
    <h1>Categorias</h1>   
        <div class="d-flex flex-row-reverse">
            <a href="<?=base_url('/categorias/cadastrar')?>">
                <button class="btn btn-success "><i class="fa-solid fa-magnifying-glass"></i></button>
            </a>    
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categorias as $categoria):?>
                    <tr>
                            <td><?= $categoria['id']?></td>
                            <td><?= $categoria['nome']?></td>
                            <td><?= $categoria['status']?></td>
                            <td>
                                <a href="<?=base_url('categorias/edita/'). $categoria['id']?>">
                                    <button class="btn btn-warning btn-sm">Editar</button>
                                </a>
                                <a href="<?=base_url('categorias/excluir/'). $categoria['id']?>">
                                    <button class="btn btn-danger btn-sm">Excluir</button>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <!-- Outras linhas podem ser adicionadas aqui -->
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            <?= $paginacao->links('default', 'default_full') ?>
        </div>

    </div>
</div>


<?= $this->endSection();?>