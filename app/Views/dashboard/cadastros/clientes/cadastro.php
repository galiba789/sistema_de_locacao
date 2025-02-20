<?=$this->extend('dashboard/layout');?>
<?=$this->section('content-wrapper')?>
<div class="content-wrapper">
    <div class="container mt-4">
    <h2>Cadastro de Clientes</h2>
    <form action="<?=base_url('clientes/salvar')?>" method="POST">
   
        <div class="mb-3">
            <label for="type" class="form-label">Tipo de Cliente</label>
            <select id="type" name="tipo" class="form-control" onchange="clientesForm()" require>
                <option value="">Selecione...</option>
                <option value="1">Pessoa Física</option>
                <option value="2">Pessoa Jurídica</option>
            </select>
        </div>

      
        <div id="selectForm"></div>

        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
    </form>


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
                        $('#estado').val(dados.estado);
                        $('#logradouro').val(dados.logradouro);
                        $('#localidade').val(dados.localidade);
                        $('#bairro').val(dados.bairro);
                        
                    }, 'json');

            };
    </script>

<?=$this->endSection()?>