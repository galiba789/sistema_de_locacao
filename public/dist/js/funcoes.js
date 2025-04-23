function clientesForm() {
    var tipo = document.getElementById("type").value;
    var formFisica = document.getElementById("formFisica");
    var formJuridica = document.getElementById("formJuridica");

    if (tipo == "1") { // Pessoa Física
        formFisica.style.display = "block";
        formJuridica.style.display = "none";

        // Habilita os campos do formulário de Pessoa Física
        enableFields(formFisica);
        // Desabilita os campos do formulário de Pessoa Jurídica
        disableFields(formJuridica);
    } else if (tipo == "2") { // Pessoa Jurídica
        formFisica.style.display = "none";
        formJuridica.style.display = "block";

        // Habilita os campos do formulário de Pessoa Jurídica
        enableFields(formJuridica);
        // Desabilita os campos do formulário de Pessoa Física
        disableFields(formFisica);
    } else {
        formFisica.style.display = "none";
        formJuridica.style.display = "none";

        // Desabilita todos os campos
        disableFields(formFisica);
        disableFields(formJuridica);
    }
}

// Função para desabilitar todos os campos dentro de um formulário
function disableFields(form) {
    var inputs = form.getElementsByTagName("input");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].setAttribute("disabled", "disabled");
        inputs[i].removeAttribute("required");
    }
}

// Função para habilitar todos os campos dentro de um formulário
function enableFields(form) {
    var inputs = form.getElementsByTagName("input");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].removeAttribute("disabled");
        if (inputs[i].hasAttribute("data-required")) {
            inputs[i].setAttribute("required", "required");
        }
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

