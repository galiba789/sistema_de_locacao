<?=$this->extend('dashboard/layout');?>
<?=$this->section('content-wrapper')?>
<div class="content-wrapper">
    <div class="container mt-4">
        <h2>Cadastro de usuario</h2>
            <form action="<?=base_url('usuarios/salvar')?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nome:</label>
                        <input type="text" name="nome" id="nome" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Senha:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email:</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Salvar</button>
            </form>
    </div>  
    
</div>    
<?=$this->endSection()?>