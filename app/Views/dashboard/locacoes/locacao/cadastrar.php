<?=$this->extend('dashboard/layout');?>
<?=$this->section('content-wrapper')?>
<div class="content-wrapper">
    <div class="container mt-4">
       <H2>Cadastro de Locação</H2>
       <form action="<?= base_url('locacoes/salvar') ?>" method="post">
        <div class="row">
            <div class=" col-md-12 mb-3 position-relative">
                <label for="cliente_id" class="form-label">Cliente:</label>
                <div class="input-group">
                    <input type="text" id="cliente_nome" class="form-control" placeholder="Selecione um cliente" disabled="" readonly>
                    <input type="hidden" name="cliente_id" id="cliente_id"> 
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#clienteModal">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>



        <button type="submit" class="btn btn-primary">Salvar Locação</button>
    </form>
    </div>  
    
</div>    


<div class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="clienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Aumenta o tamanho da modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clienteModalLabel">Selecionar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Barra de busca -->
                <input type="text" id="buscarCliente" class="form-control mb-3" placeholder="Buscar cliente..." onkeyup="filtrarClientes()">

                <!-- Tabela de clientes -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>CPF/CNPJ</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="listaClientes">
                            <?php foreach ($clientes as $cliente): ?>
                                <tr class="cliente-item">
                                    <td><?=$cliente['id']?></td>
                                    <td><?= $cliente['tipo'] == 1 ? $cliente['nome'] : $cliente['razao_social'] ?></td>
                                    <td><?= $cliente['tipo'] == 1 ? $cliente['cpf'] : $cliente['cnpj'] ?></td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="selecionarCliente('<?= $cliente['id'] ?>', '<?= $cliente['tipo'] == 1 ? $cliente['nome'] : $cliente['razao_social'] ?>')">
                                            Adicionar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?=$this->endSection()?>