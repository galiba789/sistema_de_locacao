<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper') ?>
<div class="content-wrapper">
    <div class="container mt-4">
        <h2>Cadastro de Locação</h2>
        <form action="<?= base_url('locacoes/editar/'). $locacao['id'] ?>" method="post">
            <div class="row">
                <div class="col-md-12 mb-3 position-relative">
                    <label for="cliente_id" class="form-label">Cliente:</label>
                    <div class="input-group">
                        <input type="text" id="cliente_nome" class="form-control" value="<?= $locacao['cliente_nome'] ?>" disabled readonly>
                        <input type="hidden" name="cliente_id" id="cliente_id" value="<?= $locacao['cliente_id'] ?>">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#clienteModal">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Container de Produtos -->
            <div id="produtos-container">
            <?php foreach ($locacao['produtos'] as $produto): ?>
                    <div class="row align-items-end produto-item">
                        <div class="col-md-3 mb-3 position-relative">
                            <label class="form-label">Produto:</label>
                            <div class="input-group">
                                <input type="text" class="form-control produto-nome" value="<?= $produto['nome'] ?>" disabled readonly>
                                <input type="hidden" name="produto_id[]" class="produto-id" value="<?= $produto['id'] ?>">
                                <button type="button" class="btn btn-secondary btn-selecionar-produto" data-bs-toggle="modal" data-bs-target="#ProdutosModal">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Quantidade</label>
                            <input type="number" name="quantidade[]" class="form-control quantidade" value="<?= $produto['quantidade'] ?>" min="0" oninput="update_quantidade(this)" >
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Preço Unitário</label>
                            <input type="number" name="preco_diaria[]" class="form-control preco-diaria" value="<?= $produto['preco_diaria'] ?>"  oninput="update_quantidade(this)" min="0" step="0.01">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Total</label>
                            <input type="text" name="total_unitario[]" class="form-control total-unitario" value="<?= number_format($produto['quantidade'] * $produto['preco_diaria'], 2) ?>" readonly>
                        </div>

                        <div class="col-md-2 mb-3 d-flex gap-2">
                            <button type="button" class="btn btn-success" onclick="addProduto()">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-danger" onclick="removeProduto(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (session()->getFlashdata('erro')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('erro') ?>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="situacao">Situação</label>
                    <select name="situacao" id="situacao" class="form-control" >
                        <option value="1" <?= $locacao['situacao'] == 1 ? 'selected' : '' ?>>Agendado</option>
                        <option value="2" <?= $locacao['situacao'] == 2 ? 'selected' : '' ?>>Pendente</option>
                        <option value="3" <?= $locacao['situacao'] == 3 ? 'selected' : '' ?>>Atrasado</option>
                        <option value="4" <?= $locacao['situacao'] == 4 ? 'selected' : '' ?>>Finalizado</option>
                        <option value="5" <?= $locacao['situacao'] == 5 ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="data_entrega">Data de Entrega:</label>
                    <input type="date" id="data_entrega" name="data_entrega" class="form-control" value="<?=$locacao['data_entrega']?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="data_devolucao">Data de Devolução:</label>
                    <input type="date" id="data_devolucao" name="data_devolucao" class="form-control" value="<?=$locacao['data_devolucao']?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="total_diarias">Total de Diarias:</label>
                    <input type="text" id="total_diarias" name="total_diarias" class="form-control" value="<?=$locacao['total_diarias']?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="condicao">Condição de pagamento</label>
                    <select name="condicao" id="condicao" class="form-control">
                        <option value="1">Á vista</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="forma_pagamento">Forma de pagamento:</label>
                    <select class="form-control" id="forma_pagamento" name="forma_pagamento">
                        <option value="Pix" <?= $locacao['forma_pagamento'] == 'Pix' ? 'selected' : ' ' ?>>Pix</option>
                        <option value="Dinheiro" <?= $locacao['forma_pagamento'] == 'Dinheiro' ? 'selected' : ' ' ?>>Dinheiro</option>
                        <option value="Cartão" <?= $locacao['forma_pagamento'] == 'Cartão' ? 'selected' : ' ' ?>>Cartão</option>
                        <option value="Boleto" <?= $locacao['forma_pagamento'] == 'Boleto' ? 'selected' : ' ' ?>>Boleto</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="subtotal">Subtotal:</label>
                    <input type="text" name="subtotal" id="subtotal" class="form-control" >
                </div>
                <div class="col-md-4 mb-3">
                    <label for="desconto">Desconto:</label>
                    <input type="text" name="desconto" id="desconto" class="form-control" value="<?=$locacao['desconto']?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="valor_total">Valor Total (R$):</label>
                    <input type="text" name="valor_total" id="valor_total" class="form-control" value="<?=$locacao['valor_total']?>">
                </div>

                <div class="col-md-12 mb-3">
                    <label for="acessorios">Acessorios:</label>
                    <textarea type="text" name="acessorios" id="acessorios" class="form-control"><?= $locacao['acessorios']?></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="observacao">Observação:</label>
                    <textarea type="text" name="observacao" id="observacao" class="form-control"><?= $locacao['observacao']?></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Salvar Locação</button>
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
                                    <td><?= $cliente['id'] ?></td>
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

<div class="modal fade" id="ProdutosModal" tabindex="-1" aria-labelledby="ProdutosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Aumenta o tamanho da modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ProdutosModalLabel">Selecionar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Barra de busca -->
                <input type="text" id="buscarProdutos" class="form-control mb-3" placeholder="Buscar cliente..." onkeyup="filtrarProdutos()">

                <!-- Tabela de clientes -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Estoque</th>
                                <th>Valor Diária</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="listaProdutos">
                            <?php foreach ($produtos as $produto): ?>
                                <tr class="produtos-item">
                                    <td><?= $produto['id'] ?></td>
                                    <td><?= $produto['nome'] ?></td>
                                    <td><?= $produto['quantidade'] ?></td>
                                    <td><?= $produto['preco_diaria'] ?></td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="selecionarProduto('<?= $produto['id'] ?>', '<?= $produto['nome'] ?>', '<?= $produto['quantidade'] ?>', '<?= $produto['preco_diaria'] ?>')">
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

<script>
    let linhaAtiva = null; // Armazena a linha que abriu a modal

    document.querySelectorAll('.btn-selecionar-produto').forEach(function(button) {
        button.addEventListener('click', function() {
            linhaAtiva = button.closest('.produto-item');
        });
    });


    function update_quantidade(element) {
        var row = element.closest('.produto-item');
        var quantidade = row.querySelector('.quantidade').value.trim();
        var valor_unitario = row.querySelector('.preco-diaria').value.trim();
        var totalField = row.querySelector('.total-unitario');

        if (!isValidNumber(quantidade) || !isValidNumber(valor_unitario)) {
            totalField.value = '';
            return;
        }

        var total = parseFloat(quantidade) * parseFloat(valor_unitario);
        totalField.value = total.toFixed(2);
    }

    function isValidNumber(value) {
        return /^[0-9]+(\.[0-9]+)?$/.test(value) && parseFloat(value) >= 0;
    }

    function addProduto() {
        var container = document.getElementById('produtos-container');
        var firstRow = container.querySelector('.produto-item');
        var newRow = firstRow.cloneNode(true);

        // Limpa os valores dos inputs na nova linha
        newRow.querySelector('.produto-nome').value = '';
        newRow.querySelector('.produto-id').value = '';
        newRow.querySelector('.quantidade').value = '';
        newRow.querySelector('.preco-diaria').value = '';
        newRow.querySelector('.total-unitario').value = '';

        // Adiciona a nova linha no container
        container.appendChild(newRow);

        // Define a linha recém-criada como a linha ativa
        linhaAtiva = newRow;
    }




    function removeProduto(button) {
        var row = button.closest('.produto-item');
        var container = document.getElementById('produtos-container');

        if (container.children.length > 1) {
            row.remove();
        } else {
            alert('É necessário pelo menos um produto na lista.');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        function calcularTotais() {
            let totalDiarias = parseFloat(document.getElementById("total_diarias").value) || 0;
            let subtotal = 0;

            document.querySelectorAll(".total-unitario").forEach(input => {
                let valor = parseFloat(input.value) || 0;
                subtotal += valor;
            });

            subtotal *= totalDiarias;

            document.getElementById("subtotal").value = subtotal.toFixed(2);

            let desconto = parseFloat(document.getElementById("desconto").value) || 0;
            let valorTotal = subtotal - desconto;

            document.getElementById("valor_total").value = valorTotal.toFixed(2);
        }

        document.getElementById("total_diarias").addEventListener("input", calcularTotais);
        document.getElementById("desconto").addEventListener("input", calcularTotais);

        document.querySelectorAll(".quantidade, .preco-diaria").forEach(input => {
            input.addEventListener("input", calcularTotais);
        });
    });
</script>
<?= $this->endSection() ?>