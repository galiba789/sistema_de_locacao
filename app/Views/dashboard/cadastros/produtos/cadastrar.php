<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper') ?>
<div class="content-wrapper">
    <div class="container mt-4">
        <h2>Cadastro de Produto</h2>
        <form action="<?= base_url('produtos/salvar') ?>" method="POST">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label>Nome:</label>
                    <input type="text" name="nome" id="nome" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="numero_serie">Número de série:</label>
                    <input type="text" name="numero_serie" id="numero_serie" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="sku">SKU:</label>
                    <input type="text" name="sku" id="sku" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="categoria">Categoria:</label>
                    <div class="input-group">
                        <select name="categoria_id" id="categoria_id" class="form-control">
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria['id'] ?>"><?= $categoria['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCategoria">
                                Nova Categoria
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="preco_diaria">Preço da Diária (R$):</label>
                    <input type="text" name="preco_diaria" id="preco_diaria" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="valor_minimo">Valor Mínimo:</label>
                    <input type="text" name="valor_minimo" id="valor_minimo" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="estoque">Quantidade em Estoque:</label>
                    <input type="text" name="quantidade" id="quantidade" class="form-control" value="1">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="obs">Observação:</label>
                    <textarea name="obs" id="obs" class="form-control"></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="acessorios">Acessorios:</label>
                    <textarea name="acessorios" id="acessorios" class="form-control"></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="aditivo_contratual">Aditivo contratual:</label>
                    <textarea name="aditivo_contratual" id="aditivo_contratual" class="form-control"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Salvar</button>
        </form>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="modalCategoria" tabindex="-1" role="dialog" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formNovaCategoria">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCategoriaLabel">Cadastrar Nova Categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomeCategoria">Nome da Categoria:</label>
                        <input type="text" class="form-control" id="nomeCategoria" name="nome" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('formNovaCategoria').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nomeCategoria = document.getElementById('nomeCategoria').value;
    
    fetch('/categorias/salvar-ajax', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest' // Importante para CI4 reconhecer como AJAX
        },
        body: JSON.stringify({ nome: nomeCategoria })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('categoria_id');
            const option = document.createElement('option');
            option.value = data.categoria.id;
            option.textContent = data.categoria.nome;
            select.appendChild(option);
            select.value = data.categoria.id;
            $('#modalCategoria').modal('hide');
            document.getElementById('nomeCategoria').value = '';
        } else {
            alert(data.message || 'Erro ao cadastrar categoria');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao cadastrar categoria');
    });
});
</script>


<?= $this->endSection() ?>