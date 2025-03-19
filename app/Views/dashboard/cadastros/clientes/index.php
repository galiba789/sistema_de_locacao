<?= $this->extend('dashboard/layout');?>
<?= $this->section('content-wrapper');?>
<div class="content-wrapper">
<div class="container mt-4">
    <h1>Clientes</h1>    
    <div class="card p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="tipo" class="form-label search">Tipo</label>
                    <select id="tipo" class="form-control">
                        <option value="nome">Nome</option>
                        <option value="cpf">CPF</option>
                        <option value="cnpj">CNPJ</option>
                        <option value="id">Código</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="palavra" class="form-label search">Palavra</label>
                    <input type="text" id="palavra" class="form-control" placeholder="Digite sua busca">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="col-md-2">
                <a href="/clientes/cadastrar">
                    <button class="btn btn-success w-100"><i class="fa-solid fa-pen"></i></button>
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
                    <?php foreach($clientes as $cliente){  
                    ?>
                    <tr>
                        <td><?=$cliente['id']?></td>
                        <?php if ($cliente['tipo'] == 1):?>
                            <td><a href="clientes/historico/<?=$cliente['id']?>"><?=$cliente['nome']?></a></td>
                        <?php endif;?>
                        <?php if ($cliente['tipo'] == 2):?>
                            <td><a href="clientes/historico/<?=$cliente['id']?>"><?=$cliente['razao_social']?></a></td>
                        <?php endif;?>
                        <td><?= $cliente['email']?></td>
                        
                        <td><?=$cliente['telefone_contato']?></td>
                        <?php if ($cliente['tipo'] == 1):?>
                            <td><?=$cliente['cpf']?></td>
                        <?php endif;?>
                        <?php if ($cliente['tipo'] == 2):?>
                            <td><?=$cliente['cnpj']?></td>
                        <?php endif;?>
                        <td>
                            <a href="<?=base_url('clientes/editar/'). $cliente['id']?>">
                                <button class="btn btn-warning btn-sm">Editar</button>
                            </a>
                            <a href="<?=base_url('clientes/excluir/'). $cliente['id']?>">
                                <button class="btn btn-danger btn-sm">Excluir</button>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                    <!-- Outras linhas podem ser adicionadas aqui -->
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            <?php echo $paginacao->links('default', 'custom_pager')?>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchBtn = document.querySelector(".btn-primary");
    const searchInput = document.getElementById("palavra");
    const searchType = document.getElementById("tipo");
    const tableBody = document.querySelector("tbody");

    function buscarClientes() {
        tableBody.innerHTML = "<tr><td colspan='6' class='text-center'>Carregando... <i class='fas fa-spinner fa-spin'></i></td></tr>";
        
        const baseUrl = "/clientes/buscar";
        let queryParams = [];

        if (searchType.value) {
            queryParams.push(`tipo=${encodeURIComponent(searchType.value)}`);
        }

        if (searchInput.value.trim()) {
            queryParams.push(`palavra=${encodeURIComponent(searchInput.value.trim())}`);
        }

        const url = queryParams.length > 0 ? `${baseUrl}?${queryParams.join('&')}` : baseUrl;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                tableBody.innerHTML = "";

                if (!data || data.length === 0) {
                    tableBody.innerHTML = "<tr><td colspan='6' class='text-center'>Nenhum cliente encontrado</td></tr>";
                    return;
                }

                let rows = data.map(cliente => {
                    let nomeOuRazao = cliente.tipo == 1 ? cliente.nome : cliente.razao_social;
                    let cpfOuCnpj = cliente.tipo == 1 ? cliente.cpf : cliente.cnpj;

                    return `
                        <tr>
                            <td>${cliente.id}</td>
                            <td><a href="clientes/historico/${cliente.id}">${nomeOuRazao}</a></td>
                            <td>${cliente.email || ''}</td>
                            <td>${cliente.telefone_contato || ''}</td>
                            <td>${cpfOuCnpj || ''}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                        Ações
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/clientes/editar/${cliente.id}">Editar</a></li>
                                        <li><a class="dropdown-item text-danger" href="/clientes/excluir/${cliente.id}">Excluir</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');

                tableBody.innerHTML = rows;
            })
            .catch(error => {
                console.error("Erro na busca:", error);
                tableBody.innerHTML = `<tr><td colspan='6' class='text-center text-danger'>Erro ao buscar dados: ${error.message}</td></tr>`;
            });
    }

    // Carregar clientes ao iniciar
    // buscarClientes();

    // Eventos
    if (searchBtn) {
        searchBtn.addEventListener("click", buscarClientes);
    }

    if (searchInput) {
        searchInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                buscarClientes();
            }
        });
    }
});

</script>





<?= $this->endSection();?>