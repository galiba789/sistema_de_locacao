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
                    <button class="btn btn-primary w-100">Buscar</button>
                </div>
                <div class="col-md-2">
                <a href="/clientes/cadastrar">
                    <button class="btn btn-success w-100">Cadastrar Cliente</button>
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
                    <tr>
                        <td>001</td>
                        <td>João Silva</td>
                        <td>joao@email.com</td>
                        <td>(11) 98765-4321</td>
                        <td>123.456.789-00</td>
                        <td>
                            <button class="btn btn-warning btn-sm">Editar</button>
                            <button class="btn btn-danger btn-sm">Excluir</button>
                        </td>
                    </tr>
                    <!-- Outras linhas podem ser adicionadas aqui -->
                </tbody>
            </table>
        </div>
    </div>
</div>


<?= $this->endSection();?>