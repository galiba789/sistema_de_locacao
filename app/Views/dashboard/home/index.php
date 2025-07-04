<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>


<div class="content-wrapper">
    <div class="container mt-4">
        <h1>Dashboard</h1>
        
        <div class="row d-flex">
            <div class="col-md-6 mt-3 mb-4">
                <h3>Faturamento</h3>
                <canvas id="graficoFaturamento"></canvas>
            </div>
            <div class="col-md-6 mt-3 mb-4">
                <h3>Ultímas locações</h3>
                <table class="table ultimas_loc">
                    <thead class="thead-dark">
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Data da Locação</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($locacoes)): ?>
                            <?php foreach ($locacoes as $locacao): ?>
                                <tr>
                                    <td><?= $locacao['id'] ?></td>
                                    <td><a href="clientes/historico/<?=$locacao['cliente_id']?>"><?= $locacao['tipo'] == 1 ? $locacao['nome'] : $locacao['razao_social']; ?></a></td>
                                    <td><?= date('d/m/Y H:i:s', strtotime($locacao['created_at'])) ?></td>
                                    <td>
                                        <?php if ($locacao['situacao'] == 1): ?>
                                            <span class="btn btn-info">Agendado</span>
                                        <?php elseif ($locacao['situacao'] == 2): ?>
                                            <span class="btn btn-warning">Pendente</span>
                                        <?php elseif ($locacao['situacao'] == 3): ?>
                                            <span class="btn btn-danger">Atrasado</span>
                                        <?php elseif ($locacao['situacao'] == 4): ?>
                                            <span class="btn btn-danger">Finalizada</span>
                                        <?php elseif ($locacao['situacao'] == 5): ?>
                                            <span class="btn btn-warning">Cancelado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Nenhuma locação encontrada para este mês.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6 mt-3 mb-4">
                <h3>Locações Agendadas</h3>
                <table class="table agendadas">
                    <thead class="thead-dark">
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Data da Locação</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($locacoes)): ?>
                            <?php foreach ($locacoes as $locacao):
                                if ($locacao['situacao'] == 1): ?>
                                    <tr>
                                        <td><?= $locacao['id'] ?></td>
                                        <td><a href="clientes/historico/<?=$locacao['cliente_id']?>"><?= $locacao['tipo'] == 1 ? $locacao['nome'] : $locacao['razao_social']; ?></a></td>
                                        <td><?= date('d/m/Y H:i:s', strtotime($locacao['created_at'])) ?></td>
                                        <td>
                                            <?php if ($locacao['situacao'] == 1): ?>
                                                <span class="btn btn-info">Agendado</span>
                                            <?php elseif ($locacao['situacao'] == 2): ?>
                                                <span class="btn btn-warning">Pendente</span>
                                            <?php elseif ($locacao['situacao'] == 3): ?>
                                                <span class="btn btn-danger">Atrasado</span>
                                            <?php elseif ($locacao['situacao'] == 4): ?>
                                                <span class="btn btn-danger">Finalizada</span>
                                            <?php elseif ($locacao['situacao'] == 5): ?>
                                                <span class="btn btn-warning">Cancelado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php endif;
                            endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Nenhuma locação encontrada para este mês.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 mt-3 mb-4">
                <h3>Locações Finalizadas</h3>
                <table class="table finalizadas">
                    <thead class="thead-dark">
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Data da Locação</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($locacoes)): ?>
                            <?php foreach ($locacoes as $locacao):
                                if ($locacao['situacao'] == 4): ?>
                                    <tr>
                                        <td><?= $locacao['id'] ?></td>
                                        <td><a href="clientes/historico/<?=$locacao['cliente_id']?>"><?= $locacao['tipo'] == 1 ? $locacao['nome'] : $locacao['razao_social']; ?></a></td>
                                        <td><?= date('d/m/Y H:i:s', strtotime($locacao['created_at'])) ?></td>
                                        <td>
                                            <?php if ($locacao['situacao'] == 1): ?>
                                                <span class="btn btn-info">Agendado</span>
                                            <?php elseif ($locacao['situacao'] == 2): ?>
                                                <span class="btn btn-warning">Pendente</span>
                                            <?php elseif ($locacao['situacao'] == 3): ?>
                                                <span class="btn btn-danger">Atrasado</span>
                                            <?php elseif ($locacao['situacao'] == 4): ?>
                                                <span class="btn btn-danger">Finalizada</span>
                                            <?php elseif ($locacao['situacao'] == 5): ?>
                                                <span class="btn btn-warning">Cancelado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php endif;
                            endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Nenhuma locação encontrada para este mês.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('graficoFaturamento').getContext('2d');

    const meses = <?= $meses ?>;
    const valores = <?= $valores ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Faturamento (R$)',
                data: valores,
                backgroundColor: 'rgba(60, 235, 25, 0.6)',
                borderColor: 'rgb(0, 0, 0)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>



<?= $this->endSection(); ?>