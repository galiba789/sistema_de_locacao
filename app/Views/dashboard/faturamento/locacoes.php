<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>
<div class="content-wrapper">
    <div class="container mt-4">
        <h2 class="mb-4">Relatório de Locações</h2>

        <form method="get" class="row g-3 mb-4">
            <div class="col-md-3">
                <label>Data Início</label>
                <input type="date" name="data_inicio" class="form-control" value="<?= esc($filtros['data_inicio'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label>Data Fim</label>
                <input type="date" name="data_fim" class="form-control" value="<?= esc($filtros['data_fim'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label>Status Pagamento</label>
                <select name="status" class="form-select">
                    <option value="" <?= $filtros['status'] === null ? 'selected' : '' ?>>Todas</option>
                    <option value="1" <?= $filtros['status'] === '1' ? 'selected' : '' ?>>Pagas</option>
                    <option value="0" <?= $filtros['status'] === '0' ? 'selected' : '' ?>>Pendentes</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>

        <?php if (!empty($locacoes)): ?>
            <table class="table table-bordered table-striped" id="tabela-locacoes">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($locacoes as $locacao): ?>
                        <tr>
                            <td><?= $locacao->id ?></td>
                            <td><?= esc($locacao->cliente_nome) ?></td>
                            <td><?= date('d/m/Y', strtotime($locacao->created_at)) ?></td>
                            <td>
                                <?= $locacao->pagamento == 1 ? '<span class="badge bg-success">Paga</span>' : '<span class="badge bg-warning text-dark">Pendente</span>' ?>
                            </td>
                            <td>R$ <?= number_format($locacao->valor_total, 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="mt-3">
                <h5>Total no período: <strong>R$ <?= number_format($valorTotal, 2, ',', '.') ?></strong></h5>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Nenhuma locação encontrada no período.</div>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tabela-locacoes').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            }
        });
    });
</script>
<?= $this->endSection(); ?>
