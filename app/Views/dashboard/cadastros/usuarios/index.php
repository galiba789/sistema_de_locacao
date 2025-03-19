<?= $this->extend('dashboard/layout');?>
<?= $this->section('content-wrapper');?>

<div class="content-wrapper">
<div class="container mt-4">
    <h1>Usuarios</h1>   
        <div class="d-flex flex-row-reverse">
            <a href="<?=base_url('/usuarios/cadastrar')?>">
                <button class="btn btn-success "><i class="fa-solid fa-magnifying-glass"></i></button>
            </a>    
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $usuario):?>
                    <tr>
                            <td><?= $usuario['id']?></td>
                            <td><?= $usuario['nome']?></td>
                            <td><?= $usuario['email']?></td>
                            <td>
                                <a href="<?=base_url('usuarios/edita/'). $usuario['id']?>">
                                    <button class="btn btn-warning btn-sm">Editar</button>
                                </a>
                                <a href="<?=base_url('usuarios/excluir/'). $usuario['id']?>">
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
            <?php echo $paginacao->links('default', 'custom_pager')?>
        </div>

    </div>
</div>


<?= $this->endSection();?>