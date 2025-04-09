<?=$this->extend('dashboard/layout');?>
<?=$this->section('content-wrapper')?>
<div class="content-wrapper">
    <div class="container mt-4">
        <h2>Edição de Clientes</h2>
        <?php if($cliente['tipo'] == 1):?>
            <form action="<?=base_url('clientes/update/').$cliente['id']?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nome:</label>
                        <input type="text" name="nome" id="nome" class="form-control" required value="<?=$cliente['nome']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>CPF:</label>
                        <input type="text" name="cpf" id="cpf" class="form-control" required value="<?=$cliente['cpf']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>RG:</label>
                        <input type="text" name="rg" id="rg" class="form-control" value="<?=$cliente['rg']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email:</label>
                        <input type="email" name="email" id="email" class="form-control"  value="<?=$cliente['email']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Telefone:</label>
                        <input type="text" name="telefone_contato" id="telefone_contato" class="form-control" required value="<?=$cliente['telefone_contato']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Data de Nascimento:</label>
                        <input type="date" name="nascimento" id="nascimento" class="form-control" required value="<?=$cliente['nascimento']?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Observação:</label>
                        <input type="text" name="observacao" id="observacao" class="form-control" value="<?=$cliente['observacao']?>">
                    </div>

                    <h3 class="col-md-12 mb-3">Endereço:</h3>

                    <div class="col-md-4 mb-3">
                        <label>CEP:</label>
                        <input type="text" name="cep" class="form-control" id="cep" onblur="buscarEndereco(this.value)" required value="<?=$cliente['cep']?>">
                        <small><a href="https://buscacepinter.correios.com.br/app/endereco/index.php" target="_blank">Não Sabe o CEP ? Clique aqui</a></small>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label>Logradouro:</label>
                        <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?=$cliente['logradouro']?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Número:</label>
                        <input type="text" name="numero" id="numero" class="form-control" required value="<?=$cliente['numero']?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Complemento:</label>
                        <input type="text" name="complemento" id="complemento" class="form-control" value="<?=$cliente['complemento']?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Bairro:</label>
                        <input type="text" name="bairro" id="bairro" class="form-control" value="<?=$cliente['bairro']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Estado:</label>
                        <input type="text" name="estado" id="estado" class="form-control" value="<?=$cliente['estado']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Cidade:</label>
                        <input type="text" name="localidade" id="localidade" class="form-control" value="<?=$cliente['localidade']?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Salvar</button>
            </form>
        <?php endif;?>    
        <?php if($cliente['tipo'] == 2):?>
            <form action="<?=base_url('clientes/update/').$cliente['id']?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Razão Social:</label>
                        <input type="text" name="razao_social" id="razao_social" class="form-control" required value="<?=$cliente['razao_social']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>CNPJ:</label>
                        <input type="text" name="cnpj" id="cnpj" class="form-control" required value="<?=$cliente['cnpj']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Telefone Comercial:</label>
                        <input type="text" name="telefone_comercial" id="telefone_comercial" class="form-control" value="<?=$cliente['telefone_comercial']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email:</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?=$cliente['email']?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Observação:</label>
                        <input type="text" name="observacao" id="observacao" class="form-control" value="<?=$cliente['observacao']?>">
                    </div>
                    
                    <h3 class="col-md-12 mb-3">Contato da empresa:</h3>

                    <div class="col-md-4 mb-3">
                        <label>Email de Contato:</label>
                        <input type="email" name="email_contato" id="email_contato" class="form-control" value="<?=$cliente['email_contato']?>">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Telefone de Contato:</label>
                        <input type="text" name="telefone_contato_cnpj" id="telefone_contato_cnpj" class="form-control" required value="<?=$cliente['telefone_contato']?>">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Cargo:</label>
                        <input type="text" name="cargo" id="cargo" class="form-control" value="<?=$cliente['cargo']?>">
                    </div>

                    <h3 class="col-md-12 mb-3">Endereço:</h3>

                    <div class="col-md-4 mb-3">
                        <label>CEP:</label>
                        <input type="text" name="cep" class="form-control" id="cep" onblur="buscarEndereco(this.value)" required value="<?=$cliente['cep']?>">
                        <small><a href="https://buscacepinter.correios.com.br/app/endereco/index.php" target="_blank">Não Sabe o CEP ? Clique aqui</a></small>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label>Logradouro:</label>
                        <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?=$cliente['logradouro']?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Número:</label>
                        <input type="text" name="numero" id="numero" class="form-control" value="<?=$cliente['numero']?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Complemento:</label>
                        <input type="text" name="complemento" id="complemento" class="form-control" value="<?=$cliente['complemento']?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Bairro:</label>
                        <input type="text" name="bairro" id="bairro" class="form-control" value="<?=$cliente['bairro']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Estado:</label>
                        <input type="text" name="estado" id="estado" class="form-control" value="<?=$cliente['estado']?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Cidade:</label>
                        <input type="text" name="localidade" id="localidade" class="form-control" value="<?=$cliente['localidade']?>">
                    </div>

                </div>

                <button type="submit" class="btn btn-primary mt-3">Salvar</button>
            </form>  
        <?php endif;?>
    </div>

</div>
<script>
            function buscarEndereco(cep) {
                // var cep = $('#cep').val();
                if (cep == '') {
                    alert('Informe o CEP antes de continuar');
                    $('#cep').focus();
                    return false;
                }
                $('#btn_consulta').html('Aguarde...');
                $.post('consulta', {
                        cep: cep
                    },
                    function(dados) {
                        console.log(dados);

                        $('#endereco').val(dados.logradouro);
                        $('#estado').val(dados.uf);
                        $('#logradouro').val(dados.logradouro);
                        $('#localidade').val(dados.localidade);
                        $('#bairro').val(dados.bairro);
                        
                    }, 'json');

            };
    </script>
<?=$this->endSection()?>