<?=$this->extend('dashboard/layout');?>
<?=$this->section('content-wrapper')?>
<div class="content-wrapper">
    <div class="container mt-4">
        <h2>Edição de Clientes</h2>
            <form action="<?=base_url('categorias/editar/'). $categoria['id']?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nome:</label>
                        <input type="text" name="nome" id="nome" class="form-control" value="<?=$categoria['nome']?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Salvar</button>
            </form>
    </div>  
    
</div>    
<?=$this->endSection()?>