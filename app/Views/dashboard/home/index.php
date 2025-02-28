<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>

<div class="content-wrapper">
    <div class="container mt-4">
        <h1>dashboard</h1>
        <div class="row">
            <div class="col-md-12 mt-3">
                <canvas id="faturamentoChart"></canvas>
            </div>

            <div class="col-md-6 mt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Data da Locação</th>
                            <th>Produto(s)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($locacoes)): ?>
                            <?php foreach ($locacoes as $locacao): ?>
                                <tr>
                                    <td><?= $locacao['id'] ?></td>
                                    <td><?= $locacao['cliente_id'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($locacao['created_at'])) ?></td>
                                    <td>                                        
                                        <?= $locacao['nome'] ?> (<?= $locacao['numero_serie'] ?>)
                                    </td>
                                    <td><?= $locacao['status'] ?></td> 
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
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('faturamentoChart').getContext('2d');
    var faturamentoChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            datasets: [{
                    label: 'Faturamento Total',
                    data: [1000, 1200, 1500, 1700, 1600, 1800, 1900, 2100, 2200, 2300, 2400, 2500], // Dados de faturamento total
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false
                },
                {
                    label: 'Faturamento Recebido',
                    data: [800, 1000, 1300, 1500, 1400, 1600, 1700, 1900, 2000, 2100, 2200, 2300], // Dados de faturamento recebido
                    borderColor: 'rgba(153, 102, 255, 1)',
                    fill: false
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


<?= $this->endSection(); ?>