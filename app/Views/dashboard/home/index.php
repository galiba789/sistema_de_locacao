<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>

<?php
function renderSituacaoBadge($situacao)
{
    $mapa = [
        1 => ['label' => 'Agendado', 'class' => 'primary'],
        2 => ['label' => 'Pendente', 'class' => 'warning'],
        3 => ['label' => 'Atrasado', 'class' => 'danger'],
        4 => ['label' => 'Finalizada', 'class' => 'success'],
        5 => ['label' => 'Cancelado', 'class' => 'dark'],
        6 => ['label' => 'Em andamento', 'class' => 'info'],
    ];

    if (!isset($mapa[$situacao])) return '';

    $info = $mapa[$situacao];
    return "<span class='btn btn-sm btn-{$info['class']}'>{$info['label']}</span>";
}
?>

<div class="content-wrapper">
    <div class="container mt-4">
        <h1>Dashboard</h1>

        <div class="row d-flex">
            <!-- Gráfico de faturamento -->
            <div class="col-md-6 col-sm-12 mt-3 mb-4">
                <h3>Faturamento</h3>
                <div id="chart" style="width: 100%; height: 400px;"></div>
            </div>

            <!-- Últimas locações -->
            <div class="col-md-6 mt-3 mb-4">
                <h3>Últimas locações</h3>
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
                                    <td><?= $locacao->id ?></td>
                                    <td>
                                        <a href="clientes/historico/<?= $locacao->cliente_id ?>">
                                            <?= $locacao->cliente_nome ?>
                                        </a>
                                    </td>
                                    <td><?= date('d/m/Y H:i:s', strtotime($locacao->created_at)) ?></td>
                                    <td><?= renderSituacaoBadge($locacao->situacao) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Nenhuma locação encontrada para este período.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Locações Agendadas -->
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
                                if ($locacao->situacao == 1): ?>
                                    <tr>
                                        <td><?= $locacao->id ?></td>
                                        <td>
                                            <a href="clientes/historico/<?= $locacao->cliente_id ?>">
                                                <?= $locacao->cliente_nome ?>
                                            </a>
                                        </td>
                                        <td><?= date('d/m/Y H:i:s', strtotime($locacao->created_at)) ?></td>
                                        <td><?= renderSituacaoBadge($locacao->situacao) ?></td>
                                    </tr>
                            <?php endif;
                            endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Nenhuma locação agendada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Locações Finalizadas -->
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
                                if ($locacao->situacao == 4): ?>
                                    <tr>
                                        <td><?= $locacao->id ?></td>
                                        <td>
                                            <a href="clientes/historico/<?= $locacao->cliente_id ?>">
                                                <?= $locacao->cliente_nome ?>
                                            </a>
                                        </td>
                                        <td><?= date('d/m/Y H:i:s', strtotime($locacao->created_at)) ?></td>
                                        <td><?= renderSituacaoBadge($locacao->situacao) ?></td>
                                    </tr>
                            <?php endif;
                            endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Nenhuma locação finalizada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Gráfico ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const options = {
        chart: { type: 'bar', height: 350 },
        series: [{ name: 'Faturamento', data: <?= $valores ?> }],
        xaxis: { categories: <?= $meses ?> }
    };
    const chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>

<?= $this->endSection(); ?>
