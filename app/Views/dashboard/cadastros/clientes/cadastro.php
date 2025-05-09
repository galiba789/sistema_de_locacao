<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper') ?>
<style>
    .cliente-form {
        display: none;
    }
</style>
<div class="content-wrapper">
    <div class="container mt-4">
        <h2>Cadastro de Clientes</h2>
        <form action="<?= base_url('clientes/salvar') ?>" method="POST">
            <div class="mb-3">
                <label for="type" class="form-label">Tipo de Cliente</label>
                <select id="type" name="type" class="form-control" onchange="clientesForm()" required>
                    <option value="">Selecione...</option>
                    <option value="1">Pessoa Física</option>
                    <option value="2">Pessoa Jurídica</option>
                </select>
            </div>

            <!-- Formulário de Pessoa Física -->
            <div id="formFisica" class="cliente-form">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nome:</label>
                        <input type="text" name="nome" id="nome" class="form-control" required>
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
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Telefone:</label>
                        <input type="text" name="telefone_contato" id="telefone_contato" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Data de Nascimento:</label>
                        <input type="date" name="nascimento" id="nascimento" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Observação:</label>
                        <input type="text" name="obs" id="obs" class="form-control">
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
            <div id="formJuridica" class="cliente-form">
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
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Observação:</label>
                        <input type="text" name="obs" id="obs" class="form-control">
                    </div>

                    <h3 class="col-md-12 mb-3">Contato da empresa:</h3>

                    <div class="col-md-4 mb-3">
                        <label>Email de Contato:</label>
                        <input type="email" name="email_contato" id="email_contato" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Telefone de Contato:</label>
                        <input type="text" name="telefone_contato_cnpj" id="telefone_contato_cnpj" class="form-control">
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
                        <input type="text" name="numero" id="numero" class="form-control">
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

<script>
    function buscarEndereco(cep, element) {
        // Remove todos os caracteres que não são dígitos
        cep = cep.replace(/\D/g, '');

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
    }

    // Chamar a função ao carregar a página para garantir que os campos estejam corretos
</script>


<?= $this->endSection() ?>