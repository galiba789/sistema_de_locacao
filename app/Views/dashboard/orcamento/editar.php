<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper') ?>
<div class="content-wrapper">
    <div class=" container mt-4">
        <h2>Cadastro de Locação</h2>
        <form action="<?= base_url('orcamento/editar/') . $orcamento['id'] ?>" method="post">
            <div class="row">
                <div class="col-md-12 mb-3 position-relative">
                    <label for="cliente_id" class="form-label">Cliente:</label>
                    <div class="input-group">
                        <input type="text" id="cliente_nome" class="form-control" value="<?= $orcamento['cliente_nome'] ?>" disabled readonly>
                        <input type="hidden" name="cliente_id" id="cliente_id" value="<?= $orcamento['cliente_id'] ?>">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#clienteModal">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Container de Produtos -->
            <div id="produtos-container">
                <?php foreach ($orcamento['produtos'] as $produto): ?>
                    <div class="row align-items-end produto-item">
                        <div class="status-disponibilidade alert-dismissible fade show"></div> <!-- Aqui exibimos a mensagem -->
                        <?php if (session()->getFlashdata('erro')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('erro') ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-3 mb-3 position-relative">
                            <label class="form-label">Produto:</label>
                            <div class="input-group">
                                <input type="text" class="form-control produto-nome" value="<?= $produto['produto_nome'] ?>" disabled readonly>
                                <input type="hidden" name="produto_id[]" class="produto-id" value="<?= $produto['produto_id'] ?>">
                                <button type="button" class="btn btn-secondary btn-selecionar-produto" data-bs-toggle="modal" data-bs-target="#ProdutosModal">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Quantidade</label>
                            <input type="number" name="quantidade[]" class="form-control quantidade" value="<?= $produto['quantidade'] ?>" min="0" oninput="update_quantidade(this)">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Preço Unitário</label>
                            <input type="number" name="preco_diaria[]" class="form-control preco-diaria" value="<?= $produto['preco_diaria'] ?>" oninput="update_quantidade(this)" min="0" step="0.01">
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

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="situacao">Situação</label>
                    <select name="situacao" id="situacao" class="form-control">
                        <option value="1" <?= $orcamento['situacao'] == 1 ? 'selected' : '' ?>>Agendado</option>
                        <option value="2" <?= $orcamento['situacao'] == 2 ? 'selected' : '' ?>>Pendente</option>
                        <option value="3" <?= $orcamento['situacao'] == 3 ? 'selected' : '' ?>>Atrasado</option>
                        <option value="4" <?= $orcamento['situacao'] == 4 ? 'selected' : '' ?>>Finalizado</option>
                        <option value="5" <?= $orcamento['situacao'] == 5 ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="data_entrega">Data de Entrega:</label>
                    <input type="datetime-local" id="data_entrega" name="data_entrega" class="form-control" value="<?= $orcamento['data_entrega'] ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="data_devolucao">Data de Devolução:</label>
                    <input type="datetime-local" id="data_devolucao" name="data_devolucao" class="form-control" value="<?= $orcamento['data_devolucao'] ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="total_diarias">Total de Diarias:</label>
                    <input type="text" id="total_diarias" name="total_diarias" class="form-control" value="<?= $orcamento['total_diarias'] ?>">
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
                        <option value="Pix" <?= $orcamento['forma_pagamento'] == 'Pix' ? 'selected' : ' ' ?>>Pix</option>
                        <option value="Dinheiro" <?= $orcamento['forma_pagamento'] == 'Dinheiro' ? 'selected' : ' ' ?>>Dinheiro</option>
                        <option value="Cartão" <?= $orcamento['forma_pagamento'] == 'Cartão' ? 'selected' : ' ' ?>>Cartão</option>
                        <option value="Boleto" <?= $orcamento['forma_pagamento'] == 'Boleto' ? 'selected' : ' ' ?>>Boleto</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="subtotal">Subtotal:</label>
                    <input type="text" name="subtotal" id="subtotal" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="desconto">Desconto:</label>
                    <input type="text" name="desconto" id="desconto" class="form-control" value="<?= $orcamento['desconto'] ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="valor_total">Valor Total (R$):</label>
                    <input type="text" name="valor_total" id="valor_total" class="form-control" value="<?= $orcamento['valor_total'] ?>">
                </div>

                <div class="col-md-12 mb-3">
                    <label for="acessorios">Acessorios:</label>
                    <textarea type="text" name="acessorios" id="acessorios" class="form-control"><?= $orcamento['acessorios'] ?></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="observacao">Observação:</label>
                    <textarea type="text" name="observacao" id="observacao" class="form-control"><?= $orcamento['observacao'] ?></textarea>
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
                <div class="row g-2 mb-3 align-items-center">
                    <div class="col-md-10">
                        <input type="text" id="buscarCliente" class="form-control" placeholder="Buscar cliente..." onkeyup="filtrarClientes()">
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#cadastroClienteModal"><i class="fa-solid fa-pen"></i></button>
                    </div>
                </div>

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
                                        <button class="btn btn-success btn-sm" data-bs-dismiss="modal" onclick="selecionarCliente('<?= $cliente['id'] ?>', '<?= $cliente['tipo'] == 1 ? $cliente['nome'] : $cliente['razao_social'] ?>')">
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
                                        <button class="btn btn-success btn-sm" data-bs-dismiss="modal" onclick="selecionarProduto('<?= $produto['id'] ?>', '<?= $produto['nome'] ?>', '<?= $produto['quantidade'] ?>', '<?= $produto['preco_diaria'] ?>')">
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


<div class="modal fade" id="cadastroClienteModal" tabindex="-1" aria-labelledby="cadastroClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastroClienteModalLabel">Cadastro de Clientes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <form id="formCadastroCliente" action="<?= base_url('locacoes/salvarClientes') ?>" method="POST">
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo de Cliente</label>
                        <select id="type" name="type" class="form-control" onchange="clientesForm()" required>
                            <option value="">Selecione...</option>
                            <option value="1">Pessoa Física</option>
                            <option value="2">Pessoa Jurídica</option>
                        </select>
                    </div>

                    <!-- Formulário de Pessoa Física -->
                    <div id="formFisica" class="cliente-form" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nome:</label>
                                <input type="text" name="nome" id="nome_fisica" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>CPF:</label>
                                <input type="text" name="cpf" id="cpf" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>RG:</label>
                                <input type="text" name="rg" id="rg" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email:</label>
                                <input type="email" name="email" id="email_fisica" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Telefone:</label>
                                <input type="text" name="telefone_contato_fisica" id="telefone_contato_fisica" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Data de Nascimento:</label>
                                <input type="date" name="nascimento" id="nascimento" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Observação:</label>
                                <input type="text" name="obs" id="obs_fisica" class="form-control">
                            </div>
                            <h3 class="col-md-12 mb-3">Endereço:</h3>
                            <div class="col-md-4 mb-3">
                                <label>CEP:</label>
                                <input type="text" name="cep" id="cep" class="form-control" onblur="buscarEndereco(this.value, this)" required>
                                <small><a href="https://buscacepinter.correios.com.br/app/endereco/index.php" target="_blank">Não Sabe o CEP ? Clique aqui</a></small>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label>Logradouro:</label>
                                <input type="text" name="logradouro" id="logradouro" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Número:</label>
                                <input type="text" name="numero" id="numero" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Complemento:</label>
                                <input type="text" name="complemento" id="complemento" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Bairro:</label>
                                <input type="text" name="bairro" id="bairro" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Estado:</label>
                                <input type="text" name="estado" id="estado" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Cidade:</label>
                                <input type="text" name="localidade" id="localidade" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Formulário de Pessoa Jurídica -->
                    <div id="formJuridica" class="cliente-form" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Razão Social:</label>
                                <input type="text" name="razao_social" id="razao_social" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>CNPJ:</label>
                                <input type="text" name="cnpj" id="cnpj" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Telefone Comercial:</label>
                                <input type="text" name="telefone_comercial" id="telefone_comercial" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email:</label>
                                <input type="email" name="email" id="email_juridica" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Observação:</label>
                                <input type="text" name="obs" id="obs_juridica" class="form-control">
                            </div>
                            <h3 class="col-md-12 mb-3">Contato da empresa:</h3>
                            <div class="col-md-4 mb-3">
                                <label>Email de Contato:</label>
                                <input type="email" name="email_contato" id="email_contato" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Telefone de Contato:</label>
                                <input type="text" name="telefone_contato_cnpj" id="telefone_contato_cnpj" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Cargo:</label>
                                <input type="text" name="cargo" id="cargo" class="form-control">
                            </div>
                            <h3 class="col-md-12 mb-3">Endereço:</h3>
                            <div class="col-md-4 mb-3">
                                <label>CEP:</label>
                                <input type="text" name="cep" id="cep" class="form-control" onblur="buscarEndereco(this.value, this)" required>
                                <small><a href="https://buscacepinter.correios.com.br/app/endereco/index.php" target="_blank">Não Sabe o CEP ? Clique aqui</a></small>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label>Logradouro:</label>
                                <input type="text" name="logradouro" id="logradouro" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Número:</label>
                                <input type="text" name="numero" id="numero" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Complemento:</label>
                                <input type="text" name="complemento" id="complemento" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Bairro:</label>
                                <input type="text" name="bairro" id="bairro" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Estado:</label>
                                <input type="text" name="estado" id="estado" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Cidade:</label>
                                <input type="text" name="localidade" id="localidade" class="form-control">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let linhaAtiva = null;

    // Define a função calcularTotais de forma global para que todas as funções possam usá-la
    window.calcularTotais = function() {
        let totalDiarias = parseFloat(document.getElementById("total_diarias").value) || 0;
        let subtotal = 0;

        // Percorre todas as linhas de produtos e calcula o total unitário de cada
        document.querySelectorAll(".produto-item").forEach(row => {
            let quantidade = parseFloat(row.querySelector(".quantidade").value) || 0;
            let precoDiaria = parseFloat(row.querySelector(".preco-diaria").value) || 0;
            let totalUnitario = quantidade * precoDiaria;
            row.querySelector(".total-unitario").value = totalUnitario.toFixed(2);
            subtotal += totalUnitario;
        });

        // Multiplica o subtotal pelo total de diárias
        subtotal *= totalDiarias;
        document.getElementById("subtotal").value = subtotal.toFixed(2);

        // Aplica o desconto e calcula o valor total
        let desconto = parseFloat(document.getElementById("desconto").value) || 0;
        let valorTotal = subtotal - desconto;
        document.getElementById("valor_total").value = valorTotal.toFixed(2);
    };

    // Aguarda o carregamento do DOM para adicionar os event listeners
    document.addEventListener("DOMContentLoaded", function() {
        // Atualiza os totais sempre que os inputs relevantes forem alterados
        document.addEventListener("input", function(event) {
            if (event.target.matches(".quantidade, .preco-diaria, #total_diarias, #desconto")) {
                window.calcularTotais();
            }
        });

        // Inicializa os totais na carga da página
        window.calcularTotais();

        // Vincula os botões para seleção de produto
        document.querySelectorAll('.btn-selecionar-produto').forEach(function(button) {
            button.addEventListener('click', function() {
                linhaAtiva = button.closest('.produto-item');
            });
        });
    });

    // Função para atualizar o total de um produto individual e os totais gerais
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
        window.calcularTotais();
    }

    // Função de validação para números
    function isValidNumber(value) {
        return /^[0-9]+(\.[0-9]+)?$/.test(value) && parseFloat(value) >= 0;
    }

    // Função para adicionar um novo produto à lista
    function addProduto() {
        var container = document.getElementById('produtos-container');
        var lastRow = container.querySelector('.produto-item:last-child');

        if (!lastRow) {
            alert('Selecione um produto antes de adicionar outro.');
            return;
        }

        var produtoNome = lastRow.querySelector('.produto-nome').value.trim();
        var produtoId = lastRow.querySelector('.produto-id').value.trim();
        var quantidade = lastRow.querySelector('.quantidade').value.trim();
        var precoDiaria = lastRow.querySelector('.preco-diaria').value.trim();

        if (!produtoNome || !produtoId || !isValidNumber(quantidade) || !isValidNumber(precoDiaria)) {
            alert('Preencha todos os campos antes de adicionar outro produto.');
            return;
        }

        var newRow = lastRow.cloneNode(true);

        // Limpa os valores dos inputs na nova linha
        newRow.querySelector('.produto-nome').value = '';
        newRow.querySelector('.produto-id').value = '';
        newRow.querySelector('.quantidade').value = '';
        newRow.querySelector('.preco-diaria').value = '';
        newRow.querySelector('.total-unitario').value = '';
        container.appendChild(newRow);

        linhaAtiva = newRow;
        window.calcularTotais();
    }

    // Função para remover um produto da lista
    function removeProduto(button) {
        var row = button.closest('.produto-item');
        var container = document.getElementById('produtos-container');

        if (container.children.length > 1) {
            row.remove();
        } else {
            alert('É necessário pelo menos um produto na lista.');
        }
        window.calcularTotais();
    }

    // Função para selecionar um produto e preencher os campos na linha ativa
    function selecionarProduto(id, nome, quantidade, preco_diaria) {
        if (linhaAtiva) {
            linhaAtiva.querySelector(".produto-id").value = id;
            linhaAtiva.querySelector(".produto-nome").value = nome;
            linhaAtiva.querySelector(".quantidade").value = quantidade;
            linhaAtiva.querySelector(".preco-diaria").value = preco_diaria;

            update_quantidade(linhaAtiva.querySelector(".quantidade"));

            var modal = bootstrap.Modal.getInstance(document.getElementById('ProdutosModal'));
            modal.hide();
        }
    }

    // Função para selecionar um cliente e preencher os campos correspondentes
    function selecionarCliente(id, nome) {
        var clienteIdInput = document.getElementById('cliente_id');
        var clienteNomeInput = document.getElementById('cliente_nome');

        if (clienteIdInput && clienteNomeInput) {
            clienteIdInput.value = id;
            clienteNomeInput.value = nome;

            var modal = bootstrap.Modal.getInstance(document.getElementById('clienteModal'));
            modal.hide();
        }
    }

    function buscarEndereco(cep, element) {
        if (cep === '') {
            alert('Informe o CEP antes de continuar');
            element.focus();
            return;
        }

        $.post('consulta', {
            cep: cep
        }, function(dados) {
            console.log(dados);

            // Encontrar o formulário mais próximo do campo de CEP acionado
            let formContainer = $(element).closest('.cliente-form');

            // Preencher os campos dentro do formulário correspondente
            formContainer.find('[name="logradouro"]').val(dados.logradouro);
            formContainer.find('[name="bairro"]').val(dados.bairro);
            formContainer.find('[name="estado"]').val(dados.uf);
            formContainer.find('[name="localidade"]').val(dados.localidade);
        }, 'json');
    };

    document.addEventListener("DOMContentLoaded", function() {
        function verificarDisponibilidade(produtoInput) {
            const produtoId = produtoInput.closest('.produto-item').querySelector('.produto-id').value;
            const dataEntrega = document.getElementById("data_entrega").value;
            const dataDevolucao = document.getElementById("data_devolucao").value;
            const statusDiv = produtoInput.closest('.produto-item').querySelector('.status-disponibilidade');

            if (!produtoId || !dataEntrega || !dataDevolucao) {
                statusDiv.innerHTML = "<span class='alert-warning'>Preencha todos os campos.</span>";
                return;
            }

            fetch("/locacoes/verificarDisponibilidadeAjax", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        produto_id: produtoId,
                        data_entrega: dataEntrega,
                        data_devolucao: dataDevolucao
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        statusDiv.innerHTML = "<span class='alert-success'>" + data.message + "</span>";
                    } else {
                        statusDiv.innerHTML = "<span class='alert-danger'>" + data.message + "</span>";
                    }
                })
                .catch(error => {
                    console.error("Erro:", error);
                    statusDiv.innerHTML = "<span class='alert-danger'>Erro ao verificar disponibilidade.</span>";
                });
        }

        // Monitorando a seleção do produto
        document.querySelectorAll(".produto-id").forEach(input => {
            input.addEventListener("change", function() {
                verificarDisponibilidade(this);
            });
        });

        // Monitorando alterações na data de entrega e devolução
        document.getElementById("data_entrega").addEventListener("change", function() {
            document.querySelectorAll(".produto-id").forEach(input => verificarDisponibilidade(input));
        });

        document.getElementById("data_devolucao").addEventListener("change", function() {
            document.querySelectorAll(".produto-id").forEach(input => verificarDisponibilidade(input));
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("formCadastroCliente").addEventListener("submit", function(event) {
            event.preventDefault(); // Evita o refresh da página

            let formData = new FormData(this);

            // Aqui, você pode garantir que ambos os campos de telefone sejam tratados
            let telefoneFisica = document.getElementById("telefone_contato_fisica").value;
            let telefoneJuridica = document.getElementById("telefone_contato_cnpj").value;

            if (telefoneFisica) {
                formData.append("telefone_contato", telefoneFisica);
            } else if (telefoneJuridica) {
                formData.append("telefone_contato", telefoneJuridica);
            }

            fetch("<?= base_url('locacoes/salvarClientes') ?>", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        // Atualizar os inputs de cliente com os dados cadastrados
                        document.getElementById("cliente_id").value = data.cliente.id;
                        document.getElementById("cliente_nome").value = data.cliente.tipo == 1 ? data.cliente.nome : data.cliente.razao_social;

                        document.querySelector("#cadastroClienteModal .btn-close").click();
                    } else {
                        alert("Erro ao cadastrar cliente: " + data.message);
                    }
                })
                .catch(error => console.error("Erro na requisição:", error));
        });
    });
</script>

<?= $this->endSection() ?>