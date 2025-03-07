<?=$this->extend('dashboard/layout');?>
<?=$this->section('content-wrapper')?>
<div class="content-wrapper">
    <div class="container mt-4">
        <h2>Cadastro de Produto</h2>
            <form action="<?=base_url('produtos/editar/').$produto['id']?>" method="POST">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>Nome:</label>
                        <input type="text" name="nome" id="nome" value="<?=$produto['nome']?>" class="form-control"  required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="numero_serie">Número de série:</label>
                        <input type="text" name="numero_serie" id="numero_serie" value="<?=$produto['numero_serie']?>" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="sku">SKU:</label>
                        <input type="text" name="sku" id="sku" class="form-control" value="<?=$produto['sku']?>" required>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="categoria">Categoria:</label>
                        <select name="categoria_id" id="categoria_id" class="form-control">
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria['id'] ?>" <?= ($categoria['id'] == $produto['categoria_id']) ? 'selected' : '' ?>>
                                    <?= $categoria['nome'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="col-md-4 mb-3">
                        <label for="preco_diaria">Preço da Diária (R$):</label>
                        <input type="text" name="preco_diaria" id="preco_diaria" class="form-control" value="<?=$produto['preco_diaria']?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="valor_minimo">Valor Mínimo:</label>
                        <input type="text" name="valor_minimo" id="valor_minimo" class="form-control" value="<?=$produto['valor_minimo']?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="estoque">Quantidade em Estoque:</label>
                        <input type="text" name="quantidade" id="quantidade" class="form-control" value="<?=$produto['quantidade']?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="obs">Observação:</label>
                        <textarea name="obs" id="obs" class="form-control"><?=$produto['obs']?></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="acessorios">Acessorios:</label>
                        <textarea name="acessorios" id="acessorios" class="form-control"><?=$produto['acessorios']?></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="aditivo_contratual">Aditivo contratual:</label>
                        <textarea name="aditivo_contratual" id="aditivo_contratual" class="form-control"><?=$produto['aditivo_contratual']?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Salvar</button>
            </form>
    </div>  
    
</div>    
<?=$this->endSection()?>