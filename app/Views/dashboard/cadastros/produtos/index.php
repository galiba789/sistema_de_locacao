<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>
<div class="content-wrapper">
    <div class="container mt-4">
        <h1>Produtos</h1>
        <div class="card p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label for="nome" class="form-label search">Nome</label>
                    <input type="text" id="nome" class="form-control" placeholder="Digite sua busca">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="col-md-2">
                    <a href="/produtos/cadastrar">
                        <button class="btn btn-success w-100"><i class="fa-solid fa-pen"></i></button>
                    </a>
                </div>
            </div>
        </div>
        <div id="product-list"></div>
        <div id="pagination"></div>
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th class="fs-4">Código</th>
                        <th class="fs-4">Nome</th>
                        <th class="fs-4">Estoque</th>
                        <th class="fs-4">Preço de venda (R$)</th>
                        <th class="fs-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= $produto['id'] ?></td>
                            <td><?= $produto['nome'] ?></td>
                            <td><?= $produto['quantidade'] ?></td>
                            <td>R$<?= $produto['preco_diaria'] ?></td>
                            <td>
                                <a href="<?= base_url('produtos/edita/') . $produto['id'] ?>">
                                    <button class="btn btn-warning btn-sm">Editar</button>
                                </a>
                                <a href="<?= base_url('produtos/excluir/') . $produto['id'] ?>">
                                    <button class="btn btn-danger btn-sm">Excluir</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                <?php echo $paginacao->links('default', 'custom_pager')?>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchBtn = document.querySelector(".btn-primary");
        const searchInput = document.getElementById("nome");
        const tableBody = document.querySelector("tbody");

        // Carrega todos os produtos ao abrir a página
        // fetchProdutos();

        searchBtn.addEventListener("click", function() {
            fetchProdutos();
        });

        searchInput.addEventListener("keyup", function(event) {
            if (event.key === "Enter" || searchInput.value === "") {
                fetchProdutos();
            }
        });

        function fetchProdutos() {
            const nome = searchInput.value.trim();

            fetch(`/produtos/buscar?nome=${encodeURIComponent(nome)}`)
                .then(response => response.json())
                .then(data => {
                    // Cria um novo conteúdo antes de limpar a tabela
                    let newContent = "";

                    if (data.length === 0) {
                        newContent = "<tr><td colspan='5' class='text-center'>Nenhum produto encontrado</td></tr>";
                    } else {
                        data.forEach(produto => {
                            newContent += `
                            <tr>
                                <td>${produto.id}</td>
                                <td>${produto.nome}</td>
                                <td>${produto.quantidade}</td>
                                <td>${produto.preco_diaria}</td>
                                <td>
                                    <a href="/produtos/edita/${produto.id}">
                                        <button class="btn btn-warning btn-sm">Editar</button>
                                    </a>
                                    <a href="/produtos/excluir/${produto.id}">
                                        <button class="btn btn-danger btn-sm">Excluir</button>
                                    </a>
                                </td>
                            </tr>
                        `;
                        });
                    }

                    // Agora, substituímos o conteúdo de uma vez, evitando o piscar
                    tableBody.innerHTML = newContent;
                })
                .catch(error => console.error("Erro na busca:", error));
        }
    });
</script>

<?= $this->endSection(); ?>