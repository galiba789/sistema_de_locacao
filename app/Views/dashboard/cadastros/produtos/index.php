<?= $this->extend('dashboard/layout');?>
<?= $this->section('content-wrapper');?>
<div class="content-wrapper">
<div class="container mt-4">
    <h1>Produtos</h1>    
    <div class="card p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" id="nome" class="form-control" placeholder="Digite sua busca">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="col-md-2">
                <a href="/produtos/cadastrar">
                    <button class="btn btn-success w-100"><i class="fa-solid fa-pen"></i></button>
                </a>    
                </div>
            </div>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th class="fs-4">Código</th>
                        <th class="fs-4">Nome</th>
                        <th class="fs-4">Estoque</th>
                        <th class="fs-4">Preço de venda (R$)</th>
                        <th class="fs-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($produtos as $produto):?>
                        <tr>
                            <td><?=$produto['id']?></td>
                            <td><?=$produto['nome']?></td>
                            <td><?=$produto['quantidade']?></td>
                            <td><?=$produto['preco_diaria']?></td>
                            <td>
                            <a href="<?=base_url('produtos/edita/'). $produto['id']?>">
                                    <button class="btn btn-warning btn-sm">Editar</button>
                                </a>
                                <a href="<?=base_url('produtos/excluir/'). $produto['id']?>">
                                    <button class="btn btn-danger btn-sm">Excluir</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;?>    
                    <!-- Outras linhas podem ser adicionadas aqui -->
                </tbody>
            </table>
        </div>
    </div>
</div>


<?= $this->endSection();?> 