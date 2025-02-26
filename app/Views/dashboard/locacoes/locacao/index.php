<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>
<div class="content-wrapper">
    <div class="container mt-4">
        <?php if (session()->has('contrato_id')): ?>
            <script>
                window.onload = function() {
                    let contratoUrl = "<?= base_url('/locacoes/contrato/') ?>" + "<?= session('contrato_id') ?>";
                    window.open(contratoUrl, '_blank');
                };
            </script>
        <?php endif; ?>
        <h1>Locações</h1>
        <div class="card p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select id="tipo" class="form-control">
                        <option value="">Selecione</option>
                        <option value="1">Tipo 1</option>
                        <option value="2">Tipo 2</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="palavra" class="form-label">Palavra-chave</label>
                    <input type="text" id="palavra" class="form-control" placeholder="Digite sua busca">
                </div>
                <div class="col-md-2">
                    <label for="situacao" class="form-label">Situação</label>
                    <select id="situacao" class="form-control">
                        <option value="">Selecione</option>
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tipo_venda" class="form-label">Tipo de Venda</label>
                    <select id="tipo_venda" class="form-control">
                        <option value="">Selecione</option>
                        <option value="avulso">Avulso</option>
                        <option value="pacote">Pacote</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary "><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="col-md-1">
                    <a href="/locacoes/cadastrar">
                        <button class="btn btn-success "><i class="fa-solid fa-pen"></i></button>
                    </a>
                </div>
            </div>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Cód.</th>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Périodo</th>
                        <th>Valor</th>
                        <th>Pagamento</th>
                        <th>Detalhe</th>
                        <th>Situação</th>
                        <th>Boleto</th>
                        <th>Nota Fiscal</th>
                        <th>Pagamento</th>
                    </tr>
                </thead>
                <tbody>
                    <td></td>
                    <!-- Outras linhas podem ser adicionadas aqui -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>