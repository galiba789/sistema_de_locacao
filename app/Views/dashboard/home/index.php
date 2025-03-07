<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>

<div class="content-wrapper">
    <div class="container mt-4">
        <h1>dashboard</h1>
        <div class="row">
            <div class="col-md-6 mt-3">
                <h3>Ultímas locações</h3>
                <table class="table">
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
                                    <td><?= $locacao['tipo'] == 1 ? $locacao['nome'] : $locacao['razao_social']; ?></td>
                                    <td><?= date('d/m/Y H:i:s', strtotime($locacao['created_at'])) ?></td>
                                    <td>
                                        <?php if ($locacao['situacao'] == 1): ?>
                                            <span class="btn btn-warning">Agendado</span>
                                        <?php elseif ($locacao['situacao'] == 2): ?>
                                            <span class="btn btn-warning">Pendente</span>
                                        <?php elseif ($locacao['situacao'] == 3): ?>
                                            <span class="btn btn-danger">Atrasado</span>
                                        <?php elseif ($locacao['situacao'] == 4): ?>
                                            <span class="btn btn-success">Finalizada</span>
                                        <?php elseif ($locacao['situacao'] == 5): ?>
                                            <span class="btn btn-danger">Cancelado</span>
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

            <div class="col-md-6 mt-3">
                <h3>Locações Agendadas</h3>
                <table class="table">
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
                                if($locacao['situacao'] == 1 ):?>
                                <tr>
                                    <td><?= $locacao['id'] ?></td>
                                    <td><?= $locacao['tipo'] == 1 ? $locacao['nome'] : $locacao['razao_social']; ?></td>
                                    <td><?= date('d/m/Y H:i:s', strtotime($locacao['created_at'])) ?></td>
                                    <td>
                                        <?php if ($locacao['situacao'] == 1): ?>
                                            <span class="btn btn-warning">Agendado</span>
                                        <?php elseif ($locacao['situacao'] == 2): ?>
                                            <span class="btn btn-warning">Pendente</span>
                                        <?php elseif ($locacao['situacao'] == 3): ?>
                                            <span class="btn btn-danger">Atrasado</span>
                                        <?php elseif ($locacao['situacao'] == 4): ?>
                                            <span class="btn btn-success">Finalizada</span>
                                        <?php elseif ($locacao['situacao'] == 5): ?>
                                            <span class="btn btn-danger">Cancelado</span>
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
            <div class="col-md-6 mt-3">
                <h3>Locações Finalizadas</h3>
                <table class="table">
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
                                if($locacao['situacao'] == 4 ):?>
                                <tr>
                                    <td><?= $locacao['id'] ?></td>
                                    <td><?= $locacao['tipo'] == 1 ? $locacao['nome'] : $locacao['razao_social']; ?></td>
                                    <td><?= date('d/m/Y H:i:s', strtotime($locacao['created_at'])) ?></td>
                                    <td>
                                        <?php if ($locacao['situacao'] == 1): ?>
                                            <span class="btn btn-warning">Agendado</span>
                                        <?php elseif ($locacao['situacao'] == 2): ?>
                                            <span class="btn btn-warning">Pendente</span>
                                        <?php elseif ($locacao['situacao'] == 3): ?>
                                            <span class="btn btn-danger">Atrasado</span>
                                        <?php elseif ($locacao['situacao'] == 4): ?>
                                            <span class="btn btn-success">Finalizada</span>
                                        <?php elseif ($locacao['situacao'] == 5): ?>
                                            <span class="btn btn-danger">Cancelado</span>
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
<script>

</script>


<?= $this->endSection(); ?>