<?=$this->extend('dashboard/layout');?>
<?=$this->section('content-wrapper')?>
<div class="content-wrapper">
    <div class="container mt-4">
        <h2>Cadastro de Clientes</h2>
        <label for="type">Tipos de Cliente:</label>
        <select name="type" id="type" onchange="clientesForm()">
            <option value="1">Física</option>
            <option value="2">Jurídica</option>
        </select>

        <form action="" id="selectForm"></form>

    </div>

</div>


<?=$this->endSection()?>