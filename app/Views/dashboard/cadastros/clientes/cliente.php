<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>
<div class="content-wrapper">
    <div class="container mt-4">
        <h1>Detalhes do Cliente</h1>
        <div class="card">
            <div class="card-body ">
                <h5 class="card-title"><?= $cliente['tipo'] == 1 ? $cliente['nome'] : $cliente['razao_social'] ?></h5>
                <p><strong>Tipo:</strong> <?= $cliente['tipo'] == 1 ? 'Pessoa Física' : 'Pessoa Jurídica' ?></p>
                <p><strong>Documento:</strong> <?= $cliente['tipo'] == 1 ? $cliente['cpf'] : $cliente['cnpj'] ?></p>
                <p><strong>Telefone:</strong> <?= $cliente['telefone_contato'] ?></p>
            </div>
        </div>

        <h2 class="mt-4">Últimas Locações</h2>
        <table class="table table-striped ultimas_loc_cliente">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Valor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locacoes as $locacao): ?>
                    <tr>
                        <td><?= $locacao['id'] ?></td>
                        <td><?= (new DateTime($locacao['data_entrega']))->format('d/m/Y H:i:s') ?>
                        <?= (new DateTime($locacao['data_devolucao']))->format('d/m/Y H:i:s') ?></td>
                        </td>
                        <td>R$ <?= number_format($locacao['valor_total'], 2, ',', '.') ?></td>
                        <td>
                            <?php if ($locacao['situacao'] == 1): ?>
                                <span class="btn btn-info">Agendado</span>
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
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection(); ?>