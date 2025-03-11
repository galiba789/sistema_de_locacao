function clientesForm() {
  var value = document.getElementById("type").value;
  var form = document.getElementById("selectForm");

  if (value == "1") {
    form.innerHTML = `
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
                    <input type="text" name="telefone_contato" id="telefone_contato" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Data de Nascimento:</label>
                    <input type="date" name="nascimento" id="nascimento" class="form-control" >
                </div>
                <div class="col-md-12 mb-3">
                    <label>Observação:</label>
                    <input type="text" name="obs" id="obs" class="form-control">
                </div>

                <h3 class="col-md-12 mb-3">Endereço:</h3>

               <div class="col-md-4 mb-3">
                    <label>CEP:</label>
                    <input type="text" name="cep" class="form-control" id="cep" onblur="buscarEndereco(this.value)" required>
                    <small><a href="https://buscacepinter.correios.com.br/app/endereco/index.php" target="_blank">Não Sabe o CEP ? Clique aqui</a></small>
                </div>

                <div class="col-md-8 mb-3">
                    <label>Logradouro:</label>
                    <input type="text" name="logradouro" id="logradouro" class="form-control" >
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
        `;
  } else if (value == "2") {
    form.innerHTML = `
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
                    <input type="email" name="email" id="email" class="form-control" >
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
                    <input type="text" name="telefone_contato" id="telefone_contato" class="form-control" required>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label>Cargo:</label>
                    <input type="text" name="cargo" id="cargo" class="form-control">
                </div>

                <h3 class="col-md-12 mb-3">Endereço:</h3>

               <div class="col-md-4 mb-3">
                    <label>CEP:</label>
                    <input type="text" name="cep" class="form-control" id="cep" onblur="buscarEndereco(this.value)" required>
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
        `;
  } else {
    form.innerHTML = "";
  }
}



function filtrarClientes() {
    var input = document.getElementById('buscarCliente').value.toLowerCase();
    var rows = document.querySelectorAll('#listaClientes tr');

    rows.forEach(function(row) {
        var nome = row.cells[1].textContent.toLowerCase(); 
        row.style.display = nome.includes(input) ? '' : 'none';
    });
}

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

