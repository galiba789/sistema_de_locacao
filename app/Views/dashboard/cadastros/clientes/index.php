<?= $this->extend('dashboard/layout');?>
<?= $this->section('content-wrapper');?>
<div class="content-wrapper">
<div class="container mt-4">
    <h1>Clientes</h1>    
    <div class="card p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select id="tipo" class="form-select">
                        <option value="nome">Nome</option>
                        <option value="cpf">CPF</option>
                        <option value="cnpj">CNPJ</option>
                        <option value="birthDate">Data de nascimento</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="palavra" class="form-label">Palavra</label>
                    <input type="text" id="palavra" class="form-control" placeholder="Digite sua busca">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="col-md-2">
                <a href="/clientes/cadastrar">
                    <button class="btn btn-success w-100"><i class="fa-solid fa-pen"></i></button>
                </a>    
                </div>
            </div>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>CPF/CNPJ</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($clientes as $cliente){  
                    ?>
                    <tr>
                        <td><?=$cliente['id']?></td>
                        <?php if ($cliente['tipo'] == 1):?>
                            <td><?=$cliente['nome']?></td>
                        <?php endif;?>
                        <?php if ($cliente['tipo'] == 2):?>
                            <td><?=$cliente['razao_social']?></td>
                        <?php endif;?>
                        <td><?= $cliente['email']?></td>
                        
                        <td><?=$cliente['telefone_contato']?></td>
                        <?php if ($cliente['tipo'] == 1):?>
                            <td><?=$cliente['cpf']?></td>
                        <?php endif;?>
                        <?php if ($cliente['tipo'] == 2):?>
                            <td><?=$cliente['cnpj']?></td>
                        <?php endif;?>
                        <td>
                            <a href="<?=base_url('clientes/editar/'). $cliente['id']?>">
                                <button class="btn btn-warning btn-sm">Editar</button>
                            </a>
                            <a href="<?=base_url('clientes/excluir/'). $cliente['id']?>">
                                <button class="btn btn-danger btn-sm">Excluir</button>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
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